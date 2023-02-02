<?php

/**
 * This is the model class for table "edcenso_district".
 *
 * The followings are the available columns in table 'edcenso_district':
 * @property integer $register
 * @property integer $corder
 * @property integer $attr
 * @property string $cdesc
 * @property string $default
 *
 *
 * The followings are the available model relations:
 * @property EdcensoCity $edcensoCityFk
 * @property SchoolIdentification[] $schoolIdentifications
 */
class EdcensoAlias extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EdcensoAlias the static model class
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
        return 'edcenso_alias';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['register, corder, attr, cdesc', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        // return array(
        // 	'edcensoCityFk' => array(self::BELONGS_TO, 'EdcensoCity', 'edcenso_city_fk'),
        // 	'schoolIdentifications' => array(self::HAS_MANY, 'SchoolIdentification', 'edcenso_district_fk'),
        // );
        return [];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'register' => Yii::t('default', 'ID'),
            'corder' => Yii::t('default', 'Edcenso City Fk'),
            'attr' => Yii::t('default', 'Code'),
            'cdesc' => Yii::t('default', 'Name'),
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

        $criteria->compare('register', $this->register);
        $criteria->compare('corder', $this->corder);
        $criteria->compare('attr', $this->attr);
        $criteria->compare('cdesc', $this->cdesc, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
