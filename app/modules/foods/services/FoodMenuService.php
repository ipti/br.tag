<?php
class FoodMenuService
{
    public function getFoodMenu($modelFoodMenu, $publicTarget, $modelMenuMeals)
    {
        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        // Criando objeto de cardápio que será utilizado para armazenar informações sobre o cardápio
        $foodMenu = new FoodMenuObject($modelFoodMenu, $publicTarget);
        $foodMenu->setDateFormated($modelFoodMenu);
        // Atribuindo refeições associadas ao cardápio de acordo com o dia
        foreach ($weekDays as $day) {
            $publicTarget = FoodMenuVsFoodPublicTarget::model()->findByAttributes(array("food_menu_fk" => $modelFoodMenu->id));
            $foodMenu->setDayMeals($day, $modelMenuMeals, $publicTarget->food_public_target_fk);
        }
        return $foodMenu;
    }
    public function getMealType($id)
    {
        $sql = "SELECT fmt.id as food_meal_type, fmt.description,
        TIME_FORMAT(fmm.meal_time, '%H:%i') AS meal_time,
        CASE
            WHEN fmm.turn = 'M' THEN 'Manhã'
            WHEN fmm.turn = 'T' THEN 'Tarde'
            WHEN fmm.turn = 'N' THEN 'Noite'
            ELSE ''
        END AS turn
        FROM food_menu_meal fmm
            INNER JOIN food_meal_type fmt ON fmm.food_meal_type_fk = fmt.id
        WHERE fmm.food_menuId = :id
        GROUP BY fmt.description";

        return Yii::app()->db->createCommand($sql)->bindParam(':id', $id)->queryAll();
    }
    public function getNutritionalValue($id)
    {
        $nutritionalValue = array();
        $sql = '
            select f.id, f.description, f.energy_kcal, f.protein_g, f.carbohydrate_g, f.lipidius_g from food as f
            inner join food_ingredient as fi  on fi.food_id_fk= f.id
            inner join food_menu_meal_component as  fmmc on fmmc.id = fi.food_menu_meal_componentId
            inner join  food_menu_meal as fmm on fmm.id = fmmc.food_menu_mealId
            inner join food_menu as fm  on fm.id = food_menuId
            where fm.id = :id
        ';
        $nutritionalValue = Yii::app()->db->createCommand($sql)->bindParam(':id', $id)->queryAll();

        $kcal = 0;
        $calTotal = 0;
        $ptnTotal = 0;
        $lpdTotal = 0;
        $daysOFWeek = 5;
        foreach ($nutritionalValue as $item) {
            $kcal += $item["energy_kcal"];
            $calTotal += $item["carbohydrate_g"];
            $ptnTotal += $item["protein_g"];
            $lpdTotal += $item["lipidius_g"];
        }
        $kcalAverage = $kcal / $daysOFWeek;

        $calAverage = $calTotal / $daysOFWeek;
        $calpct = (($calAverage * 4) * 100) / $kcalAverage;

        $ptnAvarage = $ptnTotal / $daysOFWeek;
        $ptnpct = (($ptnAvarage * 4) * 100) / $kcalAverage;

        $lpdAvarage = $lpdTotal / $daysOFWeek;
        $lpdpct = (($lpdAvarage * 9) * 100) / $kcalAverage;

        $result["kcalAverage"] = round($kcalAverage);

        $result["calAverage"] = round($calAverage);
        $result["calpct"] = round($calpct);

        $result["ptnAvarage"] = round($ptnAvarage);
        $result["ptnpct"] = round($ptnpct);

        $result["lpdAvarage"] = round($lpdAvarage);
        $result["lpdpct"] = round($lpdpct);


        return $result;
    }
    public function getMelsOfWeek()
    {
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

        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        foreach ($weekDays as $day) {
            foreach ($modelFoodMenus as $modelFoodMenu) {
                $modelMeals = FoodMenuMeal::model()->findAllByAttributes(array("food_menuId" => $modelFoodMenu->id));
                $publicTargetFoodMenu = FoodMenuVsFoodPublicTarget::model()->findByAttributes(array("food_menu_fk" => $modelFoodMenu->id));
                $publicTarget = FoodPublicTarget::model()->findByPk($publicTargetFoodMenu->food_public_target_fk);
                $foodMenu->setDayMeals($day, $modelMeals, $publicTarget);
            }
        }
        return $foodMenu;
    }
    public function createFoodMenuRelations($modelFoodMenu, $request, $transaction)
    {
        $weekDays = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
        foreach ($weekDays as $day) {
            // Verifica se existe alguma refeição para o dia
            if ($request[$day] !== null) {
                // $meals se trata da lista de refeições que um dia da semana pode ter
                $meals = $request[$day];
                $this->createMeals($modelFoodMenu, $meals, $day, $transaction);
            }
        }
    }
    /**
     * Método que salva no banco as alterações referentes às refeições
     */
    private function createMeals($modelFoodMenu, $meals, $day, $transaction)
    {
        foreach ($meals as $meal) {
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
            if ($saveMenuMealResult === false) {
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
    private function createComponents($foodMenuMeal, $meal, $transaction)
    {
        foreach ($meal["meals_component"] as $component) {
            $foodMenuMealComponent = new FoodMenuMealComponent;
            $foodMenuMealComponent->food_menu_mealId = $foodMenuMeal->id;
            $foodMenuMealComponent->description = $component["description"];
            // Verifica se o prato foi salvo com sucesso
            $saveComponentResult = $foodMenuMealComponent->save();
            if ($saveComponentResult === false) {
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
    private function createIngredients($modelComponent, $component, $transaction)
    {
        // $component["food_ingredients"] se trata da lista de ingredientes que um prato possui
        foreach ($component["food_ingredients"] as $ingredient) {
            $foodIngredient = new FoodIngredient;
            $foodSearch = Food::model()->findByPk($ingredient["food_id_fk"]);
            $foodIngredient->food_id_fk = $foodSearch->id;
            $foodIngredient->amount = $ingredient["amount"];
            $foodIngredient->food_menu_meal_componentId = $modelComponent->id;
            $foodMeasurement = FoodMeasurement::model()->findByPk($ingredient["food_measure_unit_id"]);
            $foodIngredient->food_measurement_fk = $foodMeasurement->id;
            $foodIngredient->validate();
            CVarDumper::dump($foodIngredient->getErrors());
            $saveIngredientResult = $foodIngredient->save();
            


            if ($saveIngredientResult === false) {
                // Caso de erro: Falha quando ocorre um erro ao tentar salvar um ingrediente de um prato
                $message = 'Ocorreu um erro ao salvar um ingrediente! Verifique as informações e tente novamente';
                $transaction->rollback();
                throw new CHttpException(500, $message);
            }
        }
    }
}
/**
 * Classes that represents a foodMenu which will be manipulated and send as response to client request
 */
class FoodMenuObject
{
    public $id;
    public $week;
    public $description;
    public $observation;
    public $foodPublicTarget;
    public $startDate;
    public $finalDate;
    public $sunday = [];
    public $monday = [];
    public $tuesday = [];
    public $wednesday = [];
    public $thursday = [];
    public $friday = [];
    public $saturday = [];

    public function __construct($model = null, $foodPublicTarget = null)
    {
        if ($model !== null && $foodPublicTarget !== null) {
            $this->id = $model->id;
            $this->week = $model->week;
            $this->description = $model->description;
            $this->observation = $model->observation;
            $this->foodPublicTarget = $foodPublicTarget['id'];
        }
    }

    public function setDateFormated($model)
    {
        $startDate = DateTime::createFromFormat("Y-m-d", $model->start_date);
        $finalDate = DateTime::createFromFormat("Y-m-d", $model->final_date);
        $this->startDate = $startDate->format("d/m/Y");
        $this->finalDate = $finalDate->format("d/m/Y");
    }

    public function setDayMeals($day, $modelMeals, $publicTarget)
    {
        foreach ($modelMeals as $modelMeal) {
            if ($modelMeal->$day) {
                $modelComponents = FoodMenuMealComponent::model()->findAllByAttributes(array('food_menu_mealId' => $modelMeal->id));
                $modelMealType = FoodMealType::model()->findByPk($modelMeal->food_meal_type_fk);
                $meal = new MealObject($modelMeal, $publicTarget, $modelMealType->description);
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
    public $foodMealType;

    public $foodPublicTargetId;
    public $foodPublicTargetName;
    public $mealsComponent = [];

    public function __construct($model, $foodMenuPublicTarget, $mealDescription)
    {
        $this->time = $model->meal_time;
        $this->sequence = $model->sequence;
        $this->turn = $model->turn;
        $this->foodMealType = $model->food_meal_type_fk;
        $this->foodMealTypeDescription = $mealDescription;
        $this->foodPublicTargetName = $foodMenuPublicTarget->name;
        $this->foodPublicTargetId = $foodMenuPublicTarget->id;
    }

    public function setComponentMeal($modelComponents)
    {
        foreach ($modelComponents as $modelComponent) {
            $modelIngredients = FoodIngredient::model()->findAllByAttributes(array('food_menu_meal_componentId' => $modelComponent->id));
            $component = new MealComponentObject($modelComponent);
            $component->setComponentIngredients($modelIngredients);
            array_push($this->mealsComponent, (array) $component);
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

    public function __construct($model)
    {
        $this->description = $model->description;
    }

    public function setComponentIngredients($modelIngredients)
    {
        foreach ($modelIngredients as $modelIngredient) {
            $foodModel = Food::model()->findByPk($modelIngredient->food_id_fk);
            $ingredient = new IngredientObject($modelIngredient, $foodModel);
            array_push($this->ingredients, (array) $ingredient);
        }
    }
}
/**
 * Classe that represents an ingredient from a component to be loaded in JSON response
 */
class IngredientObject
{
    public $foodIdFk;
    public $foodName;
    public $amount;
    public $foodMeasureUnitId;

    public function __construct($model, $foodModel)
    {
        $this->foodIdFk = $model->food_id_fk;
        $this->foodName = $foodModel->description;
        $this->amount = $model->amount;
        $this->foodMeasureUnitId = $model->food_measurement_fk;
    }
}