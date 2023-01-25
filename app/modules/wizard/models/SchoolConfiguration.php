<?php

/**
 * This is the model class for table "school_configuration".
 *
 * The followings are the available columns in table 'school_configuration':
 * @property integer $id
 * @property string $school_inep_id_fk
 * @property string $morning_initial
 * @property string $morning_final
 * @property string $afternoom_initial
 * @property string $afternoom_final
 * @property string $night_initial
 * @property string $night_final
 * @property string $allday_initial
 * @property string $allday_final
 * @property string $exam1
 * @property string $exam2
 * @property string $exam3
 * @property string $exam4
 * @property string $recovery1
 * @property string $recovery2
 * @property string $recovery3
 * @property string $recovery4
 * @property string $recovery_final
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepIdFk
 */
class SchoolConfiguration extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'school_configuration';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['school_inep_id_fk', 'required'],
            ['school_inep_id_fk', 'length', 'max' => 8],
            ['morning_initial, morning_final, afternoom_initial, afternoom_final, night_initial, night_final, allday_initial, allday_final, exam1, exam2, exam3, exam4, recovery1, recovery2, recovery3, recovery4, recovery_final', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, school_inep_id_fk, morning_initial, morning_final, afternoom_initial, afternoom_final, night_initial, night_final, allday_initial, allday_final, exam1, exam2, exam3, exam4, recovery1, recovery2, recovery3, recovery4, recovery_final', 'safe', 'on' => 'search'],
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
            'schoolInepIdFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_id_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_inep_id_fk' => yii::t('wizardModule.labels', 'School Inep Id'),
            'morning_initial' => yii::t('wizardModule.labels', 'Morning Initial'),
            'morning_final' => yii::t('wizardModule.labels', 'Morning Final'),
            'afternoom_initial' => yii::t('wizardModule.labels', 'Afternoon Initial'),
            'afternoom_final' => yii::t('wizardModule.labels', 'Afternoon Final'),
            'night_initial' => yii::t('wizardModule.labels', 'Night Initial'),
            'night_final' => yii::t('wizardModule.labels', 'Night Final'),
            'allday_initial' => yii::t('wizardModule.labels', 'All day Initial'),
            'allday_final' => yii::t('wizardModule.labels', 'All day Final'),
            'exam1' => yii::t('wizardModule.labels', 'Exam 1'),
            'exam2' => yii::t('wizardModule.labels', 'Exam 2'),
            'exam3' => yii::t('wizardModule.labels', 'Exam 3'),
            'exam4' => yii::t('wizardModule.labels', 'Exam 4'),
            'recovery1' => yii::t('wizardModule.labels', 'Recovery 1'),
            'recovery2' => yii::t('wizardModule.labels', 'Recovery 2'),
            'recovery3' => yii::t('wizardModule.labels', 'Recovery 3'),
            'recovery4' => yii::t('wizardModule.labels', 'Recovery 4'),
            'recovery_final' => yii::t('wizardModule.labels', 'Recovery Final'),
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
        $criteria->compare('school_inep_id_fk', $this->school_inep_id_fk, true);
        $criteria->compare('morning_initial', $this->morning_initial, true);
        $criteria->compare('morning_final', $this->morning_final, true);
        $criteria->compare('afternoom_initial', $this->afternoom_initial, true);
        $criteria->compare('afternoom_final', $this->afternoom_final, true);
        $criteria->compare('night_initial', $this->night_initial, true);
        $criteria->compare('night_final', $this->night_final, true);
        $criteria->compare('allday_initial', $this->allday_initial, true);
        $criteria->compare('allday_final', $this->allday_final, true);
        $criteria->compare('exam1', $this->exam1, true);
        $criteria->compare('exam2', $this->exam2, true);
        $criteria->compare('exam3', $this->exam3, true);
        $criteria->compare('exam4', $this->exam4, true);
        $criteria->compare('recovery1', $this->recovery1, true);
        $criteria->compare('recovery2', $this->recovery2, true);
        $criteria->compare('recovery3', $this->recovery3, true);
        $criteria->compare('recovery4', $this->recovery4, true);
        $criteria->compare('recovery_final', $this->recovery_final, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SchoolConfiguration the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
