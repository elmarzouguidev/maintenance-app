#!/usr/bin/env node
/*
 * Builds the kit from brand/brand.json, the single brand source.
 *
 *   npm install      (once: pinned @tailwindcss/cli, tailwindcss and alpinejs, inside ui-kit/ only)
 *   npm run build
 *
 * Steps:
 *   1. brand/brand.css   @font-face rules and the brand @theme tokens (colours, font).
 *   2. brand/brand.php   the brand config for the Laravel layouts and components.
 *   3. brand/*.svg       neutral placeholder logos, when brand.json "logo.generate" is true.
 *   4. static/assets/    brand.js, brand logos, fonts and images for the static gallery.
 *   5. static/assets/kit.js   kit runtime + the Alpine components of laravel/js + Alpine.
 *   6. static/assets/kit.css  Tailwind CSS v4 build of theme/theme.css over static/ and laravel/.
 *
 * No network and no app build: everything stays inside ui-kit/.
 */
import { execFileSync } from 'node:child_process';
import { cpSync, existsSync, mkdirSync, readFileSync, readdirSync, writeFileSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const path = (...parts) => join(root, ...parts);
const brand = JSON.parse(readFileSync(path('brand/brand.json'), 'utf8'));

const UNICODE_RANGES = {
    latin: 'U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD',
    'latin-ext': 'U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF',
};

/** brand.json colour key => Tailwind theme variable. */
const COLOR_TOKENS = {
    brand: '--color-brand',
    brandDeep: '--color-brand-deep',
    accent: '--color-accent',
    accentHover: '--color-accent-hover',
    accentSoft: '--color-accent-soft',
    accentStrong: '--color-accent-strong',
    onAccent: '--color-on-accent',
    focus: '--color-focus',
    sidebar: '--color-sidebar',
    sidebarText: '--color-sidebar-text',
    sidebarMuted: '--color-sidebar-muted',
    sidebarRail: '--color-sidebar-rail',
    sidebarFocus: '--color-sidebar-focus',
    sidebarStrong: '--color-sidebar-strong',
    sidebarHover: '--color-sidebar-hover',
    sidebarActive: '--color-sidebar-active',
};

const GENERATED = 'Generated from brand/brand.json by `npm run build`: edit brand.json, never this file.';

function write(relativePath, content) {
    const target = path(relativePath);
    mkdirSync(dirname(target), { recursive: true });
    writeFileSync(target, content);
}

function escapeXml(value) {
    return String(value).replace(/[<>&"']/g, (character) => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;', '"': '&quot;', "'": '&apos;' })[character]);
}

function phpString(value) {
    return `'${String(value).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;
}

// 1. brand/brand.css
function buildBrandCss() {
    const missing = Object.keys(COLOR_TOKENS).filter((key) => !brand.colors?.[key]);
    if (missing.length > 0) {
        throw new Error(`brand.json: missing colours: ${missing.join(', ')}`);
    }

    const faces = (brand.font.faces ?? []).map((face) => [
        '@font-face {',
        `  font-family: ${brand.font.family};`,
        `  font-style: ${face.style ?? 'normal'};`,
        `  font-weight: ${face.weight};`,
        '  font-display: swap;',
        `  src: url("${face.file}") format("woff2");`,
        `  unicode-range: ${UNICODE_RANGES[face.unicodeRange] ?? face.unicodeRange};`,
        '}',
    ].join('\n'));

    const tokens = Object.entries(COLOR_TOKENS).map(([key, variable]) => `  ${variable}: ${brand.colors[key]};`);

    write('brand/brand.css', `/*
 * Brand tokens of the UI kit. ${GENERATED}
 * Font: ${brand.font.family}, ${brand.font.licence ?? 'see its licence file'}.
 */

${faces.join('\n\n')}

@theme {
  --font-sans: ${brand.font.stack};

${tokens.join('\n')}
}
`);
}

// 2. brand/brand.php
function buildBrandPhp() {
    const assets = 'vendor/ui-kit';
    write('brand/brand.php', `<?php

/*
 * Brand configuration for the UI kit's Blade layouts and components (config('brand.*')).
 * ${GENERATED}
 * Install: copy to config/brand.php, and brand/*.svg to public/${assets}/brand/ (INSTALL.md).
 */
return [
    'name' => ${phpString(brand.name)},
    'short_name' => ${phpString(brand.shortName)},
    'initial' => ${phpString(brand.initial)},
    'version' => ${phpString(brand.version)},
    'support_email' => ${phpString(brand.supportEmail)},
    'vendor' => [
        'name' => ${phpString(brand.vendor.name)},
        'url' => ${phpString(brand.vendor.url)},
    ],
    'logo' => ${phpString(`${assets}/brand/${brand.logo.logo}`)},
    'logo_mark' => ${phpString(`${assets}/brand/${brand.logo.logoMark}`)},
    'favicon' => ${phpString(`${assets}/brand/${brand.logo.favicon}`)},
    'theme_color' => ${phpString(brand.colors.brand)},
    'images' => ${phpString(`${assets}/images`)},
];
`);
}

// 3. Placeholder logos (neutral: the initial on the brand colour, then the name).
function buildLogos() {
    if (!brand.logo.generate) {
        return;
    }

    const font = escapeXml(brand.font.stack);
    const initial = escapeXml(brand.initial);
    const mark = (size) => `<rect width="${size}" height="${size}" rx="${Math.round(size * 0.22)}" fill="${brand.colors.brand}"/>
  <text x="${size / 2}" y="${size / 2}" dy="0.35em" text-anchor="middle" font-family="${font}" font-size="${Math.round(size * 0.56)}" font-weight="600" fill="${brand.colors.onAccent}">${initial}</text>`;

    write(`brand/${brand.logo.logoMark}`, `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64" role="img" aria-label="${escapeXml(brand.name)}">
  ${mark(64)}
</svg>
`);
    write(`brand/${brand.logo.favicon}`, `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64">
  ${mark(64)}
</svg>
`);

    const width = 64 + 16 + Math.ceil(brand.name.length * 22);
    write(`brand/${brand.logo.logo}`, `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${width} 64" width="${width}" height="64" role="img" aria-label="${escapeXml(brand.name)}">
  ${mark(64)}
  <text x="80" y="32" dy="0.35em" font-family="${font}" font-size="34" font-weight="600" fill="${brand.colors.brand}">${escapeXml(brand.name)}</text>
</svg>
`);
}

// 4. Static assets: brand data, logos, fonts, images.
function buildStaticAssets() {
    write('static/assets/brand.js', `/* ${GENERATED} */
window.KIT_BRAND = ${JSON.stringify({
        name: brand.name,
        shortName: brand.shortName,
        initial: brand.initial,
        version: brand.version,
        supportEmail: brand.supportEmail,
        vendor: brand.vendor,
        themeColor: brand.colors.brand,
    }, null, 4)};
`);

    mkdirSync(path('static/assets/brand'), { recursive: true });
    for (const key of ['logo', 'logoMark', 'favicon']) {
        cpSync(path('brand', brand.logo[key]), path('static/assets/brand', brand.logo[key]));
    }
    cpSync(path('brand/fonts'), path('static/assets/fonts'), { recursive: true });
    cpSync(path('theme/images'), path('static/assets/images'), { recursive: true });
}

// 5. static/assets/kit.js: classic script (works from file://, unlike ES modules).
function buildKitJs() {
    const alpine = path('node_modules/alpinejs/dist/cdn.min.js');
    if (!existsSync(alpine)) {
        throw new Error('alpinejs is missing: run `npm install` in ui-kit/ first.');
    }

    const components = readdirSync(path('laravel/js'))
        .filter((file) => file.endsWith('.js') && file !== 'index.js')
        .sort()
        .map((file) => readFileSync(path('laravel/js', file), 'utf8')
            .replace(/^export default function /m, 'function ')
            .trim());
    const registrations = readFileSync(path('laravel/js/index.js'), 'utf8')
        .match(/Alpine\.data\([^;]+;/g) ?? [];

    write('static/assets/kit.js', [
        `/* UI kit runtime for the static gallery. Built by \`npm run build\` from tools/kit/runtime.js, laravel/js/*.js and Alpine.js ${JSON.parse(readFileSync(path('node_modules/alpinejs/package.json'), 'utf8')).version} (MIT). */`,
        readFileSync(path('tools/kit/runtime.js'), 'utf8').trim(),
        '(function () {',
        ...components,
        `document.addEventListener('alpine:init', function () {\n    ${registrations.join('\n    ')}\n});`,
        '})();',
        readFileSync(alpine, 'utf8').trim(),
        '',
    ].join('\n\n'));
}

// 6. static/assets/kit.css
function buildKitCss() {
    const cli = path('node_modules/.bin/tailwindcss');
    if (!existsSync(cli)) {
        throw new Error('@tailwindcss/cli is missing: run `npm install` in ui-kit/ first.');
    }

    execFileSync(cli, ['--input', 'tools/kit.entry.css', '--output', 'static/assets/kit.css'], { cwd: root, stdio: 'inherit' });
}

buildBrandCss();
buildBrandPhp();
buildLogos();
buildStaticAssets();
buildKitJs();
buildKitCss();
console.log(`Built the kit for "${brand.name}".`);
