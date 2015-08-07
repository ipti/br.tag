<?php

class ClassesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'fullmenu';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('index',
                    'frequency', 'saveFrequency',
                    'classContents', 'saveClassContents', 'saveContent',
                    'getdisciplines', 'getclasses', 'getclassesforfrequency', 'getcontents'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays the Class Contents
     */
    public function actionClassContents() {
        $model = new Classes;
        $dataProvider = new CActiveDataProvider('Classes');
        $this->render('classContents', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     *   Save a new Content  
     */
    public function actionSaveContent() {
        if (isset($_POST['name']) && !empty($_POST['name'])) {
            $name = strtoupper($_POST['name']);
            $description = isset($_POST['description']) ? strtoupper($_POST['description']) : "";
            $type = isset($_POST['type']) ? $_POST['type'] : 1;
            $exist = ClassResources::model()->exists('name = :n', ['n' => $name]);
            if (!$exist) {
                $newContent = new ClassResources();
                $newContent->name = $name;
                $newContent->description = $description;
                $newContent->type = $type;
                $newContent->save();

                $return = ['id' => $newContent->id, 'name' => $newContent->name];
                echo json_encode($return);
            }
        }
    }

    /**
     * Save the contents for each class.
     */
    public function actionSaveClassContents() {
        if (isset($_POST['classroom'], $_POST['month'], $_POST['day'])) {
            $classroom = $_POST['classroom'];
            $discipline = $_POST['disciplines'];
            $month = $_POST['month'];
            $days = $_POST['day'];
            $discipline = ($discipline == "Todas as disciplinas") ? null : $discipline;
            $allDisciplines = ($discipline == null);
            $allSaved = true;

            $classes = Classes::model()->findAllByAttributes(array(
                'classroom_fk' => $classroom,
                'discipline_fk' => $discipline,
                'month' => $month));

            foreach ($classes as $class) {
                $classContents = $class->classContents;
                foreach ($classContents as $classContent) {
                    $classContent->delete();
                }
            }
            foreach ($classes as $class) {
                if (isset($days[$class->day])) {
                    $contents = $days[$class->day];
                    foreach ($contents as $content) {
                        $newClassContent = new ClassHasContent();
                        $newClassContent->class_fk = $class->id;
                        $newClassContent->content_fk = $content;
                        $allSaved = $allSaved && $newClassContent->save();
                    }
                }
            }

            if ($allSaved) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Plano de Aula Atualizado com Sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Houve um erro inesperado!'));
            }
        }

        $this->redirect(array('classContents'));
    }

    /**
     * Save the frequency for each student and class.
     */
    public function actionSaveFrequency() {
        //@done s2 - modificar banco para adicionar schedule às faltas
        //@done s2 - regerar os modelos

        set_time_limit(0);
        ignore_user_abort();
        $everyThingIsOkay = true; //tudo sempre começa bem...
        $classroomID = $_POST['classroom'];
        $disciplineID = $_POST['disciplines'];
        $month = $_POST['month'];
        $instructorFaults = isset($_POST['day']) ? $_POST['day'] : array();

        $infos = $this->actionGetClassesForFrequency($classroomID, $disciplineID, $month);
        $classDays = $infos['days'];

        $disciplineID = ($disciplineID == "Todas as disciplinas") ? null : $disciplineID;
        $allDisciolines = ($disciplineID == null);

        $classes = null;
        $classes = Classes::model()->findAllByAttributes(array(
            'classroom_fk' => $classroomID,
            'discipline_fk' => $disciplineID,
            'month' => $month));

        //cadastra novas aulas
        if ($classes == null) {
            $year = date('Y');
            $time = mktime(0, 0, 0, $month, 1, $year);

            $monthDays = date('t', $time);
            for ($day = 1; $day <= $monthDays; $day++) {
                $time = mktime(0, 0, 0, $month, $day, $year);
                $weekDay = date('w', $time);
                $days = $classDays[$weekDay];
                sort($days);
                $classDays[$weekDay] = $days;

                foreach ($days as $schedule) {
                    if ($schedule != 0) {
                        $class = new Classes();
                        $class->classroom_fk = $classroomID;
                        $class->discipline_fk = $disciplineID;
                        $class->day = $day;
                        $class->month = $month;
                        $class->classtype = 'N';
                        $class->given_class = isset($instructorFaults[$day][$schedule]) ? 0 : 1;
                        $class->schedule = $schedule;

                        if ($class->validate() && $class->save()) {
                            array_push($classes, $class);
                            $everyThingIsOkay &= true;
                        } else {
                            $everyThingIsOkay &= false;
                        }
                    }
                    if (!$everyThingIsOkay)
                        break;
                }
            }
            //atualiza aulas
        }else {
            foreach ($instructorFaults as $day => $schedules) {
                foreach ($schedules as $schedule) {
                    $class = Classes::model()->findByAttributes(array(
                        'classroom_fk' => $classroomID,
                        'discipline_fk' => $disciplineID,
                        'month' => $month,
                        'day' => $day,
                        'schedule' => $schedule));

                    //Adicionar novas se não existir
                    if ($class == null) {
                        $class = new Classes();
                        $class->classroom_fk = $classroomID;
                        $class->discipline_fk = $disciplineID;
                        $class->day = $day;
                        $class->month = $month;
                        $class->classtype = 'N';
                        $class->given_class = isset($instructorFaults[$day][$schedule]) ? 0 : 1;
                        $class->schedule = $schedule;
                        if ($class->validate() && $class->save()) {
                            array_push($classes, $class);
                            $everyThingIsOkay &= true;
                        } else {
                            $everyThingIsOkay &= false;
                        }
                        //Atualizar existentes
                    } else {
                        $class->given_class = isset($instructorFaults[$day][$schedule]) ? 0 : 1;
                        $everyThingIsOkay &= $class->save();
                    }
                    if (!$everyThingIsOkay)
                        break;
                }
            }
        }

        $faults = array();
        //cadastrar faltas
        if (isset($_POST['student'])) {
            $studentsFaults = $_POST['student'];
            foreach ($studentsFaults as $studentID => $fault) {
                foreach ($fault as $d => $day) {
                    foreach ($day as $schedule => $s) {
                        $classID = null;
                        //Procure a Aula
                        foreach ($classes as $class) {
                            if ($class->day == $d && $class->schedule == $schedule) {
                                $classID = $class->id;
                                //Para quando achar
                                break;
                            }
                        }
                        //Caso a aula não exista, adicione-a
                        if ($classID == null) {
                            $newClass = new Classes();
                            $newClass->classroom_fk = $classroomID;
                            $newClass->discipline_fk = $disciplineID;
                            $newClass->day = $d;
                            $newClass->month = $month;
                            $newClass->classtype = 'N';
                            $newClass->given_class = 1;
                            $newClass->schedule = $schedule;
                            if ($newClass->validate() && $newClass->save()) {
                                array_push($classes, $newClass);
                                $classID = $newClass->id;
                                $everyThingIsOkay &= true;
                            } else {
                                $everyThingIsOkay &= false;
                            }
                        }

                        //Cadastre a falta na aula correta
                        $fault = new ClassFaults;
                        $fault->class_fk = $classID;
                        $fault->student_fk = $studentID;
                        $fault->schedule = $schedule;
                        if ($fault->validate() && $fault->save()) {
                            array_push($faults, $fault);
                            $everyThingIsOkay &= true;
                        } else {
                            $everyThingIsOkay &= false;
                        }

                        if (!$everyThingIsOkay)
                            break;
                    }
                    if (!$everyThingIsOkay)
                        break;
                }
                if (!$everyThingIsOkay)
                    break;
            }
        }

        if ($everyThingIsOkay) {
            Yii::app()->user->setFlash('success', Yii::t('default', 'Frequência Atualizada com Sucesso!'));
        } else {
            Yii::app()->user->setFlash('error', Yii::t('default', 'Houve um erro inesperado!'));
        }


        set_time_limit(30);
        $this->redirect(array('frequency'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Open the Frequency View.
     */
    public function actionFrequency() {
        $model = new Classes;
        $dataProvider = new CActiveDataProvider('Classes');
        $this->render('frequency', array(
            'dataProvider' => $dataProvider,
            'model' => $model,
        ));
    }

    /**
     * Get all disciplines by classroom
     */
    public function actionGetDisciplines() {
        echo CHtml::tag('option', array('value' => null), CHtml::encode('Todas as disciplinas'), true);

        if (!isset($_POST['classroom']) || empty($_POST['classroom']))
            return true;

        $classroom = Classroom::model()->findByPk($_POST['classroom']);
        $disciplines = ClassroomController::classroomDiscipline2array($classroom);
        $disciplinesLabels = ClassroomController::classroomDisciplineLabelArray();

        foreach ($disciplines as $i => $discipline) {
            if ($discipline != 0) {
                echo CHtml::tag('option', array('value' => $i), CHtml::encode($disciplinesLabels[$i]), true);
            }
        }
    }

    /**
     * Get all contents
     */
    public function actionGetContents() {
        $contents = ClassResources::model()->findAllByAttributes(['type' => ClassResources::CONTENT]);
        $return = [];
        foreach ($contents as $content) {
            $return[$content->id] = $content->name;
        }
        echo json_encode($return);
    }

    /**
     * Get all classes by classroom, disciplene and month
     */
    public function actionGetClasses($classroom, $month, $disciplines) {
        $discipline = ($disciplines == "Todas as disciplinas") ? null : $disciplines;
        $classes = Classes::model()->findAllByAttributes(array(
            'classroom_fk' => $classroom,
            'discipline_fk' => $discipline,
            'month' => $month));
        $return = [];
        foreach ($classes as $class) {
            $day = $class->day;
            if ($class->given_class == 1) {
                $return[$day] = [];

                $classContents = $class->classContents;
                foreach ($classContents as $classContent) {
                    $id = $classContent->contentFk->id;
                    $description = $classContent->contentFk->description;

                    $return[$day][$id] = $description;
                }
            }
        }

        if ($return === []) {
            echo json_decode(null);
        } else {
            echo json_encode($return);
        }
    }

    /**
     * Get all classes by classroom, disciplene and month
     */
    public function actionGetClassesForFrequency($classroom = null, $discipline = null, $month = null) {
        $classroom = $classroom == null ? $_POST['classroom'] : $classroom;
        $discipline = $discipline == null ? $_POST['disciplines'] : $discipline;
        $month = $month == null ? $_POST['month'] : $month;

        $discipline = ($discipline == "Todas as disciplinas") ? null : $discipline;
        $allDisciplines = ($discipline == null);

        $classes = null;

        $classes = Classes::model()->findAllByAttributes(array(
            'classroom_fk' => $classroom,
            'discipline_fk' => $discipline,
            'month' => $month));
        if ($allDisciplines) {
            $classboards = ClassBoard::model()->findAllByAttributes(array(
                'classroom_fk' => $classroom));
        } else {
            $classboards = ClassBoard::model()->findAllByAttributes(array(
                'classroom_fk' => $classroom,
                'discipline_fk' => $discipline));
        }


        $return = array('days' => array(), 'faults' => array(), 'students' => array());

        $classDays = array();
        for ($i = 0; $i <= 6; $i++) {
            $classDays[$i] = array();
        }

        foreach ($classboards as $cb) {

            $schedulesStringArray = array();

            $schedulesStringArray[0] = $cb->week_day_sunday;
            $schedulesStringArray[1] = $cb->week_day_monday;
            $schedulesStringArray[2] = $cb->week_day_tuesday;
            $schedulesStringArray[3] = $cb->week_day_wednesday;
            $schedulesStringArray[4] = $cb->week_day_thursday;
            $schedulesStringArray[5] = $cb->week_day_friday;
            //$schedulesStringArray[6] = $cb->week_day_saturday;
            $schedulesStringArray[6] = 1;
            for ($i = 0; $i <= 6; $i++) {
                $temp = $schedulesStringArray[$i];
                if ($temp == "") {
                    $temp = array();
                } else {
                    $temp = $temp[0] == ';' ? substr($temp, 1) : $temp;
                    $temp = $temp[0] == '0' ? substr($temp, 1) : $temp;
                    $temp = $temp[0] == ';' ? substr($temp, 1) : $temp;

                    $temp = $temp == "" ? array() : explode(';', $temp);
                }
                if (count($temp) >= 1) {
                    if ($allDisciplines) {
                        $classDays[$i] = array(1);
                    } else {
                        $classDays[$i] = array_merge($classDays[$i], $temp);
                        $classDays[$i] = array_unique($classDays[$i]);
                    }
                } else {
                    $classDays[$i] = ($classDays[$i] == array() || $classDays[$i] == array(0)) ? array(0) : $classDays[$i];
                };
            }
        }

        $return['days'] = $classboards == null ? null : $classDays;

        if ($classes == null) {

            $cr = Classroom::model()->findByPk($classroom);

            //@done s2 - Trocar o ano atual pelo da turma
            $year = $cr->school_year; //$year = date('Y');
            $time = mktime(0, 0, 0, $month, 1, $year);

            $monthDays = date('t', $time);
            for ($day = 1; $day <= $monthDays; $day++) {
                $time = mktime(0, 0, 0, $month, $day, $year);
                $weekDay = date('w', $time);
                $days = $classDays[$weekDay];
                sort($days);
                $classDays[$weekDay] = $days;
            }
        } else {
            foreach ($classes as $c) {
                $schedule = $allDisciplines ? 1 : $c->schedule;

                if ($c->given_class == 1) {
                    $return['faults'][$c->day][$schedule] = isset($return['faults'][$c->day][$schedule]) ? $return['faults'][$c->day][$schedule] : array();

                    $faults = ClassFaults::model()->findAllByAttributes(array('class_fk' => $c->id, 'schedule' => $schedule));

                    foreach ($faults as $f) {
                        $return['faults'][$c->day][$schedule] = isset($return['faults'][$c->day][$schedule]) ? $return['faults'][$c->day][$schedule] : array();
                        $return['faults'][$c->day][$schedule] = array_merge($return['faults'][$c->day][$schedule], array($f->student_fk));
                    }
                } else {
                    $return['instructorFaults'][$c->day] = isset($return['instructorFaults'][$c->day]) ? $return['instructorFaults'][$c->day] : array();
                    $return['instructorFaults'][$c->day] = array_merge($return['instructorFaults'][$c->day], array($schedule));
                }
            }
        }

        $criteria = new CDbCriteria();
        $criteria->with = array('studentFk');
        $criteria->together = true;
        $criteria->order = 'name';
        $enrollments = StudentEnrollment::model()->findAllByAttributes(array('classroom_fk' => $classroom), $criteria);
        $return['students'] = array();
        foreach ($enrollments as $e) {
            $return['students']['name'] = isset($return['students']['name']) ? $return['students']['name'] : array();
            $return['students']['id'] = isset($return['students']['id']) ? $return['students']['id'] : array();
            $return['students']['name'] = array_merge($return['students']['name'], array($e->studentFk->name));
            $return['students']['id'] = array_merge($return['students']['id'], array($e->student_fk));
        }
        echo json_encode($return);
        return $return;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Classes the loaded model
     * @throws CHttpException
     */
    public function loadModel($id) {
        $model = Classes::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Classes $model the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'classes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
