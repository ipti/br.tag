<?php

Yii::import('zii.behaviors.CTimestampBehavior');
class TagModel extends CActiveRecord
{
    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class'               => 'zii.behaviors.CTimestampBehavior',
                'createAttribute'     => 'created_at',
                'updateAttribute'     => 'updated_at',
                'setUpdateOnCreate'   => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ],
        ];
    }

    public function save($runValidation = true, $attributes = null)
    {
        if (!Yii::app()->user->isGuest && $this->isReadOnlyUser()) {
            Yii::app()->user->setFlash('error', 'Você não tem permissão para salvar dados.');

            return false;
        }

        return parent::save($runValidation, $attributes);
    }

    public function delete()
    {
        if ($this->isReadOnlyUser()) {
            Yii::app()->user->setFlash('error', 'Você não tem permissão para salvar dados.');

            return false;
        }

        return parent::delete();
    }

    private function isReadOnlyUser()
    {
        return Yii::app()->getAuthManager()->checkAccess('reader', Yii::app()->user->loginInfos->id);
    }
}
