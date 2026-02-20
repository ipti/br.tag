<?php

/**
 * This is the model class for table "school_room".
 *
 * The followings are the available columns in table 'school_room':
 * @property integer $id
 * @property string $school_inep_fk
 * @property string $name
 * @property string $number
 * @property integer $capacity
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property SchoolIdentification $schoolInepFk
 * @property Classroom[] $classrooms
 */
class SchoolRoom extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'school_room';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['school_inep_fk, name', 'required'],
            ['capacity', 'numerical', 'integerOnly' => true],
            ['school_inep_fk', 'length', 'max' => 8],
            ['name', 'length', 'max' => 100],
            ['number', 'length', 'max' => 45],
            ['number', 'unique', 'criteria' => [
                'condition' => 'school_inep_fk=:school_inep_fk',
                'params' => [':school_inep_fk' => $this->school_inep_fk],
            ], 'message' => Yii::t('default', 'Já existe uma sala com este número nesta escola.')],
            ['id, school_inep_fk, name, number, capacity, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'schoolInepFk' => [self::BELONGS_TO, 'SchoolIdentification', 'school_inep_fk'],
            'classrooms' => [self::HAS_MANY, 'Classroom', 'room_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_inep_fk' => Yii::t('default', 'School'),
            'name' => Yii::t('default', 'Nome da Sala'),
            'number' => Yii::t('default', 'Número'),
            'capacity' => Yii::t('default', 'Capacidade'),
            'created_at' => Yii::t('default', 'Created At'),
            'updated_at' => Yii::t('default', 'Updated At'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('school_inep_fk', $this->school_inep_fk, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('number', $this->number, true);
        $criteria->compare('capacity', $this->capacity);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => [
                    'name' => CSort::SORT_ASC,
                ],
            ],
            'pagination' => false,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SchoolRoom the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
