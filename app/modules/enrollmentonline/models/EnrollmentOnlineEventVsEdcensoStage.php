<?php

/**
 * This is the model class for table "enrollment_online_event_vs_edcenso_stage".
 *
 * The followings are the available columns in table 'enrollment_online_event_vs_edcenso_stage':
 * @property integer $id
 * @property integer $pre_enrollment_event_fk
 * @property integer $edcenso_stage_fk
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property EdcensoStageVsModality $edcensoStageFk
 * @property EnrollmentOnlinePreEnrollmentEvent $preEnrollmentEventFk
 */
class EnrollmentOnlineEventVsEdcensoStage extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'enrollment_online_event_vs_edcenso_stage';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('pre_enrollment_event_fk, edcenso_stage_fk', 'required'),
            array('pre_enrollment_event_fk, edcenso_stage_fk', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, pre_enrollment_event_fk, edcenso_stage_fk, created_at, updated_at', 'safe', 'on' => 'search'),
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
            'edcensoStageFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_fk'),
            'preEnrollmentEventFk' => array(self::BELONGS_TO, 'EnrollmentOnlinePreEnrollmentEvent', 'pre_enrollment_event_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pre_enrollment_event_fk' => 'Pre Enrollment Event Fk',
            'edcenso_stage_fk' => 'Edcenso Stage Fk',
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
        $criteria->compare('pre_enrollment_event_fk', $this->pre_enrollment_event_fk);
        $criteria->compare('edcenso_stage_fk', $this->edcenso_stage_fk);
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
     * @return EnrollmentOnlineEventVsEdcensoStage the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
