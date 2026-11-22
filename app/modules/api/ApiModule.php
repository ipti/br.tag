<?php

/**
 * Módulo de API do TAG.
 *
 * Concentra as rotas consumidas por sistemas e aparelhos externos (ex.: o
 * reconhecimento facial da merenda). As rotas não usam sessão nem login do
 * TAG: o cliente troca sua credencial por um JWT em api/auth/token e envia o
 * token no header Authorization em cada chamada. Veja o README.md do módulo.
 */
class ApiModule extends CWebModule
{
    public function init()
    {
        $this->setImport([
            'api.components.*',
            'api.models.*',
            'api.services.*',
        ]);

        // Erros não tratados também saem em JSON, nunca na página HTML do TAG
        Yii::app()->errorHandler->errorAction = 'api/default/error';
    }
}
