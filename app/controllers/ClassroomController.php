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
		$model=new Classroom;
                
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
                    $model->attributes=$_POST['Classroom'];
                    if($model->week_days_sunday
                            || $model->week_days_monday 
                            || $model->week_days_tuesday 
                            || $model->week_days_wednesday 
                            || $model->week_days_thursday 
                            || $model->week_days_friday 
                            || $model->week_days_saturday ){
                        if($model->save()){
                                Yii::app()->user->setFlash('success', Yii::t('default', 'Turma Criada com Sucesso:'));
                                $this->redirect(array('index'));
                               }
                     }
		}

		$this->render('create',array(
			'model'=>$model,'complementary_activities' => array()
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
                
//                $baseUrl = Yii::app()->theme->baseUrl; 
//                $cs = Yii::app()->getClientScript();
//                $cs->registerScriptFile($baseUrl.'/js/yourscript.js');
                
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

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
                    
                    $model->attributes=$_POST['Classroom'];
                    if($model->week_days_sunday 
                            || $model->week_days_monday 
                            || $model->week_days_tuesday 
                            || $model->week_days_wednesday 
                            || $model->week_days_thursday 
                            || $model->week_days_friday 
                            || $model->week_days_saturday ){
                        if($model->save())
                                $this->redirect(array('view','id'=>$model->id));
                    }
		}
                $compActs = array();
                if(isset($model->complementary_activity_type_1))
                    array_push($compActs, $model->complementary_activity_type_1);
                if(isset($model->complementary_activity_type_2))
                    array_push($compActs, $model->complementary_activity_type_2);
                if(isset($model->complementary_activity_type_3))
                    array_push($compActs, $model->complementary_activity_type_3);
                if(isset($model->complementary_activity_type_4))
                    array_push($compActs, $model->complementary_activity_type_4);
                if(isset($model->complementary_activity_type_5))
                    array_push($compActs, $model->complementary_activity_type_5);
                if(isset($model->complementary_activity_type_6))
                    array_push($compActs, $model->complementary_activity_type_6);

                
		$this->render('update',array(
			'model'=>$model,'complementary_activities' => $compActs
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            
            if($this->loadModel($id)->delete()){
                Yii::app()->user->setFlash('success', Yii::t('default', 'Turma excluída com sucesso:'));
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
