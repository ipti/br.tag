<?php

Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');
Yii::import('application.modules.sedsp.models.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property UsuarioSEDDataSource $UsuarioSEDDataSource
 */
class LoginUseCase
{
    private  $UsuarioSEDDataSource;

    function __construct($UsuarioSEDDataSource = null) {
        $this->UsuarioSEDDataSource = $UsuarioSEDDataSource ?? new UsuarioSEDDataSource();
    }
    public function exec($user, $password)
    {
        $authJson = $this->UsuarioSEDDataSource->login($user, $password);
        $auth = new Autenticacao($authJson);
        Yii::app()->user->setState("SED_TOKEN", $auth->getAutenticacao());
        return Yii::app()->user->getState("SED_TOKEN");
    }
}
