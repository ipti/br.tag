<?php

/**
 * This is the model class for table "feature_flags".
 *
 * The followings are the available columns in table 'feature_flags':
 * @property string $feature_name
 * @property integer $active
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property AuthItem $featureName
 */
class FeatureFlags extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'feature_flags';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['feature_name, updated_at', 'required'],
            ['active', 'numerical', 'integerOnly' => true],
            ['feature_name', 'length', 'max' => 64],
            ['feature_name, active, updated_at', 'safe', 'on' => 'search'],
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
            'featureName' => [self::BELONGS_TO, 'AuthItem', 'feature_name'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'feature_name' => 'Feature Name',
            'active' => 'Active',
            'updated_at' => 'Updated At',
        ];
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
        $criteria = new CDbCriteria();

        $criteria->compare('feature_name', $this->feature_name, true);
        $criteria->compare('active', $this->active);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FeatureFlags the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
