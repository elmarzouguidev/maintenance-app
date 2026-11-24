@php
    $hasMessages = session()->has('success') || session()->has('status') || session()->has('error') || session()->has('warning');
@endphp

@if ($hasMessages)
    <div class="px-6 pt-5">
        <div class="space-y-3">
            @if (session('success'))
                <x-admin.alert type="success" :message="session('success')" />
            @endif

            @if (session('status'))
                <x-admin.alert type="success" :message="session('status')" />
            @endif

            @if (session('error'))
                <x-admin.alert type="error" :message="session('error')" />
            @endif

            @if (session('warning'))
                <x-admin.alert type="warning" :message="session('warning')" />
            @endif
        </div>
    </div>
@endif
