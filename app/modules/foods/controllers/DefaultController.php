<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

    public function actionCreate()
    {
        $modelFoodMenu = new FoodMenu;
        $request = Yii::app()->request->getPost('foodMenu');
        $transaction = Yii::app()->db->beginTransaction();
        // Verifica se há dados na requisição enviada
        // Caso negativo, renderiza o formulário
        if ($request === null) {
            $this->render('create', array(
                'model' => $modelFoodMenu,
            ));
            Yii::app()->end();
        }

        $allFieldsAreFilled = isset($request["start_date"]) &&
            isset($request["final_date"]) &&
            isset($request["food_public_target"]) &&
            isset($request["description"]);

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
        $modelFoodMenu->observation = $request['observation'];
        $modelFoodMenu->description = $request['description'];

        // Verifica se a ação de salvar foodMenu ocorreu com sucesso, caso falhe encerra a aplicação
        $saveFoodMenuResult = $modelFoodMenu->save();

        if ($saveFoodMenuResult == false) {
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

}
