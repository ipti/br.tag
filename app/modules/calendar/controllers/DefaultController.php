<?php

    class DefaultController extends Controller {
        /**
         * @return array action filters
         */
        public function filters() {
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
        public function accessRules() {
            return [
                [
                    'allow',  // allow all users to perform 'index' and 'view' actions
                    'actions' => ['index', 'view'], 'users' => ['*'],
                ], [
                    'allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions' => ['create', 'createEvent', 'update', 'event', 'changeEvent', 'others', 'SetActual'],
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

        public function actionIndex() {
            /** @var $school CalendarSchool */
            $school = CalendarSchool::model()->findByPk(Yii::app()->user->school);
            $calendar = $school->getActualCalendar();
            $event = new CalendarEvent();

            $this->render('index', ["modelCalendar" => $calendar, "modelEvent" => $event]);
        }

        public function actionCreate() {
            $calendar = new Calendar();
            $attributes = isset($_POST['Calendar']) ? $_POST['Calendar'] : NULL;
            $copyFrom = isset($_POST['copy']) ? $_POST['copy'] : NULL;
            if ($attributes != NULL) {
                $calendar->attributes = $attributes;
                $calendar->school_fk = Yii::app()->user->school;
                if ($calendar->validate()) {
                    $calendar->save();
                    if ($calendar->actual == 1) {
                        $calendars = $calendar->schoolFk->calendars;
                        foreach ($calendars as $c) {
                            $c->actual = 0;
                            $c->save();
                        }
                        $calendar->actual = 1;
                        $calendar->save();

                        if ($copyFrom != NULL) {
                            /** @var $calendarBase Calendar */
                            $calendarBase = Calendar::model()->findByPk($copyFrom);
                            $events = $calendarBase->getCopyableEvents();

                            foreach ($events as $event) {
                                $calendarStart = new DateTime($calendar->start_date);
                                $calendarEnd = new DateTime($calendar->end_date);
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

                                if ($calendarStart->format('Y') != $calendarEnd->format('Y')) {
                                    $newStart = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventStart->format('m'), $eventStart->format('d'), $calendarEnd->format('Y'))));
                                    $newEnd = new DateTime(date("d-m-Y", mktime(0, 0, 0, $eventEnd->format('m'), $eventEnd->format('d'), $calendarEnd->format('Y'))));

                                    $e = new CalendarEvent();
                                    $e->attributes = $event->attributes;

                                    $e->start_date = $newStart->format("Y-m-d");
                                    $e->end_date = $newEnd->format("Y-m-d");

                                    $e->calendar_fk = $calendar->id;
                                    $e->id = NULL;
                                    $e->save();
                                }
                            }
                        }
                    }
                    Log::model()->saveAction("calendar", $calendar->id, "C", $calendar->school_year);
                    Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'School Calendar created successfully!'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
                }
            }
            $this->render('index', ["modelCalendar" => $calendar, "modelEvent" => new CalendarEvent()]);
        }

        public function actionCreateEvent() {
            $event = new CalendarEvent();
            $attributes = isset($_POST['CalendarEvent']) ? $_POST['CalendarEvent'] : NULL;
            if ($attributes != NULL) {
                $event->attributes = $attributes;
                if ($event->validate()) {
                    $event->save();
                    Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->school_year);
                    Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'Event created successfully!'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
                }
            }
            $this->render('index', [
                "modelCalendar" => $this->loadModel($event->calendar_fk), "modelEvent" => new CalendarEvent()
            ]);
        }

        public function actionEvent($id) {
            $event = CalendarEvent::model()->findByPk($id);
            echo json_encode($event->attributes);
        }

        public function actionChangeEvent() {
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

                if ($event->validate()) {
                    $event->save();
                    Log::model()->saveAction("calendar", $event->calendar_fk, "U", $event->calendarFk->school_year);
                    Yii::app()->user->setFlash('success', Yii::t('calendarModule.index', 'Event created successfully!'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('calendarModule.index', 'Something went wrong!'));
                }
            }
            $this->render('index', [
                "modelCalendar" => $this->loadModel($event->calendar_fk), "modelEvent" => new CalendarEvent()
            ]);

        }

        public function actionOthers() {
            /** @var $school CalendarSchool */
            $school = CalendarSchool::model()->findByPk(Yii::app()->user->school);
            $calendars = $school->calendars;
            $this->render('others', ['calendars' => $calendars]);
        }

        public function actionSetActual() {
            /** @var $school CalendarSchool */
            /** @var $calendar Calendar */
            $id = $_POST['id'];
            $calendar = Calendar::model()->findByPk($id);
            $school = $calendar->schoolFk;
            $actual = $school->getActualCalendar();

            if ($actual != NULL) {
                $actual->actual = 0;
                $actual->save();
            }

            $calendar->actual = 1;
            if (!$calendar->save()) {
                if ($actual != NULL) {
                    $actual->actual = 1;
                    $actual->save();
                }
            }
            header("location:" . yii::app()->createUrl("/calendar/default/others"));
        }

        public function loadModel($id) {
            $model = Calendar::model()->findByPk($id);
            if ($model === NULL) {
                throw new CHttpException(404, 'The requested page does not exist.');
            }

            return $model;
        }

    }