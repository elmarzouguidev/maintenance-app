@props(['tickets', 'tabs'])

<div class="tab-content diagnostic-stage-content">
    @foreach ($tabs as $tab)
        <section class="tab-pane fade {{ $tab['active'] ? 'show active' : '' }}"
            id="{{ $tab['id'] }}" role="tabpanel" aria-labelledby="{{ $tab['id'] }}-tab" tabindex="0">
            <header class="diagnostic-stage-heading">
                <div>
                    <span>{{ $tab['group'] }}</span>
                    <h2>{{ $tab['label'] }}</h2>
                </div>
                <span class="diagnostic-stage-heading-count">
                    {{ count(data_get($tickets, $tab['key'], [])) }} dossier(s)
                </span>
            </header>
            <div class="diagnostic-table-wrap">
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
