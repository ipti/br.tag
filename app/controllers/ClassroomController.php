<?php
//-----------------------------------------CLASSE VALIDADA ATÉ A SEQUENCIA 35!!------------------------
class ClassroomController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='fullmenu';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','getassistancetype',
                                        'updateassistancetypedependencies','updatecomplementaryactivity',
                                        'getcomplementaryactivitytype'),
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
        
        public function actionGetAssistanceType(){
            $classroom = new Classroom();
            $classroom->attributes = $_POST['Classroom'];

            $schoolStructure = SchoolStructure::model()->findByPk($classroom->school_inep_fk);
            
            echo CHtml::tag('option', array('value' => null),CHtml::encode('Selecione o tipo de assistencia'), true);
            if($schoolStructure != null){

                if($schoolStructure->complementary_activities == 1 || $schoolStructure->complementary_activities == 2 ){
                     echo CHtml::tag('option', array('value' => '4', "selected" =>"selected"),CHtml::encode('Atividade Complementar'), true);
                }else if($schoolStructure->aee == 1 || $schoolStructure->aee == 2 ){
                    echo CHtml::tag('option', array('value' => '5', "selected" =>"selected"),CHtml::encode('Atendimento Educacional Especializado (AEE)'), true);
                }else {
                    echo CHtml::tag('option', array('value' => '0', $classroom->assistance_type == 0 ? "selected" : "deselected" => $classroom->assistance_type == 0 ? "selected" : "deselected" ),CHtml::encode('Não se Aplica'), true);
                    echo CHtml::tag('option', array('value' => '1', $classroom->assistance_type == 1 ? "selected" : "deselected" => $classroom->assistance_type == 1 ? "selected" : "deselected" ),CHtml::encode('Classe Hospitalar'), true);
                    echo CHtml::tag('option', array('value' => '2', $classroom->assistance_type == 2 ? "selected" : "deselected" => $classroom->assistance_type == 2 ? "selected" : "deselected" ),CHtml::encode('Unidade de Internação Socioeducativa'), true);
                    echo CHtml::tag('option', array('value' => '3', $classroom->assistance_type == 3 ? "selected" : "deselected" => $classroom->assistance_type == 3 ? "selected" : "deselected" ),CHtml::encode('Unidade Prisional'), true);
                }  
                
            }
        }
        
        public function actionUpdateComplementaryActivity(){
            $classroom = new Classroom();
            $classroom->attributes = $_POST['Classroom'];
            
            $id1 = $classroom->complementary_activity_type_1;
            $id2 = $classroom->complementary_activity_type_2;
            $id3 = $classroom->complementary_activity_type_3;
            $id4 = $classroom->complementary_activity_type_4;
            $id5 = $classroom->complementary_activity_type_5;
            $id6 = $classroom->complementary_activity_type_6;
            
            $where = '';
            $where .= $id1 != 'null' ? 'id!="'.$id1.'" ':'';
            $where .= $id2 != 'null' ? '&& id!="'.$id2.'" ':'';
            $where .= $id3 != 'null' ? '&& id!="'.$id3.'" ':'';
            $where .= $id4 != 'null' ? '&& id!="'.$id4.'" ':'';
            $where .= $id5 != 'null' ? '&& id!="'.$id5.'" ':'';
            $where .= $id6 != 'null' ? '&& id!="'.$id6.'" ':'';
            
            
            $data = EdcensoComplementaryActivityType::model()->findAll($where);
            $data = CHtml::listData($data, 'id', 'name');
            
            echo CHtml::tag('option', array('value' => 'null'), CHtml::encode('Selecione a atividade complementar'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        }
        
        
        public function actionUpdateAssistanceTypeDependencies(){
            $classroom = new Classroom();
            $classroom->attributes = $_POST['Classroom'];
            
            $result = array('Stage'=>'', 'MaisEdu'=>'', 'Modality'=>'', 'AeeActivity'=>'');
               
            $result['MaisEdu'] = $classroom->assistance_type == 1 || $classroom->assistance_type == 5;
            
            $result['AeeActivity'] = $classroom->assistance_type != 5;
            
            $where = '';
            $result['Modality'] = CHtml::tag('option', array('value' => null),CHtml::encode('Selecione a modalidade'), true);
            
            if($result['MaisEdu']){
                $result['Modality'] .= CHtml::tag('option', array('value' => '3', "selected" => "selected"),CHtml::encode('Educação de Jovens e Adultos (EJA)'), true);
                $where = '(id<4 || id>38) && id!=38 && id!=41 && id!=56';
            }else{
                $result['Modality'] .= CHtml::tag('option', array('value' => '1', $classroom->modality == 1? "selected" : "deselected" => $classroom->modality == 1? "selected" : "deselected" ),CHtml::encode('Ensino Regular'), true);
                $result['Modality'] .= CHtml::tag('option', array('value' => '2', $classroom->modality == 2? "selected" : "deselected" => $classroom->modality == 2? "selected" : "deselected" ),CHtml::encode('Educação Especial - Modalidade Substitutiva'), true);
            }
                   
            $result['StageEmpty'] = false;
            
            if($classroom->assistance_type == 2 || $classroom->assistance_type == 3){
                $data = EdcensoStageVsModality::model()->findAll('id!=1 && id!=2 && id!=3 && id!=56 '.$where);
            }else if($classroom->assistance_type == 4 || $classroom->assistance_type == 5 || $classroom->assistance_type == "null"){
                $data = array();
                $result['StageEmpty'] = true;
            }else{
                $data = EdcensoStageVsModality::model()->findAll($where);
            }
            $data = CHtml::listData($data, 'id', 'name');

            $result['Stage'] = CHtml::tag('option', array('value' => null), 'Selecione o estágio vs modalidade', true);

            foreach ($data as $value => $name) {
                $result['Stage'] .= CHtml::tag('option', array('value' => $value, $classroom->edcenso_stage_vs_modality_fk == $value? "selected" : "deselected" => $classroom->edcenso_stage_vs_modality_fk == $value? "selected" : "deselected" ), CHtml::encode($name), true);
            }
            
            echo json_encode($result);
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$modelClassroom=new Classroom;
                $modelTeachingData=new InstructorTeachingData;
                $saveClassroom = false;
                $saveTeachingData = false;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Classroom']))
		{
                    // Em adição, inserir a condição dos campos 25-35 (AEE activities) 
                    // de nao deixar criar com todos os campos igual a 0
                    if(isset($_POST['Classroom']["complementary_activity_type_1"])){
                        $compActs = $_POST['Classroom']["complementary_activity_type_1"];
                    }
                    $_POST['Classroom']["complementary_activity_type_1"] = 
                            isset($compActs[0]) ? $compActs[0] : null;
                    $_POST['Classroom']["complementary_activity_type_2"] = 
                            isset($compActs[1]) ? $compActs[1] : null;
                    $_POST['Classroom']["complementary_activity_type_3"] = 
                            isset($compActs[2]) ? $compActs[2] : null;
                    $_POST['Classroom']["complementary_activity_type_4"] = 
                            isset($compActs[3]) ? $compActs[3] : null;
                    $_POST['Classroom']["complementary_activity_type_5"] = 
                            isset($compActs[4]) ? $compActs[4] : null;
                    $_POST['Classroom']["complementary_activity_type_6"] = 
                            isset($compActs[5]) ? $compActs[5] : null;
                    $modelClassroom->attributes = $_POST['Classroom'];
                    if ($modelClassroom->week_days_sunday
                            || $modelClassroom->week_days_monday
                            || $modelClassroom->week_days_tuesday
                            || $modelClassroom->week_days_wednesday
                            || $modelClassroom->week_days_thursday
                            || $modelClassroom->week_days_friday
                            || $modelClassroom->week_days_saturday) {
                        $saveClassroom = $modelClassroom->validate();
                        
                    } else {
                        $modelClassroom->addError('week_days_sunday', Yii::t('default', 'Week Days') . ' ' . Yii::t('default', 'cannot be blank'));
                    }
                }
                
                 
               //==============TEACHING DATA 
          $error = '';
        if (isset($_POST['InstructorTeachingData'])) {
            $modelTeachingData->attributes = $_POST['InstructorTeachingData'];
             //Setar a foreing key
             //=== MODEL TeachingData
            $disciplines = $modelTeachingData->discipline_1_fk;
            $countDisciplines = count($disciplines);
            //Máximo 13           
            $modelTeachingData->discipline_1_fk = isset($disciplines[0]) ? $disciplines[0] : NULL;
            $modelTeachingData->discipline_2_fk = isset($disciplines[1]) ? $disciplines[1] : NULL;
            $modelTeachingData->discipline_3_fk = isset($disciplines[2]) ? $disciplines[2] : NULL;
            $modelTeachingData->discipline_4_fk = isset($disciplines[3]) ? $disciplines[3] : NULL;
            $modelTeachingData->discipline_5_fk = isset($disciplines[4]) ? $disciplines[4] : NULL;
            $modelTeachingData->discipline_6_fk = isset($disciplines[5]) ? $disciplines[5] : NULL;
            $modelTeachingData->discipline_7_fk = isset($disciplines[6]) ? $disciplines[6] : NULL;
            $modelTeachingData->discipline_8_fk = isset($disciplines[7]) ? $disciplines[7] : NULL;
            $modelTeachingData->discipline_9_fk = isset($disciplines[8]) ? $disciplines[8] : NULL;
            $modelTeachingData->discipline_10_fk = isset($disciplines[9]) ? $disciplines[9] : NULL;
            $modelTeachingData->discipline_11_fk = isset($disciplines[10]) ? $disciplines[10] : NULL;
            $modelTeachingData->discipline_12_fk = isset($disciplines[11]) ? $disciplines[11] : NULL;
            $modelTeachingData->discipline_13_fk = isset($disciplines[12]) ? $disciplines[12] : NULL;

           
            //============================

                // Setar todos os school_inep_id
                
                if ($modelTeachingData->validate()) {
                    //Get classInepID
                    $classRoom = Classroom::model()->findByPk($modelTeachingData->classroom_id_fk);
                    $modelTeachingData->classroom_inep_id = $classRoom->inep_id;
                    $saveTeachingData = true;
                }
        }
        
        if ($saveClassroom && $saveTeachingData && $modelClassroom->save() 
                         && $modelTeachingData->save()) {
                            Yii::app()->user->setFlash('success', Yii::t('default', 'Turma adicionada com sucesso!'));
                            $this->redirect(array('index'));
                        }

        $instructor_id = isset($_GET['instructor_id']) ? $_GET['instructor_id']: NULL;
               //===================================         

		$this->render('create',array(
			'modelClassroom'=>$modelClassroom,
                        'complementary_activities' => array(),
                        'modelTeachingData' => $modelTeachingData,
                        'error' => $error,
                        'instructor_id'=> $instructor_id,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$modelClassroom=$this->loadModel($id);
                $modelTeachingData[]=  00000; // pesquisar todos os teachingDAtas
                $saveClassroom = false;
                $saveTeachingData = false;
//                $baseUrl = Yii::app()->theme->baseUrl; 
//                $cs = Yii::app()->getClientScript();
//                $cs->registerScriptFile($baseUrl.'/js/yourscript.js');
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($modelClassroom);

		if(isset($_POST['Classroom']))
		{
                    if(isset($_POST['Classroom']["complementary_activity_type_1"])){
                        $compActs = $_POST['Classroom']["complementary_activity_type_1"];
                    }
                    $_POST['Classroom']["complementary_activity_type_1"] = 
                            isset($compActs[0]) ? $compActs[0] : null;
                    $_POST['Classroom']["complementary_activity_type_2"] = 
                            isset($compActs[1]) ? $compActs[1] : null;
                    $_POST['Classroom']["complementary_activity_type_3"] = 
                            isset($compActs[2]) ? $compActs[2] : null;
                    $_POST['Classroom']["complementary_activity_type_4"] = 
                            isset($compActs[3]) ? $compActs[3] : null;
                    $_POST['Classroom']["complementary_activity_type_5"] = 
                            isset($compActs[4]) ? $compActs[4] : null;
                    $_POST['Classroom']["complementary_activity_type_6"] = 
                            isset($compActs[5]) ? $compActs[5] : null;
                    
                    $modelClassroom->attributes=$_POST['Classroom'];
                    if($modelClassroom->week_days_sunday 
                            || $modelClassroom->week_days_monday 
                            || $modelClassroom->week_days_tuesday 
                            || $modelClassroom->week_days_wednesday 
                            || $modelClassroom->week_days_thursday 
                            || $modelClassroom->week_days_friday 
                            || $modelClassroom->week_days_saturday ){
                        
                            $saveClassroom = $modelClassroom->validate();
                        
                    } else {
                        $modelClassroom->addError('week_days_sunday',  Yii::t('default', 'Week Days').' '.Yii::t('default', 'cannot be blank'));
                    }
		}
                $compActs = array();
                if(isset($modelClassroom->complementary_activity_type_1))
                    array_push($compActs, $modelClassroom->complementary_activity_type_1);
                if(isset($modelClassroom->complementary_activity_type_2))
                    array_push($compActs, $modelClassroom->complementary_activity_type_2);
                if(isset($modelClassroom->complementary_activity_type_3))
                    array_push($compActs, $modelClassroom->complementary_activity_type_3);
                if(isset($modelClassroom->complementary_activity_type_4))
                    array_push($compActs, $modelClassroom->complementary_activity_type_4);
                if(isset($modelClassroom->complementary_activity_type_5))
                    array_push($compActs, $modelClassroom->complementary_activity_type_5);
                if(isset($modelClassroom->complementary_activity_type_6))
                    array_push($compActs, $modelClassroom->complementary_activity_type_6);

                
		$this->render('update',array(
			'model'=>$modelClassroom,'complementary_activities' => $compActs
		));
                
                //===== TEACHING DATA =====
                //=======================================

        $modelTeachingData = $this->loadModel($id, $this->InstructorTeachingData);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($modelStudentIdentification);   
        $saveTeachingData = false;

        //==================================
        
        $error[] = '';
        if (isset($_POST['InstructorTeachingData'])) {
            $modelTeachingData->attributes = $_POST['InstructorTeachingData'];

            //=== MODEL TeachingData
            $disciplines = $modelTeachingData->discipline_1_fk;
            $countDisciplines = count($disciplines);
            //Máximo 13           
//            $modelTeachingData->discipline_1_fk = isset($disciplines[0]) ? $disciplines[0] : NULL;
//            $modelTeachingData->discipline_2_fk = isset($disciplines[1]) ? $disciplines[1] : NULL;
//            $modelTeachingData->discipline_3_fk = isset($disciplines[2]) ? $disciplines[2] : NULL;
//            $modelTeachingData->discipline_4_fk = isset($disciplines[3]) ? $disciplines[3] : NULL;
//            $modelTeachingData->discipline_5_fk = isset($disciplines[4]) ? $disciplines[4] : NULL;
//            $modelTeachingData->discipline_6_fk = isset($disciplines[5]) ? $disciplines[5] : NULL;
//            $modelTeachingData->discipline_7_fk = isset($disciplines[6]) ? $disciplines[6] : NULL;
//            $modelTeachingData->discipline_8_fk = isset($disciplines[7]) ? $disciplines[7] : NULL;
//            $modelTeachingData->discipline_9_fk = isset($disciplines[8]) ? $disciplines[8] : NULL;
//            $modelTeachingData->discipline_10_fk = isset($disciplines[9]) ? $disciplines[9] : NULL;
//            $modelTeachingData->discipline_11_fk = isset($disciplines[10]) ? $disciplines[10] : NULL;
//            $modelTeachingData->discipline_12_fk = isset($disciplines[11]) ? $disciplines[11] : NULL;
//            $modelTeachingData->discipline_13_fk = isset($disciplines[12]) ? $disciplines[12] : NULL;

            $saveTeachingData = true;
            //============================
                if ($modelTeachingData->validate()) {
                   
// CORRIGIR !!!!!!!!!!!!!
                    //Get classInepID
                    $classRoom = Classroom::model()->findByPk($modelTeachingData->classroom_id_fk);
                    $modelTeachingData->classroom_inep_id = $classRoom->inep_id;

                    if ($saveClassroom && $saveTeachingData && $modelClassroom->save() && $modelTeachingData->save() ) {
                         Yii::app()->user->setFlash('success', Yii::t('default', 'Turma alterada com sucesso!'));
                           // $this->redirect(array('index'));
                        $this->redirect(array('view', 'id' => $modelClassroom->id));
                    }
                }
            
        }
        //====================================
        $this->render('update', array(
            'model' => $modelTeachingData,
            'error' => $error,
        ));
        
        
        //=====================================================

                //=================================
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            
            if($this->loadModel($id)->delete()){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Turma excluída com sucesso!'));
                $this->redirect(array('index'));
            }else{
                throw new CHttpException(404,'A página requisitada não existe.');
            }
            
            
        }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Classroom',
                array('pagination' => array(
                        'pageSize' => 12,
                        )));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Classroom('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Classroom']))
			$model->attributes=$_GET['Classroom'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Classroom::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'A página requisitada não existe.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='classroom-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
