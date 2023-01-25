<?php

/**
 * This is the model class for table "calendar_event".
 *
 * The followings are the available columns in table 'calendar_event':
 * @property integer $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property integer $calendar_fk
 * @property integer $calendar_event_type_fk
 * @property integer $copyable
 * @property string $school_fk
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolFk
 * @property Calendar $calendarFk
 * @property CalendarEventType $calendarEventTypeFk
 */
class CalendarEvent extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calendar_event';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, start_date, end_date, calendar_fk, calendar_event_type_fk, copyable', 'required'),
            array('calendar_fk, calendar_event_type_fk, copyable', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>50),
            array('school_fk', 'length', 'max'=>8),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, start_date, end_date, calendar_fk, calendar_event_type_fk, copyable, school_fk', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'schoolFk' => array(self::BELONGS_TO, 'SchoolIdentification', 'school_fk'),
            'calendarFk' => array(self::BELONGS_TO, 'Calendar', 'calendar_fk'),
            'calendarEventTypeFk' => array(self::BELONGS_TO, 'CalendarEventType', 'calendar_event_type_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => yii::t('calendarModule.labels', 'ID'),
            'name' => yii::t('calendarModule.labels', 'Name'),
            'start_date' => yii::t('calendarModule.labels', 'Start Date'),
            'end_date' => yii::t('calendarModule.labels', 'End Date'),
            'calendar_fk' => yii::t('calendarModule.labels', 'Calendar'),
            'calendar_event_type_fk' => yii::t('calendarModule.labels', 'Event Type'),
            'copyable' => yii::t('calendarModule.labels', 'Copyable'),
            'school_fk' => 'School Fk',
        );
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

        $criteria=new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('calendar_fk', $this->calendar_fk);
        $criteria->compare('calendar_event_type_fk', $this->calendar_event_type_fk);
        $criteria->compare('copyable', $this->copyable);
        $criteria->compare('school_fk', $this->school_fk, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CalendarEvent the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
