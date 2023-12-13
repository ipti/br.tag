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
                    'getFoodMeasurement',
                    'viewMeals'),
				'users'=>array('@'),
			),
            // array('allow',  // deny all users
            //     'actions' => array('viewMeals'),
            //     'users'=>array('*'),
			// ),
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
		$modelFoodMenu = FoodMenu::model()->findByPk($id);
        $transaction = Yii::app()->db->beginTransaction();
        try{
            $modelFoodMenuMeals = FoodMenuMeal::model()->findAllByAttributes(array('food_menuId' => $modelFoodMenu->id));
            foreach($modelFoodMenuMeals as $modelFoodMenuMeal){
                $modelFoodMealComponents = FoodMenuMealComponent::model()->findAllByAttributes(array('food_menu_mealId' => $modelFoodMenuMeal->id));
                foreach($modelFoodMealComponents as $modelFoodMealComponent){
                    $modelFoodIngredients = FoodIngredient::model()
                    ->deleteAllByAttributes(array('food_menu_meal_componentId'=> $modelFoodMealComponent->id));
                }
                $modelFoodMenuMeal->delete();
            }
            $modelFoodMenu->delete();
            $transaction->commit();
            header('HTTP/1.1 200 OK');
            Log::model()->saveAction("foodMenu", $id, "D", $modelFoodMenu->description);
            // echo json_encode(["valid" => true, "message" => "Cardápio excluído com sucesso!"]);
            Yii::app()->end();
        }catch(Exception $e){
            $transaction->rollback();
            throw new CHttpException(500, $e->getMessage());
        }
	}
    /**
     * Essa função deve retornar um objeto com todas as refeições em todos os cardápios
     * cadastrados para cada um dos dias da semana, onde a semana será baseada no dia atual
     */
    public function actionViewMeals(){
        // Get the current date
        date_default_timezone_set('America/Bahia');
        $date = date('Y-m-d', time());

        // Create a filter to select foodMenus
        $criteria = new CDbCriteria();
        $criteria->addCondition("start_date <= :date AND final_date >= :date");
        $criteria->params = array(':date' => $date);

        // Select in database filtered foodMenus and assign it to models
        $modelFoodMenus = FoodMenu::model()->findAll($criteria);
        $foodMenu = new FoodMenuObject();
        // Iterate throw foodMenus selected and assign to response object
        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        foreach($weekDays as $day){
            foreach($modelFoodMenus as $modelFoodMenu){
                $modelMeals = FoodMenuMeal::model()->findAllByAttributes(array("food_menuId" => $modelFoodMenu->id));
                $foodMenu->setDayMeals($day, $modelMeals);
            }
        }

        $response = json_encode((array) $foodMenu);

        $this->render('viewMeals', array(
            'data'=>$response,
        ));
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
        $publicsTarget = FoodPublicTarget::model()->findAll();
        $publicsTarget = CHtml::listData($publicsTarget, 'id', 'name');
        $options = array();
        foreach($publicsTarget as $value => $name){
            array_push(
                $options,
                CHtml::tag('option', ['value' => $value],
                CHtml::encode($name),TRUE));
        }
        echo CJSON::encode($options);
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

/**
 * Classes that represents a foodMenu which will be manipulated and send as response to client request
 */
class FoodMenuObject {
    public $description;
    public $food_public_target;
    public $start_date;
    public $final_date;
    public $sunday = [];
    public $monday = [];
    public $tuesday = [];
    public $wednesday = [];
    public $thursday = [];
    public $friday = [];
    public $saturday = [];

    public function __construct($model = null, $foodPublicTarget = null){
        if($model !== null && $foodPublicTarget !== null){
            $this->description = $model->description;
            $this->food_public_target = $foodPublicTarget['id'];
        }
    }

    public function setDateFormated($model){
        $inputStartDate = $model->start_date;
        $inputFinalDate = $model->final_date;
        $startDate = new DateTime($inputStartDate);
        $finalDate = new DateTime($inputFinalDate);
        $this->start_date = $startDate->format("m/d/Y");
        $this->final_date = $finalDate->format("m/d/Y");
    }

    public function setDayMeals($day, $modelMeals){
        foreach($modelMeals as $modelMeal){
            if($modelMeal->$day){
                $modelComponents = FoodMenuMealComponent::model()->findAllByAttributes(array('food_menu_mealId' => $modelMeal->id));
                $meal = new MealObject($modelMeal);
                $meal->setComponentMeal($modelComponents);
                array_push($this->$day, (array) $meal);
            }
        }
    }
}
/**
 * Classe that represents a meal from a foodMenu to be loaded in JSON response
 */
class MealObject
{
    public $time;
    public $sequence;
    public $turn;
    public $food_meal_type;
    public $meals_component = [];

    public function __construct($model){
        $this->time = $model->meal_time;
        $this->sequence = $model->sequence;
        $this->turn = $model->turn;
        $this->food_meal_type = $model->food_meal_type_fk;
    }

    public function setComponentMeal($modelComponents){
        foreach($modelComponents as $modelComponent){
            $modelIngredients = FoodIngredient::model()->findAllByAttributes(array('food_menu_meal_componentId' => $modelComponent->id));
            $component = new MealComponentObject($modelComponent);
            $component->setComponentIngredients($modelIngredients);
            array_push($this->meals_component, (array) $component);
        }
    }
}
/**
 * Classe that represents a Component from a meal to be loaded in JSON response
 */
class MealComponentObject
{
    public $description;
    public $ingredients = [];

    public function __construct($model){
        $this->description = $model->description;
    }

    public function setComponentIngredients($modelIngredients){
        foreach($modelIngredients as $modelIngredient){
            $ingredient = new IngredientObject($modelIngredient);
            array_push($this->ingredients, (array) $ingredient);
        }
    }
}
/**
 * Classe that represents an ingredient from a component to be loaded in JSON response
 */
class IngredientObject
{
    public $food_id_fk;
    public $amount;
    public $food_measure_unit_id;

    public function __construct($model){
        $this->food_id_fk = $model->food_id_fk;
        $this->amount = $model->amount;
        $this->food_measure_unit_id = $model->food_measurement_fk;
    }
}
