<?php

/**
 * This is the model class for table "class_has_content".
 *
 * The followings are the available columns in table 'class_has_content':
 * @property integer $id
 * @property integer $schedule_fk
 * @property integer $content_fk
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property Schedule $scheduleFk
 * @property ClassResources $contentFk
 */
class ClassHasContent extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'class_has_content';
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
            ['schedule_fk, content_fk', 'required'],
            ['id, schedule_fk, content_fk', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, schedule_fk, content_fk', 'safe', 'on' => 'search'],
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
            'contentFk' => [self::BELONGS_TO, 'ClassResources', 'content_fk'],
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
            'content_fk' => Yii::t('default', 'Resource Fk'),
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
        $criteria->compare('schedule_fk', $this->schedule_fk);
        $criteria->compare('content_fk', $this->content_fk);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ClassHasContent the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
