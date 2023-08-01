<?php

/**
 * This is the model class for table "teaching_matrixes".
 *
 * The followings are the available columns in table 'teaching_matrixes':
 * @property integer $id
 * @property integer $teaching_data_fk
 * @property integer $curricular_matrix_fk
 *
 * The followings are the available model relations:
 * @property InstructorTeachingData $teachingDataFk
 * @property CurricularMatrix $curricularMatrixFk
 */
class TeachingMatrixes extends AltActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teaching_matrixes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('teaching_data_fk, curricular_matrix_fk', 'required'),
			array('teaching_data_fk, curricular_matrix_fk', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, teaching_data_fk, curricular_matrix_fk', 'safe', 'on'=>'search'),
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
			'teachingDataFk' => array(self::BELONGS_TO, 'InstructorTeachingData', 'teaching_data_fk'),
			'curricularMatrixFk' => array(self::BELONGS_TO, 'CurricularMatrix', 'curricular_matrix_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'teaching_data_fk' => 'Teaching Data Fk',
			'curricular_matrix_fk' => 'Curricular Matrix Fk',
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
		$criteria->compare('teaching_data_fk',$this->teaching_data_fk);
		$criteria->compare('curricular_matrix_fk',$this->curricular_matrix_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TeachingMatrixes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
