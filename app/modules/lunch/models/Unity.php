<?php

/**
 * This is the model class for table "lunch_unity".
 *
 * The followings are the available columns in table 'lunch_unity':
 * @property int $id
 * @property string $name
 * @property string $acronym
 *
 * The followings are the available model relations:
 * @property Item[] $items
 * @property Portion[] $portions
 */
class Unity extends TagModel
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name
     * @return Unity the static model class
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
        return 'lunch_unity';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, acronym', 'required'],
            ['name', 'length', 'max' => 45],
            ['acronym', 'length', 'max' => 10],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, acronym', 'safe', 'on' => 'search'],
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
            'items' => [self::HAS_MANY, 'Item', 'unity_fk'],
            'portions' => [self::HAS_MANY, 'Portion', 'unity_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('lunchModule.labels', 'ID'),
            'name' => Yii::t('lunchModule.labels', 'Name'),
            'acronym' => Yii::t('lunchModule.labels', 'Acronym'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('acronym', $this->acronym, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
