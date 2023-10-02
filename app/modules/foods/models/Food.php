<?php

/**
 * This is the model class for table "food".
 *
 * The followings are the available columns in table 'food':
 * @property integer $id
 * @property string $description
 * @property string $category
 * @property string $humidity_percents
 * @property string $energy_kcal
 * @property string $energy_kj
 * @property string $protein_g
 * @property string $lipidius_g
 * @property string $cholesterol_mg
 * @property string $carbohydrate_g
 * @property string $fiber_g
 * @property string $ashes_g
 * @property string $calcium_mg
 * @property string $magnesium_mg
 * @property string $manganese_mg
 * @property string $phosphorus_mg
 * @property string $iron_mg
 * @property string $sodium_mg
 * @property string $potassium_mg
 * @property string $copper_mg
 * @property string $zinc_mg
 * @property string $retinol_mcg
 * @property string $re_mcg
 * @property string $rae_mcg
 * @property string $tiamina_mg
 * @property string $riboflavin_mg
 * @property string $pyridoxine_mg
 * @property string $niacin_mg
 * @property string $vitaminC_mg
 */
class Food extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'food';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'numerical', 'integerOnly'=>true),
			array('description, category, humidity_percents, energy_kcal, energy_kj, protein_g, lipidius_g, cholesterol_mg, carbohydrate_g, fiber_g, ashes_g, calcium_mg, magnesium_mg, manganese_mg, phosphorus_mg, iron_mg, sodium_mg, potassium_mg, copper_mg, zinc_mg, retinol_mcg, re_mcg, rae_mcg, tiamina_mg, riboflavin_mg, pyridoxine_mg, niacin_mg, vitaminC_mg', 'length', 'max'=>512),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, category, humidity_percents, energy_kcal, energy_kj, protein_g, lipidius_g, cholesterol_mg, carbohydrate_g, fiber_g, ashes_g, calcium_mg, magnesium_mg, manganese_mg, phosphorus_mg, iron_mg, sodium_mg, potassium_mg, copper_mg, zinc_mg, retinol_mcg, re_mcg, rae_mcg, tiamina_mg, riboflavin_mg, pyridoxine_mg, niacin_mg, vitaminC_mg', 'safe', 'on'=>'search'),
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
			'description' => 'Description',
			'category' => 'Category',
			'humidity_percents' => 'Humidity Percents',
			'energy_kcal' => 'Energy Kcal',
			'energy_kj' => 'Energy Kj',
			'protein_g' => 'Protein G',
			'lipidius_g' => 'Lipidius G',
			'cholesterol_mg' => 'Cholesterol Mg',
			'carbohydrate_g' => 'Carbohydrate G',
			'fiber_g' => 'Fiber G',
			'ashes_g' => 'Ashes G',
			'calcium_mg' => 'Calcium Mg',
			'magnesium_mg' => 'Magnesium Mg',
			'manganese_mg' => 'Manganese Mg',
			'phosphorus_mg' => 'Phosphorus Mg',
			'iron_mg' => 'Iron Mg',
			'sodium_mg' => 'Sodium Mg',
			'potassium_mg' => 'Potassium Mg',
			'copper_mg' => 'Copper Mg',
			'zinc_mg' => 'Zinc Mg',
			'retinol_mcg' => 'Retinol Mcg',
			're_mcg' => 'Re Mcg',
			'rae_mcg' => 'Rae Mcg',
			'tiamina_mg' => 'Tiamina Mg',
			'riboflavin_mg' => 'Riboflavin Mg',
			'pyridoxine_mg' => 'Pyridoxine Mg',
			'niacin_mg' => 'Niacin Mg',
			'vitaminC_mg' => 'Vitamin C Mg',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('humidity_percents',$this->humidity_percents,true);
		$criteria->compare('energy_kcal',$this->energy_kcal,true);
		$criteria->compare('energy_kj',$this->energy_kj,true);
		$criteria->compare('protein_g',$this->protein_g,true);
		$criteria->compare('lipidius_g',$this->lipidius_g,true);
		$criteria->compare('cholesterol_mg',$this->cholesterol_mg,true);
		$criteria->compare('carbohydrate_g',$this->carbohydrate_g,true);
		$criteria->compare('fiber_g',$this->fiber_g,true);
		$criteria->compare('ashes_g',$this->ashes_g,true);
		$criteria->compare('calcium_mg',$this->calcium_mg,true);
		$criteria->compare('magnesium_mg',$this->magnesium_mg,true);
		$criteria->compare('manganese_mg',$this->manganese_mg,true);
		$criteria->compare('phosphorus_mg',$this->phosphorus_mg,true);
		$criteria->compare('iron_mg',$this->iron_mg,true);
		$criteria->compare('sodium_mg',$this->sodium_mg,true);
		$criteria->compare('potassium_mg',$this->potassium_mg,true);
		$criteria->compare('copper_mg',$this->copper_mg,true);
		$criteria->compare('zinc_mg',$this->zinc_mg,true);
		$criteria->compare('retinol_mcg',$this->retinol_mcg,true);
		$criteria->compare('re_mcg',$this->re_mcg,true);
		$criteria->compare('rae_mcg',$this->rae_mcg,true);
		$criteria->compare('tiamina_mg',$this->tiamina_mg,true);
		$criteria->compare('riboflavin_mg',$this->riboflavin_mg,true);
		$criteria->compare('pyridoxine_mg',$this->pyridoxine_mg,true);
		$criteria->compare('niacin_mg',$this->niacin_mg,true);
		$criteria->compare('vitaminC_mg',$this->vitaminC_mg,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Food the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
