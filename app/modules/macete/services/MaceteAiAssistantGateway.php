<?php

class MaceteAiAssistantGatewayException extends CException
{
    public function __construct(public readonly string $errorCode, public readonly int $statusCode = 0)
    {
        parent::__construct('O assistente pedagógico está indisponível no momento.');
    }
}

class MaceteAiAssistantGateway
{
    private string $serviceUrl;
    private string $serviceToken;
    private int $timeoutSeconds;

    public function __construct(?string $serviceUrl = null, ?string $serviceToken = null, ?int $timeoutSeconds = null)
    {
        $this->serviceUrl = rtrim($serviceUrl ?? (string) getenv('MACETE_AI_SERVICE_URL'), '/');
        $this->serviceToken = $serviceToken ?? (string) getenv('MACETE_AI_SERVICE_TOKEN');
        $this->timeoutSeconds = $timeoutSeconds ?? $this->getTimeoutFromEnvironment();
    }

    public function isConfigured(): bool
    {
        return $this->serviceUrl !== '' && $this->serviceToken !== '';
    }

    public function startConversation(array $payload): array
    {
        return $this->post('/v1/conversations', $payload);
    }

    public function sendMessage(string $conversationId, array $payload): array
    {
        return $this->post('/v1/conversations/' . rawurlencode($conversationId) . '/messages', $payload);
    }

    private function post(string $path, array $payload): array
    {
        if (!$this->isConfigured()) {
            throw new MaceteAiAssistantGatewayException('assistant_not_configured');
        }
        if (!function_exists('curl_init')) {
            throw new MaceteAiAssistantGatewayException('curl_not_available');
        }

        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $curl = curl_init($this->serviceUrl . $path);
        if ($curl === false) {
            throw new MaceteAiAssistantGatewayException('assistant_connection_failed');
        }

        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->serviceToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_CONNECTTIMEOUT => min($this->timeoutSeconds, 10),
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        curl_close($curl);

        if ($response === false || $curlError !== '') {
            throw new MaceteAiAssistantGatewayException('assistant_connection_failed');
        }
        if ($statusCode < 200 || $statusCode >= 300) {
            throw new MaceteAiAssistantGatewayException($this->normalizeErrorCode($statusCode), $statusCode);
        }

        try {
            $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new MaceteAiAssistantGatewayException('assistant_invalid_response', $statusCode);
        }
        if (!is_array($decoded)) {
            throw new MaceteAiAssistantGatewayException('assistant_invalid_response', $statusCode);
        }

        return $decoded;
    }

    private function getTimeoutFromEnvironment(): int
    {
        $value = (int) getenv('MACETE_AI_SERVICE_TIMEOUT_SECONDS');

        return $value >= 1 && $value <= 120 ? $value : 60;
    }

    private function normalizeErrorCode(int $statusCode): string
    {
        return match ($statusCode) {
            401, 403 => 'assistant_unauthorized',
            404 => 'assistant_conversation_not_found',
            422 => 'assistant_invalid_context',
            429 => 'assistant_rate_limited',
            503 => 'assistant_unavailable',
            504 => 'assistant_timeout',
            default => 'assistant_request_failed',
        };
    }
}
