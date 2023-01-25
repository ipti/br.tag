<?php

/**
 * This is the model class for table "answer".
 *
 * The followings are the available columns in table 'answer':
 * @property integer $quiz_id
 * @property integer $question_id
 * @property integer $student_id
 * @property string $value
 */
class Answer extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'answer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['quiz_id, question_id, student_id, value', 'required'],
            ['quiz_id, question_id, student_id', 'numerical', 'integerOnly' => true],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['quiz_id, question_id, student_id, value', 'safe', 'on' => 'search'],
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
            'quiz' => [self::BELONGS_TO, 'Quiz', 'quiz_id'],
            'question' => [self::BELONGS_TO, 'Question', 'question_id'],
            'student' => [self::BELONGS_TO, 'Student', 'student_id']
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'quiz_id' => 'Quiz',
            'question_id' => 'Question',
            'student_id' => 'Student',
            'value' => 'Value',
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

        $criteria->compare('quiz_id', $this->quiz_id);
        $criteria->compare('question_id', $this->question_id);
        $criteria->compare('student_id', $this->student_id);
        $criteria->compare('value', $this->value, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Answer the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
