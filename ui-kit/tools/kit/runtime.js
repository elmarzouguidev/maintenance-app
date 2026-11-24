/*
 * Static gallery runtime (bundled first in static/assets/kit.js, before Alpine starts).
 * Fills the brand placeholders of the static pages from window.KIT_BRAND (static/assets/brand.js,
 * generated from brand/brand.json), so a rebrand is a rebuild, not an edit of every page:
 *   {{ brand }}  {{ brand.short }}  {{ brand.initial }}  {{ brand.version }}  {{ brand.vendor }}  {{ brand.email }}
 * In Blade, the same values come from config('brand.*').
 */
(function () {
    var brand = window.KIT_BRAND || {};
    var values = {
        'brand': brand.name,
        'brand.short': brand.shortName,
        'brand.initial': brand.initial,
        'brand.version': brand.version,
        'brand.vendor': brand.vendor && brand.vendor.name,
        'brand.email': brand.supportEmail,
    };
    var pattern = /\{\{\s*(brand(?:\.[a-z]+)?)\s*\}\}/g;
    var fill = function (text) {
        return text.replace(pattern, function (match, key) {
            return values[key] != null ? values[key] : match;
        });
    };
    var attributes = ['title', 'alt', 'aria-label', 'placeholder', 'content', 'value'];

    var walker = document.createTreeWalker(document.documentElement, NodeFilter.SHOW_TEXT);
    var node;
    while ((node = walker.nextNode())) {
        if (node.nodeValue.indexOf('{{') !== -1) {
            node.nodeValue = fill(node.nodeValue);
        }
    }

    document.querySelectorAll('*').forEach(function (element) {
        attributes.forEach(function (name) {
            var value = element.getAttribute(name);
            if (value && value.indexOf('{{') !== -1) {
                element.setAttribute(name, fill(value));
            }
        });
    });

    document.title = fill(document.title);

    if (brand.themeColor) {
        var meta = document.querySelector('meta[name="theme-color"]') || document.head.appendChild(document.createElement('meta'));
        meta.setAttribute('name', 'theme-color');
        meta.setAttribute('content', brand.themeColor);
    }
})();
