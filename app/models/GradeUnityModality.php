<?php

/**
 * This is the model class for table "grade_unity_modality".
 *
 * The followings are the available columns in table 'grade_unity_modality':
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $weight
 * @property integer $grade_unity_fk
 *
 * The followings are the available model relations:
 * @property Grade[] $grades
 * @property GradeUnity $gradeUnityFk
 */
class GradeUnityModality extends TagModel
{

    public const TYPE_COMMON = "C";
    public const TYPE_RECOVERY = "R";


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade_unity_modality';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, grade_unity_fk', 'required'),
			array('weight, grade_unity_fk', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
			array('type', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, weight, grade_unity_fk', 'safe', 'on'=>'search'),
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
			'grades' => array(self::HAS_MANY, 'Grade', 'grade_unity_modality_fk'),
			'gradeUnityFk' => array(self::BELONGS_TO, 'GradeUnity', 'grade_unity_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'type' => 'Type',
			'weight' => 'Weight',
			'grade_unity_fk' => 'Grade Unity Fk',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('grade_unity_fk',$this->grade_unity_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return GradeUnityModality the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
