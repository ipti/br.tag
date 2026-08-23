<?php

/**
 * Configuração de alerta de vencimento do estoque da Merenda Escolar:
 * prazo padrão do município e grupos de alimentos com prazo próprio
 * (ex.: Hortaliças = 15 dias).
 */
class FoodAlertController extends Controller
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    /**
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'create', 'update', 'delete', 'saveDefaultDays'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->assertCanManageAlerts();

        $groups = FoodExpirationAlertGroup::model()->findAll(['order' => 'name']);

        $this->render('index', [
            'groups' => $groups,
            'defaultDays' => FoodExpirationAlertGroup::getDefaultAlertDays(),
        ]);
    }

    public function actionCreate()
    {
        $this->assertCanManageAlerts();

        $model = new FoodExpirationAlertGroup();

        if (isset($_POST['FoodExpirationAlertGroup'])) {
            $model->attributes = $_POST['FoodExpirationAlertGroup'];

            if ($model->save()) {
                $this->assignFoods($model, $_POST['foods'] ?? []);
                Yii::app()->user->setFlash('success', 'Grupo de alerta criado com sucesso!');
                $this->redirect(['index']);
            }
        }

        $this->render('_form', [
            'model' => $model,
            'assignedFoodIds' => [],
            'foods' => $this->getSelectableFoods(),
        ]);
    }

    public function actionUpdate($id)
    {
        $this->assertCanManageAlerts();

        $model = $this->loadModel($id);

        if (isset($_POST['FoodExpirationAlertGroup'])) {
            $model->attributes = $_POST['FoodExpirationAlertGroup'];

            if ($model->save()) {
                $this->assignFoods($model, $_POST['foods'] ?? []);
                Yii::app()->user->setFlash('success', 'Grupo de alerta atualizado com sucesso!');
                $this->redirect(['index']);
            }
        }

        $assignedFoodIds = array_map(static fn (Food $food): int => (int) $food->id, $model->foods);

        $this->render('_form', [
            'model' => $model,
            'assignedFoodIds' => $assignedFoodIds,
            'foods' => $this->getSelectableFoods(),
        ]);
    }

    public function actionDelete($id)
    {
        $this->assertCanManageAlerts();

        // Alimentos vinculados voltam a usar o prazo padrão do município
        // automaticamente (FK com ON DELETE SET NULL).
        $this->loadModel($id)->delete();

        Yii::app()->user->setFlash('success', 'Grupo de alerta excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function actionSaveDefaultDays()
    {
        $this->assertCanManageAlerts();

        $days = (int) Yii::app()->request->getPost('defaultDays');

        if ($days < 1) {
            Yii::app()->user->setFlash('error', 'Informe um número de dias maior que zero.');
            $this->redirect(['index']);
        }

        $config = InstanceConfig::model()->findByAttributes(['parameter_key' => FoodExpirationAlertGroup::DEFAULT_DAYS_PARAMETER_KEY]);
        if ($config === null) {
            $config = new InstanceConfig();
            $config->parameter_key = FoodExpirationAlertGroup::DEFAULT_DAYS_PARAMETER_KEY;
            $config->parameter_name = 'Estoque da Merenda - Dias de antecedência para alerta de vencimento (padrão)';
        }
        $config->value = (string) $days;
        $config->save(false);

        Yii::app()->user->setFlash('success', 'Prazo padrão de alerta atualizado com sucesso!');
        $this->redirect(['index']);
    }

    /**
     * @throws CHttpException se o usuário não for gestor, nutricionista ou
     * administrador — só esses papéis podem configurar o alerta de vencimento.
     */
    private function assertCanManageAlerts(): void
    {
        $userId = Yii::app()->user->loginInfos->id;
        $authManager = Yii::app()->getAuthManager();
        $canManageAlerts = $authManager->checkAccess('manager', $userId)
            || $authManager->checkAccess('nutritionist', $userId)
            || $authManager->checkAccess('admin', $userId);

        if (!$canManageAlerts) {
            throw new CHttpException(403, 'Você não tem permissão para configurar o alerta de vencimento.');
        }
    }

    /**
     * Reatribui, dentro do grupo salvo, exatamente a lista de alimentos enviada
     * pelo formulário — remove os que saíram, adiciona os que entraram. Um
     * alimento só pode estar em um grupo por vez (se já estiver em outro
     * grupo, passa a pertencer a este).
     */
    private function assignFoods(FoodExpirationAlertGroup $group, array $foodIds): void
    {
        $foodIds = array_map('intval', $foodIds);

        Food::model()->updateAll(
            ['expiration_alert_group_fk' => null],
            'expiration_alert_group_fk = :groupId',
            [':groupId' => $group->id]
        );

        if (!empty($foodIds)) {
            Food::model()->updateByPk(
                $foodIds,
                ['expiration_alert_group_fk' => $group->id]
            );
        }
    }

    /**
     * Lista de alimentos para o seletor de grupo: apenas o alimento "canônico"
     * de cada alias (mesmo critério usado no Lançamento de Estoque), sem
     * repetir a mesma comida uma vez por preparo (cru/cozido/etc.) e sem esse
     * sufixo no nome exibido.
     *
     * @return Food[]
     */
    private function getSelectableFoods(): array
    {
        $criteria = new CDbCriteria();
        $criteria->select = 'id, description';
        $criteria->condition = 'alias_id = t.id';

        $foods = Food::model()->findAll($criteria);

        foreach ($foods as $food) {
            $food->description = trim(preg_replace('/,|\b(cru[ao]?)\b/', '', $food->description));
        }

        usort($foods, static fn (Food $a, Food $b): int => strcmp($a->description, $b->description));

        return $foods;
    }

    /**
     * @throws CHttpException
     */
    public function loadModel($id): FoodExpirationAlertGroup
    {
        $model = FoodExpirationAlertGroup::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Grupo de alerta não encontrado.');
        }

        return $model;
    }
}
