<?php

/**
 * Class StockController
 *
 * @property School $school
 */

class StockController extends CController {

    public $school;

    public function init(){
        $this->school = School::model()->findByPk(yii::app()->user->school);
    }

    public function actionIndex(){
        $this->render('index');
    }

    public function actionAddItem(){
        $request = Yii::app()->getRequest();

        $item = $request->getPost('Item');
        $amount = $request->getPost('Amount');
        $school = yii::app()->user->school;

        $inventory = new Inventory();
        $inventory->school_fk = $school;
        $inventory->item_fk = $item;
        $inventory->amount = $amount;

        if($inventory->validate()){
            $inventory->save();

            $received = new Received();
            $received->inventory_fk = $inventory->id;
            $received->date = date("Y/m/d h:i:s");

            if($received->validate()){
                $received->save();
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Item added successfully.'));
                $this->redirect(['stock/index']);
            }else{
                $inventory->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem on item insertion.'));
                $this->redirect(['stock/index']);
            }
        }else{
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
        $inventory->amount = $amount > 0 ? $amount*-1 : $amount;

        if($inventory->validate()){
            $inventory->save();

            $spent = new Spent();
            $spent->inventory_fk = $inventory->id;
            $spent->date = date("Y/m/d h:i:s");
            $spent->motivation = $motivation;

            if($spent->validate()){
                $spent->save();
                Yii::app()->user->setFlash('success', Yii::t('lunchModule.stock', 'Item spent successfully.'));
                $this->redirect(['stock/index']);
            }else{
                $inventory->delete();
                Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem when spending.'));
                $this->redirect(['stock/index']);
            }
        }else{
            $inventory->delete();
            Yii::app()->user->setFlash('error', Yii::t('lunchModule.stock', 'Problem on inventory insertion.'));
            $this->redirect(['stock/index']);
        }
    }
}