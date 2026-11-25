{{-- Purchase order overview (K2): the kit detail pattern, articles and totals, like the invoice overview. --}}
@extends('layouts.admin')

@section('title', 'Bon de commande '.$command->full_number)

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Bon de commande '.$command->full_number" :eyebrow="optional($command->company)->name"
            :breadcrumbs="[['label' => 'Bons de commande', 'href' => route('commercial:bcommandes.index')], ['label' => $command->full_number]]">
            <x-slot:actions>
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-bc').showModal()">Imprimer</x-admin.button>
                @can('bcommandes.edit')
                    <x-admin.button href="{{ $command->edit_url }}" icon="pencil">Modifier</x-admin.button>
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section title="Bon de commande">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Fournisseur">
                        @if ($command->provider)
                            <span class="font-medium">{{ $command->provider->entreprise }}</span>
                            <span class="block text-small text-fg-muted">{{ $command->provider->addresse }}</span>
                            <span class="block text-small text-fg-muted tabular-nums">ICE {{ $command->provider->ice }}</span>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Société">
                        {{ optional($command->company)->name }}
                        <span class="block text-small text-fg-muted">{{ optional($command->company)->addresse }}</span>
                        <span class="block text-small text-fg-muted tabular-nums">ICE {{ optional($command->company)->ice }}</span>
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Date du bon"><x-admin.date :value="$command->date_command" /></x-admin.detail-item>
                </dl>
            </x-admin.detail-section>

            <x-admin.detail-section title="Articles">
                @if ($command->articles->isEmpty())
                    <p class="text-small text-fg-muted">Aucun article.</p>
                @else
                    <x-admin.table :headers="['Article', 'Qté', 'Prix unitaire HT', 'Montant HT']">
                        @foreach ($command->articles as $article)
                            <tr>
                                <td class="whitespace-normal">{{ str_replace('<br />', '', $article->designation) }}</td>
                                <td class="tabular-nums">{{ $article->quantity }}</td>
                                <td><x-admin.amount :value="$article->prix_unitaire" /></td>
                                <td><x-admin.amount :value="$article->montant_ht" /></td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                @endif
            </x-admin.detail-section>

            <x-slot:aside>
                <x-admin.stats-card title="Total HT" :value="\App\Support\UiKit\Format::money($command->price_ht)" icon="banknotes" variant="neutral" />
                <x-admin.stats-card title="TVA 20 %" :value="\App\Support\UiKit\Format::money($command->price_tva)" icon="document" variant="neutral" />
                <x-admin.stats-card title="Total TTC" :value="\App\Support\UiKit\Format::money($command->price_total)" icon="banknotes" />
            </x-slot:aside>
        </x-admin.detail-page>
    </div>

    <x-commercial.print-modal id="print-bc" :title="'Télécharger le bon de commande '.$command->full_number" route="public.show.bcommand" :document="$command->uuid" />
@endsection
