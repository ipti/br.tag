<?php

/**
 * This is the model class for table "student_disorder".
 *
 * The followings are the available columns in table 'student_disorder':
 * @property integer $id
 * @property integer $student_fk
 * @property integer $tdah
 * @property integer $depressao
 * @property integer $tab
 * @property integer $toc
 * @property integer $tag
 * @property integer $tod
 * @property integer $tcne
 * @property string $others
 * @property string $created_at
 * @property string $updated_at
 * @property integer $disorders_impact_learning
 * @property integer $dyscalculia
 * @property integer $dysgraphia
 * @property integer $dyslalia
 * @property integer $dyslexia
 * @property integer $tpac
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_fk', 'required'),
			array('student_fk, tdah, depressao, tab, toc, tag, tod, tcne, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac', 'numerical', 'integerOnly'=>true),
			array('others', 'length', 'max'=>200),
			array('created_at, updated_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_fk, tdah, depressao, tab, toc, tag, tod, tcne, others, created_at, updated_at, disorders_impact_learning, dyscalculia, dysgraphia, dyslalia, dyslexia, tpac', 'safe', 'on'=>'search'),
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
			'studentFk' => array(self::BELONGS_TO, 'StudentIdentification', 'student_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
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
		$criteria->compare('student_fk',$this->student_fk);
		$criteria->compare('tdah',$this->tdah);
		$criteria->compare('depressao',$this->depressao);
		$criteria->compare('tab',$this->tab);
		$criteria->compare('toc',$this->toc);
		$criteria->compare('tag',$this->tag);
		$criteria->compare('tod',$this->tod);
		$criteria->compare('tcne',$this->tcne);
		$criteria->compare('others',$this->others,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('updated_at',$this->updated_at,true);
		$criteria->compare('disorders_impact_learning',$this->disorders_impact_learning);
		$criteria->compare('dyscalculia',$this->dyscalculia);
		$criteria->compare('dysgraphia',$this->dysgraphia);
		$criteria->compare('dyslalia',$this->dyslalia);
		$criteria->compare('dyslexia',$this->dyslexia);
		$criteria->compare('tpac',$this->tpac);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StudentDisorder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
