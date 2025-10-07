<?php

/**
 * This is the model class for table "quiz".
 *
 * The followings are the available columns in table 'quiz':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $init_date
 * @property string $final_date
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property QuestionGroup[] $questionGroups
 * @property Question[] $questions
 */
class Quiz extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'quiz';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['name, status, init_date, final_date', 'required'],
            ['id, status', 'numerical', 'integerOnly' => true],
            ['name', 'length', 'max' => 150],
            ['init_date, final_date', 'length', 'max' => 10],
            ['description', 'safe'],
            ['id, name, description, status, init_date, final_date, create_date', 'safe', 'on' => 'search'],
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
            'questionGroups' => [self::HAS_MANY, 'QuestionGroup', 'quiz_id'],
            'questions' => [self::MANY_MANY, 'Question', 'quiz_question(quiz_id, question_id)'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome',
            'description' => 'Descrição',
            'status' => 'Status',
            'init_date' => 'Data Inicial',
            'final_date' => 'Data Final',
            'create_date' => 'Data de Criação',
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
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('init_date', $this->init_date, true);
        $criteria->compare('final_date', $this->final_date, true);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Quiz the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
