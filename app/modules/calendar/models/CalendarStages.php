<?php

/**
 * This is the model class for table "calendar_stages".
 *
 * The followings are the available columns in table 'calendar_stages':
 * @property integer $id
 * @property integer $calendar_fk
 * @property integer $stage_fk
 *
 * The followings are the available model relations:
 * @property Calendar $calendarFk
 * @property EdcensoStageVsModality $stageFk
 */
class CalendarStages extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calendar_stages';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['calendar_fk, stage_fk', 'required'],
            ['calendar_fk, stage_fk', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, calendar_fk, stage_fk', 'safe', 'on' => 'search'],
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
            'calendarFk' => [self::BELONGS_TO, 'Calendar', 'calendar_fk'],
            'stageFk' => [self::BELONGS_TO, 'EdcensoStageVsModality', 'stage_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'calendar_fk' => 'Calendar Fk',
            'stage_fk' => 'Stage Fk',
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
        $criteria->compare('calendar_fk', $this->calendar_fk);
        $criteria->compare('stage_fk', $this->stage_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CalendarStages the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
