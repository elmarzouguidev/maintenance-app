{{--
    Vehicle condition map (check-out / check-in): the top view with clickable zones for pointer
    users, and the same zones as labelled toggle buttons (aria-pressed) for everyone. Selected
    zones post as hidden inputs "{name}[]", unchanged.
--}}
@props([
    'name',
    'label' => '',
    'value' => [],
    'help' => 'Touchez les zones concernées pour signaler l’état du véhicule.',
    'variant' => 'checkout',
])

@php
    $selectedValues = collect(old($name, $value ?? []))
        ->filter(fn ($zone) => is_string($zone) && $zone !== '')
        ->values()
        ->all();
    $handoverLabel = $variant === 'checkout' ? 'départ' : 'retour';
    $fieldId = str($name)->slug('_')->toString();
@endphp

<fieldset x-data="vehicleConditionMapComponent(@js($selectedValues))" aria-describedby="{{ $fieldId }}-help" {{ $attributes->class('min-w-0') }}>
    @if ($label)
        <legend class="admin-form-label">{{ $label }}</legend>
    @endif
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div class="min-w-0">
            <p id="{{ $fieldId }}-help" class="admin-form-help mt-0">{{ $help }} Seuls les éléments à mentionner au procès-verbal de {{ $handoverLabel }}.</p>
        </div>
        <span class="admin-status-badge" :class="selected.length ? 'admin-status-badge-danger' : 'admin-status-badge-neutral'" role="status"
            x-text="selected.length ? `${selected.length} zone(s) signalée(s)` : 'Aucune anomalie signalée'">Aucune anomalie signalée</span>
    </div>

    <x-admin.vehicle-top-view class="mx-auto mt-4 w-full max-w-xl" />

    <ul class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3" aria-label="Zones du véhicule">
        <template x-for="zone in zones" :key="zone.key">
            <li>
                <button type="button" class="admin-zone-button" @click="toggle(zone.key)" :aria-pressed="isSelected(zone.key).toString()">
                    <span x-text="zone.label"></span>
                    <x-admin.icon name="check" class="size-4" x-show="isSelected(zone.key)" />
                </button>
            </li>
        </template>
    </ul>

    <div class="mt-3 flex justify-end" x-show="selected.length" style="display: none;">
        <x-admin.button variant="ghost" size="sm" x-on:click="clearAll()">Tout effacer</x-admin.button>
    </div>

    <template x-for="selectedZone in selected" :key="`${selectedZone}-input`">
        <input type="hidden" :name="`{{ $name }}[]`" :value="selectedZone">
    </template>

    @error($name)
        <p class="admin-form-error" role="alert"><x-admin.icon name="exclamation-circle" class="size-4" /><span>{{ $message }}</span></p>
    @enderror
    @error($name.'.*')
        <p class="admin-form-error" role="alert"><x-admin.icon name="exclamation-circle" class="size-4" /><span>{{ $message }}</span></p>
    @enderror
</fieldset>

@once
    <script>
        window.vehicleConditionMapComponent = function (initialSelections = []) {
            return {
                zones: [
                    { key: 'front', label: 'Avant' },
                    { key: 'rear', label: 'Arrière' },
                    { key: 'left_side', label: 'Côté gauche' },
                    { key: 'right_side', label: 'Côté droit' },
                    { key: 'roof', label: 'Toit / pavillon' },
                    { key: 'front_left_wheel', label: 'Roue avant gauche' },
                    { key: 'rear_left_wheel', label: 'Roue arrière gauche' },
                    { key: 'front_right_wheel', label: 'Roue avant droite' },
                    { key: 'rear_right_wheel', label: 'Roue arrière droite' },
                ],
                selected: Array.isArray(initialSelections) ? [...initialSelections] : [],
                toggle(zoneKey) {
                    if (this.selected.includes(zoneKey)) {
                        this.selected = this.selected.filter((item) => item !== zoneKey);

                        return;
                    }

                    this.selected = [...this.selected, zoneKey];
                },
                isSelected(zoneKey) {
                    return this.selected.includes(zoneKey);
                },
                clearAll() {
                    this.selected = [];
                },
                labelFor(zoneKey) {
                    return this.zones.find((zone) => zone.key === zoneKey)?.label ?? zoneKey;
                },
            };
        };
    </script>
@endonce
