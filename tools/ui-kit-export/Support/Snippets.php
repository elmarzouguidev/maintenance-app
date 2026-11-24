<?php

use Illuminate\Support\Facades\View;

/**
 * The Blade source of the demo blocks of a gallery view: the text between
 * {{-- snippet:name --}} and {{-- /snippet --}}, without its common indentation. The page
 * prints it under the demo, so the usage shown is exactly the code that rendered it.
 *
 * @return array<string, string>
 */
function kitSnippets(string $view): array
{
    $source = (string) file_get_contents(View::getFinder()->find($view));
    preg_match_all('/\{\{-- snippet:([a-z0-9-]+) --\}\}\n(.*?)\n\s*\{\{-- \/snippet --\}\}/s', $source, $matches, PREG_SET_ORDER);

    $snippets = [];
    foreach ($matches as [, $name, $code]) {
        $lines = explode("\n", rtrim($code));
        $indent = min(array_map(fn (string $line): int => strlen($line) - strlen(ltrim($line)), array_filter($lines, fn (string $line): bool => trim($line) !== '')) ?: [0]);
        $snippets[$name] = implode("\n", array_map(fn (string $line): string => substr($line, $indent), $lines));
    }

    return $snippets;
}
