<?php

/**
 * This is the model class for table "recovery_semianual".
 *
 * The followings are the available columns in table 'recovery_semianual':
 * @property integer $id
 * @property string $nameOne
 * @property string $nameTwo
 * @property integer $unityOne
 * @property integer $unityTwo
 * @property integer $grade_calculation_fk
 * @property integer $recoverMedia
 *
 * The followings are the available model relations:
 * @property GradeCalculation $gradeCalculationFk
 */
class RecoverySemianual extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'recovery_semianual';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('unityOne, unityTwo, grade_calculation_fk, recoverMedia', 'numerical', 'integerOnly'=>true),
            array('nameOne, nameTwo', 'length', 'max'=>50),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nameOne, nameTwo, unityOne, unityTwo, grade_calculation_fk, recoverMedia', 'safe', 'on'=>'search'),
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
            'gradeCalculationFk' => array(self::BELONGS_TO, 'GradeCalculation', 'grade_calculation_fk'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nameOne' => 'Name One',
            'nameTwo' => 'Name Two',
            'unityOne' => 'Unity One',
            'unityTwo' => 'Unity Two',
            'grade_calculation_fk' => 'Grade Calculation Fk',
            'recoverMedia' => 'Recover Media',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('nameOne',$this->nameOne,true);
        $criteria->compare('nameTwo',$this->nameTwo,true);
        $criteria->compare('unityOne',$this->unityOne);
        $criteria->compare('unityTwo',$this->unityTwo);
        $criteria->compare('grade_calculation_fk',$this->grade_calculation_fk);
        $criteria->compare('recoverMedia',$this->recoverMedia);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RecoverySemianual the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
