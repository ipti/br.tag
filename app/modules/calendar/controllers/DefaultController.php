<?php

class DefaultController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',  // allow all users to perform 'index' and 'view' actions
                'actions' => ['index', 'view'], 'users' => ['*'],
            ], [
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual',
                    'RemoveCalendar', 'DeleteEvent', 'editCalendar', 'ShowStages', 'loadCalendarData',
                    'loadUnityPeriods', 'editUnityPeriods', 'viewPeriods'],
                'users' => ['@'],
            ], [
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => ['admin', 'delete'], 'users' => ['admin'],
            ], [
                'deny',  // deny all users
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionCreate()
    {
        $calendar = new Calendar();
        $calendar->title = $_POST["title"];
        $calendar->start_date = $_POST["startDate"];
        $calendar->end_date = $_POST["endDate"];
        $calendar->school_year = Yii::app()->user->year;

        $calendar->save();
        foreach ($_POST["stages"] as $stage) {
            $calendarStage = new CalendarStages();
            $calendarStage->calendar_fk = $calendar->id;
            $calendarStage->stage_fk = $stage;
            $calendarStage->save();
        }
        if ($_POST["copyFrom"] != "") {
            /** @var $calendarBase Calendar */
            $calendarBase = Calendar::model()->findByPk($_POST["copyFrom"]);
            $yearBase = $calendarBase->school_year;
            $events = $calendarBase->getCopyableEvents();
            foreach ($events as $event) {

                $eventStart = new DateTime($event->start_date);
                $eventEnd = new DateTime($event->end_date);

                $startYearDifference = (int)$eventStart->format('Y') - $yearBase;
                $endYearDifference = (int)$eventEnd->format("Y") - $yearBase;

                $newStart = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventStart->format('m'), $eventStart->format('d'), $calendar->school_year + $startYearDifference)));
                $newEnd = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventEnd->format('m'), $eventEnd->format('d'), $calendar->school_year + $endYearDifference)));

                $e = new CalendarEvent();
                $e->attributes = $event->attributes;

                $e->start_date = $newStart->format("Y-m-d");
                $e->end_date = $newEnd->format("Y-m-d");

                $e->calendar_fk = $calendar->id;
                $e->id = NULL;
                $e->save();
            }
        }
        Log::model()->saveAction("calendar", $calendar->id, "C", $calendar->title);
        echo json_encode(["valid" => true]);
    }

    public function actionEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        echo json_encode($event->attributes);
    }

    public function actionChangeEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || $event == null || $event->school_fk != null) {
            $result = Yii::app()->db->createCommand("
            select count(s.id) as qtd from schedule s 
            join classroom cr on s.classroom_fk = cr.id 
            join calendar c on cr.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendarFk"])->queryRow();
            $isHardUnavailableEvent = $_POST["eventTypeFk"] == 102;
            $isSoftUnavailableEvent = $_POST["eventTypeFk"] == 101 || $_POST["eventTypeFk"] == 104;
            $isPreviousDate = strtotime($_POST["startDate"]) < strtotime('now');

            if ($event == null) {
                $event = new CalendarEvent();
                $event->school_fk = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? null : Yii::app()->user->school;
            } else {
                $isHardUnavailableEvent = !$isHardUnavailableEvent ? $event->calendar_event_type_fk == 102 : $isHardUnavailableEvent;
                $isSoftUnavailableEvent = !$isSoftUnavailableEvent ? $event->calendar_event_type_fk == 101 || $event->calendar_event_type_fk == 104 : $isSoftUnavailableEvent;
                $isPreviousDate = !$isPreviousDate ? strtotime($event->start_date) < strtotime('now') : $isPreviousDate;
            }
            if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: adicionar ou modificar eventos de <b>férias</b> poderá refletir no quadro de horário, aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-save-event'>aqui</span> para confirmar."]);
            } else if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isSoftUnavailableEvent && $isPreviousDate) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: adicionar ou modificar eventos de <b>feriados</b> com <b>datas anteriores à atual</b> poderá refletir nas aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-save-event'>aqui</span> para confirmar."]);
            } else {
                $event->calendar_fk = $_POST["calendarFk"];
                $event->name = $_POST["name"];
                $event->start_date = $_POST["startDate"];
                $event->end_date = $_POST["endDate"];
                $event->calendar_event_type_fk = $_POST["eventTypeFk"];
                $event->copyable = $_POST["copyable"] ? 1 : 0;
                $uniqueDayToDelete = null;
                if (CalendarEventType::model()->findByPk($_POST["eventTypeFk"])->unique_day === "1") {
                    $event->end_date = $event->start_date;
                    $start_scholar_year_date = CalendarEvent::model()->find("calendar_fk = :calendar_fk and calendar_event_type_fk = :calendar_event_type_fk", ["calendar_fk" => $_POST["calendarFk"], "calendar_event_type_fk" => $_POST["eventTypeFk"]]);
                    if ($start_scholar_year_date->id !== $event->id) {
                        $uniqueDayToDelete = ["id" => $start_scholar_year_date->id, "color" => $start_scholar_year_date->calendarEventTypeFk->color];
                        $start_scholar_year_date->delete();
                    }
                }
                $event->save();
                Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);

                $start = new DateTime($event->start_date);
                $end = new DateTime($event->end_date);
                $end->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);
                $datesToFill = [];
                foreach ($period as $dt) {
                    array_push($datesToFill, ["year" => $dt->format("Y"), "month" => $dt->format("n"), "day" => $dt->format("j")]);
                }

                if ($isHardUnavailableEvent || $isSoftUnavailableEvent) {
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        $schedulesToAdjust = Yii::app()->db->createCommand("
                            select s.id from schedule s 
                            join classroom cr on s.classroom_fk = cr.id 
                            join calendar c on cr.calendar_fk = c.id
                            where c.id = :id and s.day = :day and s.month = :month and s.year = :year")->bindParam(":id", $_POST["calendarFk"])->bindParam(":day", $dt->format("j"))->bindParam(":month", $dt->format("n"))->bindParam(":year", $dt->format("Y"))->queryAll();
                        foreach ($schedulesToAdjust as $scheduleToAdjust) {
                            if ($isHardUnavailableEvent) {
                                Schedule::model()->deleteAll("id = :id", [":id" => $scheduleToAdjust["id"]]);
                            } else {
                                Schedule::model()->updateAll(["unavailable" => 1], "id = :id", [":id" => $scheduleToAdjust["id"]]);
                                ClassFaults::model()->deleteAll("schedule_fk = :schedule_fk", [":schedule_fk" => $scheduleToAdjust["id"]]);
                                ClassContents::model()->deleteAll("schedule_fk = :schedule_fk", [":schedule_fk" => $scheduleToAdjust["id"]]);
                                ClassDiaries::model()->deleteAll("schedule_fk = :schedule_fk", [":schedule_fk" => $scheduleToAdjust["id"]]);
                            }
                        }
                    }
                }

                $calendarEventType = CalendarEventType::model()->findByPk($_POST["eventTypeFk"]);


                echo json_encode([
                    "valid" => true,
                    "datesToFill" => $datesToFill,
                    "color" => $calendarEventType->color,
                    "icon" => $calendarEventType->icon,
                    "eventId" => $event->id,
                    "eventName" => $event->name,
                    "uniqueDayToDelete" => $uniqueDayToDelete
                ]);
            }
        } else {
            echo json_encode(["valid" => false, "alert" => "error", "error" => "Apenas administradores podem alterar este evento."]);
        }
    }

    public function actionDeleteEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) || $event->school_fk != null) {
            $result = Yii::app()->db->createCommand("
            select count(s.id) as qtd from schedule s 
            join classroom cr on s.classroom_fk = cr.id 
            join calendar c on cr.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendarId"])->queryRow();
            $isHardUnavailableEvent = $event->calendar_event_type_fk == 102;
            $isSoftUnavailableEvent = $event->calendar_event_type_fk == 101 || $event->calendar_event_type_fk == 104;
            $isPreviousDate = strtotime($event->start_date) < strtotime('now');
            if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: remover eventos de <b>férias</b> poderá refletir no quadro de horário, aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-delete-event'>aqui</span> para confirmar."]);
            } else if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isSoftUnavailableEvent && $isPreviousDate) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: remover eventos de <b>feriado</b> ou <b>ponto facultativo</b> com <b>datas anteriores à atual</b> poderá refletir nas aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-delete-event'>aqui</span> para confirmar."]);
            } else {

                $start = new DateTime($event->start_date);
                $end = new DateTime($event->end_date);
                $end->modify('+1 day');
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);
                foreach ($period as $dt) {
                    if ($isSoftUnavailableEvent) {
                        $schedulesToAdjust = Yii::app()->db->createCommand("
                            select s.id from schedule s 
                            join classroom cr on s.classroom_fk = cr.id 
                            join calendar c on cr.calendar_fk = c.id
                            where c.id = :id and s.day = :day and s.month = :month and s.year = :year")->bindParam(":id", $_POST["calendarId"])->bindParam(":day", $dt->format("j"))->bindParam(":month", $dt->format("n"))->bindParam(":year", $dt->format("Y"))->queryAll();
                        foreach ($schedulesToAdjust as $scheduleToAdjust) {
                            Schedule::model()->updateAll(["unavailable" => 0], "id = :id", [":id" => $scheduleToAdjust["id"]]);
                        }
                    }
                }

                $color = $event->calendarEventTypeFk->color;
                Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
                $event->delete();

                echo json_encode(["valid" => true, "id" => $_POST["id"], "color" => $color]);
            }
        } else {
            echo json_encode(["valid" => false, "alert" => "error", "error" => "Apenas administradores podem remover este evento."]);
        }
    }

    public function actionRemoveCalendar()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $result = Yii::app()->db->createCommand("
            select count(cr.id) as qtd from classroom cr
            join calendar c on cr.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendar_removal_id"])->queryRow();
            if ((int)$result["qtd"] > 0) {
                echo json_encode(["valid" => false, "error" => "Permissão negada. O calendário está sendo utilizando por alguma turma."]);
            } else {
                $calendar = Calendar::model()->findByPk($_POST['calendar_removal_id']);
                Log::model()->saveAction("calendar", $calendar->id, "D", $calendar->title);
                $calendar->delete();
                echo json_encode(["valid" => true]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem remover calendários."]);
        }
    }

    public function loadModel($id)
    {
        $model = Calendar::model()->findByPk($id);
        if ($model === NULL) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function actionEditCalendar()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $alertUser = false;
            $calendar = Calendar::model()->findByPk($_POST["id"]);
            if ($_POST["confirm"] === "false" && ($calendar->start_date != $_POST["startDate"] || $calendar->end_date != $_POST["endDate"])) {
                $result = Yii::app()->db->createCommand("
                    select count(s.id) as qtd from schedule s 
                    join classroom cr on s.classroom_fk = cr.id 
                    join calendar c on cr.calendar_fk = c.id
                    where c.id = :id")->bindParam(":id", $calendar->id)->queryRow();
                if ((int)$result["qtd"] > 0) {
                    $alertUser = true;
                }
            }

            if (!$alertUser) {
                Log::model()->saveAction("calendar", $calendar->id, "U", $calendar->title);
                $calendar->title = $_POST["title"];
                $calendar->start_date = $_POST["startDate"];
                $calendar->end_date = $_POST["endDate"];
                $calendar->save();
                $criteria = new CDbCriteria();
                $criteria->addCondition("calendar_fk = " . $calendar->id);
                $criteria->addNotInCondition("stage_fk", $_POST["stages"]);
                CalendarStages::model()->deleteAll($criteria);
                foreach ($_POST["stages"] as $stageId) {
                    $calendarStage = CalendarStages::model()->find("calendar_fk = :calendar_fk and stage_fk = :stage_fk", ["calendar_fk" => $calendar->id, "stage_fk" => $stageId]);
                    if ($calendarStage == null) {
                        $calendarStage = new CalendarStages();
                        $calendarStage->stage_fk = $stageId;
                        $calendarStage->calendar_fk = $calendar->id;
                        $calendarStage->save();
                    }
                }


                Yii::app()->db->createCommand("
                            delete from schedule s 
                            join classroom cr on s.classroom_fk = cr.id 
                            join calendar c on c.id = cr.calendar_fk
                            where c.id = :id and 
                            (CONCAT(s.year, '-', LPAD(s.month, 2, '0'), '-', LPAD(s.day, 2, '0')) < :start_date or CONCAT(s.year, '-', LPAD(s.month, 2, '0'), '-', LPAD(s.day, 2, '0')) > :end_date)"
                )->bindParam(":id", $calendar->id)->bindParam(":start_date", $calendar->start_date)->bindParam(":end_date", $calendar->end_date);

                echo json_encode(["valid" => true]);
            } else {
                echo json_encode(["valid" => false, "alert" => "primary", "message" => "ATENÇÃO: alterar datas poderá refletir no QUADRO DE HORÁRIO, AULAS MINISTRADAS e FREQUÊNCIA de turmas.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-edit-calendar-event'>aqui</span> para confirmar."]);
            }
        } else {
            echo json_encode(["valid" => false, "alert" => "error", "message" => "Apenas administradores podem editar calendários."]);
        }
    }

    public function actionShowStages()
    {
        $result = Yii::app()->db->createCommand("
            select edcenso_stage_vs_modality.id, edcenso_stage_vs_modality.name 
            from calendar_stages 
            inner join edcenso_stage_vs_modality on calendar_stages.stage_fk = edcenso_stage_vs_modality.id 
            where calendar_fk = :id order by edcenso_stage_vs_modality.name"
        )->bindParam(":id", $_POST["id"])->queryAll();
        foreach ($result as &$stage) {
            $periods = Yii::app()->db->createCommand("
            select count(*) as qtd
            from grade_unity_periods
            inner join grade_unity on grade_unity_periods.grade_unity_fk = grade_unity.id
            where grade_unity.edcenso_stage_vs_modality_fk = :stage and grade_unity_periods.calendar_fk = :calendar_fk"
            )->bindParam(":stage", $stage["id"])->bindParam(":calendar_fk", $_POST["id"])->queryRow();
            $stage["hasPeriod"] = (int)$periods["qtd"] > 0;
        }

        echo json_encode($result);
    }

    public function actionLoadCalendarData()
    {
        $result = [];
        $calendar = Calendar::model()->findByPk($_POST["id"]);
        $result["id"] = $calendar->id;
        $result["title"] = $calendar->title;
        $result["startDate"] = $calendar->start_date;
        $result["endDate"] = $calendar->end_date;
        $result["stages"] = [];
        foreach ($calendar->calendarStages as $calendarStage) {
            array_push($result["stages"], $calendarStage->stage_fk);
        }
        echo json_encode($result);
    }

    public function actionLoadUnityPeriods()
    {
        $result = [];
        $result["year"] = Yii::app()->user->year;
        $result["stages"] = [];

        $calendar = Calendar::model()->findByPk($_POST["id"]);
        $calendarInitialDate = date("d/m/Y", strtotime($calendar->start_date));
        $result["calendarFinalDate"] = date("d/m/Y", strtotime($calendar->end_date));

        $criteria = new CDbCriteria();
        $criteria->alias = "cs";
        $criteria->join = "join edcenso_stage_vs_modality esvm on esvm.id = cs.stage_fk";
        $criteria->order = "esvm.name";
        $criteria->condition = "cs.calendar_fk = :calendar_fk";
        $criteria->params = ["calendar_fk" => $_POST["id"]];
        $calendarStages = CalendarStages::model()->findAll($criteria);
        foreach ($calendarStages as $calendarStage) {
            $gradeUnities = GradeUnity::model()->findAll('edcenso_stage_vs_modality_fk = :stage and type not in ("RS", "RF")', ["stage" => $calendarStage->stage_fk]);
            $stageArray["title"] = $calendarStage->stageFk->name;
            $stageArray["unities"] = [];
            foreach ($gradeUnities as $index => $gradeUnity) {
                $unity["id"] = $gradeUnity->id;
                $unity["name"] = $gradeUnity->name;
                if ($index == 0) {
                    $unity["initial_date"] = $calendarInitialDate;
                } else {
                    $gradeUnityPeriod = GradeUnityPeriods::model()->find("grade_unity_fk = :grade_unity_fk and calendar_fk = :calendar_fk", [":grade_unity_fk" => $gradeUnity->id, ":calendar_fk" => $calendar->id]);
                    $unity["initial_date"] = $gradeUnityPeriod == null ? "" : date("d/m/Y", strtotime($gradeUnityPeriod->initial_date));
                }
                array_push($stageArray["unities"], $unity);
            }
            array_push($result["stages"], $stageArray);
        }
        echo json_encode(["valid" => true, "result" => $result]);
    }


    public
    function actionEditUnityPeriods()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $error = "";
            if (empty($_POST["gradeUnities"])) {
                $error .= "Unidades não foram preenchidas.";
            } else {
                foreach ($_POST['gradeUnities'] as $gup) {
                    $gradeUnityPeriod = GradeUnityPeriods::model()->find('grade_unity_fk = :gradeUnityFk and calendar_fk = :calendar_fk', [':gradeUnityFk' => $gup["gradeUnityFk"], ':calendar_fk' => $_POST["calendarFk"]]);
                    if ($gradeUnityPeriod == null) {
                        $gradeUnityPeriod = new GradeUnityPeriods();
                        $gradeUnityPeriod->grade_unity_fk = $gup["gradeUnityFk"];
                        $gradeUnityPeriod->calendar_fk = $_POST["calendarFk"];
                    }
                    $gradeUnityPeriod->initial_date = implode('-', array_reverse(explode('/', $gup["initialDate"])));
                    $gradeUnityPeriod->save();
                }
            }

            if ($error != "") {
                echo json_encode(["valid" => false, "error" => $error]);
            } else {
                echo json_encode(["valid" => true]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem editar a vigência das unidades."]);
        }
    }

    public
    function actionViewPeriods()
    {
        $result = [];

        $gradeUnityPeriods = Yii::app()->db->createCommand("
            select gup.initial_date, gu.name 
            from grade_unity_periods gup 
            inner join grade_unity gu on gup.grade_unity_fk = gu.id 
            where gu.edcenso_stage_vs_modality_fk = :stageId and calendar_fk = :calendar_fk
            order by initial_date"
        )->bindParam(":stageId", $_POST["stageId"])->bindParam(":calendar_fk", $_POST["calendarId"])->queryAll();

        $calendar = Calendar::model()->findByPk($_POST["calendarId"]);
        $start = new DateTime($calendar->start_date);
        $end = new DateTime($calendar->end_date);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end);
        $colorIndex = 0;
        $unityName = "";
        foreach ($period as $dt) {
            $gregorianDate = $dt->format("Y-m-d");
            for ($i = 0; $i < count($gradeUnityPeriods); $i++) {
                if ($i == count($gradeUnityPeriods) - 1) {
                    if ($gregorianDate >= $gradeUnityPeriods[$i]["initial_date"]) {
                        $colorIndex = $i;
                        $unityName = $gradeUnityPeriods[$i]["name"];
                    }
                } else {
                    if ($gregorianDate >= $gradeUnityPeriods[$i]["initial_date"] && $gregorianDate < $gradeUnityPeriods[$i + 1]["initial_date"]) {
                        $colorIndex = $i;
                        $unityName = $gradeUnityPeriods[$i]["name"];
                    }
                }
            }
            array_push($result, ["year" => $dt->format("Y"), "month" => $dt->format("n"), "day" => $dt->format("j"), "colorIndex" => $colorIndex, "unityName" => $unityName]);
        }
        echo json_encode($result);
    }
}