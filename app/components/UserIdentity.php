<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
//	public function authenticate()
//	{
//		$users=array(
//			// username => password
//			'demo'=>'demo',
//			'admin'=>'admin',
//		);
//		if(!isset($users[$this->username]))
//			$this->errorCode=self::ERROR_USERNAME_INVALID;
//		elseif($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
//		return !$this->errorCode;
//	}
//        
    

    public function authenticate() {
        $record = Users::model()->findByAttributes(array('username' => $this->username));
        if ($record === null || !$record->active)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($record->password !== md5($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            if(Yii::app()->getAuthManager()->checkAccess('admin',$record->id)){
                $userSchools = [];
                $this->setState('hardfoot',false);
                //@done s2 - mostrar apenas escolas ativas
                $userSchools = SchoolIdentification::model()->findAllByAttributes(array('situation'=>'1'),array('order'=>'name'));
                $school =  isset($userSchools[0])? $userSchools[0]->inep_id : '';
            }else{
                $this->setState('hardfoot',true);
                $userSchools = $record->usersSchools;
                $school = isset($record->usersSchools[0]->school_fk) ? $record->usersSchools[0]->school_fk : null;
            }
            $this->setState('version','2.10.1');
            $this->setState('loginInfos', $record);
            $this->setState('usersSchools',$userSchools);
            $this->setState('school',$school);
            $this->errorCode = self::ERROR_NONE;
            //AdminController::actionBackup(false);
            
        }
        return !$this->errorCode;
    }
}