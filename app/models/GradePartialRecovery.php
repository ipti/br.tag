<?php

/**
 * This is the model class for table "grade_partial_recovery".
 *
 * The followings are the available columns in table 'grade_partial_recovery':
 * @property integer $id
 * @property string $name
 * @property integer $order_partial_recovery
 * @property integer $grade_rules_fk
 * @property integer $grade_calculation_fk
 *
 * The followings are the available model relations:
 * @property GradeCalculation $gradeCalculationFk
 * @property GradeRules $gradeRulesFk
 * @property GradeUnity[] $gradeUnities
 */
class GradePartialRecovery extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_partial_recovery';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, order_partial_recovery, grade_rules_fk, grade_calculation_fk', 'required'],
            ['order_partial_recovery, grade_rules_fk, grade_calculation_fk', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 50],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, order_partial_recovery, grade_rules_fk, grade_calculation_fk', 'safe', 'on' => 'search'],
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
            'gradeCalculationFk' => [self::BELONGS_TO, 'GradeCalculation', 'grade_calculation_fk'],
            'gradeRulesFk' => [self::BELONGS_TO, 'GradeRules', 'grade_rules_fk'],
            'gradeUnities' => [self::HAS_MANY, 'GradeUnity', 'parcial_recovery_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'order_partial_recovery' => 'Order Partial Recovery',
            'grade_rules_fk' => 'Grade Rules Fk',
            'grade_calculation_fk' => 'Grade Calculation Fk',
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('order_partial_recovery', $this->order_partial_recovery);
        $criteria->compare('grade_rules_fk', $this->grade_rules_fk);
        $criteria->compare('grade_calculation_fk', $this->grade_calculation_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return GradePartialRecovery the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
