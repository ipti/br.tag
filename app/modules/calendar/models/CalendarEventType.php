<?php

/**
 * This is the model class for table "calendar_event_type".
 *
 * The followings are the available columns in table 'calendar_event_type':
 * @property integer $id
 * @property string $name
 * @property string $icon
 * @property string $color
 * @property integer $unique_day
 *
 * The followings are the available model relations:
 * @property CalendarEvent[] $calendarEvents
 */
class CalendarEventType extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calendar_event_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, icon, color, unique_day', 'required'],
            ['unique_day', 'numerical', 'integerOnly' => true],
            ['name, icon, color', 'length', 'max' => 50],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, name, icon, color, unique_day', 'safe', 'on' => 'search'],
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
            'calendarEvents' => [self::HAS_MANY, 'CalendarEvent', 'calendar_event_type_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => yii::t('calendarModule.labels', 'ID'),
            'name' => yii::t('calendarModule.labels', 'Name'),
            'icon' => yii::t('calendarModule.labels', 'Icon'),
            'color' => yii::t('calendarModule.labels', 'Color'),
            'unique_day' => yii::t('calendarModule.labels', 'Unique Day')
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
        $criteria->compare('icon', $this->icon, true);
        $criteria->compare('color', $this->color, true);
        $criteria->compare('unique_day', $this->unique_day);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CalendarEventType the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getNameTranslated()
    {
        return yii::t('calendarModule.labels', $this->name);
    }
}
