<?php

namespace app\modules\v1\models;

use yii\mongodb\ActiveRecord;

class Institution extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'institution';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'name', 'type', 'status'];
    }

    public function rules()
    {
        return [
            [['name', 'type', 'status'], 'required', 'message' => 'Campo obrigatório']
        ];
    }
}


?>