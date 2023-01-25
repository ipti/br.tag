<?php

/**
 * This is the model class for table "student_enrollment".
 *
 * The followings are the available columns in table 'student_enrollment':
 * @property integer $id
 * @property string $name
 * @property string $code
 */
class Vaccine extends AltActiveRecord
{
    public $name;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Vaccine the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'vaccine';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max'=>100),
            array('code', 'length', 'max'=>10),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
            'code' => Yii::t('default', 'Code')
        );
    }
}
