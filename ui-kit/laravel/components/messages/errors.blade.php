{{-- Validation summary shown above forms. --}}
@if ($errors->any())
    <div role="alert" class="admin-alert admin-alert-error mb-6">
        <x-admin.icon name="x-circle" class="mt-px size-5" />
        <div class="min-w-0 flex-1">
            <h3 class="font-semibold">Des erreurs sont survenues.</h3>
            <ul class="mt-2 list-disc space-y-1 ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
