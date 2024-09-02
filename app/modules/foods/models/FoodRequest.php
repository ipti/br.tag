<?php

/**
 * This is the model class for table "food_request".
 *
 * The followings are the available columns in table 'food_request':
 * @property integer $id
 * @property string $date
 * @property string $status
 * @property integer $notice_fk
 * @property string $reference_id
 *
 * The followings are the available model relations:
 * @property FoodNotice $noticeFk
 * @property FoodRequestItem[] $foodRequestItems
 * @property FoodRequestVsFarmerRegister[] $foodRequestVsFarmerRegisters
 * @property FoodRequestVsSchoolIdentification[] $foodRequestVsSchoolIdentifications
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
    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
                'timestampExpression' => new CDbExpression('CONVERT_TZ(NOW(), "+00:00", "-03:00")'),
            ]
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('notice_fk', 'numerical', 'integerOnly'=>true),
            array('status', 'length', 'max'=>12),
            array('reference_id', 'length', 'max'=>36),
            array('date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, date, status, notice_fk, reference_id', 'safe', 'on'=>'search'),
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
            'noticeFk' => array(self::BELONGS_TO, 'FoodNotice', 'notice_fk'),
            'foodRequestItems' => array(self::HAS_MANY, 'FoodRequestItem', 'food_request_fk'),
            'foodRequestVsFarmerRegisters' => array(self::HAS_MANY, 'FoodRequestVsFarmerRegister', 'food_request_fk'),
            'foodRequestVsSchoolIdentifications' => array(self::HAS_MANY, 'FoodRequestVsSchoolIdentification', 'food_request_fk'),
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
            'status' => 'Status',
            'notice_fk' => 'Edital',
            'reference_id' => 'Reference',
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
        $criteria->compare('status',$this->status,true);
        $criteria->compare('notice_fk',$this->notice_fk);
        $criteria->compare('reference_id',$this->reference_id,true);

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
