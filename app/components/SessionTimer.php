<?php

class SessionTimer
{
    public static function getSessionTime()
    {
        $sessionDuration = time() - Yii::app()->user->getState('last_activity', 0);
        return Yii::app()->user->authTimeout - $sessionDuration;
        // Caso a sessão ainda não tenha sido iniciada
    }

}

?>