<?php

/**
 * This is the model class for table "lunch_received".
 *
 * The followings are the available columns in table 'lunch_received':
 * @property integer $id
 * @property string $date
 * @property integer $inventory_fk
 *
 * The followings are the available model relations:
 * @property Inventory $inventory
 */
class Received extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Received the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'lunch_received';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['inventory_fk', 'required'],
            ['inventory_fk', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, date, inventory_fk', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'inventory' => [self::BELONGS_TO, 'Inventory', 'inventory_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lunchModule.labels', 'ID'),
            'date' => Yii::t('lunchModule.labels', 'Date'),
            'inventory_fk' => Yii::t('lunchModule.labels', 'Inventory'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('date', $this->date, true);
        $criteria->compare('inventory_fk', $this->inventory_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
