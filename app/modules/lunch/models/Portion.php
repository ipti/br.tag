<?php

/**
 * This is the model class for table "lunch_portion".
 *
 * The followings are the available columns in table 'lunch_portion':
 * @property integer $id
 * @property integer $item_fk
 * @property integer $amount
 * @property integer $unity_fk
 * @property double $measure
 *
 * The followings are the available model relations:
 * @property MealPortion[] $mealPortions
 * @property Item $item
 * @property Unity $unity
 */
class Portion extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Portion the static model class
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
        return 'lunch_portion';
    }

    /**
     * Get the item name with the measure
     * @return String the item name with the measure
     */
    public function getConcatName()
    {
        return $this->item->name . " (". $this->measure.$this->unity->acronym.")";
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('item_fk, amount, unity_fk, measure', 'required'),
            array('item_fk, amount, unity_fk', 'numerical', 'integerOnly'=>true),
            array('measure', 'numerical'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, item_fk, amount, unity_fk, measure', 'safe', 'on'=>'search'),
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
            'mealPortions' => array(self::HAS_MANY, 'MealPortion', 'portion_fk'),
            'item' => array(self::BELONGS_TO, 'Item', 'item_fk'),
            'unity' => array(self::BELONGS_TO, 'Unity', 'unity_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Yii::t('lunchModule.labels', 'ID'),
            'item_fk' => Yii::t('lunchModule.labels', 'Item'),
            'amount' => Yii::t('lunchModule.labels', 'Amount'),
            'unity_fk' => Yii::t('lunchModule.labels', 'Unity'),
            'measure' => Yii::t('lunchModule.labels', 'Measure'),
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
        $criteria->compare('item_fk', $this->item_fk);
        $criteria->compare('amount', $this->amount);
        $criteria->compare('unity_fk', $this->unity_fk);
        $criteria->compare('measure', $this->measure);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
