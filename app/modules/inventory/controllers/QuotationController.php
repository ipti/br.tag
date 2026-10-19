<?php

class QuotationController extends Controller
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
                'actions' => ['index', 'view', 'report', 'create', 'update', 'delete'],
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
        $model = new InventoryQuotation('search');
        $model->unsetAttributes();
        if (isset($_GET['InventoryQuotation'])) {
            $model->attributes = $_GET['InventoryQuotation'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new InventoryQuotation();
        $model->date = date('Y-m-d');
        $model->status = InventoryQuotation::STATUS_OPEN;
        $model->user_id = Yii::app()->user->loginInfos->id;

        if (isset($_POST['InventoryQuotation'])) {
            $model->attributes = $_POST['InventoryQuotation'];
            $model->user_id = Yii::app()->user->loginInfos->id;
            $quotationItems = $this->normalizeQuotationItems($_POST['quotationItems'] ?? []);

            if (empty($quotationItems)) {
                $model->addError('title', 'Adicione pelo menos um item cotado.');
            } else {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $this->saveQuotationItems($model, $quotationItems);
                        $transaction->commit();
                        $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $model->addError('title', 'Erro ao salvar cotação: ' . $e->getMessage());
                }
            }
        }

        $this->render('create', [
            'model' => $model,
            'items' => InventoryItem::model()->findAll(['order' => 'name ASC']),
            'suppliers' => InventorySupplier::model()->findAll(['order' => 'name ASC']),
            'quotationItems' => [],
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if ($model->status == InventoryQuotation::STATUS_CLOSED && !isset($_POST['InventoryQuotation'])) {
            Yii::app()->user->setFlash('error', 'Esta cotação está encerrada e não pode ser editada.');
        }

        if (isset($_POST['InventoryQuotation'])) {
            $model->attributes = $_POST['InventoryQuotation'];
            $quotationItems = $this->normalizeQuotationItems($_POST['quotationItems'] ?? []);

            if (empty($quotationItems)) {
                $model->addError('title', 'Adicione pelo menos um item cotado.');
            } else {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    if ($model->save()) {
                        $this->saveQuotationItems($model, $quotationItems);
                        $transaction->commit();
                        $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    $model->addError('title', 'Erro ao salvar cotação: ' . $e->getMessage());
                }
            }
        }

        $existingItems = [];
        foreach ($model->items as $quotationItem) {
            $existingItems[] = [
                'item_id' => $quotationItem->item_id,
                'supplier_id' => $quotationItem->supplier_id,
                'quoted_value' => $quotationItem->quoted_value,
            ];
        }

        $this->render('update', [
            'model' => $model,
            'items' => InventoryItem::model()->findAll(['order' => 'name ASC']),
            'suppliers' => InventorySupplier::model()->findAll(['order' => 'name ASC']),
            'quotationItems' => $existingItems,
        ]);
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $this->render('view', [
            'model' => $model,
            'itemsGrouped' => $model->getItemsGrouped(),
        ]);
    }

    public function actionReport($id)
    {
        $model = $this->loadModel($id);
        $school = SchoolIdentification::model()->findByPk(Yii::app()->user->school);

        $this->layout = 'webroot.themes.default.views.layouts.reportsclean';
        $this->render('report', [
            'model' => $model,
            'itemsGrouped' => $model->getItemsGrouped(),
            'school' => $school,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            if (!isset($_GET['ajax'])) {
                $this->redirect(['index']);
            }
        } else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function loadModel($id)
    {
        $model = InventoryQuotation::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Filtra linhas do repeater sem item/fornecedor/valor preenchidos (ex: linha
     * deixada em branco no formulário).
     */
    private function normalizeQuotationItems(array $rawItems): array
    {
        $normalized = [];
        foreach ($rawItems as $rawItem) {
            if (empty($rawItem['item_id']) || empty($rawItem['supplier_id']) || !isset($rawItem['quoted_value']) || $rawItem['quoted_value'] === '') {
                continue;
            }
            $normalized[] = [
                'item_id' => (int) $rawItem['item_id'],
                'supplier_id' => (int) $rawItem['supplier_id'],
                'quoted_value' => (float) str_replace(',', '.', (string) $rawItem['quoted_value']),
            ];
        }
        return $normalized;
    }

    /**
     * Apaga os itens cotados existentes e recria a partir do que veio no formulário
     * — mesmo padrão de "sincronização completa" já usado em outros módulos do TAG
     * pra telas com repeater de linhas dinâmicas.
     */
    private function saveQuotationItems(InventoryQuotation $quotation, array $quotationItems): void
    {
        InventoryQuotationItem::model()->deleteAll(
            'quotation_id = :quotation_id',
            [':quotation_id' => $quotation->id]
        );

        foreach ($quotationItems as $itemData) {
            $quotationItem = new InventoryQuotationItem();
            $quotationItem->quotation_id = $quotation->id;
            $quotationItem->item_id = $itemData['item_id'];
            $quotationItem->supplier_id = $itemData['supplier_id'];
            $quotationItem->quoted_value = $itemData['quoted_value'];

            if (!$quotationItem->save()) {
                throw new Exception('Não foi possível salvar um item da cotação.');
            }
        }
    }
}
