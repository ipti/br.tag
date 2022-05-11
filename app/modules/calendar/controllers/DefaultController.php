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
                'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual', 'RemoveCalendar', 'DeleteEvent', 'editCalendarTitle', 'ShowStages'],
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
            $calendar = Yii::app()->db->createCommand("select calendar.title from calendar_stages inner join calendar on calendar_stages.calendar_fk = calendar.id where YEAR(calendar.start_date) = " . Yii::app()->user->year . " and stage_fk = " . $stage)->queryRow();
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
            Yii::app()->user->setFlash('success', "Calendário Escolar criado com sucesso!");
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
        /** @var CalendarEvent $event */
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        if ($event == null) {
            $event = new CalendarEvent();
        }
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
        $event->school_fk = Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id) ? null : Yii::app()->user->school;
        $event->save();
        Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);

        $start = new DateTime($event->start_date);
        $end = new DateTime($event->end_date);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end);
        $datesToFill = [];
        foreach($period as $dt) {
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

    public function actionDeleteEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        $color = $event->calendarEventTypeFk->color;
        Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
        $event->delete();
        echo json_encode(["valid" => true, "id" => $_POST["id"], "color" => $color]);
    }

    public function actionRemoveCalendar()
    {
        $calendar = Calendar::model()->findByPk($_POST['calendar_removal_id']);
        Log::model()->saveAction("calendar", $calendar->id, "D", $calendar->title);
        $calendar->delete();
        header("location:" . yii::app()->createUrl("/calendar/default/index"));
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
        $calendar = Calendar::model()->findByPk($_POST["Calendar"]["id"]);
        Log::model()->saveAction("calendar", $calendar->id, "U", $calendar->title);
        $calendar->title = $_POST["Calendar"]["title"];
        $calendar->save();
        header("location:" . yii::app()->createUrl("/calendar/default/index"));
    }

    public function actionShowStages()
    {
        $result = Yii::app()->db->createCommand("select edcenso_stage_vs_modality.name from calendar_stages inner join edcenso_stage_vs_modality on calendar_stages.stage_fk = edcenso_stage_vs_modality.id where calendar_fk = " . $_POST["id"] . " order by edcenso_stage_vs_modality.name")->queryAll();
        echo json_encode($result);
    }
}