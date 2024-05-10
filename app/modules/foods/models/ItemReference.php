<?php

/**
 * This is the model class for table "item_reference".
 *
 * The followings are the available columns in table 'item_reference':
 * @property string $id
 * @property integer $codigo
 * @property string $nome
 * @property string $grupo
 * @property double $caloria
 * @property double $proteina
 * @property double $lipidio
 * @property double $carboidrato
 * @property double $sodium_mg
 * @property double $potassium_mg
 * @property double $calcium_mg
 * @property double $fiber_g
 * @property integer $gramsPortion
 */
class ItemReference extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'item_reference';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('codigo, nome, grupo', 'required'),
			array('codigo, gramsPortion', 'numerical', 'integerOnly'=>true),
			array('caloria, proteina, lipidio, carboidrato, sodium_mg, potassium_mg, calcium_mg, fiber_g', 'numerical'),
			array('nome, grupo', 'length', 'max'=>100),
			array('id, codigo, nome, grupo, caloria, proteina, lipidio, carboidrato, sodium_mg, potassium_mg, calcium_mg, fiber_g, gramsPortion', 'safe', 'on'=>'search'),
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
			'codigo' => 'Codigo',
			'nome' => 'Nome',
			'grupo' => 'Grupo',
			'caloria' => 'Caloria',
			'proteina' => 'Proteina',
			'lipidio' => 'Lipidio',
			'carboidrato' => 'Carboidrato',
			'sodium_mg' => 'Sodium Mg',
			'potassium_mg' => 'Potassium Mg',
			'calcium_mg' => 'Calcium Mg',
			'fiber_g' => 'Fiber G',
			'gramsPortion' => 'Grams Portion',
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
		$criteria->compare('codigo',$this->codigo);
		$criteria->compare('nome',$this->nome,true);
		$criteria->compare('grupo',$this->grupo,true);
		$criteria->compare('caloria',$this->caloria);
		$criteria->compare('proteina',$this->proteina);
		$criteria->compare('lipidio',$this->lipidio);
		$criteria->compare('carboidrato',$this->carboidrato);
		$criteria->compare('sodium_mg',$this->sodium_mg);
		$criteria->compare('potassium_mg',$this->potassium_mg);
		$criteria->compare('calcium_mg',$this->calcium_mg);
		$criteria->compare('fiber_g',$this->fiber_g);
		$criteria->compare('gramsPortion',$this->gramsPortion);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ItemReference the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
