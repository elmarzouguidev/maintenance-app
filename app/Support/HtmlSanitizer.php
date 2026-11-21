<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer as SymfonyHtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Sanitizes user supplied rich text before it is rendered as raw HTML.
 *
 * The heavy lifting is delegated to symfony/html-sanitizer: only the safe W3C
 * elements/attributes are kept, unknown elements are dropped and unsafe URL
 * schemes are removed.
 */
class HtmlSanitizer
{
    private static ?SymfonyHtmlSanitizer $sanitizer = null;

    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return self::sanitizer()->sanitize($html);
    }

    private static function sanitizer(): SymfonyHtmlSanitizer
    {
        if (self::$sanitizer instanceof SymfonyHtmlSanitizer) {
            return self::$sanitizer;
        }

        $config = (new HtmlSanitizerConfig)
            ->allowSafeElements()
            ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
            ->allowRelativeLinks()
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeMedias()
            ->forceAttribute('a', 'rel', 'noopener noreferrer')
            ->withMaxInputLength(1_000_000);

        return self::$sanitizer = new SymfonyHtmlSanitizer($config);
    }
}
