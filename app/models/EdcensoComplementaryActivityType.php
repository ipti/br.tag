<?php

/**
 * This is the model class for table "edcenso_complementary_activity_type".
 *
 * The followings are the available columns in table 'edcenso_complementary_activity_type':
 * @property integer $id
 * @property string $name
 */
class EdcensoComplementaryActivityType extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EdcensoComplementaryActivityType the static model class
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
        return 'edcenso_complementary_activity_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['id, name', 'required'],
            ['id', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 200],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name', 'safe', 'on' => 'search'],
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
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('default', 'ID'),
            'name' => Yii::t('default', 'Name'),
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
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
