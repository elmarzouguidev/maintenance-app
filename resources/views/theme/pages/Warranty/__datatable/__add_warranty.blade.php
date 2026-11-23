{{-- Add-warranty modal. No page opens it yet: its trigger is commented out in the legacy view too. --}}
<x-modal name="addWarranty" title="Ajouter une Garantie:">
    <form class="addWarrantyForm" id="addWarrantyForm" action="{{ route('admin:warranty.store') }}" method="post">
        @csrf

        <x-card flush>
            <div class="flex flex-col gap-6">
                <x-select name="ticket" label="Ticket" required>
                    <option value="espece">Choisir le ticket</option>
                    @foreach ($tickets as $ticket)
                        <option value="{{ $ticket->uuid }}">{{ $ticket->code }}</option>
                    @endforeach
                </x-select>

                <x-input name="start_at" type="date" label="Date de départ" :value="now()->format('d-m-Y')" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" append-icon="mdi mdi-calendar" />

                <x-input name="end_at" type="date" label="Date de fin" :value="now()->format('d-m-Y')" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" append-icon="mdi mdi-calendar" />

                <x-textarea name="notes" id="textarea" label="description" maxlength="225" rows="2" />
            </div>
        </x-card>

        <div class="mb-4 mt-6 flex flex-wrap justify-end gap-2">
            <x-button>{{ __('buttons.store') }}</x-button>
        </div>
    </form>
</x-modal>
