#!/usr/bin/env node
/*
 * "Kit only" check, same rules as the source app's raw-style scanner: the static pages and the
 * Blade components and layouts use the semantic tokens only. Forbidden: Tailwind palette colour
 * utilities, deprecated aliases, arbitrary values ([…]; [var(--…)] is allowed in Blade
 * components), colour codes, inline colour styles, font-bold and heavier, external <script> or
 * <link> assets. Raw values belong in theme/theme.css and brand/brand.json only.
 * Run: npm run check (or node tools/check-tokens.mjs). Exit code 1 on any violation.
 */
import { readFileSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const PREFIX = '(?<![\\w-])(?:[\\w\\[\\]&=-]+:)*!?-?';
const COLOUR_UTILITIES = '(?:bg|text|border(?:-[xytrblse])?|ring(?:-offset)?|outline|fill|stroke|from|via|to|divide|placeholder|caret|accent|decoration|shadow)';
const PALETTE = '(?:slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose|black|white)';
const RULES = {
    'palette colour': new RegExp(`${PREFIX}${COLOUR_UTILITIES}-${PALETTE}(?:-\\d{2,3})?(?:\\/[\\d.]+)?!?(?![\\w-])`, 'g'),
    'deprecated alias': new RegExp(`${PREFIX}${COLOUR_UTILITIES}-nidam-[a-z-]+(?:\\/[\\d.]+)?!?(?![\\w-])`, 'g'),
    'arbitrary value': new RegExp(`${PREFIX}[a-z][\\w-]*-\\[[^\\]\\s]+\\](?![\\w-])`, 'g'),
    'colour code': /(?<![&\w#])#(?:[0-9a-fA-F]{8}|[0-9a-fA-F]{6}|[0-9a-fA-F]{3,4})(?![\w-])|\b(?:rgba?|hsla?|oklch|oklab)\(/g,
    'inline colour style': /\bstyle\s*=\s*"[^"]*\b(?:color|background(?:-color)?|border-color|fill|stroke)\s*:/gi,
    'heavy font weight': new RegExp(`${PREFIX}font-(?:bold|extrabold|black)(?![\\w-])`, 'g'),
    'external asset': /<(?:script|link)\b[^>]*\b(?:src|href)\s*=\s*["']https?:\/\//gi,
};

function files(directory, extension, skip = []) {
    return readdirSync(directory).flatMap((entry) => {
        const path = join(directory, entry);
        if (skip.includes(entry)) {
            return [];
        }

        return statSync(path).isDirectory() ? files(path, extension, skip) : (path.endsWith(extension) ? [path] : []);
    });
}

const scanned = [
    ...files(join(root, 'static'), '.html', ['assets']),
    ...files(join(root, 'laravel'), '.blade.php'),
];
const offenders = [];
for (const file of scanned) {
    // <pre><code> blocks are documentation (snippets), not markup.
    const source = readFileSync(file, 'utf8').replace(/<pre[\s\S]*?<\/pre>/g, '');
    for (const [rule, pattern] of Object.entries(RULES)) {
        let matches = [...source.matchAll(pattern)].map((match) => match[0].trim());
        if (rule === 'arbitrary value' && file.endsWith('.blade.php')) {
            matches = matches.filter((match) => !/\[var\(--[\w-]+\)\]/.test(match));
        }
        if (matches.length > 0) {
            offenders.push(`${relative(root, file)} → ${rule}: ${[...new Set(matches)].slice(0, 5).join(', ')}`);
        }
    }
}

console.log(`Files scanned: ${scanned.length}. Files with raw styling: ${offenders.length}.`);
offenders.forEach((line) => console.log(`  ${line}`));
process.exit(offenders.length > 0 ? 1 : 0);
