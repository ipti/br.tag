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
    public function getFoodIngredientsList()
    {
        $result = array();

        $foods = $this->getFoodFromTheMenu();

        $students = $this->getStudents();
        $studentsTurn = $this->getStudentsTurn($students);

        if ($students != null && $foods != null) {
            $result = $this->processFood($foods, $studentsTurn);
        }
        return $result;
    }
    public function processFood($foods, $studentsTurn) {
        $result = array();
        foreach ($foods as $food) {
            // verifica se tem alunos nesse turno
            $turn = $food["turn"];

            $idFood = $food["id"];
            if (!array_key_exists($idFood, $result)) {
                $measure = '';

                switch ($food["measure"]) {
                    case 'g':
                        $measure = "Kg";
                        break;
                    case 'ml':
                        $measure = ($food["measurementUnit"] == "g") ? "kg" : "L";
                        break;
                    default:
                        $measure = $food["measure"];
                        break;
                }
                $result[$idFood] = array(
                    'id' => $idFood,
                    'name' => str_replace(',', '', $food["description"]),
                    'total' => 0, // Inicializa o total como 0
                    'measure' => $measure,
                );
            }

            // Atualiza o total
            $value = $food["total"];

            if($food["measure"] == 'g' || $food["measure"]  == 'ml'){
                $value = $food["total"]/1000;
            }
            $result[$idFood]['total'] +=
                ($value * $studentsTurn[$turn]) + ($value * $studentsTurn["Integral"]);
        }
        return $result;
    }
    private function getStudentsTurn($students){
        $studentsTurn = ['Manhã' => '0', 'Tarde' => '0', 'Noite' => '0', 'Integral' => '0'];

        foreach ($students as $element) {
            $turn = $element['turn'];
            $totalStudents = $element['total_students'];


            $studentsTurn[$turn] = $totalStudents;
        }
        return  $studentsTurn;
    }
    private function getStudents(){
        $sql = "SELECT
        COUNT(*) as total_students,
        CASE
            WHEN c.turn = 'M' THEN 'Manhã'
            WHEN c.turn = 'T' THEN 'Tarde'
            WHEN c.turn = 'N' THEN 'Noite'
            WHEN c.turn = 'I' THEN 'Integral'
            ELSE ''
        END AS turn
    FROM
        student_enrollment
        INNER JOIN classroom as c ON student_enrollment.classroom_fk = c.id
    WHERE
        (status IN (1, 6, 7, 8, 9, 10) OR status IS NULL) AND
        (c.school_year = :user_year AND student_enrollment.school_inep_id_fk = :user_school)
    GROUP BY
        c.turn;
    ";

        return Yii::app()->db->createCommand($sql)
            ->bindParam(':user_year', Yii::app()->user->year)
            ->bindParam(':user_school', Yii::app()->user->school)->queryAll();
    }
    private function getFoodFromTheMenu(){
        date_default_timezone_set('America/Bahia');
        $date = date('Y-m-d', time());

        $sql = "SELECT
        CASE
            WHEN fmm.turn = 'M' THEN 'Manhã'
            WHEN fmm.turn = 'T' THEN 'Tarde'
            WHEN fmm.turn = 'N' THEN 'Noite'
            ELSE ''
        END AS turn,
        fi.food_id_fk,
        f.description,
        f.measurementUnit,
        fm2.measure,
        f.id,
        SUM(fi.amount) as total_amount,
        SUM(fm2.value) as total_value,
        SUM((fi.amount * fm2.value)) as total
        FROM food_menu fm
        JOIN food_menu_meal fmm ON fmm.food_menuId = fm.id
        JOIN food_menu_meal_component fmmc ON fmm.id = fmmc.food_menu_mealId
        JOIN food_ingredient fi ON fmmc.id = fi.food_menu_meal_componentId
        JOIN food_measurement fm2 ON fm2.id = fi.food_measurement_fk
        JOIN food f ON f.id = fi.food_id_fk
        WHERE fm.start_date <= :date AND fm.final_date >= :date
        GROUP BY turn, fi.food_id_fk ;";

         return Yii::app()->db->createCommand($sql)->bindParam(':date', $date)->queryAll();
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
