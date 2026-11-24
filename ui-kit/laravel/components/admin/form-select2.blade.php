{{--
    Searchable select (adminSelect2 in ui-kit/laravel/js/select2.js). The submitted value is the
    hidden input, as before. Keyboard: the search field is a combobox; arrows move through
    the listbox, Enter chooses, Escape closes and returns focus to the trigger.
--}}
@props([
    'label',
    'name',
    'options' => [],
    'value' => '',
    'required' => false,
    'placeholder' => 'Sélectionner une option',
    'searchPlaceholder' => 'Rechercher…',
    'emptyText' => 'Aucun résultat',
])

@php
    $selectedValue = old($name, $value);
    $fieldId = str($name)->replace(['[', ']', '.'], '_')->slug('_')->toString();
    $hasError = $errors->has($name);
@endphp

<div
    x-data="adminSelect2({
        name: @js($name),
        options: @js(collect($options)->map(fn ($optionLabel, $optionValue) => [
            'value' => (string) $optionValue,
            'label' => (string) $optionLabel,
        ])->values()->all()),
        selectedValue: @js($selectedValue !== null ? (string) $selectedValue : ''),
        placeholder: @js($placeholder),
        searchPlaceholder: @js($searchPlaceholder),
        emptyText: @js($emptyText),
    })"
    @click.outside="close()"
    @keydown.escape="if (open) { $event.preventDefault(); $event.stopPropagation(); close(); $refs.trigger.focus() }"
    {{ $attributes->class(['min-w-0']) }}
>
    @if (isset($label))
        <label for="{{ $fieldId }}-trigger" id="{{ $fieldId }}-label" class="admin-form-label">
            {{ $label }}
            @if ($required)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="selectedValue" @if ($required) required @endif>

    <div class="relative">
        <button
            type="button"
            id="{{ $fieldId }}-trigger"
            x-ref="trigger"
            class="admin-form-input admin-select2-trigger {{ $hasError ? 'error' : '' }}"
            @click="toggle()"
            aria-haspopup="listbox"
            :aria-expanded="open.toString()"
            aria-controls="{{ $fieldId }}-listbox"
            aria-labelledby="{{ $fieldId }}-label {{ $fieldId }}-value"
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $fieldId }}-error" @endif
        >
            <span id="{{ $fieldId }}-value" class="truncate" :class="selectedLabel ? 'text-fg' : 'text-fg-muted'" x-text="selectedLabel || placeholder">{{ $placeholder }}</span>
            <x-admin.icon name="chevron-up-down" class="size-4 text-fg-muted" />
        </button>

        <div
            x-show="open"
            x-transition:enter="admin-popover-enter"
            x-transition:enter-start="opacity-0 motion-safe:scale-96"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition-opacity duration-100 ease-out"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="admin-select2-dropdown origin-top"
            style="display: none;"
        >
            <div class="border-b border-line p-2">
                <input
                    type="text"
                    x-model="search"
                    x-ref="searchInput"
                    class="admin-form-input"
                    role="combobox"
                    aria-autocomplete="list"
                    aria-expanded="true"
                    aria-controls="{{ $fieldId }}-listbox"
                    :aria-activedescendant="highlighted >= 0 ? @js($fieldId.'-option-') + highlighted : null"
                    :aria-label="searchPlaceholder"
                    :placeholder="searchPlaceholder"
                    autocomplete="off"
                    @input="highlighted = 0"
                    @keydown.arrow-down.prevent="move(1)"
                    @keydown.arrow-up.prevent="move(-1)"
                    @keydown.home.prevent="highlighted = 0"
                    @keydown.end.prevent="highlighted = entries.length - 1"
                    @keydown.enter.prevent="chooseHighlighted()"
                >
            </div>

            <ul id="{{ $fieldId }}-listbox" role="listbox" aria-labelledby="{{ $fieldId }}-label" class="max-h-64 overflow-y-auto overscroll-contain p-1.5">
                <template x-for="(entry, index) in entries" :key="entry.value === '' ? '__none' : entry.value">
                    <li
                        :id="@js($fieldId.'-option-') + index"
                        role="option"
                        :aria-selected="(entry.value === selectedValue).toString()"
                        class="admin-select2-option"
                        :class="{ 'active': entry.value === selectedValue, 'is-highlighted': index === highlighted, 'text-fg-muted': entry.value === '' }"
                        @mousemove="highlighted = index"
                        @click="select(entry.value, true); $refs.trigger.focus()"
                    >
                        <span class="truncate" x-text="entry.label"></span>
                        <x-admin.icon name="check" class="size-4" x-show="entry.value === selectedValue" />
                    </li>
                </template>
            </ul>

            <p x-show="filteredOptions.length === 0" class="px-3 pb-3 text-body text-fg-muted" x-text="emptyText" role="status"></p>
        </div>
    </div>

    @error($name)
        <p id="{{ $fieldId }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
