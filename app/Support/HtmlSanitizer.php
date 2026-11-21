<?php

namespace App\Support;

use DOMAttr;
use DOMComment;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Dependency-free HTML sanitizer used before rendering user supplied rich text.
 *
 * It keeps a small allow-list of formatting tags, strips every other tag
 * (removing the dangerous ones together with their content) and only keeps a
 * short list of safe attributes.
 */
class HtmlSanitizer
{
    /**
     * Tags allowed to remain in the sanitized output.
     *
     * @var list<string>
     */
    private const ALLOWED_TAGS = [
        'a', 'b', 'blockquote', 'br', 'code', 'div', 'em', 'h1', 'h2', 'h3',
        'h4', 'h5', 'h6', 'hr', 'i', 'img', 'li', 'mark', 'ol', 'p', 'pre',
        's', 'small', 'span', 'strike', 'strong', 'sub', 'sup', 'table',
        'tbody', 'td', 'tfoot', 'th', 'thead', 'tr', 'u', 'ul',
    ];

    /**
     * Tags removed together with their content.
     *
     * @var list<string>
     */
    private const FORBIDDEN_TAGS = [
        'applet', 'base', 'embed', 'form', 'frame', 'frameset', 'iframe',
        'link', 'math', 'meta', 'noscript', 'object', 'script', 'style',
        'svg', 'template',
    ];

    /**
     * Attributes allowed per tag.
     *
     * @var array<string, list<string>>
     */
    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title'],
        'img' => ['alt', 'height', 'src', 'title', 'width'],
        'td' => ['colspan', 'rowspan'],
        'th' => ['colspan', 'rowspan'],
    ];

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousUseErrors = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'
            .'<div data-html-sanitizer-root="1">'.$html.'</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousUseErrors);

        $root = (new DOMXPath($document))
            ->query('//div[@data-html-sanitizer-root="1"]')
            ->item(0);

        if (! $root instanceof DOMElement) {
            return e($html);
        }

        self::sanitizeChildren($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return $output;
    }

    private static function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if ($child instanceof DOMComment) {
                $parent->removeChild($child);

                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->nodeName);

            if (in_array($tag, self::FORBIDDEN_TAGS, true)) {
                $parent->removeChild($child);

                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                self::sanitizeChildren($child);
                self::unwrap($child);

                continue;
            }

            self::sanitizeAttributes($child, $tag);
            self::sanitizeChildren($child);
        }
    }

    private static function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent instanceof DOMNode) {
            return;
        }

        while ($element->firstChild !== null) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowedAttributes = self::ALLOWED_ATTRIBUTES[$tag] ?? [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            if (! $attribute instanceof DOMAttr) {
                continue;
            }

            $name = strtolower($attribute->nodeName);

            if (! in_array($name, $allowedAttributes, true)) {
                $element->removeAttribute($attribute->nodeName);

                continue;
            }

            if (in_array($name, ['href', 'src'], true) && ! self::isSafeUrl($attribute->nodeValue)) {
                $element->removeAttribute($attribute->nodeName);
            }
        }

        if ($tag === 'a') {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }

    private static function isSafeUrl(?string $url): bool
    {
        $url = trim((string) $url);

        if ($url === '') {
            return false;
        }

        // Browsers strip tabs/newlines from URLs, so "jav\tascript:" would still run.
        if (preg_match('/[\x00-\x20]/', $url) === 1) {
            return false;
        }

        if (str_starts_with($url, '#') || str_starts_with($url, '/') || str_starts_with($url, './') || str_starts_with($url, '../') || str_starts_with($url, '?')) {
            return true;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        if ($scheme === null) {
            return true;
        }

        return in_array(strtolower($scheme), ['http', 'https', 'mailto', 'tel'], true);
    }
}
