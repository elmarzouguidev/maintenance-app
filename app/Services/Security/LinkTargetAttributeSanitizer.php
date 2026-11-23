<?php

namespace App\Services\Security;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

/**
 * Only allows the target="_blank" value on links.
 */
class LinkTargetAttributeSanitizer implements AttributeSanitizerInterface
{
    public function getSupportedElements(): ?array
    {
        return ['a'];
    }

    public function getSupportedAttributes(): ?array
    {
        return ['target'];
    }

    public function sanitizeAttribute(string $element, string $attribute, string $value, HtmlSanitizerConfig $config): ?string
    {
        return $value === '_blank' ? '_blank' : null;
    }
}
