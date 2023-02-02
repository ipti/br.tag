<?php

/**
 * This is the model class for table "class_faults".
 *
 * The followings are the available columns in table 'class_faults':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer $student_fk
 * @property string $fkid
 * @property string $justification
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property StudentEnrollment $studentFk
 */
class ClassFaults extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ClassFaults the static model class
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
        return 'class_faults';
    }

    public function behaviors()
    {
        return [
            'afterSave' => [
                'class' => 'application.behaviors.CAfterSaveBehavior',
                'schoolInepId' => Yii::app()->user->school,
            ],
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['schedule_fk, student_fk', 'required'],
            ['schedule_fk, student_fk', 'numerical', 'integerOnly' => true],
            ['justification', 'length', 'max' => 200],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, schedule_fk, student_fk', 'safe', 'on' => 'search'],
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
            'scheduleFk' => [self::BELONGS_TO, 'Schedule', 'schedule_fk'],
            'studentFk' => [self::BELONGS_TO, 'StudentEnrollment', 'student_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('default', 'ID'),
            'schedule_fk' => Yii::t('default', 'Schedule Fk'),
            'student_fk' => Yii::t('default', 'Student Fk'),
            'justification' => Yii::t('default', 'Justification')
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
        $criteria->compare('schedule_fk', $this->schedule_fk);
        $criteria->compare('student_fk', $this->student_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}
