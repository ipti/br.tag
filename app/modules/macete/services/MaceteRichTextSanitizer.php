<?php

class MaceteRichTextSanitizer
{
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><ul><ol><li><a><div>';

    public static function sanitize(?string $content): string
    {
        $content = trim((string) $content);
        if ($content === '') {
            return '';
        }

        $content = strip_tags($content, self::ALLOWED_TAGS);
        if (!class_exists('DOMDocument')) {
            return $content;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrors = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!DOCTYPE html><html><body><div id="macete-rich-text-root">' . $content . '</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrors);

        $root = $document->getElementById('macete-rich-text-root');
        if ($root === null) {
            return '';
        }

        $elements = $root->getElementsByTagName('*');
        for ($index = $elements->length - 1; $index >= 0; $index--) {
            $element = $elements->item($index);
            $tagName = strtolower($element->tagName);
            $href = $tagName === 'a' ? trim($element->getAttribute('href')) : '';
            $listType = $tagName === 'li' ? $element->getAttribute('data-list') : '';

            while ($element->attributes->length > 0) {
                $element->removeAttributeNode($element->attributes->item(0));
            }

            if ($tagName === 'a' && self::isSafeLink($href)) {
                $element->setAttribute('href', $href);
                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }

            if ($tagName === 'li' && in_array($listType, ['ordered', 'bullet'], true)) {
                $element->setAttribute('data-list', $listType);
            }
        }

        $result = '';
        foreach ($root->childNodes as $child) {
            $result .= $document->saveHTML($child);
        }

        return trim($result);
    }

    private static function isSafeLink(string $href): bool
    {
        if ($href === '') {
            return false;
        }

        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto'], true);
    }
}
