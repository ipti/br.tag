<?php

/**
 * Erro de negócio da API: vira uma resposta JSON com o status HTTP e o código
 * de erro informados, no formato {"erro": {"codigo", "mensagem", "detalhes"}}.
 */
class ApiException extends Exception
{
    private int $httpStatus;
    private string $errorCode;
    private $details;

    public function __construct(int $httpStatus, string $errorCode, string $message, $details = null)
    {
        parent::__construct($message);
        $this->httpStatus = $httpStatus;
        $this->errorCode = $errorCode;
        $this->details = $details;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getDetails()
    {
        return $this->details;
    }
}
