{{-- Page preloader (Skote #preloader): covers the page until window "load", then fades out. --}}
<div data-preloader class="fixed inset-0 z-9999 flex items-center justify-center bg-white transition-opacity duration-300" aria-hidden="true">
    <div class="size-10 animate-spin rounded-full border-4 border-primary/25 border-t-primary"></div>
</div>

<script>
    (function () {
        var hide = function () {
            var preloader = document.querySelector('[data-preloader]');

            if (! preloader) {
                return;
            }

            preloader.classList.add('opacity-0');
            setTimeout(function () { preloader.remove(); }, 350);
        };

        if (document.readyState === 'complete') {
            hide();
        } else {
            window.addEventListener('load', hide);
        }
    })();
</script>
