<?php

/**
 * Respostas JSON para erros que escapam dos controllers da API
 * (rota inexistente, erro de PHP etc.). Configurado em ApiModule::init().
 */
class DefaultController extends ApiController
{
    public function actionIndex()
    {
        $this->respond([
            'nome' => 'API do TAG',
            'versao' => TAG_VERSION,
        ]);
    }

    public function actionError()
    {
        $error = Yii::app()->errorHandler->error;
        $status = is_array($error) && isset($error['code']) && $error['code'] >= 400 && $error['code'] < 600
            ? (int) $error['code']
            : 500;

        if ($status === 404) {
            $this->respondError(404, 'rota_nao_encontrada', 'Rota não encontrada.');
            return;
        }

        $this->respondError($status, 'erro_interno', 'Erro interno ao processar a requisição.');
    }
}
