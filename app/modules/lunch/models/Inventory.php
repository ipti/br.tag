<?php

/**
 * This is the model class for table "lunch_inventory".
 *
 * The followings are the available columns in table 'lunch_inventory':
 * @property integer $id
 * @property string $school_fk
 * @property integer $item_fk
 * @property double $amount
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolFk
 * @property Received[] $receiveds
 * @property Spent[] $spents
 * @property Item $item
 */
class Inventory extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Inventory the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'lunch_inventory';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('school_fk, item_fk, amount', 'required'),
            array('item_fk', 'numerical', 'integerOnly'=>true),
            array('amount', 'numerical'),
            array('school_fk', 'length', 'max'=>8),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, school_fk, item_fk, amount', 'safe', 'on'=>'search'),
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
            'school' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
            'received' => array(self::BELONGS_TO, 'Received', 'inventory_fk'),
            'spent' => array(self::BELONGS_TO, 'Spent', 'inventory_fk'),
            'item' => [self::BELONGS_TO, 'Item', 'item_fk']
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('lunchModule.labels', 'ID'),
            'school_fk' => Yii::t('lunchModule.labels', 'School'),
            'item_fk' => Yii::t('lunchModule.labels', 'Item'),
            'amount' => Yii::t('lunchModule.labels', 'Amount'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('school_fk', $this->school_fk, true);
        $criteria->compare('item_fk', $this->item_fk);
        $criteria->compare('amount', $this->amount);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
