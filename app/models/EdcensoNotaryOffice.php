<?php

/**
 * This is the model class for table "edcenso_notary_office".
 *
 * The followings are the available columns in table 'edcenso_notary_office':
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $uf
 * @property string $cod
 */
class EdcensoNotaryOffice extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EdcensoNotaryOffice the static model class
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
        return 'edcenso_notary_office';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name', 'length', 'max' => 250],
            ['city', 'length', 'max' => 100],
            ['uf', 'length', 'max' => 2],
            ['cod', 'length', 'max' => 6],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, name, city, uf, cod', 'safe', 'on' => 'search'],
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
            'city' => Yii::t('default', 'City'),
            'uf' => Yii::t('default', 'Uf'),
            'cod' => Yii::t('default', 'Cod'),
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
        $criteria->compare('city', $this->city, true);
        $criteria->compare('uf', $this->uf, true);
        $criteria->compare('cod', $this->cod, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
