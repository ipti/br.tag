<?php

/**
 * JWT assinado com HS256 (HMAC-SHA256), só com funções nativas do PHP.
 *
 * Aceita apenas tokens com cabeçalho alg=HS256 e typ=JWT, compara a
 * assinatura em tempo constante e confere exp, nbf e iat.
 */
class ApiJwt
{
    private const ALGORITHM = 'HS256';
    private const LEEWAY_SECONDS = 60;
    private const MIN_SECRET_LENGTH = 32;

    public static function encode(array $claims, string $secret): string
    {
        self::assertSecret($secret);

        $header = ['alg' => self::ALGORITHM, 'typ' => 'JWT'];
        $segments = [
            self::base64UrlEncode(json_encode($header)),
            self::base64UrlEncode(json_encode($claims)),
        ];
        $signature = hash_hmac('sha256', implode('.', $segments), $secret, true);
        $segments[] = self::base64UrlEncode($signature);

        return implode('.', $segments);
    }

    /**
     * Valida o token e devolve as claims. Lança ApiException 401 se o token
     * estiver malformado, com assinatura inválida, expirado ou ainda não válido.
     */
    public static function decode(string $token, string $secret, ?int $now = null): array
    {
        self::assertSecret($secret);
        $now = $now ?? time();

        $segments = explode('.', $token);
        if (count($segments) !== 3) {
            throw self::invalidToken();
        }

        [$encodedHeader, $encodedClaims, $encodedSignature] = $segments;
        $header = json_decode((string) self::base64UrlDecode($encodedHeader), true);
        $claims = json_decode((string) self::base64UrlDecode($encodedClaims), true);
        $signature = self::base64UrlDecode($encodedSignature);

        if (!is_array($header) || !is_array($claims) || $signature === false) {
            throw self::invalidToken();
        }

        if (($header['alg'] ?? null) !== self::ALGORITHM || ($header['typ'] ?? null) !== 'JWT') {
            throw self::invalidToken();
        }

        $expected = hash_hmac('sha256', $encodedHeader . '.' . $encodedClaims, $secret, true);
        if (!hash_equals($expected, $signature)) {
            throw self::invalidToken();
        }

        if (!isset($claims['exp']) || !is_int($claims['exp']) || $claims['exp'] + self::LEEWAY_SECONDS < $now) {
            throw new ApiException(401, 'token_expirado', 'O token expirou. Peça um novo em api/auth/token.');
        }

        if (isset($claims['nbf']) && (!is_int($claims['nbf']) || $claims['nbf'] - self::LEEWAY_SECONDS > $now)) {
            throw self::invalidToken();
        }

        if (isset($claims['iat']) && (!is_int($claims['iat']) || $claims['iat'] - self::LEEWAY_SECONDS > $now)) {
            throw self::invalidToken();
        }

        return $claims;
    }

    private static function assertSecret(string $secret): void
    {
        if (strlen($secret) < self::MIN_SECRET_LENGTH) {
            throw new ApiException(
                500,
                'api_nao_configurada',
                'A API não está configurada neste servidor.'
            );
        }
    }

    private static function invalidToken(): ApiException
    {
        return new ApiException(401, 'token_invalido', 'Token inválido.');
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @return string|false
     */
    private static function base64UrlDecode(string $data)
    {
        if ($data === '' || preg_match('/[^A-Za-z0-9_-]/', $data)) {
            return false;
        }

        $padding = strlen($data) % 4;
        if ($padding === 1) {
            return false;
        }
        if ($padding > 0) {
            $data .= str_repeat('=', 4 - $padding);
        }

        return base64_decode(strtr($data, '-_', '+/'), true);
    }
}
