{{--
    Custom Alpine date/time picker (datePicker() in layouts/date_picker_js.blade.php, included by the kit layouts). The submitted
    value is the hidden input, unchanged: "Y-m-d", "Y-m-d H:i" (with-time) or "H:i"
    (time-only). Keyboard: the trigger opens a non-modal dialog with focus on the selected
    day; arrows move by day/week, Home/End to the week start/end, PageUp/PageDown by month,
    Enter/Space chooses, Escape closes and returns focus to the trigger.
    native: below md, a native date/datetime-local/time input (no name) replaces the custom
    trigger and writes into the same Alpine state, so the hidden input and its format do not
    change (phone-first screens).
--}}
@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => 'Sélectionner une date',
    'minDate' => '',
    'maxDate' => '',
    'minYear' => null,
    'required' => false,
    'withTime' => false,
    'timeOnly' => false,
    'native' => false,
])

@php
    $fieldId = str($name)->replace(['[', ']', '.'], '_')->slug('_')->toString();
    $hasError = $errors->has($name);

    // Year choices: from the minimum (min date, min-year, or 10 years back) to the maximum
    // (max date, or 20 years ahead), always including the current and the selected year.
    $currentYear = (int) now()->format('Y');
    $yearOf = fn ($date): ?int => filled($date) && preg_match('/^(\d{4})-/', (string) $date, $match) ? (int) $match[1] : null;
    $selectedYear = $timeOnly ? null : $yearOf($value);
    $firstYear = min($yearOf($minDate) ?? (is_numeric($minYear) ? (int) $minYear : $currentYear - 10), $selectedYear ?? $currentYear, $currentYear);
    $lastYear = max($yearOf($maxDate) ?? $currentYear + 20, $selectedYear ?? $currentYear, $currentYear);
    $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    $nativeType = $timeOnly ? 'time' : ($withTime ? 'datetime-local' : 'date');
    $nativeBound = fn (string $date): string => $date === '' || $timeOnly ? '' : ($withTime ? $date.'T00:00' : $date);
    $dayNames = ['Lun' => 'lundi', 'Mar' => 'mardi', 'Mer' => 'mercredi', 'Jeu' => 'jeudi', 'Ven' => 'vendredi', 'Sam' => 'samedi', 'Dim' => 'dimanche'];
@endphp

