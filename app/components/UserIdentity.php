<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    public function isMd5($string)
    {
        $md5Pattern = '/^[a-fA-F0-9]{32}$/';

        return preg_match($md5Pattern, $string);
    }

    public function authenticate()
    {
        $record = Users::model()->findByAttributes(['username' => $this->username]);

        if( $this->hasMd5PassValidadeError($record)){
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }

        if ($record === null || !$record->active) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else{
            $this->errorCode = $this->passwordVerification($record) === "errorNone" ? self::ERROR_NONE : self::ERROR_PASSWORD_INVALID;
        }

        return !$this->errorCode;
    }


    private function hasMd5PassValidadeError($record){
        if ($this->isMd5($record->password)) {
            if ($record->password === md5($this->password)) {
                $passwordHasher = new PasswordHasher();
                $record->password = $passwordHasher->bcriptHash($this->password);
                $record->save();
                return false;
            } else {
               return true;
            }
        }
    }
    private function passwordVerification($record){
        if (!password_verify($this->password, $record->password)) {
            return "passInvalid";
        } else {
            if (
                Yii::app()->getAuthManager()->checkAccess('admin', $record->id)
                || Yii::app()->getAuthManager()->checkAccess('nutritionist', $record->id)
                || Yii::app()->getAuthManager()->checkAccess('reader', $record->id)
                || Yii::app()->getAuthManager()->checkAccess('guardian', $record->id)
            ) {
                $userSchools = [];
                $this->setState('hardfoot', false);
                $userSchools = SchoolIdentification::model()->findAllByAttributes(['situation' => '1'], ['order' => 'name']);
                $school = isset($userSchools[0]) ? $userSchools[0]->inep_id : '';
            } else {
                $this->setState('hardfoot', true);
                $userSchools = $record->usersSchools;
                $school = isset($record->usersSchools[0]->school_fk) ? $record->usersSchools[0]->school_fk : null;
            }
            $this->setState('version', '2.10.1');
            $this->setState('loginInfos', $record);
            $this->setState('usersSchools', $userSchools);
            $this->setState('school', $school);
            return "errorNone";
        }
    }
}
