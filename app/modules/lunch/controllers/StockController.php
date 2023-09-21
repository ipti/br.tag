<?php

/**
 * Class StockController
 *
 * @property School $school
 */
class StockController extends CController{

    public $school;

    public function init(){
        $this->school = School::model()->findByPk(yii::app()->user->school);
    }

    public function actionIndex(){
        $this->render('index');
    }

    public function actionAddItem(){
        $request = Yii::app()->getRequest();

        $postItem = $request->getPost('Item', false);
        $postInventory = $request->getPost('Inventory', false);

        $school = yii::app()->user->school;

        if (!$postItem) {
            $item_id = $postInventory['item'];
            $amount = $postInventory['amount'];
        } else {
            $item = new Item();
            $item->name = $postItem['name'];
            $item->description = $postItem['description'];
            $item->unity_fk = $postItem['unity_fk'];
            $item->measure = $postItem['measure'];

            if ($item->validate()) {
                $item->save();
                $item_id = $item->id;
                $amount = $postInventory['amount'];
            }
        }

        $inventory = new Inventory();
        $inventory->school_fk = $school;
        $inventory->item_fk = $item_id;
        $inventory->amount = $amount;

        if ($inventory->validate()) {
            $inventory->save();

            $received = new Received();
            $received->inventory_fk = $inventory->id;
            $received->date = date("Y/m/d h:i:s");

            if ($received->validate()) {
                $received->save();
                Log::model()->saveAction("lunch_stock", $received->id, "C", $received->inventory->item->name . "|" . $amount * $received->inventory->item->measure . " " . $received->inventory->item->unity->acronym);
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Item added successfully.'));
                $this->redirect(['stock/index']);
            } else {
                $inventory->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem on item insertion.'));
                $this->redirect(['stock/index']);
            }
        } else {
            $inventory->delete();
            Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem on inventory insertion.'));
            $this->redirect(['stock/index']);
        }
    }

    public function actionRemoveItem(){
        $request = Yii::app()->getRequest();

        $item = $request->getPost('Item');
        $amount = $request->getPost('Amount');
        $motivation = $request->getPost('Motivation');
        $school = yii::app()->user->school;

        $inventory = new Inventory();
        $inventory->school_fk = $school;
        $inventory->item_fk = $item;
        $inventory->amount = $amount > 0 ? $amount * -1 : $amount;

        if ($inventory->validate()) {
            $inventory->save();

            $spent = new Spent();
            $spent->inventory_fk = $inventory->id;
            $spent->date = date("Y/m/d h:i:s");
            $spent->motivation = $motivation;

            if ($spent->validate()) {
                $spent->save();
                Log::model()->saveAction("lunch_stock", $spent->id, "D", $spent->inventory->item->name . "|" . $amount * $spent->inventory->item->measure . " " . $spent->inventory->item->unity->acronym);
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Item spent successfully.'));
                $this->redirect(['stock/index']);
            } else {
                $inventory->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem when spending.'));
                $this->redirect(['stock/index']);
            }
        } else {
            $inventory->delete();
            Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem on inventory insertion.'));
            $this->redirect(['stock/index']);
        }
    }
}
