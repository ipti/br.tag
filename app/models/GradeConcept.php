<?php

/**
 * This is the model class for table "grade_concept".
 *
 * The followings are the available columns in table 'grade_concept':
 * @property integer $id
 * @property string $name
 * @property string $acronym
 * @property double $value
 *
 * The followings are the available model relations:
 * @property Grade[] $grades
 */
class GradeConcept extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_concept';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, acronym', 'required'),
            array('value', 'numerical'),
            array('name', 'length', 'max'=>50),
            array('acronym', 'length', 'max'=>5),
            // The following rule is used by search().
            array('id, name, acronym, value', 'safe', 'on'=>'search'),
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
            'grades' => array(self::HAS_MANY, 'Grade', 'grade_concept_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'name' => 'Nome',
            'acronym' => 'Acrônimo',
            'value' => 'Valor',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('acronym',$this->acronym,true);
        $criteria->compare('value',$this->value);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GradeConcept the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
