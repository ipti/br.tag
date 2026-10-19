<?php

/**
 * Configuração de alerta de vencimento do estoque da Merenda Escolar, por
 * escola: prazo padrão da escola e grupos de alimentos com prazo próprio
 * (ex.: Hortaliças = 15 dias).
 */
class FoodalertController extends Controller
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

        $schoolFk = Yii::app()->user->school;

        $groups = FoodExpirationAlertGroup::model()->findAllByAttributes(['school_fk' => $schoolFk], ['order' => 'name']);

        $this->render('index', [
            'groups' => $groups,
            'defaultDays' => FoodExpirationAlertSchoolConfig::getDefaultAlertDays($schoolFk),
        ]);
    }

    public function actionCreate()
    {
        $this->assertCanManageAlerts();

        $model = new FoodExpirationAlertGroup();
        $model->school_fk = Yii::app()->user->school;

        if (isset($_POST['FoodExpirationAlertGroup'])) {
            $model->attributes = $_POST['FoodExpirationAlertGroup'];
            $model->school_fk = Yii::app()->user->school;

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
            $model->school_fk = Yii::app()->user->school;

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

        $this->loadModel($id)->delete();

        Yii::app()->user->setFlash('success', 'Grupo de alerta excluído com sucesso!');
        $this->redirect(['index']);
    }

    public function actionSaveDefaultDays()
    {
        $this->assertCanManageAlerts();

        $schoolFk = Yii::app()->user->school;
        $days = (int) Yii::app()->request->getPost('defaultDays');

        if ($days < 1) {
            Yii::app()->user->setFlash('error', 'Informe um número de dias maior que zero.');
            $this->redirect(['index']);
        }

        $config = FoodExpirationAlertSchoolConfig::model()->findByPk($schoolFk);
        if ($config === null) {
            $config = new FoodExpirationAlertSchoolConfig();
            $config->school_fk = $schoolFk;
        }
        $config->default_days = $days;
        $config->save(false);

        Yii::app()->user->setFlash('success', 'Prazo padrão de alerta atualizado com sucesso!');
        $this->redirect(['index']);
    }

    /**
     * @throws CHttpException se o usuário não for merendeira, gestor,
     * nutricionista ou administrador — só esses papéis podem configurar o
     * alerta de vencimento.
     */
    private function assertCanManageAlerts(): void
    {
        $userId = Yii::app()->user->loginInfos->id;
        $authManager = Yii::app()->getAuthManager();
        $canManageAlerts = $authManager->checkAccess('manager', $userId)
            || $authManager->checkAccess('nutritionist', $userId)
            || $authManager->checkAccess('admin', $userId)
            || $authManager->checkAccess('foodServiceWorker', $userId);

        if (!$canManageAlerts) {
            throw new CHttpException(403, 'Você não tem permissão para configurar o alerta de vencimento.');
        }
    }

    /**
     * Reatribui, dentro do grupo salvo, exatamente a lista de alimentos
     * enviada pelo formulário — remove os que saíram, adiciona os que
     * entraram. Dentro da mesma escola, um alimento só pode estar em um
     * grupo por vez (se já estiver em outro grupo desta escola, passa a
     * pertencer a este).
     */
    private function assignFoods(FoodExpirationAlertGroup $group, array $foodIds): void
    {
        $foodIds = array_values(array_unique(array_map('intval', $foodIds)));
        $db = Yii::app()->db;

        $db->createCommand()->delete(
            'food_expiration_alert_group_food',
            'group_fk = :groupId',
            [':groupId' => $group->id]
        );

        if (empty($foodIds)) {
            return;
        }

        $params = [':schoolFk' => $group->school_fk];
        $foodPlaceholders = [];
        foreach ($foodIds as $index => $foodId) {
            $placeholder = ':food' . $index;
            $foodPlaceholders[] = $placeholder;
            $params[$placeholder] = $foodId;
        }

        $db->createCommand()->delete(
            'food_expiration_alert_group_food',
            'food_fk IN (' . implode(',', $foodPlaceholders) . ') AND group_fk IN (SELECT id FROM food_expiration_alert_group WHERE school_fk = :schoolFk)',
            $params
        );

        $command = $db->createCommand();
        foreach ($foodIds as $foodId) {
            $command->insert('food_expiration_alert_group_food', [
                'group_fk' => $group->id,
                'food_fk' => $foodId,
            ]);
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
     * @throws CHttpException se o grupo não existir ou não pertencer à
     * escola atual (evita que uma escola configure o grupo de outra).
     */
    public function loadModel($id): FoodExpirationAlertGroup
    {
        $model = FoodExpirationAlertGroup::model()->findByPk($id);
        if ($model === null || $model->school_fk !== Yii::app()->user->school) {
            throw new CHttpException(404, 'Grupo de alerta não encontrado.');
        }

        return $model;
    }
}
