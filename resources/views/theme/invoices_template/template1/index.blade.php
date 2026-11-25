{{--
    Invoice PDF (mPDF): public.show.invoice (?has_header=1|0), invoices.pdf.build and the e-mailed
    invoice. The company logo block is unchanged; the rest is laid out for several pages: a
    "suite" header from page 2, item headings repeated on every page, rows and totals never split,
    the amount in words, then the general conditions. Without has_header (pre-printed paper) the
    logo and the company footer stay off, as before. Tables only: mPDF has no flex or grid.
--}}
@php
    $hasHeader ??= true;
    $brand = '#1572A1';
    $client = $invoice->client;
    $hasDiscount = $invoice->articles->contains(fn ($article) => (float) $article->remise > 0);
    $columns = $hasDiscount ? 6 : 5;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Facture {{ $invoice->code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 9.5pt; color: #1f2937; line-height: 1.4; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; }
        p { margin: 0; }
        .muted { color: #6b7280; }

        /* Continuation header, from page 2 */
        .continuation td { font-size: 8pt; color: #4b5563; padding-bottom: 3pt; }
        .continuation-rule { border-bottom: 1pt solid {{ $brand }}; }

        /* Title and parties */
        .doc-type { font-size: 20pt; font-weight: bold; color: {{ $brand }}; letter-spacing: 1pt; }
        .doc-number { font-size: 11pt; font-weight: bold; color: #111827; margin-top: 2pt; }
        .facts td { font-size: 9pt; padding: 2.5pt 0; border-bottom: 0.5pt solid #e5e7eb; }
        .facts .label { color: #6b7280; width: 42%; }
        .facts .value { color: #111827; font-weight: bold; }
        .bill-to { border: 0.8pt solid #d1d5db; }
        .bill-to-title { background-color: #f3f4f6; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.6pt; color: #374151; padding: 4pt 9pt; border-bottom: 0.8pt solid #d1d5db; }
        .bill-to-body { padding: 7pt 9pt; font-size: 9.5pt; }
        .bill-to-name { font-size: 11pt; font-weight: bold; color: #111827; }

        /* Items */
        .items { margin-top: 14pt; }
        .items th { background-color: {{ $brand }}; color: #ffffff; font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.4pt; padding: 5pt 6pt; text-align: left; }
        .items td { padding: 6pt 6pt; border-bottom: 0.5pt solid #e5e7eb; font-size: 9pt; }
        .items tr.even td { background-color: #f9fafb; }
        .items .num { text-align: right; white-space: nowrap; }
        .items .center { text-align: center; }
        .items .designation { font-weight: bold; color: #111827; }
        .items .description { color: #4b5563; font-size: 8.5pt; margin-top: 2pt; }

        /* Totals */
        .summary { margin-top: 12pt; }
        .words { padding-right: 14pt; }
        .words-box td { border-left: 2.5pt solid {{ $brand }}; padding: 7pt 10pt; background-color: #f3f4f6; font-size: 9pt; color: #374151; }
        .totals td { padding: 4pt 8pt; font-size: 9.5pt; border-bottom: 0.5pt solid #e5e7eb; }
        .totals .label { color: #4b5563; }
        .totals .value { text-align: right; white-space: nowrap; color: #111827; }
        .totals tr.grand td { background-color: {{ $brand }}; color: #ffffff; font-weight: bold; font-size: 11pt; border-bottom: none; }

        .conditions { margin-top: 16pt; font-size: 8.5pt; color: #374151; }
        .conditions-title { font-size: 8.5pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5pt; color: #111827; border-bottom: 0.8pt solid #d1d5db; padding-bottom: 3pt; margin-bottom: 5pt; }
    </style>
</head>

<body>
<htmlpageheader name="invoice-continuation">
    <table class="continuation">
        <tr>
            <td style="width: 60%;"><strong style="color: #111827;">Facture N° {{ $invoice->code }}</strong> (suite) · {{ optional($client)->entreprise }}</td>
            <td style="width: 40%; text-align: right;">Date : {{ $invoice->invoice_date?->format('d/m/Y') }}</td>
        </tr>
    </table>
    <div class="continuation-rule"></div>
</htmlpageheader>
{{-- No header on page 1 (the logo is there); the continuation header from page 2 on. --}}
<sethtmlpageheader name="invoice-continuation" value="on" show-this-page="0" />
@include('theme.pdf.partials.footer', ['company' => $invoice->company, 'hasHeader' => $hasHeader])

@if ($hasHeader && filled($companyLogo))
    <div class="invoice-logo" style="margin-top: -50px; margin-bottom:50px">
        <table>
            <tr>
                <td style="text-align: center;">
                    <img src="{{ $companyLogo }}" style="width: 100%;" />
                </td>
            </tr>
        </table>
    </div>
@endif

{{-- Title, invoice facts and the customer --}}
<table>
    <tr>
        <td style="width: 50%; padding-right: 12pt;">
            <div class="doc-type">FACTURE</div>
            <div class="doc-number">N° {{ $invoice->code }}</div>
            <table class="facts" style="margin-top: 8pt;">
                <tr><td class="label">Date de facture</td><td class="value">{{ $invoice->invoice_date?->format('d/m/Y') }}</td></tr>
                @if ($invoice->due_date)
                    <tr><td class="label">Date d’échéance</td><td class="value">{{ $invoice->due_date->format('d/m/Y') }}</td></tr>
                @endif
                <tr><td class="label">Mode de règlement</td><td class="value">{{ $invoice->payment_mode ?: '—' }}</td></tr>
                @if ($invoice->bc_code)
                    <tr><td class="label">Bon de commande</td><td class="value">{{ $invoice->bc_code }}</td></tr>
                @endif
                @if ($invoice->bl_code)
                    <tr><td class="label">Bon de livraison</td><td class="value">{{ $invoice->bl_code }}</td></tr>
                @endif
            </table>
        </td>
        <td style="width: 50%;">
            <table class="bill-to">
                <tr><td class="bill-to-title">Facturé à</td></tr>
                <tr>
                    <td class="bill-to-body">
                        <div class="bill-to-name">{{ optional($client)->entreprise }}</div>
                        @if (optional($client)->addresse)
                            <p style="margin-top: 3pt;">{{ $client->addresse }}</p>
                        @endif
                        @if (optional($client)->ice)
                            <p style="margin-top: 3pt;"><span class="muted">ICE :</span> {{ $client->ice }}</p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Items: the heading repeats on every page, a row never splits --}}
<table class="items">
    <thead>
        <tr>
            <th style="width: 5%;" class="center">N°</th>
            <th>Désignation</th>
            <th style="width: 8%;" class="center">Qté</th>
            <th style="width: 15%; text-align: right;">P.U HT</th>
            @if ($hasDiscount)
                <th style="width: 9%; text-align: right;">Remise</th>
            @endif
            <th style="width: 17%; text-align: right;">Montant HT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice->articles as $article)
            <tr class="{{ $loop->even ? 'even' : '' }}" style="page-break-inside: avoid;">
                <td class="center muted">{{ $loop->iteration }}</td>
                <td>
                    <div class="designation"><strong>{!! sanitize_html($article->designation) !!}</strong></div>
                    @if (filled(strip_tags((string) $article->description)))
                        <div class="description">{!! sanitize_html($article->description) !!}</div>
                    @endif
                </td>
                <td class="center">{{ $article->quantity }}</td>
                <td class="num">{{ $article->formated_prix_unitaire }} DH</td>
                @if ($hasDiscount)
                    <td class="num">{{ (float) $article->remise > 0 ? rtrim(rtrim(number_format((float) $article->remise, 2, '.', ''), '0'), '.').' %' : '' }}</td>
                @endif
                <td class="num"><strong>{{ $article->formated_montant_ht }} DH</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Amount in words and totals, kept together --}}
<table class="summary" style="page-break-inside: avoid;">
    <tr>
        <td class="words" style="width: 55%;">
            <table class="words-box">
                <tr>
                    <td>
                        Arrêtée la présente facture à la somme de :<br />
                        <strong style="color: #111827;">{{ \Illuminate\Support\Str::ucfirst(\App\Support\AmountInWords::dirhams($invoice->price_total)) }} toutes taxes comprises.</strong>
                    </td>
                </tr>
            </table>
        </td>
        <td style="width: 45%;">
            <table class="totals">
                <tr><td class="label">Total HT</td><td class="value">{{ $invoice->formated_price_ht }} DH</td></tr>
                @if ((float) $invoice->formated_total_remise > 0)
                    <tr><td class="label">Remise</td><td class="value">{{ $invoice->formated_total_remise }} DH</td></tr>
                @endif
                <tr><td class="label">TVA 20 %</td><td class="value">{{ $invoice->formated_total_tva }} DH</td></tr>
                <tr class="grand"><td>Total TTC</td><td class="value" style="color: #ffffff;">{{ $invoice->formated_price_total }} DH</td></tr>
            </table>
        </td>
    </tr>
</table>

@if (filled(strip_tags((string) $invoice->condition_general)))
    <div class="conditions">
        <div class="conditions-title">Conditions générales</div>
        {!! sanitize_html($invoice->condition_general) !!}
    </div>
@endif

</body>
</html>
