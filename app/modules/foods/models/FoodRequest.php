<?php

/**
 * This is the model class for table "food_request".
 *
 * The followings are the available columns in table 'food_request':
 * @property integer $id
 * @property string $date
 * @property integer $food_fk
 * @property double $amount
 * @property string $measurementUnit
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Food $foodFk
 */
class FoodRequest extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'food_request';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_fk', 'required'),
            array('food_fk', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('measurementUnit', 'length', 'max'=>7),
            array('description', 'length', 'max'=>100),
            array('status', 'length', 'max'=>12),
            array('school_fk', 'length', 'max'=>8),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date, food_fk, amount, measurementUnit, description, status, school_fk', 'safe', 'on'=>'search'),
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
            'foodFk' => array(self::BELONGS_TO, 'Food', 'food_fk'),
            'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'date' => 'Data',
            'food_fk' => 'Food Fk',
            'amount' => 'Quantidade',
            'measurementUnit' => 'Unidade',
            'description' => 'Descrição',
            'status' => 'Status',
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('date',$this->date,true);
        $criteria->compare('food_fk',$this->food_fk);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('measurementUnit',$this->measurementUnit,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('school_fk',$this->school_fk,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodRequest the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}

