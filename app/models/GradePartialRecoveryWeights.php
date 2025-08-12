<?php

/**
 * This is the model class for table "grade_partial_recovery_weights".
 *
 * The followings are the available columns in table 'grade_partial_recovery_weights':
 * @property int $id
 * @property int $weight
 * @property int $unity_fk
 * @property int $partial_recovery_fk
 *
 * The followings are the available model relations:
 * @property GradeUnity $unityFk
 * @property GradePartialRecovery $partialRecoveryFk
 */
class GradePartialRecoveryWeights extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_partial_recovery_weights';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['weight, partial_recovery_fk', 'required'],
            ['weight, unity_fk, partial_recovery_fk', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            ['id, weight, unity_fk, partial_recovery_fk', 'safe', 'on' => 'search'],
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
            'unityFk' => [self::BELONGS_TO, 'GradeUnity', 'unity_fk'],
            'partialRecoveryFk' => [self::BELONGS_TO, 'GradePartialRecovery', 'partial_recovery_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'weight' => 'Weight',
            'unity_fk' => 'Unity Fk',
            'partial_recovery_fk' => 'Partial Recovery Fk',
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
     * based on the search/filter conditions
     */
    public function search()
    {

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('weight', $this->weight);
        $criteria->compare('unity_fk', $this->unity_fk);
        $criteria->compare('partial_recovery_fk', $this->partial_recovery_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return GradePartialRecoveryWeights the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
