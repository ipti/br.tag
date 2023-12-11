<?php

class FoodMenuController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $MODEL_FOOD_MENU = 'FoodMenu';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array(
                    'index',
                    'view',
                    'create',
                    'update',
                    'plateAccordion',
                    'getFood',
                    'getTacoFoods',
                    'getPublicTarget',
                    'getMealType',
                    'getFoodMeasurement'),
				'users'=>array('@'),
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        $modelFoodMenu = new FoodMenu;
        $request = Yii::app()->request->getPost('foodMenu');

        // Verifica se há dados na requisição enviada
		if($request !== null)
		{
            if(
                isset($request["start_date"]) &&
                isset($request["final_date"]) &&
                isset($request["food_public_target"]) &&
                isset($request["description"])
            )
                {
                    $sucess = true;
                    $transaction = Yii::app()->db->beginTransaction();
                    // Atribui valores às propriedades do model foodMenu (Cardápio)
                    $startTimestamp = strtotime(str_replace('/', '-', $request["start_date"]));
                    $finalTimestamp = strtotime(str_replace('/', '-', $request["final_date"]));
                    $modelFoodMenu->start_date = date('Y-m-d', $startTimestamp);
                    $modelFoodMenu->final_date = date('Y-m-d', $finalTimestamp);
                    $modelFoodMenu->description = $request['description'];

                    // Verifica se a ação de salvar foodMenu ocorreu com sucesso
                    if($modelFoodMenu->save()){

                        // Atribui valores às propriedades do model FoodMenuVsFoodPublicTarget (Tabela N:N entre cardápio e publico alvo)
                        $publicTarget = FoodPublicTarget::model()->findByPk($request['food_public_target']);
                        $foodMenuVsPublicTarget = new FoodMenuVsFoodPublicTarget;
                        $foodMenuVsPublicTarget->food_menu_fk = $modelFoodMenu->id;
                        $foodMenuVsPublicTarget->food_public_target_fk = $publicTarget->id;
                        $foodMenuVsPublicTarget->save();

                        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];

                        foreach($weekDays as $day){
                            // Verifica se existe alguma refeição para o dia
                            if($request[$day] !== null){
                                // $meals se trata da lista de refeições que um dia da semana pode ter
                                $meals = $request[$day];
                                foreach($meals as $meal)
                                {
                                    $foodMenuMeal = new FoodMenuMeal;
                                    $foodMealType = FoodMealType::model()->findByPk($meal["food_meal_type"]);

                                    $foodMenuMeal->food_menuId = $modelFoodMenu->id;
                                    $foodMenuMeal->$day = 1;
                                    $foodMenuMeal->turn = $meal['turn'];
                                    $foodMenuMeal->sequence = $meal['sequence'];
                                    $foodMenuMeal->meal_time = $meal["time"];
                                    $foodMenuMeal->food_meal_type_fk = $foodMealType->id;

                                    if($foodMenuMeal->save())
                                    {
                                        // $meal["meals_component"] se trata da lista de pratos que uma refeição pode ter
                                        foreach($meal["meals_component"] as $component)
                                        {
                                            $foodMenuMealComponent = new FoodMenuMealComponent;
                                            $foodMenuMealComponent->food_menu_mealId = $foodMenuMeal->id;
                                            $foodMenuMealComponent->description = $component["description"];

                                            if($foodMenuMealComponent->save())
                                            {
                                                // $component["food_ingredients"] se trata da lista
                                                foreach($component["food_ingredients"] as $ingredient)
                                                {
                                                    $foodIngredient = new FoodIngredient;
                                                    $foodSearch = Food::model()->findByPk($ingredient["food_id_fk"]);
                                                    $foodIngredient->food_id_fk = $foodSearch->id;
                                                    $foodIngredient->amount = $ingredient["amount"];
                                                    $foodIngredient->food_menu_meal_componentId = $foodMenuMealComponent->id;
                                                    $foodMeasurement = FoodMeasurement::model()->findByPk($ingredient["food_measurement_id"]);
                                                    $foodIngredient->food_measurement_fk = $foodMeasurement->id;
                                                    if(!$foodIngredient->save())
                                                    {
                                                        // echo 'Cardápio foi cadastrado com sucesso.';
                                                        echo 'Ocorreu um erro. Não foi possível salvar um dos ingredientes <br>';
                                                        $sucess = false;
                                                    }
                                                    else
                                                    {
                                                        echo 'Cardápio foi cadastrado com sucesso.<br>';
                                                        // Yii::app()->end();
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        echo 'Ocorreu um erro. Não foi possível salvar uma refeição';
                                        Yii::app()->end();
                                        // Yii::app()->user->setFlash('error', 'Ocorreu um erro ao salvar uma refeição! Tente novamente');
                                    }
                                }
                            }
                        }
                    }
                    else{
                        echo 'Ocorreu um erro. Não foi possível salvar o cardápio';
                        Yii::app()->end();
                        // Yii::app()->user->setFlash('error', 'Ocorreu um erro ao salvar o cardápio! Tente novamente.');
                    }
                    // Verifica se todos os comandos SQL foram executados corretamente
                    if($sucess){
                        $transaction->commit();
                        //  Yii::app()->user->setFlash('success', 'Cardápio salvo com sucesso!');
					
                    }else{
                        $transaction->rollback();
                        // Yii::app()->user->setFlash('error', 'Ocorreu um erro ao salvar o registro! Verifique as informações e tente novamente.');
                    }
                }
                // Log::model()->saveAction(
                //     "foodMenu", $modelFoodMenu->id, "C", $modelFoodMenu->description
                // );
                // Yii::app()->user->setFlash($flash, Yii::t('default', $msg));
            }else{
			$this->render('create', array(
				'model'=>$modelFoodMenu,
			));
			Yii::app()->end();
        }
	}
	public function actionGetTacoFoods() {
		$foods = Food::model()->findAll(array(
            'select' => 'id, description'
        ));
		$resultArray = array();
		foreach ($foods as $food) {
            $resultArray[$food->id] = $food->description;
        }
		echo json_encode($resultArray);
	}
	public function actionGetFood() {
		$idFood = Yii::app()->request->getQuery('idFood');
		$food = Food::model()->findByAttributes(array('id' => $idFood));
		$result = array();
		$result["id"] = $food->id;
		$result["name"] = $food->description;
		$result["kcal"] = is_numeric($food->energy_kcal) ? round($food->energy_kcal, 2) : $food->energy_kcal;
		$result["pt"] = is_numeric($food->protein_g) ? round($food->protein_g, 2) : $food->protein_g;
		$result["lip"] = is_numeric($food->lipidius_g) ? round($food->lipidius_g, 2) : $food->lipidius_g;
		$result["cho"] = is_numeric($food->cholesterol_mg) ? round($food->cholesterol_mg, 2) : $food->cholesterol_mg;

		echo CJSON::encode($result);
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FoodMenu']))
		{
			$model->attributes=$_POST['FoodMenu'];
			if($model->save()){
				$this->redirect(array('view','id'=>$model->id));
			}

		}
        $modelMenu = [
            "name" => "Cárdapio 2023",
            "food_public_target" => "1",
            "start_date" => "09/11/2023",
            "final_date" => "10/12/2023",
            "observation" =>  "teste",
            "sunday" =>  [],
            "monday" => [
                [
                    "time" => "07:30",
                    "sequence"=> 0,
                    "turn"=> "M",
                    "food_meal_type" =>1,
                    "meals_component" =>[
                        [
                            "description" => "nome prato",
                            "food_ingredients" => [
                                [
                                    "amount" => "12",
                                    "food_id_fk" => "1",
                                    "food_measure_unit_id" => "1"
                                ]
                            ]
                        ],
                        [
                            "description" => "nome prato2",
                            "food_ingredients" => [
                                [
                                    "amount" => "12",
                                    "food_id_fk" => "1",
                                    "food_measure_unit_id" => "1"
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    "time" => "12:30",
                    "sequence"=> 0,
                    "turn"=> "T",
                    "food_meal_type" =>2,
                    "meals_component" =>[]
                ]
            ],
            "tuesday" => [
                [
                    "time" => "19:30",
                    "sequence"=> 0,
                    "turn"=> "N",
                    "food_meal_type" =>3
                ]
            ],
            "wednesday" => [
            ],
            "thursday" => [
                
            ],
            "friday" => [
            ],
            "saturday" => []
        ];
		$this->render('update',array(
			'model'=>$model,
			'modelMenu'=>$modelMenu,
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

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

		$returnUrl = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : 'admin';
		if (filter_var($returnUrl, FILTER_VALIDATE_URL)) {
			$this->redirect($returnUrl);
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('FoodMenu');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FoodMenu('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FoodMenu'])){
			$model->attributes=$_GET['FoodMenu'];
		}


		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FoodMenu the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FoodMenu::model()->findByPk($id);
		if($model===null)
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FoodMenu $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='food-menu-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    /**
     * Método que retorna os públicos alvos que podem estar relacionados a um cardápio
     */
    public function actionGetPublicTarget(){

        $publicsTarget = FoodPublicTarget::model()->findAll(
            array(
                'select' => 'id, name'
            )
        );
		$resultArray = array();
		foreach ($publicsTarget as $publicTarget) {
            $resultArray[$publicTarget->id] = $publicTarget->name;
        }
		echo json_encode($resultArray);

    }
    /**
     * Método que retorna os tipos de refeição
     */
    public function actionGetMealType(){
        $mealsType = FoodMealType::model()->findAll();
        $mealsType = CHtml::listData($mealsType, 'id', 'description');
        $options = array();
        foreach($mealsType as $value => $description){
            array_push(
                $options,
                CHtml::tag('option', ['value'=> $value],
                CHtml::encode($description),TRUE));
        }
        echo CJSON::encode($options);
    }

    /**
     * Método que retorna os tipos de medidas que podem ser utilizadas ao cadastrar um ingrediente a um prato
     */
    public function actionGetFoodMeasurement(){
        $foodMeasurements = FoodMeasurement::model()->findAll();
        $options = array();
        foreach($foodMeasurements as $foodMeasurement){
            array_push(
                $options,
                array(
                    "id" => $foodMeasurement->id,
                    "unit" => $foodMeasurement->unit,
                    "value" => $foodMeasurement->value,
                    "measure" => $foodMeasurement->measure
                ));
        }
        echo CJSON::encode($options);
    }
}
