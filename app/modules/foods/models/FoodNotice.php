
<?php

/**
 * This is the model class for table "food_notice".
 *
 * The followings are the available columns in table 'food_notice':
 * @property integer $id
 * @property string $name
 * @property string $date
 * @property string $status
 * @property string $reference_id
 * @property string $file_name
 *
 * The followings are the available model relations:
 * @property FarmerFoods[] $farmerFoods
 * @property FoodNoticeItem[] $foodNoticeItems
 * @property FoodRequest[] $foodRequests
 */
class FoodNotice extends TagModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food_notice';
	}


    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, date', 'required'),
            array('name', 'length', 'max'=>255),
            array('status', 'length', 'max'=>7),
            array('reference_id', 'length', 'max'=>36),
            array('file_name', 'length', 'max'=>100),
            // The following rule is used by search().
            array('id, name, date, status, reference_id, file_name', 'safe', 'on'=>'search'),
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
            'farmerFoods' => array(self::HAS_MANY, 'FarmerFoods', 'foodNotice_fk'),
            'foodNoticeItems' => array(self::HAS_MANY, 'FoodNoticeItem', 'foodNotice_fk'),
            'foodRequests' => array(self::HAS_MANY, 'FoodRequest', 'notice_fk'),
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
            'date' => 'Date',
            'status' => 'Status',
            'reference_id' => 'Reference',
            'file_name' => 'File Name',
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
        $criteria->compare('date',$this->date,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('reference_id',$this->reference_id,true);
        $criteria->compare('file_name',$this->file_name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FoodNotice the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
