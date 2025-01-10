<?php

/**
 * This is the model class for table "instance_config".
 *
 * The followings are the available columns in table 'instance_config':
 * @property integer $id
 * @property string $parameter_key
 * @property string $parameter_name
 * @property string $value
 */
class InstanceConfig extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'instance_config';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parameter_key, parameter_name', 'required'),
            array('parameter_key', 'length', 'max' => 40),
            array('parameter_name', 'length', 'max' => 150),
            array('value', 'length', 'max' => 250),
            // The following rule is used by search().
            array('id, parameter_key, parameter_name, value', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parameter_key' => 'Parameter Key',
            'parameter_name' => 'Parameter Name',
            'value' => 'Value',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('parameter_key', $this->parameter_key, true);
        $criteria->compare('parameter_name', $this->parameter_name, true);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InstanceConfig the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
