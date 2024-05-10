<?php

/**
 * This is the model class for table "recommendations".
 *
 * The followings are the available columns in table 'recommendations':
 * @property string $id
 * @property integer $item_reference_id
 * @property integer $item_codigo
 * @property string $item_nome
 * @property double $score
 * @property double $normalized_score
 * @property string $traffic_light_color
 */
class Recommendations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'recommendations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_reference_id, item_codigo, item_nome, score, normalized_score, traffic_light_color', 'required'),
			array('item_reference_id, item_codigo', 'numerical', 'integerOnly'=>true),
			array('score, normalized_score', 'numerical'),
			array('item_nome', 'length', 'max'=>100),
			array('traffic_light_color', 'length', 'max'=>25),
			array('id, item_reference_id, item_codigo, item_nome, score, normalized_score, traffic_light_color', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_reference_id' => 'Item Reference',
			'item_codigo' => 'Item Codigo',
			'item_nome' => 'Item Nome',
			'score' => 'Score',
			'normalized_score' => 'Normalized Score',
			'traffic_light_color' => 'Traffic Light Color',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('item_reference_id',$this->item_reference_id);
		$criteria->compare('item_codigo',$this->item_codigo);
		$criteria->compare('item_nome',$this->item_nome,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('normalized_score',$this->normalized_score);
		$criteria->compare('traffic_light_color',$this->traffic_light_color,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Recommendations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
