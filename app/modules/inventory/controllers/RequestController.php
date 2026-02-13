<?php

class RequestController extends Controller
{
    public $layout = 'webroot.themes.default.views.layouts.fullmenu';

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
                'actions' => ['index', 'create', 'view', 'admin', 'approve', 'reject', 'update', 'delete'],
                'users' => ['@'],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $isAdmin = TagUtils::isAdmin();

        // Only allow editing if Status is Pending (Schools) or any status (Admin)
        if (!$isAdmin && $model->status != InventoryRequest::STATUS_PENDING) {
            Yii::app()->user->setFlash('error', 'Apenas solicitações pendentes podem ser editadas.');
            $this->redirect(['index']);
        }

        // Security check for managers
        if (!TagUtils::isAdmin() && $model->school_inep_fk != Yii::app()->user->school) {
            throw new CHttpException(403, 'Você não tem permissão para editar esta solicitação.');
        }

        if (isset($_POST['InventoryRequest'])) {
            $model->attributes = $_POST['InventoryRequest'];
            
            if (!$isAdmin) {
                // Prevent tampering
                $model->school_inep_fk = Yii::app()->user->school;
                $model->user_id = Yii::app()->user->loginInfos->id;
                $model->status = InventoryRequest::STATUS_PENDING;
            }

            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'Solicitação atualizada com sucesso.');
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        $isAdmin = TagUtils::isAdmin();

        if (!$isAdmin && $model->status != InventoryRequest::STATUS_PENDING) {
            throw new CHttpException(403, 'Apenas solicitações pendentes podem ser excluídas.');
        }

        if (!TagUtils::isAdmin() && $model->school_inep_fk != Yii::app()->user->school) {
            throw new CHttpException(403, 'Você não tem permissão para excluir esta solicitação.');
        }

        $model->delete();

        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria();
        if (Yii::app()->user->checkAccess('manager')) {
            $criteria->compare('school_inep_fk', Yii::app()->user->school);
        }
        $criteria->order = 'requested_at DESC';

        $dataProvider = new CActiveDataProvider('InventoryRequest', [
            'criteria' => $criteria,
        ]);

        $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new InventoryRequest();
        $isAdmin = TagUtils::isAdmin();

        // Default values
        $model->school_inep_fk = Yii::app()->user->school;
        $model->user_id = Yii::app()->user->loginInfos->id;
        $model->status = InventoryRequest::STATUS_PENDING;

        if (isset($_POST['InventoryRequest'])) {
            $model->attributes = $_POST['InventoryRequest'];
            
            // Re-enforce school/user if not admin to prevent tampering
            if (!$isAdmin) {
                $model->school_inep_fk = Yii::app()->user->school;
                $model->user_id = Yii::app()->user->loginInfos->id;
            }

            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionAdmin()
    {
        $model = new InventoryRequest('search');
        $model->unsetAttributes();
        if (isset($_GET['InventoryRequest'])) {
            $model->attributes = $_GET['InventoryRequest'];
        }

        $this->render('admin', [
            'model' => $model,
        ]);
    }

    public function actionApprove($id)
    {
        if (!TagUtils::isAdmin()) {
            throw new CHttpException(403, 'Você não tem permissão para esta ação.');
        }

        $model = $this->loadModel($id);
        if ($model->status == InventoryRequest::STATUS_PENDING) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $model->status = InventoryRequest::STATUS_APPROVED;
                $model->observation = $_POST['observation'] ?? '';
                
                if ($model->save()) {
                    // Create automatic movement (Entry for the school)
                    $movement = new InventoryMovement();
                    $movement->item_id = $model->item_id;
                    $movement->school_inep_fk = $model->school_inep_fk;
                    $movement->user_id = Yii::app()->user->loginInfos->id;
                    $movement->type = InventoryMovement::TYPE_ENTRY;
                    $movement->quantity = $model->quantity;
                    $movement->date = date('Y-m-d');
                    $movement->destination = 'Solicitação #' . $model->id;

                    if ($movement->save()) {
                        // Update Stock
                        $stock = InventoryStock::model()->findByAttributes([
                            'item_id' => $movement->item_id,
                            'school_inep_fk' => $movement->school_inep_fk
                        ]);

                        if (!$stock) {
                            $stock = new InventoryStock();
                            $stock->item_id = $movement->item_id;
                            $stock->school_inep_fk = $movement->school_inep_fk;
                            $stock->quantity = 0;
                        }

                        $stock->quantity += $movement->quantity;
                        if ($stock->save()) {
                            $transaction->commit();
                            Yii::app()->user->setFlash('success', 'Solicitação aprovada e estoque atualizado.');
                        } else {
                            throw new Exception('Erro ao atualizar estoque.');
                        }
                    } else {
                        throw new Exception('Erro ao criar movimento de estoque.');
                    }
                }
            } catch (Exception $e) {
                $transaction->rollback();
                Yii::app()->user->setFlash('error', 'Erro ao aprovar solicitação: ' . $e->getMessage());
            }
        }
        $this->redirect(['admin']);
    }

    public function actionReject($id)
    {
        if (!TagUtils::isAdmin()) {
            throw new CHttpException(403, 'Você não tem permissão para esta ação.');
        }

        $model = $this->loadModel($id);
        if ($model->status == InventoryRequest::STATUS_PENDING) {
            $model->status = InventoryRequest::STATUS_REJECTED;
            $model->observation = $_POST['observation'] ?? '';
            
            if ($model->save()) {
                Yii::app()->user->setFlash('error', 'Solicitação rejeitada.');
            }
        }
        $this->redirect(['admin']);
    }

    public function loadModel($id)
    {
        $model = InventoryRequest::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
}
