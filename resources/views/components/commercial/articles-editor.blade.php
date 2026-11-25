{{--
    Article rows of a commercial document (invoice, estimate, credit note, BC, BL), as kit fields
    with Alpine: add and remove rows, live line totals (quantity × unit price − discount %), and
    the HT / TVA 20 % / TTC totals. Replaces the jQuery repeater; the server still computes and
    stores the amounts. Posts {name}[i][designation|description|quantity|prix_unitaire|remise].

    <x-commercial.articles-editor name="articles" required />       one row to start (create)
    <x-commercial.articles-editor name="articlesnew" />              no row until "Ajouter" (edit)
    :discount="false" hides the discount column (credit notes ignore it).
--}}
@props([
    'name' => 'articles',
    'required' => false,
    'discount' => true,
])

@php
    $blankRow = ['designation' => '', 'description' => '', 'quantity' => 1, 'prix_unitaire' => '', 'remise' => 0];
    $initialRows = collect(old($name, $required ? [$blankRow] : []))
        ->map(fn ($row): array => array_merge($blankRow, array_intersect_key((array) $row, $blankRow)))
        ->values()
        ->all();
    $rowErrors = collect($errors->getMessages())
        ->filter(fn ($messages, string $key): bool => str_starts_with($key, $name.'.'))
        ->map(fn (array $messages): string => $messages[0])
        ->all();
@endphp

<div {{ $attributes->class('space-y-4') }}
    x-data="{
        rows: @js($initialRows),
        errors: @js($rowErrors),
        blank: @js($blankRow),
        add() { this.rows.push({ ...this.blank }) },
        remove(index) { this.rows.splice(index, 1) },
        lineTotal(row) {
            const gross = (parseFloat(row.quantity) || 0) * (parseFloat(String(row.prix_unitaire).replace(',', '.')) || 0);
            return gross - gross * (parseFloat(row.remise) || 0) / 100;
        },
        get totalHt() { return this.rows.reduce((sum, row) => sum + this.lineTotal(row), 0) },
        money(value) {
            return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value) + ' ' + @js(\App\Support\UiKit\Format::$currency);
        },
        error(index, field) { return this.errors[@js($name) + '.' + index + '.' + field] ?? null },
    }">
    <template x-for="(row, index) in rows" :key="index">
        <fieldset class="admin-inset space-y-4 p-4">
            <legend class="sr-only" x-text="'Article ' + (index + 1)"></legend>
            <div class="flex items-center justify-between gap-3">
                <p class="text-small font-semibold text-fg" aria-hidden="true" x-text="'Article ' + (index + 1)"></p>
                <button type="button" class="admin-icon-button admin-icon-button-sm" x-on:click="remove(index)"
                    x-show="{{ $required ? 'rows.length > 1' : 'true' }}" :aria-label="'Retirer l’article ' + (index + 1)" title="Retirer l’article">
                    <x-admin.icon name="trash" class="size-4" />
                </button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="min-w-0">
                    <label class="admin-form-label" :for="@js($name) + '-' + index + '-designation'">Désignation <span class="text-status-danger" aria-hidden="true">*</span></label>
                    <textarea class="admin-form-input min-h-20 py-2" rows="2" required
                        :id="@js($name) + '-' + index + '-designation'" :name="@js($name) + '[' + index + '][designation]'" x-model="row.designation"
                        :aria-invalid="error(index, 'designation') ? 'true' : null"></textarea>
                    <p class="admin-form-error" role="alert" x-show="error(index, 'designation')" x-cloak><span x-text="error(index, 'designation')"></span></p>
                </div>
                <div class="min-w-0">
                    <label class="admin-form-label" :for="@js($name) + '-' + index + '-description'">Description</label>
                    <textarea class="admin-form-input min-h-20 py-2" rows="2"
                        :id="@js($name) + '-' + index + '-description'" :name="@js($name) + '[' + index + '][description]'" x-model="row.description"></textarea>
                </div>
            </div>

            <div @class(['grid gap-4 sm:grid-cols-2', 'lg:grid-cols-4' => $discount, 'lg:grid-cols-3' => ! $discount])>
                <div class="min-w-0">
                    <label class="admin-form-label" :for="@js($name) + '-' + index + '-quantity'">Quantité <span class="text-status-danger" aria-hidden="true">*</span></label>
                    <input type="number" min="1" step="1" inputmode="numeric" class="admin-form-input tabular-nums" required
                        :id="@js($name) + '-' + index + '-quantity'" :name="@js($name) + '[' + index + '][quantity]'" x-model="row.quantity"
                        :aria-invalid="error(index, 'quantity') ? 'true' : null">
                    <p class="admin-form-error" role="alert" x-show="error(index, 'quantity')" x-cloak><span x-text="error(index, 'quantity')"></span></p>
                </div>
                <div class="min-w-0">
                    <label class="admin-form-label" :for="@js($name) + '-' + index + '-prix_unitaire'">Prix unitaire HT <span class="text-status-danger" aria-hidden="true">*</span></label>
                    <input type="text" inputmode="decimal" class="admin-form-input tabular-nums" required
                        :id="@js($name) + '-' + index + '-prix_unitaire'" :name="@js($name) + '[' + index + '][prix_unitaire]'" x-model="row.prix_unitaire"
                        :aria-invalid="error(index, 'prix_unitaire') ? 'true' : null">
                    <p class="admin-form-error" role="alert" x-show="error(index, 'prix_unitaire')" x-cloak><span x-text="error(index, 'prix_unitaire')"></span></p>
                </div>
                @if ($discount)
                    <div class="min-w-0">
                        <label class="admin-form-label" :for="@js($name) + '-' + index + '-remise'">Remise %</label>
                        <input type="number" min="0" max="100" step="0.01" inputmode="decimal" class="admin-form-input tabular-nums"
                            :id="@js($name) + '-' + index + '-remise'" :name="@js($name) + '[' + index + '][remise]'" x-model="row.remise"
                            :aria-invalid="error(index, 'remise') ? 'true' : null">
                        <p class="admin-form-error" role="alert" x-show="error(index, 'remise')" x-cloak><span x-text="error(index, 'remise')"></span></p>
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="admin-form-label">Montant HT</p>
                    <p class="flex h-10 items-center text-body font-semibold text-fg tabular-nums" x-text="money(lineTotal(row))"></p>
                </div>
            </div>
        </fieldset>
    </template>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <x-admin.button variant="secondary" icon="plus" x-on:click="add()">Ajouter un article</x-admin.button>

        <dl class="min-w-64 space-y-1 text-small" x-show="rows.length > 0">
            <div class="flex justify-between gap-6"><dt class="text-fg-muted">Total HT</dt><dd class="tabular-nums text-fg" x-text="money(totalHt)"></dd></div>
            <div class="flex justify-between gap-6"><dt class="text-fg-muted">TVA 20 %</dt><dd class="tabular-nums text-fg" x-text="money(totalHt * 0.2)"></dd></div>
            <div class="flex justify-between gap-6 border-t border-line pt-1"><dt class="font-semibold text-fg">Total TTC</dt><dd class="font-semibold tabular-nums text-fg" x-text="money(totalHt * 1.2)"></dd></div>
        </dl>
    </div>
</div>
