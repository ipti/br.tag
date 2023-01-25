<?php

/**
 * This is the model class for table "calendar".
 *
 * The followings are the available columns in table 'calendar':
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property integer $available
 *
 * The followings are the available model relations:
 * @property CalendarEvent[] $calendarEvents
 * @property CalendarStages[] $calendarStages
 * @property Classroom[] $classrooms
 */
class Calendar extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calendar';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['title, start_date, end_date, available', 'required'],
            ['available', 'numerical', 'integerOnly' => true],
            ['title', 'length', 'max' => 50],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, title, start_date, end_date, available', 'safe', 'on' => 'search'],
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
            'calendarEvents' => [self::HAS_MANY, 'CalendarEvent', 'calendar_fk'],
            'calendarStages' => [self::HAS_MANY, 'CalendarStages', 'calendar_fk'],
            'classrooms' => [self::HAS_MANY, 'Classroom', 'calendar_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::t('calendarModule.labels', 'ID'),
            'title' => yii::t('calendarModule.labels', 'Title'),
            'start_date' => yii::t('calendarModule.labels', 'Start Date'),
            'end_date' => yii::t('calendarModule.labels', 'End Date'),
            'available' => 'Available',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('available', $this->available);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    public function getCopyableEvents()
    {
        /** @var $event CalendarEvent */
        $events = $this->calendarEvents;
        $copyables = [];
        foreach ($events as $event) {
            if ($event->copyable) {
                array_push($copyables, $event);
            }
        }
        return $copyables;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Calendar the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
