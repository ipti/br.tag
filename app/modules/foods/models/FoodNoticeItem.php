<?php

/**
 * This is the model class for table "food_notice_item".
 *
 * The followings are the available columns in table 'food_notice_item':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $measurement
 * @property string $year_amount
 * @property integer $food_id
 * @property integer $foodNotice_fk
 *
 * The followings are the available model relations:
 * @property Food $food
 * @property FoodNotice $foodNoticeFk
 */
class FoodNoticeItem extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_notice_item';
	}


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, measurement', 'required'),
            array('food_id, foodNotice_fk', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            array('description', 'length', 'max'=>1000),
            array('measurement, year_amount', 'length', 'max'=>20),
            // The following rule is used by search().
            array('id, name, description, measurement, year_amount, food_id, foodNotice_fk', 'safe', 'on'=>'search'),
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
            'food' => array(self::BELONGS_TO, 'Food', 'food_id'),
            'foodNoticeFk' => array(self::BELONGS_TO, 'FoodNotice', 'foodNotice_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'measurement' => 'Measurement',
            'year_amount' => 'Year Amount',
            'food_id' => 'Food',
            'foodNotice_fk' => 'Food Notice Fk',
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
        $criteria->compare('description',$this->description,true);
        $criteria->compare('measurement',$this->measurement,true);
        $criteria->compare('year_amount',$this->year_amount,true);
        $criteria->compare('food_id',$this->food_id);
        $criteria->compare('foodNotice_fk',$this->foodNotice_fk);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodNoticeItem the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
