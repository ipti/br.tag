<?php

/**
 * This is the model class for table "food_measurement".
 *
 * The followings are the available columns in table 'food_measurement':
 * @property int $id
 * @property string $unit
 * @property float $value
 * @property string $measure
 */
class FoodMeasurement extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_measurement';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['unit, value, measure', 'required'],
            ['value', 'numerical'],
            ['unit', 'length', 'max' => 14],
            ['measure', 'length', 'max' => 2],
            // The following rule is used by search().
            ['id, unit, value, measure', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit' => 'Unit',
            'value' => 'Value',
            'measure' => 'Measure',
        ];
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
     * based on the search/filter conditions
     */
    public function search()
    {

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('unit', $this->unit, true);
        $criteria->compare('value', $this->value);
        $criteria->compare('measure', $this->measure, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return FoodMeasurement the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
