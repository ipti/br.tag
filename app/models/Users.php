<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property integer $active
 * @property integer $role_fk
 *
 * The followings are the available model relations:
 * @property Roles $roleFk
 * @property UsersSchool[] $usersSchools
 */
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
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
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, username, password, role_fk', 'required'),
			array('active, role_fk', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			array('username', 'length', 'max'=>32),
			array('password', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, username, password, active, role_fk', 'safe', 'on'=>'search'),
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
			'roleFk' => array(self::BELONGS_TO, 'Roles', 'role_fk'),
			'usersSchools' => array(self::HAS_MANY, 'UsersSchool', 'user_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('default', 'ID'),
			'name' => Yii::t('default', 'Name'),
			'username' => Yii::t('default', 'Username'),
			'password' => Yii::t('default', 'Password'),
			'active' => Yii::t('default', 'Active'),
			'role_fk' => Yii::t('default', 'Role Fk'),
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('role_fk',$this->role_fk);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}