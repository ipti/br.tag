<?php

/**
 * This is the model class for table "enrollment_online_pre_enrollment_event_online".
 *
 * The followings are the available columns in table 'enrollment_online_pre_enrollment_event_online':
 * @property integer $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property integer $year
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property EnrollmentOnlineEventVsEdcensoStage[] $enrollmentOnlineEventVsEdcensoStages
 */
class EnrollmentOnlinePreEnrollmentEventOnline extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'enrollment_online_pre_enrollment_event_online';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, start_date, end_date, year', 'required'),
            array('year', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, start_date, end_date, year, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'enrollmentOnlineEventVsEdcensoStages' => array(self::HAS_MANY, 'EnrollmentOnlineEventVsEdcensoStage', 'pre_enrollment_event_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Nome do evento',
            'start_date' => 'Data de início',
            'end_date' => 'Data de término',
            'year' => 'Ano',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('start_date', $this->start_date, true);
        $criteria->compare('end_date', $this->end_date, true);
        $criteria->compare('year', $this->year);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return EnrollmentOnlinePreEnrollmentEventOnline the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
