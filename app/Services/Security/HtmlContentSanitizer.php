<?php

namespace App\Services\Security;

use Illuminate\Container\Attributes\Singleton;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerAction;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Sanitizes rich text produced by the admin TinyMCE editor (<x-admin.tinymce-editor>).
 *
 * The allowlist mirrors what the editor's toolbar and plugins generate, minus inline styles,
 * event handlers and embedded media. Unknown wrappers (div, span, font...) are unwrapped so
 * their text survives, while executable or embedding elements are dropped with their content.
 */
#[Singleton]
class HtmlContentSanitizer
{
    /**
     * @var array<string, array<int, string>>
     */
    private const ALLOWED_ELEMENTS = [
        'p' => [], 'br' => [], 'hr' => [], 'blockquote' => [], 'pre' => [],
        'h1' => [], 'h2' => [], 'h3' => [], 'h4' => [], 'h5' => [], 'h6' => [],
        'strong' => [], 'b' => [], 'em' => [], 'i' => [], 'u' => [], 's' => [],
        'sub' => [], 'sup' => [], 'code' => [],
        'ul' => [], 'ol' => [], 'li' => [],
        'a' => ['href', 'title', 'target'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'table' => [], 'caption' => [], 'thead' => [], 'tbody' => [], 'tfoot' => [], 'tr' => [],
        'th' => ['colspan', 'rowspan', 'scope'], 'td' => ['colspan', 'rowspan'],
    ];

    private const DROPPED_ELEMENTS = [
        'script', 'style', 'iframe', 'object', 'embed', 'form', 'template', 'noscript', 'svg', 'math',
    ];

    private readonly HtmlSanitizer $sanitizer;

    public function __construct()
    {
        $config = (new HtmlSanitizerConfig)
            ->defaultAction(HtmlSanitizerAction::Block)
            ->allowLinkSchemes(['http', 'https', 'mailto'])
            ->allowRelativeLinks()
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeMedias()
            ->withAttributeSanitizer(new LinkTargetAttributeSanitizer)
            ->withMaxInputLength(-1);

        foreach (self::ALLOWED_ELEMENTS as $element => $attributes) {
            $config = $config->allowElement($element, $attributes);
        }

        foreach (self::DROPPED_ELEMENTS as $element) {
            $config = $config->dropElement($element);
        }

        $this->sanitizer = new HtmlSanitizer($config);
    }

    public function sanitize(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return $this->protectNewTabLinks(trim($this->sanitizer->sanitize($html)));
    }

    /**
     * Like sanitize(), but keeps empty content null so nullable columns stay null.
     */
    public function sanitizeNullable(?string $html): ?string
    {
        $sanitized = $this->sanitize($html);

        return $sanitized === '' ? null : $sanitized;
    }

    /**
     * Sanitizes the given rich-text keys of a payload, leaving absent keys untouched.
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, string>  $fields
     * @return array<string, mixed>
     */
    public function sanitizeFields(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $this->sanitizeNullable($data[$field]);
            }
        }

        return $data;
    }

    public function sanitizeText(string $value): string
    {
        return trim(strip_tags($value));
    }

    /**
     * The sanitizer never lets a rel attribute through, so every surviving target="_blank" link gets a fresh one.
     */
    private function protectNewTabLinks(string $html): string
    {
        return (string) preg_replace('/<a\b([^>]*\btarget="_blank"[^>]*)>/', '<a$1 rel="noopener noreferrer">', $html);
    }
}
