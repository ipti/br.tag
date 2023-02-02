<?php

/**
 * This is the model class for table "course_class_has_class_resource".
 *
 * The followings are the available columns in table 'course_class_has_class_resource':
 * @property integer $id
 * @property integer $course_class_fk
 * @property integer $class_resource_fk
 * @property string $amount
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property CourseClass $courseClassFk
 * @property ClassResources $classResourceFk
 */
class CourseClassHasClassResource extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'course_class_has_class_resource';
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
            ['course_class_fk, class_resource_fk', 'required'],
            ['course_class_fk, class_resource_fk', 'numerical', 'integerOnly' => true],
            ['amount', 'length', 'max' => 10],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, course_class_fk, class_resource_fk, amount', 'safe', 'on' => 'search'],
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
            'courseClassFk' => [self::BELONGS_TO, 'CourseClass', 'course_class_fk'],
            'classResourceFk' => [self::BELONGS_TO, 'ClassResource', 'class_resource_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_class_fk' => 'Course Class Fk',
            'class_resource_fk' => 'Class Resource Fk',
            'amount' => 'Amount',
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
        $criteria->compare('course_class_fk', $this->course_class_fk);
        $criteria->compare('class_resource_fk', $this->class_resource_fk);
        $criteria->compare('amount', $this->amount, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CourseClassHasClassResource the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
