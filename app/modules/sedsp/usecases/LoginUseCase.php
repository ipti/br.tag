<?php

Yii::import('application.modules.sedsp.models.*');
Yii::import('application.modules.sedsp.datasources.sed.*');
Yii::import('application.modules.sedsp.datasources.tag.*');

/**
 * @property StudentTAGDataSource $studentTAGDataSource
 * @property UsuarioSEDDataSource $UsuarioSEDDataSource
 */
class LoginUseCase
{
    private  $UsuarioSEDDataSource;

    public function __construct($UsuarioSEDDataSource = null) {
        $this->UsuarioSEDDataSource = $UsuarioSEDDataSource ?? new UsuarioSEDDataSource();
    }
    /**
     * Login into SED API and set SED_TOKEN on Yii State
     * 
     * Access Token using the code below
     * <code>
     *  <?php
     *      Yii::app()->user->getState("SED_TOKEN");
     *  ?>
     * </code>
     * @param string $user
     * @param string $password
     * @return string Token
     */
    public function exec($user, $password)
    {
        $authJson = $this->UsuarioSEDDataSource->login($user, $password);
        $auth = new Autenticacao($authJson);
        $cookie = new CHttpCookie('SED_TOKEN',$auth->getAutenticacao());
        $cookie->expire = time()+60*15;
        Yii::app()->request->cookies['SED_TOKEN'] = $cookie;
        //Yii::app()->user->setState("SED_TOKEN", $auth->getAutenticacao());
        return Yii::app()->request->cookies['SED_TOKEN']->value;
    }
}
