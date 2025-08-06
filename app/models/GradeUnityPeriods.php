<?php

/**
 * This is the model class for table "grade_unity_periods".
 *
 * The followings are the available columns in table 'grade_unity_periods':
 *
 * @property int    $id
 * @property string $initial_date
 * @property int    $grade_unity_fk
 * @property int    $calendar_fk
 *
 * The followings are the available model relations:
 * @property GradeUnity $gradeUnityFk
 * @property Calendar   $calendarFk
 */
class GradeUnityPeriods extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'grade_unity_periods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['initial_date, grade_unity_fk, calendar_fk', 'required'],
            ['grade_unity_fk, calendar_fk', 'numerical', 'integerOnly'=>true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, initial_date, grade_unity_fk, calendar_fk', 'safe', 'on'=>'search'],
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
            'gradeUnityFk' => [self::BELONGS_TO, 'GradeUnity', 'grade_unity_fk'],
            'calendarFk'   => [self::BELONGS_TO, 'Calendar', 'calendar_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'initial_date'   => 'Initial Date',
            'grade_unity_fk' => 'Grade Unity Fk',
            'calendar_fk'    => 'Calendar Fk',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('initial_date', $this->initial_date, true);
        $criteria->compare('grade_unity_fk', $this->grade_unity_fk);
        $criteria->compare('calendar_fk', $this->calendar_fk);

        return new CActiveDataProvider($this, [
            'criteria'=> $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return GradeUnityPeriods the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
