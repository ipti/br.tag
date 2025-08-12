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
        $exit = true;
        if ($this->handleLegacyPassword($record)) {
            $exit = false;
        }

        if ($record === null || !$record->active) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            $exit = false;
        }

        if (!password_verify($this->password, $record->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
            $exit = false;
        }

        $this->loadUserContext($record);
        $this->errorCode = self::ERROR_NONE;

        return $exit;
    }

    private function handleLegacyPassword($record): bool
    {
        if ($record && $this->isMd5($record->password)) {
            if ($record->password === md5($this->password)) {
                $passwordHasher = new PasswordHasher();
                $record->password = $passwordHasher->bcriptHash($this->password);
                $record->save();
            } else {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
                return true;
            }
        }
        return false;
    }

    private function loadUserContext($record): void
    {
        if (
            Yii::app()->getAuthManager()->checkAccess('admin', $record->id)
            || Yii::app()->getAuthManager()->checkAccess('nutritionist', $record->id)
            || Yii::app()->getAuthManager()->checkAccess('reader', $record->id)
            || Yii::app()->getAuthManager()->checkAccess('guardian', $record->id)
        ) {
            $this->setState('hardfoot', false);
            $schools = SchoolIdentification::model()->findAllByAttributes(['situation' => '1'], ['order' => 'name']);
            $school = isset($schools[0]) ? $schools[0]->inep_id : '';
        } else {
            $this->setState('hardfoot', true);
            $schools = $record->usersSchools;
            $school = isset($schools[0]->school_fk) ? $schools[0]->school_fk : null;
        }

        $this->setState('version', '2.10.1');
        $this->setState('loginInfos', $record);
        $this->setState('usersSchools', $schools);
        $this->setState('school', $school);
    }
}
