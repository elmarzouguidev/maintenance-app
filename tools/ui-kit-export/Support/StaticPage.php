<?php

namespace UiKitExport;

use RuntimeException;

/**
 * Turns an HTML response of the source app into a static gallery page of ui-kit/static:
 * - no app secrets or session data (CSRF token, honeypot, anti-bot scripts, creator metas);
 * - the product name, logos and version become brand placeholders ({{ brand }} …) filled at
 *   runtime by static/assets/kit.js from brand.js;
 * - app URLs become "#" (pages link to each other with relative paths written in the views);
 * - the kit assets (kit.css, brand.js, kit.js) and the gallery bar are added.
 * The markup of the app is otherwise unchanged.
 */
final class StaticPage
{
    /** Source product name, as rendered by the app (config('app.name')). */
    public static string $productName = 'NidamCar';

    /** @var list<string> Strings the app renders that must become the {{ brand.version }} placeholder. */
    public static array $versionStrings = [];

    /**
     * @param  array<string, string>  $page  Manifest page
     */
    public static function write(array $page, string $html): string
    {
        $target = self::kitPath('static/'.$page['path']);
        @mkdir(dirname($target), 0775, true);
        file_put_contents($target, self::convert($page, $html));

        return $target;
    }

    /**
     * @param  array<string, string>  $page
     */
    public static function convert(array $page, string $html): string
    {
        if (! str_contains($html, '</head>') || ! preg_match('/<body[^>]*>/', $html)) {
            throw new RuntimeException('Not a full HTML page: '.$page['path']);
        }

        $rel = str_repeat('../', substr_count($page['path'], '/'));

        // Secrets, session data and source-app metadata.
        $html = preg_replace('/\s*<meta name="csrf-token"[^>]*>/', '', $html);
        $html = preg_replace('/\s*<input type="hidden" name="_token"[^>]*>/', '', $html);
        $html = preg_replace('/\s*<div id="[A-Za-z0-9_]+_wrap"[^>]*style="display:\s*none"[^>]*>.*?<\/div>/s', '', $html);
        $html = preg_replace('/\s*<script[^>]*challenges\.cloudflare\.com[^>]*><\/script>/', '', $html);
        $html = preg_replace('/\s*<meta content="app_(creator|version)"[^>]*>/', '', $html);
        $html = preg_replace('/\s*<meta http-equiv="Content-Security-Policy"[^>]*>/', '', $html);
        $html = preg_replace('/\s*<meta name="(googlebot|google)"[^>]*>/', '', $html);

        // Brand: favicon, logos, theme colour (kit.js adds it from brand.js), name, version, vendor.
        $html = preg_replace('/<link rel="icon"[^>]*>/', '<link rel="icon" type="image/svg+xml" href="'.$rel.'assets/brand/favicon.svg">', $html);
        $html = preg_replace('/<link rel="apple-touch-icon"[^>]*>/', '<link rel="apple-touch-icon" href="'.$rel.'assets/brand/logo-mark.svg">', $html);
        $html = preg_replace('/\s*<meta name="theme-color"[^>]*>/', '', $html);
        $html = preg_replace('#https?://[^"\'\s]*/assets/images/nidamcar-icon\.png#', $rel.'assets/brand/logo-mark.svg', $html);
        $html = preg_replace('#https?://[^"\'\s]*/assets/images/nidamcar-logo\.png#', $rel.'assets/brand/logo.svg', $html);
        $html = preg_replace('#https?://[^"\'\s]*/assets/images/(vehicle-condition-top-view\.png)#', $rel.'assets/images/$1', $html);
        // Paths of the kit's own Blade components (config/brand.php), when rendered through them.
        $html = preg_replace('#https?://[^"\'\s]*/vendor/ui-kit/brand/([a-z-]+\.svg)#', $rel.'assets/brand/$1', $html);
        $html = preg_replace('#https?://[^"\'\s]*/vendor/ui-kit/images/([a-z-]+\.png)#', $rel.'assets/images/$1', $html);
        foreach (self::$versionStrings as $version) {
            $html = str_replace($version, '{{ brand.version }}', $html);
        }
        $html = preg_replace('#<a href="https://elmarzougui\.com/?"([^>]*)>Elmarzougui\.com</a>#i', '<a href="#"$1>{{ brand.vendor }}</a>', $html);
        $html = str_replace([self::$productName, strtoupper(self::$productName)], '{{ brand }}', $html);

        // Fictional e-mail addresses only, on the reserved example.com domain.
        $html = preg_replace('/([A-Za-z0-9._%+-]+)@(?!example\.com\b)[A-Za-z0-9.-]+\.[A-Za-z]{2,}/', '$1@example.com', $html);

        // App URLs: nothing in the gallery points back to a server.
        $html = preg_replace('#https?://(127\.0\.0\.1|localhost)(:\d+)?[^"\'\s<)]*#', '#', $html);
        $html = str_replace(['href="#/', 'action="#/'], ['href="#', 'action="#'], $html);

        // Kit assets and page metadata.
        $head = implode("\n    ", [
            '<meta name="kit:source" content="'.e($page['source']).'">',
            '<meta name="kit:kit-files" content="'.e($page['kit']).'">',
            '<meta name="kit:pattern" content="'.e($page['pattern']).'">',
            '<meta name="kit:states" content="'.e($page['states']).'">',
            '<link rel="stylesheet" href="'.$rel.'assets/kit.css">',
            '<script src="'.$rel.'assets/brand.js" defer></script>',
            '<script src="'.$rel.'assets/kit.js" defer></script>',
        ]);
        $html = preg_replace('#\s*</head>#', "\n    ".$head."\n</head>", $html, 1);
        $html = preg_replace('/(<body[^>]*>)/', '$1'."\n".self::bar($page, $rel), $html, 1);

        return preg_replace("/\n{3,}/", "\n\n", $html);
    }

