@props(['tickets', 'tabs'])

<div class="tab-content pt-4">
    @foreach ($tabs as $tab)
        @php
            $stageCount = count(data_get($tickets, $tab['key'], []));
        @endphp
        <section class="tab-pane fade {{ $tab['active'] ? 'show active' : '' }}"
            id="{{ $tab['id'] }}" role="tabpanel" aria-labelledby="{{ $tab['id'] }}-tab" tabindex="0">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <span class="text-muted font-size-12 text-uppercase">{{ $tab['group'] }}</span>
                    <h5 class="mb-0">{{ $tab['label'] }}</h5>
                </div>
                <span class="text-muted">{{ $stageCount }} dossier(s)</span>
            </div>
            <div class="table-responsive">
                <x-diagnostic.admin.ticket-table
                    :tickets="$tickets"
                    :ticket-key="$tab['key']"
                    :show-action-button="$tab['showActionButton']"
                    :action-text="$tab['actionText']"
                    :action-class="$tab['actionClass']" />
            </div>
        </section>
    @endforeach
</div>
