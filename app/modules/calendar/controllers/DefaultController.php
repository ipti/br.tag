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
                'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual', 'RemoveCalendar', 'DeleteEvent', 'editCalendarTitle', 'ShowStages', 'changeCalendarStatus'],
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
            $isSoftUnavailableEvent = $_POST["eventTypeFk"] == 101;
            $isPreviousDate = strtotime($_POST["startDate"]) < strtotime('now');

            if ($event == null) {
                $event = new CalendarEvent();
                $event->school_fk = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? null : Yii::app()->user->school;
            } else {
                $isHardUnavailableEvent = !$isHardUnavailableEvent ? $event->calendar_event_type_fk == 1000 || $event->calendar_event_type_fk == 1001 || $event->calendar_event_type_fk == 102 : $isHardUnavailableEvent;
                $isSoftUnavailableEvent = !$isSoftUnavailableEvent ? $event->calendar_event_type_fk == 101 : $isSoftUnavailableEvent;
                $isPreviousDate = !$isPreviousDate ? strtotime($event->start_date) < strtotime('now') : $isPreviousDate;
            }
            // if ((int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
            //     echo json_encode(["valid" => false, "error" => "Não é possivel alterar eventos de férias, início ou fim de ano escolar quando existe turma com quadro de horário preenchido."]);
            // }
            if ((int)$result["qtd"] > 0 && $isSoftUnavailableEvent && $isPreviousDate) {
                echo json_encode(["valid" => false, "error" => "Não é possivel alterar eventos de feriados com datas anteriores à atual quando existe turma com quadro de horário preenchido."]);
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
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem alterar este evento."]);
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
            $isSoftUnavailableEvent = $event->calendar_event_type_fk == 101;
            $isPreviousDate = strtotime($event->start_date) < strtotime('now');
            if ((int)$result["qtd"] > 0 && $isHardUnavailableEvent) {
                echo json_encode(["valid" => false, "error" => "Não se pode remover eventos de férias, início ou fim de ano escolar quando existe turma: (a) com a mesma etapa do calendário; e (b) com quadro de horário preenchido."]);
            } else if ((int)$result["qtd"] > 0 && $isSoftUnavailableEvent && $isPreviousDate) {
                echo json_encode(["valid" => false, "error" => "Não se pode remover eventos de feriados com datas anteriores à atual quando existe turma: (a) com a mesma etapa do calendário; e (b) com quadro de horário preenchido."]);
            } else {
                $color = $event->calendarEventTypeFk->color;
                Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
                $event->delete();
                echo json_encode(["valid" => true, "id" => $_POST["id"], "color" => $color]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem remover este evento."]);
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
                echo json_encode(["valid" => false, "error" => "Não se pode remover calendários quando existe turma: (a) com a mesma etapa do calendário; e (b) com quadro de horário preenchido."]);
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

    public function actionEditCalendarTitle()
    {
        if (Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)) {
            $calendar = Calendar::model()->findByPk($_POST["id"]);
            Log::model()->saveAction("calendar", $calendar->id, "U", $calendar->title);
            $calendar->title = $_POST["title"];
            $calendar->save();
            echo json_encode(["valid" => true]);
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem editar título de calendários."]);
        }
    }

    public function actionShowStages()
    {
        $result = Yii::app()->db->createCommand("select edcenso_stage_vs_modality.name from calendar_stages inner join edcenso_stage_vs_modality on calendar_stages.stage_fk = edcenso_stage_vs_modality.id where calendar_fk = :id order by edcenso_stage_vs_modality.name")->bindParam(":id", $_POST["id"])->queryAll();
        echo json_encode($result);
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
                $calendar->available = $calendar->available ? 0 : 1;
                $calendar->save();
                echo json_encode(["valid" => true, "available" => $calendar->available]);
            } else {
                echo json_encode(["valid" => false, "error" => "Não se pode indisponibilizar calendários quando  quando existe turma: (a) com a mesma etapa do calendário; e (b) com quadro de horário preenchido."]);
            }
        } else {
            echo json_encode(["valid" => false, "error" => "Apenas administradores podem disponibilizar ou indisponibilizar calendários."]);
        }
    }
}