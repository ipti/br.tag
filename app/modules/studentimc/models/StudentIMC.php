<?php

/**
 * This is the model class for table "student_imc".
 *
 * The followings are the available columns in table 'student_imc':
 * @property integer $id
 * @property double $height
 * @property double $weight
 * @property double $IMC
 * @property string $observations
 * @property integer $student_fk
 * @property string $created_at
 * @property string $updated_at
 * @property integer $student_imc_classification_fk
 *
 * The followings are the available model relations:
 * @property StudentIdentification $studentFk
 * @property StudentImcClassification $studentImcClassificationFk
 */
class StudentImc extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'student_imc';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('height, weight, IMC, student_fk', 'required'),
            array('student_fk, student_imc_classification_fk', 'numerical', 'integerOnly' => true),
            array('height, weight, IMC', 'numerical'),
            array('observations', 'length', 'max' => 500),
            array('created_at, updated_at', 'safe'),
            array('id, height, weight, IMC, observations, student_fk, created_at, updated_at, student_imc_classification_fk', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return[
            'studentFk' => [self::BELONGS_TO, 'StudentIdentification', 'student_fk'],
            'studentImcClassificationFk' => [self::BELONGS_TO, 'StudentImcClassification', 'student_imc_classification_fk'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'height' => Yii::t('default', 'Height'),
            'weight' => Yii::t('default', 'Weight'),
            'IMC' => Yii::t('default', 'IMC'),
            'student_imc_classification_fk' => Yii::t('default', 'Classification'),
            'observations' => Yii::t('default', 'Observations'),
            'student_fk' => Yii::t('default', 'Student'),
            'created_at' => Yii::t('default', 'Created_At'),
            'updated_at' => Yii::t('default', 'Updated_At'),
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
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('height', $this->height);
        $criteria->compare('weight', $this->weight);
        $criteria->compare('IMC', $this->IMC);
        $criteria->compare('observations', $this->observations, true);
        $criteria->compare('student_fk', $this->student_fk);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('student_imc_classification_fk', $this->student_imc_classification_fk);

        return new CActiveDataProvider($this, ['criteria' => $criteria]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StudentImc the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
