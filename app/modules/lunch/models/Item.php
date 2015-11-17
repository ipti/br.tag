<?php

/**
 * This is the model class for table "lunch_item".
 *
 * The followings are the available columns in table 'lunch_item':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $unity_fk
 * @property double $measure
 *
 * The followings are the available model relations:
 * @property Unity $unity
 * @property Portion[] $portions
 * @property Inventory[] $inventories
 * @property Item[] $items
 */
class Item extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lunch_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, unity_fk, measure', 'required'),
			array('unity_fk', 'numerical', 'integerOnly'=>true),
			array('measure', 'numerical'),
			array('name', 'length', 'max'=>45),
			array('description', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, unity_fk, measure', 'safe', 'on'=>'search'),
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
			'unity' => array(self::BELONGS_TO, 'Unity', 'unity_fk'),
			'portions' => array(self::HAS_MANY, 'Portion', 'item_fk'),
			'inventories' => array(self::HAS_MANY, 'Inventory', 'item_fk'),
			'schools' => array(self::MANY_MANY, 'School', 'lunch_inventory(school_fk, item_fk)'),
		);
	}

	public function getConcatName(){
		return $this->name.' x'.$this->measure.$this->unity->acronym;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'name' => Yii::t('default', 'Name'),
			'description' => Yii::t('default', 'Description'),
			'unity_fk' => Yii::t('default', 'Unity Fk'),
			'measure' => Yii::t('default', 'Measure'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('unity_fk',$this->unity_fk);
		$criteria->compare('measure',$this->measure);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}