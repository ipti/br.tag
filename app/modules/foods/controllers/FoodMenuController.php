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
        $transaction = Yii::app()->db->beginTransaction();
        // Verifica se há dados na requisição enviada
        // Caso negativo, renderiza o formulário
		if($request === null)
		{
            $this->render('create', array(
                'model'=>$modelFoodMenu,
            ));
        }

        $allFieldsAreFilled =   isset($request["start_date"]) &&
                                isset($request["final_date"]) &&
                                isset($request["food_public_target"]) &&
                                isset($request["description"]);

        if($allFieldsAreFilled === false){
            // Caso de erro> Falha quando um dos campos obrigatórios do cardápio não foram enviados
            $message = 'Ocorreu um erro! Campos obrigatórios do Cardápio não foram preenchidos.';
            throw new CHttpException(400, $message);
        }

        $message = null;
        // Atribui valores às propriedades do model foodMenu(Cardápio) e trata o formato das datas
        $startTimestamp = strtotime(str_replace('/', '-', $request["start_date"]));
        $finalTimestamp = strtotime(str_replace('/', '-', $request["final_date"]));
        $modelFoodMenu->start_date = date('Y-m-d', $startTimestamp);
        $modelFoodMenu->final_date = date('Y-m-d', $finalTimestamp);
        $modelFoodMenu->observation = $request['observation'];
        $modelFoodMenu->description = $request['description'];

        // Verifica se a ação de salvar foodMenu ocorreu com sucesso, caso falhe encerra a aplicação
        $saveFoodMenuResult = $modelFoodMenu->save();

        if($saveFoodMenuResult == false){
            $message = 'Ocorreu um erro ao salvar o cardápio! Tente novamente.';
            $transaction->rollback();
            throw new CHttpException(500, $message);
        }

        // Atribui valores às propriedades do model FoodMenuVsFoodPublicTarget (Tabela N:N entre cardápio e publico alvo)
        $publicTarget = FoodPublicTarget::model()->findByPk($request['food_public_target']);
        $foodMenuVsPublicTarget = new FoodMenuVsFoodPublicTarget;
        $foodMenuVsPublicTarget->food_menu_fk = $modelFoodMenu->id;
        $foodMenuVsPublicTarget->food_public_target_fk = $publicTarget->id;
        $foodMenuVsPublicTarget->save();

        // Chamando método que irá adicionar novos registros relacionados ao cardápio
        $this->createFoodMenuRelations($modelFoodMenu, $request, $transaction);
        // Salvar alterações no banco
        $transaction->commit();
        header('HTTP/1.1 201 Created');
        Log::model()->saveAction("foodMenu", $modelFoodMenu->id, "C", $modelFoodMenu->description);
        Yii::app()->end();
	}

    /**
     * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        $request = Yii::app()->request->getRawBody();
        $request = json_decode($request, true);
        $modelFoodMenu=$this->loadModel($id);
        $modelMenuMeals = FoodMenuMeal::model()->findAllByAttributes(array('food_menuId'=>$modelFoodMenu->id));

        if($request == null){
             // Bloco de código para identificar qual o público alvo do cardápio
             $publicTargetSql = "
             SELECT fpt.id, fpt.name FROM food_public_target fpt
             LEFT JOIN food_menu_vs_food_public_target fmvfpt ON fmvfpt.food_public_target_fk = fpt.id
             WHERE fmvfpt.food_menu_fk = :id";
             $publicTarget = Yii::app()->db->createCommand($publicTargetSql)->bindParam(':id', $modelFoodMenu->id)->queryRow();

             $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];

             // Criando objeto de cardápio que será utilizado para armazenar informações sobre o cardápio
             $foodMenu = new FoodMenuObject($modelFoodMenu, $publicTarget);
             $foodMenu->setDateFormated($modelFoodMenu);
             // Atribuindo refeições associadas ao cardápio de acordo com o dia
             foreach($weekDays as $day){
                 $foodMenu->setDayMeals($day, $modelMenuMeals);
             }

             // Convertendo objeto do cardápio em um JSON para enviar como resposta da requisição AJAX
             $response = json_encode((array) $foodMenu);

             $this->render('update', array(
                 'data'=>$response,
             ));
        }
        
        // Trecho do código para excluir todos os registros associados ao cardápio
        $transaction = Yii::app()->db->beginTransaction();
        foreach($modelMenuMeals as $modelMenuMeal){
            $modelFoodComponents = FoodMenuMealComponent::model()->findAllByAttributes(array('food_menu_mealId'=>$modelMenuMeal->id));
            foreach($modelFoodComponents as $modelFoodComponent){
                $modelFoodIngredients =  FoodIngredient::model()->findAllByAttributes(array('food_menu_meal_componentId'=>$modelFoodComponent->id));
                foreach($modelFoodIngredients as $modelFoodIngredient){
                    $modelFoodIngredient->delete();
                }
                $modelFoodComponent->delete();
            }
            $modelMenuMeal->delete();
        }
        // Chamada de função que irá salvar as novas informações do cardápio
        $this->createFoodMenuRelations($modelFoodMenu, $request, $transaction);
        $transaction->commit();
        header('HTTP/1.1 200 OK');
        Log::model()->saveAction("foodMenu", $modelFoodMenu->id, "U", $modelFoodMenu->description);
        Yii::app()->end();
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
    protected function createFoodMenuRelations($modelFoodMenu, $request, $transaction){
        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        foreach($weekDays as $day){
            // Verifica se existe alguma refeição para o dia
            if($request[$day] !== null){
                // $meals se trata da lista de refeições que um dia da semana pode ter
                $meals = $request[$day];
                $this->createMeals($modelFoodMenu, $meals, $day, $transaction);
            }
        }
    }
    /**
     * Método que salva no banco as alterações referentes às refeições
     */
    private function createMeals($modelFoodMenu,$meals, $day, $transaction){
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

                    // Verifica se a refeição foi salva com sucesso
                    $saveMenuMealResult = $foodMenuMeal->save();
                    if($saveMenuMealResult === false){
                         // Caso de erro: Falha quando ocorre um erro ao tentar salvar uma refeição
                         $message = 'Ocorreu um erro ao salvar uma refeição! Tente novamente';
                         $transaction->rollback();
                         throw new CHttpException(500, $message);
                    }
                    // Caso de sucesso: a refeição foi salva com sucesso
                    $this->createComponents($foodMenuMeal, $meal, $transaction);
                }
    }

    /**
     * Método que salva no banco as alterações referentes aos pratos
     */
    private function createComponents($foodMenuMeal, $meal, $transaction){
        // $meal["meals_component"] se trata da lista de pratos que uma refeição pode ter
        foreach($meal["meals_component"] as $component)
        {
            $foodMenuMealComponent = new FoodMenuMealComponent;
            $foodMenuMealComponent->food_menu_mealId = $foodMenuMeal->id;
            $foodMenuMealComponent->description = $component["description"];
            // Verifica se o prato foi salvo com sucesso
            $saveComponentResult = $foodMenuMealComponent->save();
            if($saveComponentResult === false){
                $message = "Ocorreu um erro ao salvar um prato! Verifique as informações e tente novamente";
                $transaction->rollback();
                throw new CHttpException(500, $message);
            }
            $this->createIngredients($foodMenuMealComponent, $component, $transaction);
        }
    }

    /**
     * Método que salva no banco as alterações relacionadas aos ingredientes de um prato
     */
    private function createIngredients($modelComponent, $component, $transaction){
        // $component["food_ingredients"] se trata da lista de ingredientes que um prato possui
        foreach($component["food_ingredients"] as $ingredient)
        {
            $foodIngredient = new FoodIngredient;
            $foodSearch = Food::model()->findByPk($ingredient["food_id_fk"]);
            $foodIngredient->food_id_fk = $foodSearch->id;
            $foodIngredient->amount = $ingredient["amount"];
            $foodIngredient->food_menu_meal_componentId = $modelComponent->id;
            $foodMeasurement = FoodMeasurement::model()->findByPk($ingredient["food_measure_unit_id"]);
            $foodIngredient->food_measurement_fk = $foodMeasurement->id;
            $saveIngredientResult = $foodIngredient->save();
            if($saveIngredientResult === false){
                // Caso de erro: Falha quando ocorre um erro ao tentar salvar um ingrediente de um prato
                $message = 'Ocorreu um erro ao salvar um ingrediente! Verifique as informações e tente novamente';
                $transaction->rollback();
                throw new CHttpException(500, $message);
            }
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

    public function __construct($model, $foodPublicTarget){
        $this->description = $model->description;
        $this->food_public_target = $foodPublicTarget['id'];
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
