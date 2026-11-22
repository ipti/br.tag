<?php

/**
 * Troca a credencial de um cliente (client_id + client_secret) por um JWT.
 */
class ApiAuthService
{
    public function issueToken(string $clientId, string $clientSecret): array
    {
        if ($clientId === '' || $clientSecret === '') {
            throw new ApiException(400, 'credenciais_ausentes', 'Informe client_id e client_secret.');
        }

        $client = ApiClient::model()->findByAttributes(['client_id' => $clientId]);

        if ($client === null) {
            // Gasta o mesmo tempo de uma verificação real, para a resposta não
            // revelar quais client_id existem
            password_verify($clientSecret, password_hash($clientId, PASSWORD_DEFAULT));
            throw self::invalidCredentials();
        }

        if (!$client->verifySecret($clientSecret) || !$client->active) {
            throw self::invalidCredentials();
        }

        $now = time();
        $claims = [
            'iss' => 'tag',
            'aud' => INSTANCE,
            'sub' => $client->client_id,
            'iat' => $now,
            'nbf' => $now,
            'exp' => $now + ApiController::TOKEN_TTL_SECONDS,
            'jti' => bin2hex(random_bytes(16)),
        ];
        if (!empty($client->school_inep_fk)) {
            $claims['escola_id'] = $client->school_inep_fk;
        }

        $token = ApiJwt::encode($claims, ApiController::getJwtSecret());

        $client->last_used_at = date('Y-m-d H:i:s');
        $client->save(false, ['last_used_at']);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => ApiController::TOKEN_TTL_SECONDS,
            'escola_id' => $client->school_inep_fk,
        ];
    }

    private static function invalidCredentials(): ApiException
    {
        return new ApiException(401, 'credenciais_invalidas', 'client_id ou client_secret inválido.');
    }
}
