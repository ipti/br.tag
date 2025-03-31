<?php
Yii::import('application.modules.foods.usecases.*');
class FoodmenuController extends Controller
{

    public $modelFoodMenu = 'FoodMenu';
    public $defaultAction = "viewlunch";

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
        if ($request === null) {
            $mealTypeList = $this->actionGetMealType();
            $tacoFoodsList = $this->actionGetTacoFoods();
            $foodMeasurementList = $this->actionGetFoodMeasurement();
            $this->render('create', array(
                'model' => $modelFoodMenu,
                'mealTypeList' => $mealTypeList,
                'tacoFoodsList' => $tacoFoodsList,
                'foodMeasurementList' => $foodMeasurementList,
            )
            );
            Yii::app()->end();
        }

        $allFieldsAreFilled = isset($request["start_date"]) &&
            isset($request["final_date"]) &&
            isset($request["food_public_target"]) &&
            isset($request["description"]) &&
            isset($request["stages"]);

        if ($allFieldsAreFilled === false) {
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
        $modelFoodMenu->week = $request['week'];
        $modelFoodMenu->observation = $request['observation'];
        $modelFoodMenu->description = $request['description'];
        $modelFoodMenu->include_saturday = $request['include_saturday'];

        // Verifica se a ação de salvar foodMenu ocorreu com sucesso, caso falhe encerra a aplicação
        $saveFoodMenuResult = $modelFoodMenu->save();

        if ($saveFoodMenuResult == false) {
            $message = 'Ocorreu um erro ao salvar o cardápio! Tente novamente.';
            $transaction->rollback();
            throw new CHttpException(500, $message);
        }

        // Salvando etapas do foodMenu
        $stages = $request["stages"];

        foreach ($stages as $stage) {
           $foodStage = new FoodMenuVsEdcensoStageVsModality();
           $foodStage->food_menu_fk = $modelFoodMenu->id;
           $foodStage->edcenso_stage_vs_modality_fk = $stage;
           $foodStage->save();

        }

        /* Atribui valores às propriedades do model FoodMenuVsFoodPublicTarget
        (Tabela N:N entre cardápio e publico alvo) */
        $publicTarget = FoodPublicTarget::model()->findByPk($request['food_public_target']);
        $foodMenuVsPublicTarget = new FoodMenuVsFoodPublicTarget;
        $foodMenuVsPublicTarget->food_menu_fk = $modelFoodMenu->id;
        $foodMenuVsPublicTarget->food_public_target_fk = $publicTarget->id;
        $foodMenuVsPublicTarget->save();

        // Chamando método que irá adicionar novos registros relacionados ao cardápio
        $createFoodMenuRelations = new CreateFoodMenuRelations();
        $createFoodMenuRelations->exec($modelFoodMenu, $request, $transaction);
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
        $request = Yii::app()->request->getPost('foodMenu');
        $modelFoodMenu = $this->loadModel($id);
        $modelMenuMeals = FoodMenuMeal::model()->findAllByAttributes(array('food_menuId' => $modelFoodMenu->id));
        if ($request == null) {
            // Bloco de código para identificar qual o público alvo do cardápio
            $publicTargetSql = "
             SELECT fpt.id, fpt.name FROM food_public_target fpt
             LEFT JOIN food_menu_vs_food_public_target fmvfpt ON fmvfpt.food_public_target_fk = fpt.id
             WHERE fmvfpt.food_menu_fk = :id";
            $publicTarget = Yii::app()->db->createCommand($publicTargetSql)
                ->bindParam(':id', $modelFoodMenu->id)
                ->queryRow();

            $stagesSQL = "Select fmesvsm.edcenso_stage_vs_modality_fk FROM food_menu_vs_edcenso_stage_vs_modality fmesvsm
            INNER JOIN food_menu fm on fm.id = fmesvsm.food_menu_fk
            Where fmesvsm.food_menu_fk = :id";

            $stages = Yii::app()->db->createCommand($stagesSQL)
                ->bindParam(':id', $modelFoodMenu->id)
                ->queryColumn();

            $getFoodMenu = new GetFoodMenu();
            $foodMenu = $getFoodMenu->exec($modelFoodMenu, $publicTarget, $modelMenuMeals);

            // Convertendo objeto do cardápio em um JSON para enviar como resposta da requisição AJAX
            $mealTypeList = $this->actionGetMealType();
            $tacoFoodsList = $this->actionGetTacoFoods();
            $foodMeasurementList = $this->actionGetFoodMeasurement();
            $this->render('update', array(
                'model' => $foodMenu,
                'mealTypeList' => $mealTypeList,
                'tacoFoodsList' => $tacoFoodsList,
                'foodMeasurementList' => $foodMeasurementList,
                'stages' => $stages,
            )
            );
            Yii::app()->end();
        }

        // Trecho do código para excluir todos os registros associados ao cardápio
        $transaction = Yii::app()->db->beginTransaction();

        if ($modelFoodMenu != null) {
            $modelFoodMenu->start_date = DateTime::createFromFormat('d/m/Y', $request["start_date"])->format("Y-m-d");
            $modelFoodMenu->final_date = DateTime::createFromFormat('d/m/Y', $request["final_date"])->format("Y-m-d");
            $modelFoodMenu->week = $request['week'];
            $modelFoodMenu->observation = $request['observation'];
            $modelFoodMenu->description = $request['description'];
            $modelFoodMenu->include_saturday = intval($request['include_saturday']);
            $modelFoodMenu->save();

            //atualiza FoodMenuvVsPublicTarget
            $foodMenuVsPublicTarget = FoodMenuVsFoodPublicTarget::model()
                ->findByAttributes(array('food_menu_fk' => $modelFoodMenu->id));
            $publicTarget = FoodPublicTarget::model()->findByPk($request['food_public_target']);
            $foodMenuVsPublicTarget->food_public_target_fk = $publicTarget->id;
            $foodMenuVsPublicTarget->save();

             // Salvando etapas do foodMenu
             $currentStages = FoodMenuVsEdcensoStageVsModality::model()->findAllByAttributes(["food_menu_fk"=> $modelFoodMenu->id]);
             foreach ($currentStages as $stage) {
                $stage->delete();
             }

                $stages = $request["stages"];

                foreach ($stages as $stage) {
                $foodStage = new FoodMenuVsEdcensoStageVsModality();
                $foodStage->food_menu_fk = $modelFoodMenu->id;
                $foodStage->edcenso_stage_vs_modality_fk = $stage;
                $foodStage->save();

                }
        }

        foreach ($modelMenuMeals as $modelMenuMeal) {
            $modelFoodComponents = FoodMenuMealComponent::model()->findAllByAttributes(
                array('food_menu_mealId' => $modelMenuMeal->id)
            );
            foreach ($modelFoodComponents as $modelFoodComponent) {
                $modelFoodIngredients = FoodIngredient::model()->findAllByAttributes(
                    array('food_menu_meal_componentId' => $modelFoodComponent->id)
                );
                foreach ($modelFoodIngredients as $modelFoodIngredient) {
                    $modelFoodIngredient->delete();
                }
                $modelFoodComponent->delete();
            }
            $modelMenuMeal->delete();
        }
        // Chamada de função que irá salvar as novas informações do cardápio
        $createFoodMenuRelations = new CreateFoodMenuRelations();
        $createFoodMenuRelations->exec($modelFoodMenu, $request, $transaction);
        $transaction->commit();
        header('HTTP/1.1 200 OK');
        Log::model()->saveAction("foodMenu", $modelFoodMenu->id, "U", $modelFoodMenu->description);
        Yii::app()->end();
    }

