<?php

class DefaultController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'getInstructorClassrooms',
                    'getClassroomStudents',
                    'getAeeRecord',
                    'checkStudentAeeRecord',
                    'delete'
                ),
                'users' => array('@'),
            ),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionGetInstructorClassrooms() {
        $sql = "SELECT c.id, c.name
                from classroom c
                join instructor_teaching_data itd on itd.classroom_id_fk = c.id
                join instructor_identification ii on itd.instructor_fk = ii.id
                WHERE ii.users_fk = :users_fk and c.school_year = :user_year and c.aee = 1
                ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':users_fk', Yii::app()->user->loginInfos->id, PDO::PARAM_INT)
        ->bindValue(':user_year', Yii::app()->user->year, PDO::PARAM_INT);

        $classrooms = $command->queryAll();

        echo json_encode($classrooms);
    }

    public function actionGetClassroomStudents() {
        $classroomId = Yii::app()->request->getPost('classroomId');

        $sql = "SELECT std.id, std.name
                FROM student_enrollment se
                JOIN classroom c ON c.id = se.classroom_fk
                JOIN student_identification std ON std.id = se.student_fk
                WHERE c.id = :classroom_id";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':classroom_id', $classroomId, PDO::PARAM_INT);

        $students = $command->queryAll();

        echo json_encode($students);
    }

    public function actionGetAeeRecord() {
        $completeRecord = [];
        $recordId = Yii::app()->request->getPost('recordId');

        $sql = "SELECT sar.id, sar.date, si.name AS studentName, c.name AS classroomName, ii.name AS instructorName
                from student_aee_record sar
                join student_identification si on si.id = sar.student_fk
                join classroom c on c.id = sar.classroom_fk
                join instructor_identification ii on ii.id = sar.instructor_fk
                WHERE sar.id = :record_id";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':record_id', $recordId, PDO::PARAM_INT);

        $aeeRecord = $command->queryAll();


        foreach ($aeeRecord as $record) {
            array_push($completeRecord, [
                "id"=> $record["id"],
                "date" => date("d/m/Y", strtotime($record["date"])),
                "studentName"=> $record["studentName"],
                "classroomName"=> $record["classroomName"],
                "instructorName"=> $record["instructorName"],
            ]);
        }

        echo json_encode($completeRecord);
    }

    public function actionCheckStudentAeeRecord() {
        $studentId = Yii::app()->request->getPost('studentId');

        $criteria = new CDbCriteria();
        $criteria->condition = 't.student_fk = :student';
        $criteria->params = array(':student' => $studentId);
        $existingAeeRecord = StudentAeeRecord::model()->find($criteria);

        $response = $existingAeeRecord ? $existingAeeRecord->id : '';
        echo json_encode($response);
    }

	public function actionCreate()
	{
		$model=new StudentAeeRecord;

		if(Yii::app()->request->isAjaxRequest)
		{
			$classroomId = Yii::app()->request->getPost('classroomId');
			$studentId = Yii::app()->request->getPost('studentId');
			$learningNeeds = Yii::app()->request->getPost('learningNeeds');
			$characterization = Yii::app()->request->getPost('characterization');

            $instructor = InstructorIdentification::model()->findByAttributes(array('users_fk'=>Yii::app()->user->loginInfos->id));

            $model->learning_needs = $learningNeeds;
            $model->characterization = $characterization;
            $model->student_fk = $studentId;
            $model->classroom_fk = $classroomId;
            $model->school_fk = Yii::app()->user->school;
            $model->instructor_fk = $instructor->id;

            if($model->save()) {
                Yii::app()->user->setFlash('success', Yii::t('default', 'Ficha AEE foi cadastrada com sucesso!'));
            }

		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $recordId = Yii::app()->request->getPost('recordId');

		if($recordId != null)
		{
            $learningNeeds = Yii::app()->request->getPost('learningNeeds');
			$characterization = Yii::app()->request->getPost('characterization');

            $model->learning_needs = $learningNeeds;
            $model->characterization = $characterization;

			if($model->save()) {
				Yii::app()->user->setFlash('success', Yii::t('default', 'Ficha AEE foi atualizada com sucesso!'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

        Yii::app()->user->setFlash('success', Yii::t('default', 'Ficha AEE excluída com sucesso!'));

        $this->redirect(array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
        if(Yii::app()->getAuthManager()->checkAccess('instructor', Yii::app()->user->loginInfos->id)) {
            $dataProvider = new CActiveDataProvider('StudentAeeRecord', array(
                'criteria' => array(
                    'with' => array(
                        'instructorFk' => array(
                            'together' => true,
                            'condition' => 'instructorFk.users_fk=:userId',
                            'params' => array(':userId' => Yii::app()->user->loginInfos->id),
                        ),
                        'schoolFk' => array(
                            'together' => true,
                            'condition' => 'schoolFk.inep_id=:schoolId',
                            'params' => array(':schoolId' => Yii::app()->user->school),
                        ),
                    ),
                ),
            ));
        }

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(Yii::app()->getAuthManager()->checkAccess('admin', Yii::app()->user->loginInfos->id)){
            $dataProvider = new CActiveDataProvider('StudentAeeRecord', array(
                'criteria' => array(
                    'with' => array(
                        'schoolFk' => array(
                            'together' => true,
                            'condition' => 'schoolFk.inep_id=:schoolId',
                            'params' => array(':schoolId' => Yii::app()->user->school),
                        ),
                    ),
                ),
            ));
        }

        $this->render('admin',array(
            'dataProvider'=>$dataProvider,
        ));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return StudentAeeRecord the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=StudentAeeRecord::model()->findByPk($id);
		if($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
        }
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param StudentAeeRecord $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='student-aee-record-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
