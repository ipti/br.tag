<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property integer $active
 * @property integer $hash
 *
 * The followings are the available model relations:
 * @property AuthItem[] $authItems
 * @property CoursePlan[] $coursePlans
 * @property InstructorIdentification[] $instructorIdentifications
 * @property UsersSchool[] $usersSchools
 *
 */

class Users extends AltActiveRecord
{

	public $hash;

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
			array('name, username, password', 'required'),
			array('active', 'numerical', 'integerOnly'=>true),
			array('hash', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>150),
			array('username', 'length', 'max'=>32),
			array('password', 'length', 'min' => 6, 'max'=>60, 'tooShort' => 'A senha deve ter pelo menos 6 caracteres.'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, username, password, active', 'safe', 'on'=>'search'),
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
			'authItems' => array(self::MANY_MANY, 'auth_item', 'auth_assignment(userid, itemname)'),
            'coursePlans' => array(self::HAS_MANY, 'CoursePlan', 'users_fk'),
            'instructorIdentifications' => array(self::HAS_MANY, 'InstructorIdentification', 'users_fk'),
			'usersSchools' => array(self::HAS_MANY, 'UsersSchool', 'user_fk'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => Yii::t('default', 'Name'),
			'username' => Yii::t('default', 'Username'),
			'password' => Yii::t('default', 'Password'),
			'active' => Yii::t('default', 'Active'),
			'hash' => Yii::t('default', 'Hash')
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
		$criteria->condition = "username != 'admin'";
		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getRole()
	{

        $role = Yii::app()->db->createCommand()

		->select('itemname')

		->from('auth_assignment')

		->where('userid=:id', array(':id'=>$this->id))

		->queryScalar();


		return $role;
	}
}
