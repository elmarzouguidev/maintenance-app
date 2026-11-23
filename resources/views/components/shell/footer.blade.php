{{-- Footer of the Tailwind layout. Mirrors theme/layouts/_parts/_footer (Skote .footer). --}}
<footer class="flex min-h-[60px] items-center bg-footer px-6 py-5 text-muted">
    <div class="grid w-full grid-cols-1 gap-2 sm:grid-cols-2">
        <div>
            {{ now()->year }} © <a href="https://casamaintenance.ma" target="_blank" rel="noopener">CASA MAINTENANCE</a>
            <b class="ms-2 text-[#0000ff]">V2.2.0 - 18-08-2025</b>
        </div>
        <div class="hidden text-end sm:block">
            Product by
            <a href="https://wedoapp.ma" target="_blank" rel="noopener" class="text-14 font-medium text-[#0000ff]">WEDO APP</a>
        </div>
    </div>
</footer>
