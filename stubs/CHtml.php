<?php

/**
 * Yii 1.1 stub - CHtml
 * https://www.yiiframework.com/doc/api/1.1/CHtml
 */
class CHtml
{
    /**
     * Encodes special characters into HTML entities.
     * @param string $text data to be encoded
     * @return string the encoded data
     */
    public static function encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Decodes HTML entities back to their corresponding characters.
     * @param string $text data to be decoded
     * @return string the decoded data
     */
    public static function decode($text)
    {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }

    /**
     * Generates an open HTML tag.
     * @param string $tag
     * @param array $htmlOptions
     * @return string
     */
    public static function openTag($tag, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a close HTML tag.
     * @param string $tag
     * @return string
     */
    public static function closeTag($tag)
    {
        return '';
    }

    /**
     * Generates an HTML tag.
     * @param string $tag
     * @param array $htmlOptions
     * @param string|false $content
     * @param bool $closeTag
     * @return string
     */
    public static function tag($tag, $htmlOptions = [], $content = false, $closeTag = true)
    {
        return '';
    }

    /**
     * Generates an input field.
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    public static function inputField($type, $name, $value = '', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a text field input.
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    public static function textField($name, $value = '', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a password field input.
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    public static function passwordField($name, $value = '', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a hidden field input.
     * @param string $name
     * @param string $value
     * @param array $htmlOptions
     * @return string
     */
    public static function hiddenField($name, $value = '', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a drop-down list.
     * @param string $name
     * @param string $select
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    public static function dropDownList($name, $select, $data, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a list box.
     * @param string $name
     * @param mixed $select
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    public static function listBox($name, $select, $data, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a radio button input.
     * @param string $name
     * @param bool $checked
     * @param array $htmlOptions
     * @return string
     */
    public static function radioButton($name, $checked = false, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a checkbox input.
     * @param string $name
     * @param bool $checked
     * @param array $htmlOptions
     * @return string
     */
    public static function checkBox($name, $checked = false, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a label tag.
     * @param string $label
     * @param string $for
     * @param array $htmlOptions
     * @return string
     */
    public static function label($label, $for, $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a link tag.
     * @param string $text
     * @param string $url
     * @param array $htmlOptions
     * @return string
     */
    public static function link($text, $url = '#', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a submit button.
     * @param string $label
     * @param array $htmlOptions
     * @return string
     */
    public static function submitButton($label = 'Submit', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Generates a button.
     * @param string $label
     * @param array $htmlOptions
     * @return string
     */
    public static function button($label = 'Button', $htmlOptions = [])
    {
        return '';
    }

    /**
     * Renders HTML attributes.
     * @param array $htmlOptions
     * @return string
     */
    public static function renderAttributes($htmlOptions)
    {
        return '';
    }

    /**
     * Normalizes the HTML options.
     * @param array $htmlOptions
     * @return array
     */
    public static function normalizeUrl($htmlOptions)
    {
        return [];
    }

    /**
     * Generates a list data array from a data source.
     *
     * @param array $models the data source (array of objects or arrays)
     * @param string $valueField the attribute name for list option values
     * @param string $textField the attribute name(s) for list option labels. Multiple attributes can be concatenated with '.' (e.g. 'firstName.lastName')
     * @param string|null $groupField the attribute name for group labels (optional)
     * @return array the list data that can be used in dropDownList, listBox etc.
     */
    public static function listData($models, $valueField, $textField, $groupField = null)
    {
        return [];
    }
}
