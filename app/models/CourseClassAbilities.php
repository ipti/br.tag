<?php

/**
 * This is the model class for table "course_class_abilities".
 *
 * The followings are the available columns in table 'course_class_abilities':
 * @property integer $id
 * @property string $description
 * @property string $code
 * @property integer $edcenso_discipline_fk
 * @property integer $edcenso_stage_vs_modality_fk
 * @property integer $parent_fk
 *
 * The followings are the available model relations:
 * @property EdcensoDiscipline $edcensoDisciplineFk
 * @property EdcensoStageVsModality $edcensoStageVsModalityFk
 * @property CourseClassHasClassAbility[] $courseClassHasClassAbilities
 */
class CourseClassAbilities extends CActiveRecord
{
	public $parent_fk;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'course_class_abilities';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, edcenso_discipline_fk', 'required'),
			array('edcenso_discipline_fk, edcenso_stage_vs_modality_fk, parent_fk', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>500),
			array('code', 'length', 'max'=>20),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, code, edcenso_discipline_fk, edcenso_stage_vs_modality_fk, parent_fk', 'safe', 'on'=>'search'),
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
			'edcensoDisciplineFk' => array(self::BELONGS_TO, 'EdcensoDiscipline', 'edcenso_discipline_fk'),
			'edcensoStageVsModalityFk' => array(self::BELONGS_TO, 'EdcensoStageVsModality', 'edcenso_stage_vs_modality_fk'),
			'courseClassHasClassAbilities' => array(self::HAS_MANY, 'CourseClassHasClassAbility', 'course_class_ability_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'description' => 'Description',
			'code' => 'Code',
			'edcenso_discipline_fk' => 'Edcenso Discipline Fk',
			'edcenso_stage_vs_modality_fk' => 'Edcenso Stage Vs Modality Fk',
			'parent_fk' => "parent_fk"
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

		$criteria = new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('edcenso_discipline_fk',$this->edcenso_discipline_fk);
		$criteria->compare('edcenso_stage_vs_modality_fk',$this->edcenso_stage_vs_modality_fk);
		$criteria->compare('parent_fk',$this->parent_fk);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CourseClassAbilities the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
