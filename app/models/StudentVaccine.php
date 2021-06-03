<?php

/**
 * This is the model class for table "student_vaccine".
 *
 * The followings are the available columns in table 'student_vaccine':
 * @property integer $vaccine_id
 * @property integer $student_id
 */
class StudentVaccine extends AltActiveRecord
{

    public $student_id;
    public $vaccine_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return StudentVaccine the static model class
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
        return 'student_vaccine';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('vaccine_id', 'required'),
            array('student_id', 'required')
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.


         return array(
            'student_id' => array(self::BELONGS_TO, 'StudentIdentification', 'id'),
            'vaccine_id' => array(self::BELONGS_TO, 'Vaccine', 'id'),
        );
    }

     /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('default', 'ID'),
            'student_id' => Yii::t('default', 'Student ID'),
            'vaccine_id' => Yii::t('default', 'Vaccine ID')
        );
    }



}