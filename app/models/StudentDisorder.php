<?php

/**
 * This is the model class for table "student_disorder".
 *
 * The followings are the available columns in table 'student_disorder':
 * @property int $id
 * @property int $student_fk
 * @property int $tdah
 * @property int $depressao
 * @property int $tab
 * @property int $toc
 * @property int $tag
 * @property int $tod
 * @property int $tcne
 * @property string $others
 * @property string $created_at
 * @property string $updated_at
 * @property int $disorders_impact_learning
 * @property int $dyscalculia
 * @property int $dysgraphia
 * @property int $dyslalia
 * @property int $dyslexia
 * @property int $tpac
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 */
class StudentDisorder extends TagModel
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_disorder';
    }

    /**
     * @return array validation rules for model attributes
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['student_fk', 'required'],
            ['student_fk, tdah, depressao, tab, toc, tag, tod, tcne, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac', 'numerical', 'integerOnly' => true],
            ['others', 'length', 'max' => 200],
            ['created_at, updated_at', 'safe'],
            // The following rule is used by search().
            ['id, student_fk, tdah, depressao, tab, toc, tag, tod, tcne, others, created_at, updated_at, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'studentFk' => [self::BELONGS_TO, 'StudentIdentification', 'student_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_fk' => 'Student Fk',
            'tdah' => 'Tdah',
            'depressao' => 'Depressao',
            'tab' => 'Tab',
            'toc' => 'Toc',
            'tag' => 'Tag',
            'tod' => 'Tod',
            'tcne' => 'Tcne',
            'others' => 'Others',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'disorders_impact_learning' => 'Transtorno(s) que impacta(m) o desenvolvimento da aprendizagem',
            'dyscalculia' => 'Discalculia',
            'dysgraphia' => 'Disgrafia',
            'dyslalia' => 'Dislalia',
            'dyslexia' => 'Dislexia',
            'tpac' => 'Tpac',
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
     * based on the search/filter conditions
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id);
        $criteria->compare('student_fk', $this->student_fk);
        $criteria->compare('tdah', $this->tdah);
        $criteria->compare('depressao', $this->depressao);
        $criteria->compare('tab', $this->tab);
        $criteria->compare('toc', $this->toc);
        $criteria->compare('tag', $this->tag);
        $criteria->compare('tod', $this->tod);
        $criteria->compare('tcne', $this->tcne);
        $criteria->compare('others', $this->others, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('disorders_impact_learning', $this->disorders_impact_learning);
        $criteria->compare('dyscalculia', $this->dyscalculia);
        $criteria->compare('dysgraphia', $this->dysgraphia);
        $criteria->compare('dyslalia', $this->dyslalia);
        $criteria->compare('dyslexia', $this->dyslexia);
        $criteria->compare('tpac', $this->tpac);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name
     * @return StudentDisorder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
