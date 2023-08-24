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
                'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual', 'RemoveCalendar', 'DeleteEvent', 'editCalendar', 'ShowStages', 'changeCalendarStatus', 'loadCalendarData', 'loadUnityPeriods', 'editUnityPeriods'],
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
        $error = "";
        foreach ($_POST["stages"] as $stage) {
            $calendar = Yii::app()->db->createCommand("select calendar.title from calendar_stages inner join calendar on calendar_stages.calendar_fk = calendar.id where YEAR(calendar.start_date) = :year and stage_fk = :stage")
                ->bindParam(":year", Yii::app()->user->year)
                ->bindParam(":stage", $stage)
                ->queryRow();
            if ($calendar != null) {
                if ($error == "") {
                    $error .= "Já existe Calendário para a(s) seguinte(s) etapa(s) selecionada(s):<br><br>";
                }
                $edcensoStageVsModality = EdcensoStageVsModality::model()->findByPk($stage);
                $error .= "• <b>" . $edcensoStageVsModality->name . "</b> no Calendário <b>" . $calendar["title"] . "</b><br>";
            }
        }
        if ($error == "") {
            $calendar = new Calendar();
            $calendar->title = $_POST["title"];
            $calendar->start_date = Yii::app()->user->year . "-01-01";
            $calendar->end_date = Yii::app()->user->year . "-12-31";
            $calendar->available = 0;
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
                $events = $calendarBase->getCopyableEvents();
                foreach ($events as $event) {
                    $calendarStart = new DateTime($calendar->start_date);
                    $eventStart = new DateTime($event->start_date);
                    $eventEnd = new DateTime($event->end_date);

                    $newStart = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventStart->format('m'), $eventStart->format('d'), $calendarStart->format('Y'))));
                    $newEnd = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventEnd->format('m'), $eventEnd->format('d'), $calendarStart->format('Y'))));

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
        } else {
            echo json_encode(["valid" => false, "error" => $error]);
        }
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
            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
            join calendar c on cs.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendarFk"])->queryRow();
            $isHardUnavailableEvent = $_POST["eventTypeFk"] == 1000 || $_POST["eventTypeFk"] == 1001 || $_POST["eventTypeFk"] == 102;
            $isSoftUnavailableEvent = $_POST["eventTypeFk"] == 101 || $_POST["eventTypeFk"] == 104;
            $isPreviousDate = strtotime($_POST["startDate"]) < strtotime('now');

            if ($event == null) {
                $event = new CalendarEvent();
                $event->school_fk = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? null : Yii::app()->user->school;
            } else {
                $isHardUnavailableEvent = !$isHardUnavailableEvent ? $event->calendar_event_type_fk == 1000 || $event->calendar_event_type_fk == 1001 || $event->calendar_event_type_fk == 102 : $isHardUnavailableEvent;
                $isSoftUnavailableEvent = !$isSoftUnavailableEvent ? $event->calendar_event_type_fk == 101 || $event->calendar_event_type_fk == 104 : $isSoftUnavailableEvent;
                $isPreviousDate = !$isPreviousDate ? strtotime($event->start_date) < strtotime('now') : $isPreviousDate;
            }
            if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: adicionar ou modificar eventos de <b>férias</b>, <b>início</b> ou <b>fim de ano escolar</b>, poderá refletir no quadro de horário, aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-save-event'>aqui</span> para confirmar."]);
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
                    if ($event->calendar_event_type_fk == 1000) {
                        $start = new DateTime(Yii::app()->user->year . "-01-01 00:00:00");
                        $end = new DateTime($event->end_date);
                    } else if ($event->calendar_event_type_fk == 1001) {
                        $start = new DateTime($event->start_date);
                        $start->modify('+1 day');
                        $end = new DateTime(Yii::app()->user->year . "-12-31 23:59:59");
                    } else {
                        $start = new DateTime($event->start_date);
                        $end = new DateTime($event->end_date);
                        $end->modify('+1 day');
                    }
                    $interval = DateInterval::createFromDateString('1 day');
                    $period = new DatePeriod($start, $interval, $end);
                    foreach ($period as $dt) {
                        $schedulesToAdjust = Yii::app()->db->createCommand("
                            select s.id from schedule s 
                            join classroom cr on s.classroom_fk = cr.id 
                            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
                            join calendar c on cs.calendar_fk = c.id
                            where c.id = :id and s.day = :day and s.month = :month")->bindParam(":id", $_POST["calendarFk"])->bindParam(":day", $dt->format("j"))->bindParam(":month", $dt->format("n"))->queryAll();
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
            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
            join calendar c on cs.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendarId"])->queryRow();
            $isHardUnavailableEvent = $event->calendar_event_type_fk == 1000 || $event->calendar_event_type_fk == 1001 || $event->calendar_event_type_fk == 102;
            $isSoftUnavailableEvent = $event->calendar_event_type_fk == 101 || $event->calendar_event_type_fk == 104;
            $isPreviousDate = strtotime($event->start_date) < strtotime('now');
            if (!$_POST["confirm"] && (int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
                echo json_encode(["valid" => false, "alert" => "primary", "error" => "ATENÇÃO: remover eventos de <b>férias</b>, <b>início</b> ou <b>fim de ano escolar</b>, poderá refletir no quadro de horário, aulas ministradas e frequência das escolas que a utilizam.<br><br>TEM CERTEZA que deseja continuar? Clique <span class='confirm-delete-event'>aqui</span> para confirmar."]);
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
                            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
                            join calendar c on cs.calendar_fk = c.id
                            where c.id = :id and s.day = :day and s.month = :month")->bindParam(":id", $_POST["calendarId"])->bindParam(":day", $dt->format("j"))->bindParam(":month", $dt->format("n"))->queryAll();
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
            select count(s.id) as qtd from schedule s 
            join classroom cr on s.classroom_fk = cr.id 
            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
            join calendar c on cs.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["calendar_removal_id"])->queryRow();
            if ((int)$result["qtd"] > 0) {
                echo json_encode(["valid" => false, "error" => "Não se pode remover calendários quando existe quadro de horário desta etapa preenchido por alguma escola."]);
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
        $error = "";
        $stagesToRemove = [];
        $stagesToInsert = [];
        if (empty($_POST["stages"])) {
            $error .= "Calendário deve conter etapas.";
        } else {
            //Valida stages que estão sendo usados em outros calendários
            foreach ($_POST["stages"] as $stage) {
                $calendar = Yii::app()->db->createCommand("select c.title from calendar_stages cs inner join calendar c on (cs.calendar_fk = c.id) where YEAR(c.start_date) = :year and c.id != :calendarId and stage_fk = :stage")
                    ->bindParam(":year", Yii::app()->user->year)
                    ->bindParam(":calendarId", $_POST["id"])
                    ->bindParam(":stage", $stage)
                    ->queryRow();
                if ($calendar != null) {
                    if ($error == "") {
                        $error .= "Já existe outro calendário com a(s) seguinte(s) etapa(s) selecionada(s):<br><br>";
                    }
                    $edcensoStageVsModality = EdcensoStageVsModality::model()->findByPk($stage);
                    $error .= "• <b>" . $edcensoStageVsModality->name . "</b> no Calendário <b>" . $calendar["title"] . "</b><br>";
                }
            }

            //Recupera stages previamente no calendario
            $stagesResult = Yii::app()->db->createCommand("select cs.* from calendar_stages cs inner join calendar c on (cs.calendar_fk = c.id) left join edcenso_stage_vs_modality esvm on (esvm.id = cs.stage_fk) where YEAR(c.start_date) = :year and c.id = :calendarId")
                ->bindParam(":year", Yii::app()->user->year)
                ->bindParam(":calendarId", $_POST["id"])
                ->queryAll();
            if ($error == "") {
                //Lista stages que estão sendo removidos
                foreach ($stagesResult as $rowStage) {
                    if (!in_array($rowStage["stage_fk"], $_POST["stages"])) {
                        $result = Yii::app()->db->createCommand("select count(*) qtd from schedule s join classroom cr on s.classroom_fk = cr.id join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk where cs.stage_fk = :stage")
                            ->bindParam(":stage", $rowStage["stage_fk"])
                            ->queryRow();
                        if ((int)$result["qtd"] == 0) {
                            array_push($stagesToRemove, $rowStage["stage_fk"]);
                        } else {
                            $error .= "Etapa(s) com quadro de horário preenchido não pode(m) ser removido(s):<br><br>";
                            $edcensoStageVsModality = EdcensoStageVsModality::model()->findByPk($rowStage["stage_fk"]);
                            $error .= "• <b>" . $edcensoStageVsModality->name . "</b><br>";
                            break;
                        }
                    }
                }
            }
        }
        if ($error == "") {
            //Lista stages que estão sendo adicionados
            foreach ($_POST["stages"] as $selectedStage) {
                if (array_search($selectedStage, array_column($stagesResult, 'stage_fk')) === false) {
                    array_push($stagesToInsert, $selectedStage);
                }
            }
            if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
                $calendar = Calendar::model()->findByPk($_POST["id"]);
                Log::model()->saveAction("calendar", $calendar->id, "U", $calendar->title);
                $calendar->title = $_POST["title"];
                $calendar->save();
                foreach ($stagesToRemove as $stage) {
                    $calendarStage = CalendarStages::model()->findByAttributes(['stage_fk' => $stage]);
                    Log::model()->saveAction("calendarStages", $calendarStage->id, "D", $calendarStage->stageFk->name);
                    $calendarStage->delete();
                }
                foreach ($stagesToInsert as $stage) {
                    $calendarStage = new CalendarStages();
                    $calendarStage->calendar_fk = $calendar->id;
                    $calendarStage->stage_fk = $stage;
                    $calendarStage->save();
                }
                echo json_encode(["valid" => true]);
            } else {
                echo json_encode(["valid" => false, "error" => "Apenas administradores podem editar título de calendários."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => $error]);
        }
    }

    public function actionShowStages()
    {
        $result = Yii::app()->db->createCommand("select edcenso_stage_vs_modality.name from calendar_stages inner join edcenso_stage_vs_modality on calendar_stages.stage_fk = edcenso_stage_vs_modality.id where calendar_fk = :id order by edcenso_stage_vs_modality.name")->bindParam(":id", $_POST["id"])->queryAll();
        echo json_encode($result);
    }

    public function actionLoadCalendarData()
    {
        $result = [];
        $calendar = Calendar::model()->findByPk($_POST["id"]);
        $result["id"] = $calendar->id;
        $result["title"] = $calendar->title;
        $result["stages"] = [];
        foreach($calendar->calendarStages as $calendarStage) {
            array_push($result["stages"], $calendarStage->stage_fk);
        }
        echo json_encode($result);
    }

    public function actionLoadUnityPeriods()
    {
        $result = [];
        $result["year"] = Yii::app()->user->year;
        $result["stages"] = [];

        $calendarEvents = CalendarEvent::model()->findAll('calendar_fk = :calendarId and calendar_event_type_fk in (1000, 1001)', ["calendarId" => $_POST["id"]]);
        $calendarInitialDate = "";
        foreach ($calendarEvents as $calendarEvent) {
            if ($calendarEvent->calendarEventTypeFk->id == 1000 ){
                $calendarInitialDate = date("d/m/Y", strtotime($calendarEvent->start_date));
            }
            if ($calendarEvent->calendarEventTypeFk->id == 1001 ){
                $result["calendarFinalDate"] = date("Y/m/d", strtotime($calendarEvent->start_date));
            }
        }

        if (count($calendarEvents) == 2) {
            $criteria = new CDbCriteria();
            $criteria->alias = "cs";
            $criteria->join = "join edcenso_stage_vs_modality esvm on esvm.id = cs.stage_fk";
            $criteria->order = "esvm.name";
            $criteria->condition = "cs.calendar_fk = :calendar_fk";
            $criteria->params = ["calendar_fk" => $_POST["id"]];
            $calendarStages = CalendarStages::model()->findAll($criteria);
            foreach ($calendarStages as $calendarStage) {
                $gradeUnities = GradeUnity::model()->findAll('edcenso_stage_vs_modality_fk = :stage', ["stage" => $calendarStage->stage_fk]);
                $stageArray["title"] = $calendarStage->stageFk->name;
                $stageArray["unities"] = [];
                foreach ($gradeUnities as $index => $gradeUnity) {
                    $unity["id"] = $gradeUnity->id;
                    $unity["name"] = $gradeUnity->name;
                    if ($index == 0) {
                        $unity["initial_date"] = $calendarInitialDate;
                    } else {
                        $unity["initial_date"] = $gradeUnity->gradeUnityPeriods == null ? "" : date("d/m/Y", strtotime($gradeUnity->gradeUnityPeriods[0]->initial_date));

                    }
                    array_push($stageArray["unities"], $unity);
                }
                array_push($result["stages"], $stageArray);
            }
            echo json_encode(["valid" => true, "result" => $result]);
        } else {
            echo json_encode(["valid" => false, "error" => "Devem ser cadastradas as datas inicial e final do ano letivo.<br>"]);
        }

    }

    public function actionEditUnityPeriods()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $error = "";
            if (empty($_POST["gradeUnities"])) {
                $error .= "Unidades não foram preenchidas.";
            } else {
                $ano = Yii::app()->user->year;
                foreach ($_POST['gradeUnities'] as $gup) {
                    $gradeUnityPeriod = GradeUnityPeriods::model()->find('grade_unity_fk = :gradeUnityFk and school_year = :year', [':gradeUnityFk' => $gup["gradeUnityFk"], ':year' => $ano]);
                    var_dump($gradeUnityPeriod);
                    if ($gradeUnityPeriod == null) {
                        $gradeUnityPeriod = new GradeUnityPeriods();
                        $gradeUnityPeriod->grade_unity_fk = $gup["gradeUnityFk"];
                        $gradeUnityPeriod->school_year = $ano;
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
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem editar título de calendários."]);
        }

    }

    public function actionChangeCalendarStatus()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $result = Yii::app()->db->createCommand("
            select count(s.id) as qtd from schedule s 
            join classroom cr on s.classroom_fk = cr.id 
            join calendar_stages cs on cs.stage_fk = cr.edcenso_stage_vs_modality_fk
            join calendar c on cs.calendar_fk = c.id
            where c.id = :id")->bindParam(":id", $_POST["id"])->queryRow();

            $calendar = Calendar::model()->findByPk($_POST["id"]);
            
            if (!$calendar->available || (int)$result["qtd"] == 0) {
                $calendar->available = intVal($calendar->available) == 0 ? 1 : 0;
                $calendar->save();
                echo json_encode(["valid" => true, "available" => $calendar->available]);
            } else {
                echo json_encode(["valid" => false, "error" => "Não se pode indisponibilizar calendários quando existe quadro de horário desta etapa preenchido por alguma escola."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem disponibilizar ou indisponibilizar calendários."]);
        }
    }
}