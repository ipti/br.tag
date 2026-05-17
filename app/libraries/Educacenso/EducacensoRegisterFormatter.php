<?php

class EducacensoRegisterFormatter
{
    private const EXPECTED_FIELD_COUNTS_BY_YEAR = [
        2026 => [
            0 => 53,
            10 => 187,
            20 => 66,
            30 => 110,
            40 => 7,
            50 => 38,
            60 => 33,
        ],
    ];

    public static function format($registerType, array $register, $year)
    {
        $expectedFieldCount = self::getExpectedFieldCount($registerType, $year);

        if ($expectedFieldCount !== null) {
            $normalizedRegister = [];
            for ($corder = 1; $corder <= $expectedFieldCount; $corder++) {
                $normalizedRegister[$corder] = $register[$corder] ?? '';
            }

            return implode('|', $normalizedRegister);
        }

        ksort($register);
        return implode('|', $register);
    }

    public static function formatLine($registerType, $line, $year)
    {
        $expectedFieldCount = self::getExpectedFieldCount($registerType, $year);

        if ($expectedFieldCount === null) {
            return $line;
        }

        $fields = explode('|', $line);
        $normalizedFields = [];
        for ($index = 0; $index < $expectedFieldCount; $index++) {
            $normalizedFields[] = $fields[$index] ?? '';
        }

        return implode('|', $normalizedFields);
    }

    public static function sanitizeFileContent($content)
    {
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        $asciiContent = self::convertEncoding('UTF-8', 'ASCII//TRANSLIT//IGNORE', $content);
        if ($asciiContent !== false) {
            $content = $asciiContent;
        }

        return strtoupper($content);
    }

    public static function encodeOutput($content)
    {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($content, 'ISO-8859-1', 'UTF-8');
        }

        $encodedContent = self::convertEncoding('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $content);
        if ($encodedContent !== false) {
            return $encodedContent;
        }

        return $content;
    }

    private static function convertEncoding($fromEncoding, $toEncoding, $content)
    {
        if (!function_exists('iconv')) {
            return false;
        }

        set_error_handler(static function () {
            return true;
        });

        try {
            return iconv($fromEncoding, $toEncoding, $content);
        } finally {
            restore_error_handler();
        }
    }

    private static function getExpectedFieldCount($registerType, $year)
    {
        $year = (int) $year;
        $registerType = (int) $registerType;

        return self::EXPECTED_FIELD_COUNTS_BY_YEAR[$year][$registerType] ?? null;
    }
}
