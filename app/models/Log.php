<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $table
 * @property string $table_pk
 * @property string $crud
 * @property string $purged_info
 * @property string $date
 */
class Log extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table, table_pk, crud', 'required'),
			array('table', 'length', 'max'=>50),
			array('table_pk', 'length', 'max'=>20),
			array('crud', 'length', 'max'=>1),
			array('purged_info', 'length', 'max'=>100),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, table, table_pk, crud, purged_info, date', 'safe', 'on'=>'search'),
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
			'table' => 'Table',
			'table_pk' => 'Table Pk',
			'crud' => 'Crud',
			'purged_info' => 'Purged Info',
			'date' => 'Date',
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
		$criteria->compare('table',$this->table,true);
		$criteria->compare('table_pk',$this->table_pk,true);
		$criteria->compare('crud',$this->crud,true);
		$criteria->compare('purged_info',$this->purged_info,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function saveAction($table, $tablePk, $crud, $purgedInfo = null) {
		date_default_timezone_set("America/Recife");
		$date = new DateTime();
		$log = new Log();
		$log->table = $table;
		$log->table_pk = $tablePk;
		$log->crud = $crud;
		$log->date = $date->format("Y-m-d H:i:s");
		$log->purged_info = $purgedInfo;
		$log->save();
	}
}