    /**
     * The gallery bar (data-kit-chrome): not part of the design system. It links every page
     * without JavaScript (details/summary) and states the source, pattern and states shown.
     *
     * @param  array<string, string>  $page
     */
    public static function bar(array $page, string $rel): string
    {
        $sections = '';
        foreach (Manifest::sections() as $section) {
            $links = '';
            foreach ($section['pages'] as $item) {
                $path = $section['dir'].'/'.$item['key'].'.html';
                $current = $path === $page['path'] ? ' aria-current="page" class="font-semibold text-fg"' : ' class="text-fg-muted hover:text-fg hover:underline"';
                $links .= '<li><a href="'.$rel.$path.'"'.$current.'>'.e($item['title']).'</a></li>';
            }
            $sections .= '<div class="min-w-0"><p class="font-semibold text-fg">'.e($section['title']).'</p><ul class="mt-1 space-y-1">'.$links.'</ul></div>';
        }

        $isIndex = ($page['kind'] ?? '') === 'index';

        return <<<HTML
<div data-kit-chrome class="relative z-50 border-b border-line bg-surface-muted text-caption text-fg-muted">
    <div class="flex min-h-9 flex-wrap items-center gap-x-4 gap-y-1 px-4 py-1.5 lg:px-6">
        <a href="{$rel}index.html" class="font-semibold text-fg hover:underline">UI kit · {{ brand }}</a>
        <span class="min-w-0 truncate">{$page['section']} › {$page['title']}</span>
        <details class="ms-auto">
            <summary class="cursor-pointer rounded-control font-medium text-fg focus-visible:outline-2 focus-visible:outline-focus">All pages</summary>
            <nav aria-label="Kit pages" class="admin-popover admin-popover-scroll absolute inset-x-4 top-full z-50 mt-1 grid gap-4 p-4 sm:grid-cols-2 lg:start-auto lg:end-6 lg:w-2xl lg:grid-cols-3">{$sections}</nav>
        </details>
    </div>
HTML
            .($isIndex ? '' : '
    <p class="border-t border-line px-4 py-1.5 lg:px-6"><span class="font-medium text-fg">Source:</span> <code>'.e($page['source']).'</code> · <span class="font-medium text-fg">Kit:</span> <code>'.e($page['kit']).'</code> · <span class="font-medium text-fg">Pattern:</span> '.e($page['pattern']).' · <span class="font-medium text-fg">States:</span> '.e($page['states']).'</p>')
            ."\n</div>";
    }

    public static function kitPath(string $relative = ''): string
    {
        return dirname(__DIR__, 3).'/ui-kit/'.$relative;
    }
}
