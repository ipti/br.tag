<?php

/**
 * POST api/auth/token: troca client_id + client_secret por um JWT.
 */
class AuthController extends ApiController
{
    public function actionToken()
    {
        $this->requireMethod('POST');
        $body = $this->getJsonBody();

        $token = (new ApiAuthService())->issueToken(
            is_string($body['client_id'] ?? null) ? trim($body['client_id']) : '',
            is_string($body['client_secret'] ?? null) ? $body['client_secret'] : ''
        );

        $this->respond($token);
    }
}
