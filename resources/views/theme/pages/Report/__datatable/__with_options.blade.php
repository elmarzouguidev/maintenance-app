<x-card>
    <x-flash-messages />

    <x-table light-head nowrap flush>
        <x-slot:head>
            <tr>
                <th class="w-5">
                    <x-checkbox class="text-16" aria-label="Sélectionner" />
                </th>
                <th>position</th>
                <th>client</th>
                <th>chiffre d'affaire Total</th>
                <th>chiffre d'affaire encaissé</th>
                <th>action</th>
            </tr>
        </x-slot:head>

        @foreach ($clients as $client)
            <tr>
                <td>
                    <x-checkbox class="text-16" aria-label="Sélectionner {{ $client->entreprise }}" />
                </td>
                <td><a href="{{ $client->url }}" class="font-bold text-body">{{ $loop->index + 1 }}</a></td>
                <td>{{ $client->entreprise }}</td>
                <td>{{ number_format($client->invoices_sum_price_total, 2) }}</td>
                <td>{{ number_format($client->price_total_paid, 2) }}</td>
                <td>
                    <x-button :href="$client->url" size="sm" pill>Détails</x-button>
                </td>
            </tr>
        @endforeach
    </x-table>
</x-card>
