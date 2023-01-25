<?php

/**
 * This is the model class for table "question_option".
 *
 * The followings are the available columns in table 'question_option':
 * @property integer $id
 * @property string $description
 * @property string $answer
 * @property integer $question_id
 *
 * The followings are the available model relations:
 * @property Question $question
 */
class QuestionOption extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'question_option';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['description, answer, question_id', 'required'],
            ['question_id, complement', 'numerical', 'integerOnly' => true],
            ['description', 'length', 'max' => 255],
            ['answer', 'length', 'max' => 255],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, description, answer, question_id', 'safe', 'on' => 'search'],
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
            'question' => [self::BELONGS_TO, 'Question', 'question_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Descrição',
            'answer' => 'Resposta',
            'question_id' => 'Questão',
            'complement' => 'Complemento',
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
        $criteria->compare('description', $this->description, true);
        $criteria->compare('answer', $this->answer, true);
        $criteria->compare('question_id', $this->question_id);
        $criteria->compare('complement', $this->complement);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return QuestionOption the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
