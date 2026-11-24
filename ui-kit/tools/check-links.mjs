#!/usr/bin/env node
/*
 * Link check of the static gallery: every relative href/src of every page resolves to a file,
 * and every page is reachable from static/index.html by following links. Run: npm run check
 * (or node tools/check-links.mjs). Exit code 1 on any problem.
 */
import { existsSync, readFileSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const staticRoot = resolve(dirname(fileURLToPath(import.meta.url)), '../static');

function pages(directory) {
    return readdirSync(directory).flatMap((entry) => {
        const path = join(directory, entry);
        if (statSync(path).isDirectory()) {
            return entry === 'assets' ? [] : pages(path);
        }

        return entry.endsWith('.html') ? [path] : [];
    });
}

function links(page) {
    const html = readFileSync(page, 'utf8');
    return [...html.matchAll(/\s(?:href|src)="([^"]*)"/g)]
        .map((match) => match[1])
        .filter((url) => url !== '' && !/^(#|[a-z][a-z0-9+.-]*:|\/\/)/i.test(url) && !url.includes('{{'))
        .map((url) => resolve(dirname(page), url.split('#')[0].split('?')[0]));
}

const all = pages(staticRoot);
const broken = [];
for (const page of all) {
    for (const target of links(page)) {
        if (!existsSync(target)) {
            broken.push(`${relative(staticRoot, page)} → ${relative(staticRoot, target)}`);
        }
    }
}

const index = join(staticRoot, 'index.html');
const reached = new Set([index]);
const queue = [index];
while (queue.length > 0) {
    for (const target of links(queue.shift())) {
        if (target.endsWith('.html') && existsSync(target) && !reached.has(target)) {
            reached.add(target);
            queue.push(target);
        }
    }
}
const orphans = all.filter((page) => !reached.has(page)).map((page) => relative(staticRoot, page));

console.log(`Pages: ${all.length}. Reachable from index.html: ${reached.size}. Broken links: ${broken.length}. Unreachable pages: ${orphans.length}.`);
broken.forEach((line) => console.log(`  broken: ${line}`));
orphans.forEach((line) => console.log(`  unreachable: ${line}`));
process.exit(broken.length > 0 || orphans.length > 0 ? 1 : 0);
