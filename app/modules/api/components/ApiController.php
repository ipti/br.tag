<?php

/**
 * Base dos controllers do módulo de API.
 *
 * Estende CController (e não o Controller do TAG) para não depender de sessão
 * nem de login. Toda resposta sai em JSON, inclusive os erros.
 */
abstract class ApiController extends CController
{
    public const TOKEN_TTL_SECONDS = 3600;
    private const JWT_SECRET_ENV = 'TAG_API_JWT_SECRET';
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    /** Cliente autenticado na requisição atual (preenchido por authenticate()). */
    protected ?ApiClient $client = null;

    public function runAction($action)
    {
        try {
            parent::runAction($action);
        } catch (ApiException $e) {
            $this->respondError($e->getHttpStatus(), $e->getErrorCode(), $e->getMessage(), $e->getDetails());
        } catch (CHttpException $e) {
            $this->respondError($e->statusCode, 'erro_http', $e->getMessage());
        } catch (Throwable $e) {
            Yii::log('Erro na API: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), CLogger::LEVEL_ERROR, 'application.api');
            $this->respondError(500, 'erro_interno', 'Erro interno ao processar a requisição.');
        }
    }

    protected function respond($data, int $status = 200): void
    {
        $this->sendJson($data, $status);
    }

    protected function respondError(int $status, string $code, string $message, $details = null): void
    {
        $error = ['codigo' => $code, 'mensagem' => $message];
        if ($details !== null) {
            $error['detalhes'] = $details;
        }
        $this->sendJson(['erro' => $error], $status);
    }

    protected function requireMethod(string $method): void
    {
        if (strtoupper(Yii::app()->request->getRequestType()) !== strtoupper($method)) {
            header('Allow: ' . strtoupper($method));
            throw new ApiException(405, 'metodo_nao_permitido', 'Use o método ' . strtoupper($method) . ' nesta rota.');
        }
    }

    /**
     * Corpo da requisição em JSON, já decodificado.
     */
    protected function getJsonBody(): array
    {
        $raw = file_get_contents('php://input');
        $body = json_decode((string) $raw, true);

        if (!is_array($body)) {
            throw new ApiException(400, 'json_invalido', 'O corpo da requisição precisa ser um objeto JSON válido.');
        }

        return $body;
    }

    /**
     * Valida o JWT do header Authorization e carrega o cliente da API.
     *
     * O token só vale no município (banco) em que foi emitido e para um
     * cliente que continue ativo, então desativar o cliente bloqueia o
     * aparelho na hora, mesmo com um token ainda dentro da validade.
     */
    protected function authenticate(): ApiClient
    {
        $token = $this->getBearerToken();
        if ($token === null) {
            throw new ApiException(401, 'token_ausente', 'Envie o token no header Authorization: Bearer <token>.');
        }

        $claims = ApiJwt::decode($token, self::getJwtSecret());

        if (($claims['aud'] ?? null) !== INSTANCE) {
            throw new ApiException(401, 'token_invalido', 'Token inválido.');
        }

        $client = ApiClient::model()->findByAttributes(['client_id' => (string) ($claims['sub'] ?? '')]);
        if ($client === null || !$client->active) {
            throw new ApiException(401, 'cliente_inativo', 'O cliente da API não existe ou foi desativado.');
        }

        $this->client = $client;
        return $client;
    }

    /**
     * Escola do cliente autenticado. As rotas de presença e de alunos só
     * funcionam para clientes presos a uma escola.
     */
    protected function getClientSchoolInep(): string
    {
        if ($this->client === null || empty($this->client->school_inep_fk)) {
            throw new ApiException(403, 'cliente_sem_escola', 'Este cliente da API não está vinculado a uma escola.');
        }

        return $this->client->school_inep_fk;
    }

    public static function getJwtSecret(): string
    {
        return (string) getenv(self::JWT_SECRET_ENV);
    }

    private function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? null;

        if ($header === null && function_exists('getallheaders')) {
            foreach (getallheaders() as $name => $value) {
                if (strcasecmp($name, 'Authorization') === 0) {
                    $header = $value;
                    break;
                }
            }
        }

        if ($header === null || !preg_match('/^Bearer\s+(\S+)$/i', trim($header), $matches)) {
            return null;
        }

        return $matches[1];
    }

    private function sendJson($data, int $status): void
    {
        if (!headers_sent()) {
            http_response_code($status);
            header('Content-Type: application/json; charset=utf-8');
            header('Cache-Control: no-store');
        }
        echo json_encode($data, self::JSON_FLAGS);
    }
}
