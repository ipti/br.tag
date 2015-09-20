<?php

/**
 * This is the model class for table "grade".
 *
 * The followings are the available columns in table 'grade':
 * @property integer $id
 * @property double $grade1
 * @property double $grade2
 * @property double $grade3
 * @property double $grade4
 * @property double $recovery_grade1
 * @property double $recovery_grade2
 * @property double $recovery_grade3
 * @property double $recovery_grade4
 * @property double $recovery_final_grade
 * @property integer $disciplne_fk
 * @property integer $enrollment_fk
 * @property string $fkid
 *
 * The followings are the available model relations:
 * @property EdcensoDiscipline[] $disciplineFk
 * @property StudentEnrollment[] $enrollmentFk
 */
class Grade extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade';
	}

        public function behaviors() {
            return [
                'afterSave'=>[
                    'class'=>'application.behaviors.CAfterSaveBehavior',
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
		return array(
                        array('discipline_fk, enrollment_fk', 'required'),
			array('grade1, grade2, grade3, grade4, recovery_grade1, recovery_grade2, recovery_grade3, recovery_grade4, recovery_final_grade, discipline_fk, enrollment_fk', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, grade1, grade2, grade3, grade4, recovery_grade1, recovery_grade2, recovery_grade3, recovery_grade4, recovery_final_grade', 'safe', 'on'=>'search'),
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
			'disciplineFk' => array(self::HAS_ONE, 'EdcensoDiscipline', 'discipline_fk'),
			'enrollmentFk' => array(self::HAS_ONE, 'StudentEnrollment', 'enrollment_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default','ID'),
			'grade1' => Yii::t('default','Grade1'),
			'grade2' => Yii::t('default','Grade2'),
			'grade3' => Yii::t('default','Grade3'),
			'grade4' => Yii::t('default','Grade4'),
			'recovery_grade1' => Yii::t('default','Recovery Grade1'),
			'recovery_grade2' => Yii::t('default','Recovery Grade2'),
			'recovery_grade3' => Yii::t('default','Recovery Grade3'),
			'recovery_grade4' => Yii::t('default','Recovery Grade4'),
			'recovery_final_grade' => Yii::t('default','Recovery Final Grade'),
			'discipline_fk' => Yii::t('default','Discipline'),
			'enrollment_fk' => Yii::t('default','Enrollment'),
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
		$criteria->compare('grade1',$this->grade1);
		$criteria->compare('grade2',$this->grade2);
		$criteria->compare('grade3',$this->grade3);
		$criteria->compare('grade4',$this->grade4);
		$criteria->compare('recovery_grade1',$this->recovery_grade1);
		$criteria->compare('recovery_grade2',$this->recovery_grade2);
		$criteria->compare('recovery_grade3',$this->recovery_grade3);
		$criteria->compare('recovery_grade4',$this->recovery_grade4);
		$criteria->compare('recovery_final_grade',$this->recovery_final_grade);
		$criteria->compare('discipline_fk',$this->discipline_fk);
		$criteria->compare('enrollment_fk',$this->enrollment_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