    public function actionGetTacoFoods()
    {
        $foods = Food::model()->findAll(
            array(
                'select' => 'id, description'
            )
        );
        $resultArray = array();
        foreach ($foods as $food) {
            $resultArray[$food->id] = $food->description;
        }
        return $resultArray;
    }
    public function actionGetFood()
    {
        $idFood = Yii::app()->request->getQuery('idFood');
        $food = Food::model()->findByAttributes(array('id' => $idFood));
        $result = array();
        $result["id"] = $food->id;
        $result["name"] = $food->description;
        $result["kcal"] = is_numeric($food->energy_kcal) ? round($food->energy_kcal, 2) : $food->energy_kcal;
        $result["pt"] = is_numeric($food->protein_g) ? round($food->protein_g, 2) : $food->protein_g;
        $result["lip"] = is_numeric($food->lipidius_g) ? round($food->lipidius_g, 2) : $food->lipidius_g;
        $result["cho"] = is_numeric($food->carbohydrate_g) ? round($food->carbohydrate_g, 2) : $food->carbohydrate_g;
        $result["measurementUnit"] = $food->measurementUnit;

        echo CJSON::encode($result);
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
        try {
            $modelFoodMenuMeals = FoodMenuMeal::model()->findAllByAttributes(
                array('food_menuId' => $modelFoodMenu->id)
            );
            foreach ($modelFoodMenuMeals as $modelFoodMenuMeal) {
                $modelFoodMealComponents = FoodMenuMealComponent::model()->findAllByAttributes(
                    array('food_menu_mealId' => $modelFoodMenuMeal->id)
                );
                foreach ($modelFoodMealComponents as $modelFoodMealComponent) {
                    FoodIngredient::model()->deleteAllByAttributes(
                        array('food_menu_meal_componentId' => $modelFoodMealComponent->id)
                    );
                }
                $modelFoodMenuMeal->delete();
            }
            $modelFoodMenuVsPublicTarget = FoodMenuVsFoodPublicTarget::model()->findByAttributes(
                array('food_menu_fk' => $modelFoodMenu->id)
            );
            $modelFoodMenuVsPublicTarget->delete();
            $modelFoodMenu->delete();
            $transaction->commit();
            header('HTTP/1.1 200 OK');
            Log::model()->saveAction("foodMenu", $id, "D", $modelFoodMenu->description);
            $this->redirect(array('index'));
            Yii::app()->end();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new CHttpException(500, $e->getMessage());
        }
    }
    /**
     * Essa função deve retornar um objeto com todas as refeições em todos os cardápios
     * cadastrados para cada um dos dias da semana, onde a semana será baseada no dia atual
     */
    public function actionViewLunch()
    {
        $result = ['Manhã' => '0', 'Tarde' => '0', 'Noite' => '0', 'Integral' => '0'];
        $sql = "SELECT COUNT(*) as total_students,
        CASE
            WHEN c.turn = 'M' THEN 'Manhã'
            WHEN c.turn = 'T' THEN 'Tarde'
            WHEN c.turn = 'N' THEN 'Noite'
            WHEN c.turn = 'I' THEN 'Integral'
            ELSE ''
        END AS turn
        FROM student_enrollment
        inner JOIN classroom as c ON student_enrollment.classroom_fk = c.id
        WHERE
            (status IN (1, 6, 7, 8, 9, 10) OR
            status IS NULL) and
            (c.school_year = :user_year and
            student_enrollment.school_inep_id_fk = :user_school)
          group by c.turn";

        $sql = Yii::app()->db->createCommand($sql)
            ->bindParam(':user_year', Yii::app()->user->year)
            ->bindParam(':user_school', Yii::app()->user->school)->queryAll();

        foreach ($sql as $element) {
            $turn = $element['turn'];
            $totalStudents = $element['total_students'];


            $result[$turn] = $totalStudents;

        }

        $this->render('viewlunch', array(
            "studentsByTurn" => $result
        )
        );
        Yii::app()->end();
    }
    public function actionGetMealsOfWeek()
    {

        $getMelsOfWeek = new GetMelsOfWeek();
        $foodMenu = $getMelsOfWeek->exec();

        $response = json_encode((array) $foodMenu);
        echo $response;
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider(
           'FoodMenu',
            array(
                'pagination' => false
            )
        );

        $this->render('index', array(
            'dataProvider' => $dataProvider,
        )
        );
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new FoodMenu('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['FoodMenu'])) {
            $model->attributes = $_GET['FoodMenu'];
        }


        $this->render('admin', array(
            'model' => $model,
        )
        );
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
        $model = FoodMenu::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param FoodMenu $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'food-menu-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
    /**
     * Método que retorna os públicos alvos que podem estar relacionados a um cardápio
     */
    public function actionGetPublicTarget()
    {

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
    public function actionGetMealType()
    {
        $mealsType = FoodMealType::model()->findAll();
        $mealsType = CHtml::listData($mealsType, 'id', 'description');
        $options = array();
        foreach ($mealsType as $value => $description) {
            array_push(
                $options,
                CHtml::tag(
                    'option',
                    ['value' => $value],
                    CHtml::encode($description),
                    true
                )
            );
        }
        return $options;
    }

    /**
     * Método que retorna os tipos de medidas que podem ser utilizadas ao cadastrar um ingrediente a um prato
     */
    public function actionGetFoodMeasurement()
    {
        $foodMeasurements = FoodMeasurement::model()->findAll();
        $options = array();
        foreach ($foodMeasurements as $foodMeasurement) {
            array_push(
                $options,
                array(
                    "id" => $foodMeasurement->id,
                    "unit" => $foodMeasurement->unit,
                    "value" => $foodMeasurement->value,
                    "measure" => $foodMeasurement->measure
                )
            );
        }
        return $options;
    }
}
