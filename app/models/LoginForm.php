<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	public $year;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password, year', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>Yii::t('default','Remember me next time'),
            'username' => 'Usuário',
            'password' => 'Senha',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password',Yii::t('default','Incorrect username or password.'));
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
                $this->_identity->setState('year', $this->year);
		if($this->_identity===null)
		{
                    // local desta bagaça ./app/components/UserIdentity.php
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
                        
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = $this->rememberMe ? 3600*24*30 : 20*60; // 30 days or 20 min
			Yii::app()->user->login($this->_identity);
            Yii::app()->user->setState("authTimout",$duration);
            Yii::app()->user->setState("rememberMe",$this->rememberMe);
			return true;
		} else {
			return false;
        }
	}
}
