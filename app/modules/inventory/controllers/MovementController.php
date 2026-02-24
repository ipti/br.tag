<?php

class MovementController extends Controller
{

    public function filters()
    {
        return [
            'accessControl',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'view', 'createEntry', 'createExit', 'history', 'transfer'],
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
        $schoolId = Yii::app()->user->school;
        $criteria = new CDbCriteria();
        
        if (Yii::app()->user->checkAccess('manager')) {
            $criteria->compare('school_inep_fk', $schoolId);
        }

        $dataProvider = new CActiveDataProvider('InventoryStock', [
            'criteria' => $criteria,
            'pagination' => false,
        ]);

        // Alertas de estoque baixo
        $lowStockCriteria = new CDbCriteria();
        $lowStockCriteria->with = ['item'];
        if (Yii::app()->user->checkAccess('manager')) {
            $lowStockCriteria->compare('t.school_inep_fk', $schoolId);
        }
        $lowStockCriteria->addCondition('t.quantity <= item.minimum_stock');
        $lowStockCriteria->addCondition('item.minimum_stock > 0');

        $lowStockProvider = new CActiveDataProvider('InventoryStock', [
            'criteria' => $lowStockCriteria,
            'pagination' => false,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'lowStockProvider' => $lowStockProvider,
            'isAdmin' => TagUtils::isAdmin(),
        ]);
    }

    /**
     * Entry Movement (Admin only usually, but let's allow manager if they receive goods)
     * The prompt said: "entrada e saida... secretaria de educação (admin) pode enviar... 
     * gestor (manager) tem controle da sua escola"
     */
    public function actionCreateEntry()
    {
        $model = new InventoryMovement();
        $model->type = InventoryMovement::TYPE_ENTRY;
        $model->user_id = Yii::app()->user->loginInfos->id;
        $model->date = date('Y-m-d');

        if (isset($_POST['InventoryMovement'])) {
            $model->attributes = $_POST['InventoryMovement'];
            if (empty($model->school_inep_fk)) {
                $model->school_inep_fk = null;
            }
            $transaction = Yii::app()->db->beginTransaction();
            try {
                if ($model->save()) {
                    $this->updateStock($model);
                    $transaction->commit();
                    $this->redirect(['history']);
                }
            } catch (Exception $e) {
                $transaction->rollback();
                $model->addError('quantity', 'Erro ao atualizar estoque: ' . $e->getMessage());
            }
        }

        $this->render('createEntry', [
            'model' => $model,
        ]);
    }

    public function actionCreateExit()
    {
        $model = new InventoryMovement();
        $model->type = InventoryMovement::TYPE_EXIT;
        $model->user_id = Yii::app()->user->loginInfos->id;
        $model->date = date('Y-m-d');
        
        if (Yii::app()->user->checkAccess('manager')) {
            $model->school_inep_fk = Yii::app()->user->school;
        }

        if (isset($_POST['InventoryMovement'])) {
            $model->attributes = $_POST['InventoryMovement'];
            if (empty($model->school_inep_fk)) {
                $model->school_inep_fk = null;
            }
            
            // Check availability
            $stock = InventoryStock::model()->find(
                'item_id = :item_id AND (school_inep_fk = :school_id OR (school_inep_fk IS NULL AND :school_id IS NULL))',
                [':item_id' => $model->item_id, ':school_id' => $model->school_inep_fk]
            );

            if (!$stock || $stock->quantity < $model->quantity) {
                $model->addError('quantity', 'Quantidade insuficiente em estoque.');
            } else {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $this->updateStock($model);
                        $transaction->commit();
                        $this->redirect(['history']);
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $model->addError('quantity', 'Erro ao atualizar estoque: ' . $e->getMessage());
                }
            }
        }

        $this->render('createExit', [
            'model' => $model,
        ]);
    }

    public function actionTransfer()
    {
        if (!TagUtils::isAdmin()) {
            throw new CHttpException(403, 'Ação permitida apenas para administradores.');
        }

        $model = new InventoryMovement();
        $model->date = date('Y-m-d');

        if (isset($_POST['InventoryMovement'])) {
            $data = $_POST['InventoryMovement'];
            $itemId = $data['item_id'];
            $schoolId = $data['school_inep_fk'];
            $quantity = $data['quantity'];

            // 1. Check Central stock
            $centralStock = InventoryStock::model()->find(
                'item_id = :item_id AND school_inep_fk IS NULL',
                [':item_id' => $itemId]
            );

            if (empty($schoolId)) {
                $model->addError('school_inep_fk', 'Selecione uma escola de destino.');
            } elseif (!$centralStock || $centralStock->quantity < $quantity) {
                $model->addError('quantity', 'Saldo insuficiente no Almoxarifado Central.');
            } else {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $targetSchool = SchoolIdentification::model()->findByPk($schoolId);
                    // 2. Exit from Central
                    $exit = new InventoryMovement();
                    $exit->item_id = $itemId;
                    $exit->school_inep_fk = null;
                    $exit->user_id = Yii::app()->user->loginInfos->id;
                    $exit->type = InventoryMovement::TYPE_EXIT;
                    $exit->quantity = $quantity;
                    $exit->date = $data['date'];
                    $exit->destination = 'Transferência para ' . ($targetSchool ? $targetSchool->name : 'unidade desconhecida');
                    
                    if ($exit->save()) {
                        $this->updateStock($exit);

                        // 3. Entry into School
                        $entry = new InventoryMovement();
                        $entry->item_id = $itemId;
                        $entry->school_inep_fk = $schoolId;
                        $entry->user_id = Yii::app()->user->loginInfos->id;
                        $entry->type = InventoryMovement::TYPE_ENTRY;
                        $entry->quantity = $quantity;
                        $entry->date = $data['date'];
                        $entry->destination = 'Recebido do Almoxarifado Central';
                        
                        if ($entry->save()) {
                            $this->updateStock($entry);
                            $transaction->commit();
                            $this->redirect(['history']);
                        }
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $model->addError('quantity', 'Erro na transferência: ' . $e->getMessage());
                }
            }
        }

        $this->render('transfer', [
            'model' => $model,
        ]);
    }

    public function actionHistory()
    {
        $model = new InventoryMovement('search');
        $model->unsetAttributes();
        
        if (isset($_GET['InventoryMovement'])) {
            $model->attributes = $_GET['InventoryMovement'];
        }

        $this->render('history', [
            'model' => $model,
        ]);
    }

    private function updateStock($movement)
    {
        $stock = InventoryStock::model()->find(
            'item_id = :item_id AND (school_inep_fk = :school_id OR (school_inep_fk IS NULL AND :school_id IS NULL))',
            [':item_id' => $movement->item_id, ':school_id' => $movement->school_inep_fk]
        );

        if (!$stock) {
            $stock = new InventoryStock();
            $stock->item_id = $movement->item_id;
            $stock->school_inep_fk = $movement->school_inep_fk;
            $stock->quantity = 0;
        }

        if ($movement->type == InventoryMovement::TYPE_ENTRY) {
            $stock->quantity += $movement->quantity;
        } else {
            $stock->quantity -= $movement->quantity;
        }

        if (!$stock->save()) {
            throw new Exception('Não foi possível atualizar o registro de estoque.');
        }
    }
}
