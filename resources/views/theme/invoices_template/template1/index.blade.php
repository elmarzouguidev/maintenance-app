{{--
    Invoice PDF (mPDF): public.show.invoice (?has_header=1|0), invoices.pdf.build and the e-mailed
    invoice. The company logo block is unchanged (full page width, as before); everything below it
    sits in an indented sheet with room between blocks. Several pages: a "suite" header from page
    2, item headings repeated on every page, rows and totals never split. Without has_header
    (pre-printed paper) the logo and the company footer stay off. Tables only: mPDF has no flex or
    grid, and draws no border or background on a div inside a table cell.
--}}
@php
    $hasHeader ??= true;
    $brand = '#1572A1';
    $client = $invoice->client;
    $hasDiscount = $invoice->articles->contains(fn ($article) => (float) $article->remise > 0);
    $hasRemise = (float) str_replace(',', '', (string) $invoice->formated_total_remise) > 0;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Facture {{ $invoice->code }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #1f2937; line-height: 1.5; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; }
        p { margin: 0; }

        /* Everything below the logo: indented from the page edges (the logo keeps its width). */
        .sheet { margin: 0 9mm; }

        /* One label style, one value style */
        .label { font-size: 7.5pt; text-transform: uppercase; letter-spacing: 0.8pt; color: #6b7280; }
        .value { font-size: 10pt; color: #111827; }

        /* Continuation header, from page 2 */
        .continuation td { font-size: 8.5pt; color: #6b7280; padding-bottom: 4pt; }
        .continuation-rule { border-bottom: 0.8pt solid #d1d5db; }

        /* Title */
        .doc-type { font-size: 22pt; font-weight: bold; color: {{ $brand }}; letter-spacing: 2pt; }
        .doc-number { font-size: 12pt; color: #111827; margin-top: 3pt; }
        .dates td { padding: 2pt 0 2pt 18pt; text-align: right; }

        /* The two boxes: invoice details and customer */
        .box-title { background-color: #f3f4f6; padding: 5pt 12pt; border: 0.8pt solid #d1d5db; }
        .box-body { padding: 8pt 12pt; border: 0.8pt solid #d1d5db; border-top: none; }
        .box-row td { padding: 2.5pt 0; }
        .customer-name { font-size: 12pt; font-weight: bold; color: #111827; }
        .customer-line { color: #374151; margin-top: 3pt; }

        /* Items */
        .items th { background-color: {{ $brand }}; color: #ffffff; font-size: 8pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8pt; padding: 6pt 8pt; text-align: left; }
        .items td { padding: 6.5pt 8pt; border-bottom: 0.6pt solid #e5e7eb; font-size: 9.5pt; color: #1f2937; }
        .items .num { text-align: right; white-space: nowrap; }
        .items .center { text-align: center; }
        .items .index { color: #9ca3af; }
        .items .description { color: #6b7280; font-size: 8.5pt; margin-top: 3pt; }

        /* Totals */
        .totals td { padding: 5pt 12pt; font-size: 10pt; border-bottom: 0.6pt solid #e5e7eb; }
        .totals .amount { text-align: right; white-space: nowrap; color: #111827; }
        .totals tr.grand td { background-color: {{ $brand }}; color: #ffffff; font-weight: bold; font-size: 11.5pt; border-bottom: none; padding-top: 7pt; padding-bottom: 7pt; }
        .totals tr.grand .grand-label { font-size: 8.5pt; text-transform: uppercase; letter-spacing: 0.8pt; }
        .words td { border-top: 0.8pt solid #d1d5db; border-bottom: 0.8pt solid #d1d5db; padding: 8pt 0; }
        .words-text { font-size: 10pt; color: #111827; margin-top: 3pt; }

        .conditions-text { font-size: 9pt; color: #374151; margin-top: 5pt; }
    </style>
</head>

<body>
<htmlpageheader name="invoice-continuation">
    <div class="sheet">
        <table class="continuation">
            <tr>
                <td style="width: 60%;"><span style="color: #111827;">Facture N° {{ $invoice->code }}</span> — suite · {{ optional($client)->entreprise }}</td>
                <td style="width: 40%; text-align: right;">{{ $invoice->invoice_date?->format('d/m/Y') }}</td>
            </tr>
        </table>
        <div class="continuation-rule"></div>
    </div>
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

<div class="sheet">
    {{-- Title and dates --}}
    <table>
        <tr>
            <td style="width: 55%;">
                <div class="doc-type">FACTURE</div>
                <div class="doc-number">N° {{ $invoice->code }}</div>
            </td>
            <td style="width: 45%;">
                <table class="dates">
                    <tr>
                        <td><div class="label">Date de facture</div><div class="value">{{ $invoice->invoice_date?->format('d/m/Y') }}</div></td>
                        @if ($invoice->due_date)
                            <td><div class="label">Échéance</div><div class="value">{{ $invoice->due_date->format('d/m/Y') }}</div></td>
                        @endif
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Invoice details | customer --}}
    <table style="margin-top: 14pt;">
        <tr>
            <td style="width: 48%;">
                <table>
                    <tr><td class="box-title"><span class="label">Détails</span></td></tr>
                    <tr>
                        <td class="box-body">
                            <table>
                                <tr class="box-row"><td class="label" style="width: 46%;">Mode de règlement</td><td class="value">{{ $invoice->payment_mode ?: '—' }}</td></tr>
                                <tr class="box-row"><td class="label">Bon de commande</td><td class="value">{{ $invoice->bc_code ?: '—' }}</td></tr>
                                <tr class="box-row"><td class="label">Bon de livraison</td><td class="value">{{ $invoice->bl_code ?: '—' }}</td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%;">
                <table>
                    <tr><td class="box-title"><span class="label">Facturé à</span></td></tr>
                    <tr>
                        <td class="box-body">
                            <div class="customer-name">{{ optional($client)->entreprise }}</div>
                            @if (optional($client)->addresse)
                                <div class="customer-line">{{ $client->addresse }}</div>
                            @endif
                            @if (optional($client)->ice)
                                <div class="customer-line"><span class="label">ICE</span>&nbsp; {{ $client->ice }}</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Items: the heading repeats on every page, a row never splits --}}
    <table class="items" style="margin-top: 16pt;">
        <thead>
            <tr>
                <th style="width: 6%;" class="center">N°</th>
                <th>Désignation</th>
                <th style="width: 8%;" class="center">Qté</th>
                <th style="width: 16%; text-align: right;">P.U HT</th>
                @if ($hasDiscount)
                    <th style="width: 9%; text-align: right;">Remise</th>
                @endif
                <th style="width: 17%; text-align: right;">Montant HT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->articles as $article)
                <tr style="page-break-inside: avoid;">
                    <td class="center index">{{ $loop->iteration }}</td>
                    <td>
                        {!! sanitize_html($article->designation) !!}
                        @if (filled(strip_tags((string) $article->description)))
                            <div class="description">{!! sanitize_html($article->description) !!}</div>
                        @endif
                    </td>
                    <td class="center">{{ $article->quantity }}</td>
                    <td class="num">{{ $article->formated_prix_unitaire }} DH</td>
                    @if ($hasDiscount)
                        <td class="num">{{ (float) $article->remise > 0 ? rtrim(rtrim(number_format((float) $article->remise, 2, '.', ''), '0'), '.').' %' : '—' }}</td>
                    @endif
                    <td class="num">{{ $article->formated_montant_ht }} DH</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals on the right, then the amount in words on its own line; kept together --}}
    <div style="page-break-inside: avoid;">
        <table style="margin-top: 12pt;">
            <tr>
                <td style="width: 52%;"></td>
                <td style="width: 48%;">
                    <table class="totals">
                        <tr><td class="label">Total HT</td><td class="amount">{{ $invoice->formated_price_ht }} DH</td></tr>
                        @if ($hasRemise)
                            <tr><td class="label">Remise</td><td class="amount">{{ $invoice->formated_total_remise }} DH</td></tr>
                        @endif
                        <tr><td class="label">TVA 20 %</td><td class="amount">{{ $invoice->formated_total_tva }} DH</td></tr>
                        <tr class="grand"><td class="grand-label">Total TTC</td><td class="amount" style="color: #ffffff;">{{ $invoice->formated_price_total }} DH</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="words" style="margin-top: 14pt;">
            <tr>
                <td>
                    <div class="label">Arrêtée la présente facture à la somme de</div>
                    <div class="words-text">{{ \Illuminate\Support\Str::ucfirst(\App\Support\AmountInWords::dirhams($invoice->price_total)) }} toutes taxes comprises.</div>
                </td>
            </tr>
        </table>
    </div>

    @if (filled(strip_tags((string) $invoice->condition_general)))
        <div style="margin-top: 14pt;">
            <div class="label">Conditions générales</div>
            <div class="conditions-text">{!! sanitize_html($invoice->condition_general) !!}</div>
        </div>
    @endif
</div>

</body>
</html>
