<?php

/**
 * Yii 1.1 stub - CJSON
 * https://www.yiiframework.com/doc/api/1.1/CJSON
 */
class CJSON
{
    /**
     * Encodes a PHP variable into a JSON string.
     *
     * @param mixed $value data to be encoded
     * @return string JSON representation of the input data
     */
    public static function encode($value)
    {
        return json_encode($value);
    }

    /**
     * Decodes a JSON string into a PHP variable.
     *
     * @param string $json the JSON string
     * @param bool $asArray whether to return objects as associative arrays
     * @return mixed the PHP representation of the JSON string
     */
    public static function decode($json, $asArray = true)
    {
        return json_decode($json, $asArray);
    }
}
