#!/bin/sh
# Leak check: nothing of the source app, no secrets and no real e-mail addresses in the kit.
# Run from ui-kit/: npm run check (or sh tools/check-leaks.sh). Exit code 1 on any finding.
cd "$(dirname "$0")/.." || exit 1
status=0

# Source product and author names, local hosts, anti-bot scripts, encrypted payloads, key formats.
# (This file itself holds the patterns, so it is excluded.)
matches=$(grep -rniE 'nidamcar|elmarzougui|rentcar|saveme|127\.0\.0\.1|localhost|turnstile|eyJ[A-Za-z0-9+/=]{20,}|sk_(live|test)_|AKIA[0-9A-Z]{16}' . \
    --exclude-dir=node_modules --exclude=check-leaks.sh --exclude=package-lock.json)
if [ -n "$matches" ]; then
    echo "Source-app references or secrets:"; echo "$matches" | cut -c1-200; status=1
fi

# E-mail addresses: only the reserved example.com domain.
emails=$(grep -rhoE '[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}' . --exclude-dir=node_modules --exclude=package-lock.json \
    | grep -viE '@example\.com$' | grep -viE '\.(png|svg|woff2|js|css)$' | sort -u)
if [ -n "$emails" ]; then
    echo "E-mail addresses outside example.com:"; echo "$emails"; status=1
fi

[ $status -eq 0 ] && echo "No leaks: no source-app reference, secret or real e-mail address."
exit $status
