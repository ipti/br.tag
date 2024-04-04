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
 * @property string $status
 * @property string $school_fk
 * @property integer $farmer_fk
 *
 * The followings are the available model relations:
 * @property Food $foodFk
 * @property FarmerRegister $farmerFk
 * @property SchoolIdentification $schoolFk
 */
class FoodRequest extends CActiveRecord
{
    public function tableName()
    {
        return 'food_request';
    }

    public function rules()
    {
        return array(
            array('school_fk, farmer_fk', 'required'),
            array('food_fk, farmer_fk', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('measurementUnit', 'length', 'max'=>7),
            array('description', 'length', 'max'=>100),
            array('status', 'length', 'max'=>12),
            array('school_fk', 'length', 'max'=>8),
            array('date', 'safe'),
            array('id, date, food_fk, amount, measurementUnit, description, status, school_fk, farmer_fk', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'foodFk' => array(self::BELONGS_TO, 'Food', 'food_fk'),
            'farmerFk' => array(self::BELONGS_TO, 'FarmerRegister', 'farmer_fk'),
            'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
        );
    }

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
            'school_fk' => 'School Fk',
            'farmer_fk' => 'Agricultor',
        );
    }

    public function search()
    {

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('date',$this->date,true);
        $criteria->compare('food_fk',$this->food_fk);
        $criteria->compare('amount',$this->amount);
        $criteria->compare('measurementUnit',$this->measurementUnit,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('school_fk',$this->school_fk,true);
        $criteria->compare('farmer_fk',$this->farmer_fk);

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
