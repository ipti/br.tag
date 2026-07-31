<?php

class MaceteAiAssistantGatewayException extends CException
{
    public function __construct(public readonly string $errorCode, public readonly int $statusCode = 0)
    {
        parent::__construct('O assistente pedagógico está indisponível no momento.');
    }
}
