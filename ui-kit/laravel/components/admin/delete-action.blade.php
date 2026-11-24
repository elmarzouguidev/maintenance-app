@props(['action', 'confirm', 'refusal' => null, 'label' => 'Supprimer'])

@if ($refusal)
    <span class="inline-flex" title="{{ $refusal }}">
        <x-admin.button type="button" variant="danger" size="sm" disabled aria-disabled="true" aria-describedby="{{ $refusalId = 'delete-refusal-'.md5($action) }}">{{ $label }}</x-admin.button>
        <span id="{{ $refusalId }}" class="sr-only">{{ $refusal }}</span>
    </span>
@else
    <form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($confirm));">
        @csrf
        @method('DELETE')
        <x-admin.button type="submit" variant="danger" size="sm">{{ $label }}</x-admin.button>
    </form>
@endif
