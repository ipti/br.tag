<?php

class ConfigurationController extends Controller
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSchool()
    {
        $year = Yii::app()->user->school;
        $model = SchoolConfiguration::model()->findByAttributes(array("school_inep_id_fk" => $year));

        if (!isset($model))
            $model = new SchoolConfiguration;

        if (isset($_POST['SchoolConfiguration'])) {
            $model->setAttributes($_POST['SchoolConfiguration']);

            if ($model->save()) {
                if (Yii::app()->getRequest()->getIsAjaxRequest())
                    Yii::app()->end();
                else
                    $this->redirect(array('index'));
            }
        }
        $this->render('school', array('model' => $model));
    }

    public function actionClassroom()
    {
        if (isset($_POST['Classrooms'])) {
            $Classrooms_ids = $_POST['Classrooms'];
            $year = Yii::app()->user->year;
            $logYear = "";
            foreach ($Classrooms_ids as $id) {
                $classroom = Classroom::model()->findByPk($id);
                $logYear = $classroom->school_year;
                $class_board = ClassBoard::model()->findAllByAttributes(array('classroom_fk' => $id));
                $teaching_data = InstructorTeachingData::model()->findAllByAttributes(array('classroom_id_fk' => $id));

                $newClassroom = new Classroom();
                $newClassroom->attributes = $classroom->attributes;
                $newClassroom->school_year = $year;
                $newClassroom->id = null;
                $newClassroom->inep_id = null;
                $save = $newClassroom->save();
                if ($save) {
                    $save = true;
                    foreach ($class_board as $cb) {
                        $newClassBorad = new ClassBoard();
                        $newClassBorad->attributes = $cb->attributes;
                        $newClassBorad->id = null;
                        $newClassBorad->classroom_fk = $newClassroom->id;
                        $save = $save && $newClassBorad->save();

                    }
                    foreach ($teaching_data as $td) {
                        $newTeachingData = new InstructorTeachingData();
                        $newTeachingData->attributes = $td->attributes;
                        $newTeachingData->id = null;
                        $newTeachingData->classroom_id_fk = $newClassroom->id;
                        $newTeachingData->classroom_inep_id = null;
                        $save = $save && $newTeachingData->save();
                    }
                }
            }
            if ($save) {

                Log::model()->saveAction("wizard_classroom", $logYear, "C", $logYear);
                Yii::app()->user->setFlash('success', Yii::t('default', 'Turmas reutilizadas com sucesso!'));
                $this->redirect('?r=classroom');
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Erro na reutilização das Turmas.'));
                $this->render('classrooms', array(
                    'title' => Yii::t('default', 'Reaproveitamento das Turmas')
                ));
            }
            return true;
        }
        $this->render('classrooms', array(
            'title' => Yii::t('default', 'Reaproveitamento das Turmas')
        ));
    }

    public function actionStudent()
    {
        if (isset($_POST["Classrooms"], $_POST["StudentEnrollment"])) {
            $save = true;
            $logYear = "";
            foreach ($_POST["Classrooms"] as $classroom) {
                $emrollments = StudentEnrollment::model()->findAll("classroom_fk = :c", array("c" => $classroom));
                $emrollments->status = 2;
                $logYear = Classroom::model()->findByPk($classroom)->school_year;
                foreach ($emrollments as $e) {
                    $enrollment = new StudentEnrollment();
                    $enrollment->attributes = $_POST["StudentEnrollment"];

                    $st = StudentIdentification::model()->findByPk($e->student_fk);
                    $c = Classroom::model()->findByPk($enrollment->classroom_fk);
                    $exist = StudentEnrollment::model()->findAll("classroom_fk = :c AND student_fk = :s",
                        array("c" => $c->id, "s" => $st->id));
                    //Se não existe, cadastra
                    if (count($exist) == 0) {
                        $enrollment->school_inep_id_fk = Yii::app()->user->school;
                        $enrollment->student_fk = $st->id;
                        $enrollment->classroom_fk = $c->id;
                        $enrollment->student_inep_id = $st->inep_id;
                        $enrollment->classroom_inep_id = $c->inep_id;
                        $enrollment->status = 1;
                        $save = $save && $enrollment->save();
                    }
                }
            }
            if ($save) {
                Log::model()->saveAction("wizard_student", $logYear, "C", $logYear);
                Yii::app()->user->setFlash('success', Yii::t('default', 'Alunos matriculados com sucesso!'));
            } else {
                Yii::app()->user->setFlash('error', Yii::t('default', 'Erro na matrícula dos Alunos.'));
            }
            $this->render('index');
        } else {
            $this->render('students', array(
                'title' => Yii::t('default', 'Student Configuration')
            ));
        }
    }

    public function actionGetStudents()
    {
        if (isset($_POST["Classrooms"]) && !empty($_POST["Classrooms"])) {
            $id = $_POST["Classrooms"];
            $criteria = new CDbCriteria();
            $criteria->join = "JOIN student_enrollment AS se ON (se.student_fk = t.id AND se.classroom_fk = $id)";
            $criteria->order = "name ASC";

            $data = CHtml::listData(StudentIdentification::model()->findAll($criteria), 'id', 'name' . 'id');

            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo "";
        }
    }

}