<div x-data="datePicker(@js($value), @js($minDate), @js($maxDate), @js($withTime), @js($timeOnly))" x-init="init()"
    {{ $attributes->class(['relative w-full min-w-0']) }}
    @click.outside="isOpen = false"
    @keydown.escape="if (isOpen) { $event.preventDefault(); $event.stopPropagation(); close() }">

    @if ($label)
        <label for="{{ $fieldId }}-{{ $native ? 'native' : 'trigger' }}" id="{{ $fieldId }}-label" class="admin-form-label">
            {{ $label }}
            @if ($required)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="finalValue" @if ($required) required @endif>

    @if ($native)
        {{-- Phone: the device's own picker. No name: the hidden input above is what is submitted. --}}
        <input type="{{ $nativeType }}" id="{{ $fieldId }}-native" x-ref="native"
            :value="nativeValue" x-on:change="setNative($event.target.value)" x-on:input="setNative($event.target.value)"
            @if ($withTime) step="60" @endif
            @if ($nativeBound((string) $minDate) !== '') min="{{ $nativeBound((string) $minDate) }}" @endif
            @if ($nativeBound((string) $maxDate) !== '') max="{{ $nativeBound((string) $maxDate) }}" @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $fieldId }}-error" @endif
            class="admin-form-input tabular-nums md:hidden {{ $hasError ? 'error' : '' }}">
    @endif

    <div @class(['relative', 'hidden md:block' => $native])>
        <button type="button" id="{{ $fieldId }}-trigger" x-ref="trigger" @click="toggle()"
            class="admin-form-input flex items-center gap-2.5 pe-16 text-start {{ $hasError ? 'error' : '' }}"
            aria-haspopup="dialog" :aria-expanded="isOpen.toString()" aria-controls="{{ $fieldId }}-dialog"
            aria-labelledby="{{ $label ? $fieldId.'-label ' : '' }}{{ $fieldId }}-value"
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $fieldId }}-error" @endif>
            <x-admin.icon :name="$timeOnly ? 'clock' : 'calendar'" class="size-5 text-fg-muted" />
            <span id="{{ $fieldId }}-value" class="flex-1 truncate tabular-nums" :class="finalValue ? 'text-fg' : 'text-fg-muted'"
                x-text="displayLabel || @js($placeholder)">{{ $placeholder }}</span>
        </button>

        <div class="pointer-events-none absolute inset-y-0 end-0 flex items-center gap-0.5 pe-1.5">
            <button type="button" x-show="finalValue" @click="clearAll(); $refs.trigger.focus()"
                class="admin-icon-button admin-icon-button-sm pointer-events-auto" aria-label="Effacer" title="Effacer">
                <x-admin.icon name="close" class="size-4" />
            </button>
            <x-admin.icon name="chevron-down" class="size-4 text-fg-muted" />
        </div>
    </div>

    <div x-show="isOpen" id="{{ $fieldId }}-dialog" role="dialog" aria-modal="false"
        aria-label="{{ $timeOnly ? 'Choisir une heure' : 'Choisir une date' }}"
        x-transition:enter="admin-popover-enter"
        x-transition:enter-start="opacity-0 motion-safe:scale-96"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition-opacity duration-100 ease-out"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="admin-popover absolute start-0 z-50 mt-1.5 origin-top-left p-3 {{ $timeOnly ? 'w-60' : 'w-76' }}"
        style="display: none;">

        @unless ($timeOnly)
            <div class="mb-2 flex items-center justify-between gap-1">
                <button type="button" @click="prevMonth()" class="admin-icon-button admin-icon-button-sm" aria-label="Mois précédent" title="Mois précédent">
                    <x-admin.icon name="chevron-left" class="size-4" />
                </button>

                <div class="flex min-w-0 items-center gap-1">
                    <select x-model.number="currentMonth" aria-label="Mois"
                        class="h-8 rounded-control border border-line-strong bg-surface ps-2 text-small font-medium text-fg focus:outline-2 focus:-outline-offset-1 focus:outline-focus">
                        @foreach ($months as $index => $month)
                            <option value="{{ $index }}">{{ $month }}</option>
                        @endforeach
                    </select>
                    <select x-model.number="currentYear" aria-label="Année"
                        class="h-8 rounded-control border border-line-strong bg-surface ps-2 text-small font-medium tabular-nums text-fg focus:outline-2 focus:-outline-offset-1 focus:outline-focus">
                        @foreach (range($firstYear, $lastYear) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" @click="nextMonth()" class="admin-icon-button admin-icon-button-sm" aria-label="Mois suivant" title="Mois suivant">
                    <x-admin.icon name="chevron-right" class="size-4" />
                </button>
            </div>

            <p id="{{ $fieldId }}-month" class="sr-only" aria-live="polite" x-text="months[currentMonth] + ' ' + currentYear"></p>

            <table role="grid" aria-labelledby="{{ $fieldId }}-month" class="w-full border-collapse"
                @keydown="onGridKeydown($event)">
                <thead>
                    <tr>
                        @foreach ($dayNames as $short => $long)
                            <th scope="col" abbr="{{ $long }}" class="pb-1 text-center text-caption font-medium text-fg-muted">{{ $short }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(week, weekIndex) in weeks" :key="currentYear + '-' + currentMonth + '-' + weekIndex">
                        <tr>
                            <template x-for="(day, dayIndex) in week" :key="dayIndex">
                                <td class="p-0.5 text-center" :role="day ? 'gridcell' : null" :aria-selected="day ? isSelected(day).toString() : null">
                                    <template x-if="day">
                                        <button type="button" :data-day="day" @click="!isDisabled(day) && selectDay(day)"
                                            :tabindex="day === focusedDay ? 0 : -1"
                                            :aria-label="fullDateLabel(day)"
                                            :aria-current="isToday(day) ? 'date' : null"
                                            :aria-disabled="isDisabled(day) ? 'true' : null"
                                            :class="{
                                                'bg-accent font-semibold text-on-accent': isSelected(day),
                                                'font-semibold text-accent-strong ring-1 ring-inset ring-accent': isToday(day) && ! isSelected(day),
                                                'cursor-not-allowed text-fg-muted line-through opacity-60': isDisabled(day),
                                                'text-fg hover:bg-surface-muted': ! isSelected(day) && ! isDisabled(day),
                                            }"
                                            class="mx-auto flex size-9 items-center justify-center rounded-control text-body tabular-nums transition-colors duration-100 focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-focus">
                                            <span x-text="day"></span>
                                        </button>
                                    </template>
                                </td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        @endunless

        @if ($withTime || $timeOnly)
            <fieldset @class(['mt-3 border-t border-line pt-3' => ! $timeOnly])>
                <legend class="mb-2 flex items-center gap-1.5 text-caption font-medium text-fg-muted">
                    <x-admin.icon name="clock" class="size-4" />
                    Heure
                </legend>

                <div class="flex items-center justify-center gap-2">
                    @foreach (['Hour' => ['Heures', 'Heure', 0, 23], 'Minute' => ['Minutes', 'Minute', 0, 59]] as $unit => [$unitLabel, $singular, $min, $max])
                        <div class="flex flex-col items-center gap-1">
                            <button type="button" @click="increment{{ $unit }}()" class="admin-icon-button admin-icon-button-sm" aria-label="{{ $singular }} suivante">
                                <x-admin.icon name="chevron-up" class="size-4" />
                            </button>
                            <input type="text" inputmode="numeric" maxlength="2" aria-label="{{ $unitLabel }}"
                                x-model="selected{{ $unit }}"
                                @input="selected{{ $unit }} = clamp($event.target.value, {{ $min }}, {{ $max }})"
                                @keydown.arrow-up.prevent="increment{{ $unit }}()"
                                @keydown.arrow-down.prevent="decrement{{ $unit }}()"
                                class="admin-form-input h-11 w-14 px-0 text-center text-xl font-semibold tabular-nums">
                            <button type="button" @click="decrement{{ $unit }}()" class="admin-icon-button admin-icon-button-sm" aria-label="{{ $singular }} précédente">
                                <x-admin.icon name="chevron-down" class="size-4" />
                            </button>
                        </div>
                        @if ($unit === 'Hour')
                            <span class="pb-0.5 text-xl font-semibold text-fg-muted" aria-hidden="true">:</span>
                        @endif
                    @endforeach
                </div>

                <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                    <template x-for="t in quickTimes" :key="t">
                        <button type="button" @click="setQuickTime(t)"
                            :aria-pressed="(selectedHour === t.split(':')[0] && selectedMinute === t.split(':')[1]).toString()"
                            class="rounded-control border border-line px-2 py-1 text-caption font-medium tabular-nums text-fg transition-colors duration-100 hover:bg-surface-muted focus-visible:outline-2 focus-visible:outline-offset-1 focus-visible:outline-focus aria-pressed:border-accent aria-pressed:bg-accent aria-pressed:text-on-accent"
                            x-text="t">
                        </button>
                    </template>
                </div>
            </fieldset>
        @endif

        <div class="mt-3 flex items-center justify-between gap-2 border-t border-line pt-2.5">
            @unless ($timeOnly)
                <x-admin.button variant="ghost" size="sm" x-on:click="selectToday()">Aujourd'hui</x-admin.button>
            @endunless
            <span class="min-w-0 truncate text-caption text-fg-muted" x-text="displayLabel || 'Aucune sélection'"></span>
            <x-admin.button variant="ghost" size="sm" x-on:click="close()">Fermer</x-admin.button>
        </div>
    </div>

    @error($name)
        <p id="{{ $fieldId }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
