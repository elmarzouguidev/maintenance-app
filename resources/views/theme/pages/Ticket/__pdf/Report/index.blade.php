<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $ticket->code}} - {{ $ticket->article}}</title>
    <style>

        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #777;
        }

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 5px;
            margin-bottom: 5px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 2px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 20px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 1px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: center;
        }

        .invoice-box table tr td:nth-child(3) {
            text-align: center;
        }

        .invoice-box table tr td:nth-child(4) {
            text-align: center;
        }

        .invoice-box table tr.heading td {
            background: rgba(85, 110, 230, .25) !important;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.heading-price td {
            background: #eee;
            /*border-bottom: 2px solid #325288;*/
            font-weight: bold;
            text-align: right;
        }

        .invoice-box table tr.details td {
            padding-bottom: 5px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .invoice-box table tr.total td:nth-child(3) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .report-section {
            margin-top: 14px;
        }

        .report-images tr,
        .report-feature-image tr {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>
@include('theme.pdf.partials.footer', ['company' => null, 'hasHeader' => false])
<div class="invoice-box">
    <h1>Rapport Complet du Ticket : {{ $ticket->code }}</h1>
    <p>{{ $ticket->article }}</p>

    <table class="report-details">
        <tbody>
            <tr class="heading"><td>Technicien : {{ optional($ticket->technicien)->full_name }}</td></tr>
            <tr class="heading"><td>La date d'entrée : {{ $ticket->created_at->format('d-m-Y') }}</td></tr>
            @if ($ticket->started_at)
                <tr class="heading"><td>La date de départ de diagnostique : {{ $ticket->started_at->format('d-m-Y') }}</td></tr>
            @endif
            @if ($ticket->finished_at)
                <tr class="heading"><td>La date de finalisation de diagnostique : {{ $ticket->finished_at->format('d-m-Y') }}</td></tr>
            @endif
            @if ($ticket->delivery_count)
                <tr class="heading"><td>La date de sortie : {{ optional($ticket->delivery)->date_end->format('d-m-Y') }}</td></tr>
            @endif
        </tbody>
    </table>

    <table class="report-feature-image">
        <tbody>
            <tr><td style="text-align:center"><p>Figure : 1</p><img src="{{ $data['firstImage'] }}" style="width:70%" /></td></tr>
        </tbody>
    </table>

    <div class="report-section">
        <h5 style="text-align:center;color:red">Rapport de réparation</h5>
        {!! optional($ticket->reparationReports)->content !!}
    </div>

    <div class="report-section">
        <h5 style="text-align:center;color:red">Rapport de diagnostique</h5>
        {!! optional($ticket->diagnoseReports)->content !!}
    </div>

    <table class="report-images">
        <tbody>
            @foreach ($data['allImages'] as $index => $img)
                <tr><td style="text-align:center"><p>Figure : {{ $index + 1 }}</p><img src="{{ $img }}" style="width:70%" /></td></tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>

</html>
