<?php

/**
 * This is the model class for table "work_by_discipline".
 *
 * The followings are the available columns in table 'work_by_discipline':
 * @property integer $id
 * @property integer $classroom_fk
 * @property integer $discipline_fk
 * @property integer $school_days
 *
 * The followings are the available model relations:
 * @property Classroom $classroomFk
 * @property EdcensoDiscipline $disciplineFk
 */
class WorkByDiscipline extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'work_by_discipline';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['classroom_fk, discipline_fk', 'required'],
            ['classroom_fk, discipline_fk, school_days', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, classroom_fk, discipline_fk, school_days', 'safe', 'on' => 'search'],
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
            'classroomFk' => [self::BELONGS_TO, 'Classroom', 'classroom_fk'],
            'disciplineFk' => [self::BELONGS_TO, 'EdcensoDiscipline', 'discipline_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'classroom_fk' => 'Classroom Fk',
            'discipline_fk' => 'Discipline Fk',
            'school_days' => 'School Days',
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
        $criteria->compare('classroom_fk', $this->classroom_fk);
        $criteria->compare('discipline_fk', $this->discipline_fk);
        $criteria->compare('school_days', $this->school_days);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return WorkByDiscipline the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
