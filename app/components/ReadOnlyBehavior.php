<?php

class ReadOnlyBehavior extends CActiveRecordBehavior
{
    public function beforeSave($event)
    {
        if ($this->isReadOnlyUser()) {
            Yii::app()->user->setFlash('error', 'Você não tem permissão para salvar dados.');
            $event->isValid = false;
            $controller = Yii::app()->controller->id;
            $defaultUrl = Yii::app()->createUrl($controller);

            Yii::app()->controller->redirect($defaultUrl);
            return false;
        }
        return parent::beforeSave($event);
    }

    public function beforeDelete($event)
    {
        if ($this->isReadOnlyUser()) {
            Yii::app()->user->setFlash('error', 'Você não tem permissão para excluir registros.');
            $event->isValid = false; // Cancela o salvamento

            $controller = Yii::app()->controller->id;
            $defaultUrl = Yii::app()->createUrl($controller);

            Yii::app()->controller->redirect($defaultUrl);
        }
    }

    private function isReadOnlyUser()
    {
        return Yii::app()->getAuthManager()->checkAccess('reader', Yii::app()->user->loginInfos->id);

    }
}
