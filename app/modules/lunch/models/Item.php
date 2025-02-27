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
class Item extends TagModel{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'lunch_item';
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
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
	public function relations(){
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
        $school = School::model()->findByPk(yii::app()->user->school);

        $criteria = new CDbCriteria;
        $criteria->select = 'SUM(amount) as amount';
        $criteria->condition = "school_fk = :schoolId AND item_fk = :itemId";
        $criteria->params = array(':schoolId' => $school->inep_id, ':itemId' => $this->id);

        $inventory = Inventory::model()->find($criteria);
        $amount = isset($inventory->amount)? floatval($inventory->amount):0;

		return $this->name.' ('.$amount.' x '.$this->measure.$this->unity->acronym.')';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return [
			'id' => Yii::t('lunchModule.labels', 'ID'),
			'name' => Yii::t('lunchModule.labels', 'Name'),
			'description' => Yii::t('lunchModule.labels', 'Description'),
			'unity_fk' => Yii::t('lunchModule.labels', 'Unity'),
			'measure' => Yii::t('lunchModule.labels', 'Measure'),
		];
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search(){
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
