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
                'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual', 'RemoveCalendar', 'DeleteEvent', 'editCalendarTitle'],
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

    public function actionCreateEvent()
    {
        $event = new CalendarEvent();
        $attributes = isset($_POST['CalendarEvent']) ? $_POST['CalendarEvent'] : NULL;
        if ($attributes != NULL) {
            $event->attributes = $attributes;
            if (CalendarEventType::model()->findByPk($attributes["calendar_event_type_fk"])->unique_day === "1") {
                $event->end_date = $event->start_date;
                CalendarEvent::model()->deleteAllByAttributes("calendar_fk = :calendar_fk and calendar_event_type_fk = :calendar_event_type_fk", ["calendar_fk" => $attributes["calendar_fk"], "calendar_event_type_fk" => $attributes["calendar_event_type_fk"]]);
            }
            if ($event->validate()) {
                $event->save();
                Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
                Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'Event created successfully!'));
                header("location:" . yii::app()->createUrl("/calendar/default/index"));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
            }
        }
        $this->render('index', [
            "modelCalendar" => $this->loadModel($event->calendar_fk), "modelEvent" => new CalendarEvent()
        ]);
    }

    public function actionEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST["id"]);
        echo json_encode($event->attributes);
    }

    public function actionChangeEvent()
    {
        /** @var CalendarEvent $event */
        $attributes = isset($_POST['CalendarEvent']) ? $_POST['CalendarEvent'] : NULL;
        if ($attributes != NULL) {
            $event = NULL;
            if ($attributes["id"] == -1) {
                $event = new CalendarEvent();
                $event->attributes = $attributes;
                $event->setAttribute("id", NULL);
            } else {
                $event = CalendarEvent::model()->findByPk($attributes["id"]);
                $event->attributes = $attributes;
            }
            if (CalendarEventType::model()->findByPk($attributes["calendar_event_type_fk"])->unique_day === "1") {
                $event->end_date = $event->start_date;
                $start_scholar_year_date = CalendarEvent::model()->find("calendar_fk = :calendar_fk and calendar_event_type_fk = :calendar_event_type_fk", ["calendar_fk" => $attributes["calendar_fk"], "calendar_event_type_fk" => $attributes["calendar_event_type_fk"]]);
                if ($start_scholar_year_date->id !== $event->id) {
                    $start_scholar_year_date->delete();
                }
            }
            if ($event->validate()) {
                $event->save();
                Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
                Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'Event created successfully!'));
                $this->redirect($attributes["url"]);
            } else {
                Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
            }
        }
        $this->render('index', [
            "modelCalendar" => $this->loadModel($event->calendar_fk), "modelEvent" => new CalendarEvent()
        ]);
    }

    public function actionDeleteEvent()
    {
        $event = CalendarEvent::model()->findByPk($_POST['CalendarEvent']["id"]);
        Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->title);
        $event->delete();
        $this->redirect($_POST['CalendarEvent']["url"]);
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
        $this->redirect($_POST["Calendar"]["url"]);
    }
}