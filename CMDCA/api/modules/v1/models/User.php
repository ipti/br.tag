<?php

namespace app\modules\v1\models;

use yii\mongodb\ActiveRecord;
use MongoDB\BSON\ObjectId;

class User extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'user';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'name', 'email', 'status', 'address', 'credential', 'institution_id'];
    }

    public function rules()
    {
        return [
            [['name', 'email', 'status', 'credential','institution_id'], 'required', 'message' => 'Campo obrigatório'],
            ['email', 'email', 'message' => 'E-mail inválido'],
            ['address', 'safe'],
        ];
    }

    public function beforeValidate(){
        if(!is_object($this->institution_id)){
            $this->institution_id = new ObjectId($this->institution_id);
        }
        return true;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $credential = $this->credential;
                $credential['password'] = password_hash($credential['password'], PASSWORD_BCRYPT, ['cost'=>12]);
                $this->credential = $credential;
            }
            return true;
        }
        return false;
    }
}



?>