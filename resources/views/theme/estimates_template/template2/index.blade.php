<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ optional($estimate->client)->entreprise }} - {{ $estimate->estimate_date->format('d-m-Y') }}</title>
</head>

<body>

    <style>
        @import "https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700";

        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        total,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }

        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: "";
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        body {

            max-width: 900px;
            @if (!$hasHeader)margin-top: 30%;
            @else margin: auto;
            @endif
            padding: 2px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 17px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        strong {
            font-weight: 700;
        }

        #container {
            position: relative;
            padding: 1%;
        }

        #header {
            height: 80px;
        }

        #header>#reference {
            float: right;
            text-align: right;
        }

        #header>#reference h3 {
            margin: 0;
        }

        #header>#reference h4 {
            margin: 0;
            font-size: 85%;
            font-weight: 600;
        }

        #header>#reference p {
            margin: 0;
            margin-top: 2%;
            font-size: 85%;
        }

        #header>#logo {
            width: 50%;
            float: left;
        }

        #fromto {
            height: 160px;
        }

        #fromto>#from,
        #fromto>#to {
            width: 45%;
            min-height: 90px;
            margin-top: 30px;
            font-size: 85%;
            padding: 1.5%;
            line-height: 120%;
        }

        #fromto>#from {
            float: left;
            width: 45%;
            background: #efefef;
            margin-top: 30px;
            font-size: 85%;
            padding: 1.5%;
        }

        #fromto>#to {
            float: right;
            border: solid grey 1px;
        }

        #items {
            margin-top: 10px;
        }

        #items>p {
            font-weight: 700;
            text-align: right;
            margin-bottom: 1%;
            font-size: 65%;
        }

        #items>table {
            width: 100%;
            font-size: 85%;
            border: solid grey 1px;
        }

        #items>table th:first-child {
            text-align: left;
        }

        #items>table th {
            font-weight: 400;
            padding: 1px 4px;
        }

        #items>table td {
            padding: 1px 4px;
        }

        #items>table th:nth-child(2),
        #items>table th:nth-child(4) {
            width: 45px;
        }

        #items>table th:nth-child(3) {
            width: 60px;
        }

        #items>table th:nth-child(5) {
            width: 80px;
        }

        #items>table tr td:not(:first-child) {
            text-align: right;
            padding-right: 1%;
        }

        #items table td {
            border-right: solid grey 1px;
        }

        #items table tr td {
            padding-top: 3px;
            padding-bottom: 3px;
            height: 10px;
        }

        #items table tr:nth-child(1) {
            border: solid grey 1px;
        }

        #items table tr th {
            border-right: solid grey 1px;
            padding: 3px;
        }

        #items table tr:nth-child(2)>td {
            padding-top: 8px;
        }

        #summary {
            height: 170px;
            margin-top: 30px;
        }

        #summary #note {
            float: left;
        }

        #summary #note h4 {
            font-size: 10px;
            font-weight: 600;
            font-style: italic;
            margin-bottom: 4px;
        }

        #summary #note p {
            font-size: 10px;
            font-style: italic;
        }

        #summary #total table {
            font-size: 85%;
            width: 260px;
            float: right;
        }

        #summary #total table td {
            padding: 3px 4px;
        }

        #summary #total table tr td:last-child {
            text-align: right;
        }

        #summary #total table tr:nth-child(3) {
            background: #efefef;
            font-weight: 600;
        }

        #footer {
            margin: auto;
            position: absolute;
            left: 4%;
            bottom: 4%;
            right: 4%;
            border-top: solid grey 1px;
        }

        #footer p {
            margin-top: 1%;
            font-size: 65%;
            line-height: 140%;
            text-align: center;
        }

    </style>


    <div id="container">
        <div id="header">
            <div id="logo">
                <img src="{{ $companyLogo }}" style="width: 100%;" />
            </div>
            <div id="reference">
                <h3><strong>DEVIS</strong></h3>
                <h4>N° : {{ $estimate->code }}</h4>
                <p>Date : {{ $estimate->estimate_date->format('d-m-Y') }}</p>
            </div>
        </div>

        <div id="fromto">
            <div id="from">
                <p>
                    <strong>Your company</strong><br>
                    8 avenue des Champs Elysées <br>
                    75000 Paris <br><br>
                    Tél.: 01 00 00 00 00 <br>
                    Email: contact@website.com <br>
                    Web: www.website.com
                </p>
            </div>
            <div id="to">
                <p>
                    <strong>John Doe</strong><br>
                    10 rue Charles Rouxel<br>
                    77014 Paris
                </p>
            </div>
        </div>

        <div id="items">
            <p>Montants exprimés en Euros</p>
            <table>
                <tr>
                    <th>Désignation</th>
                    <th>TVA</th>
                    <th>P.U. HT</th>
                    <th>Qté</th>
                    <th>Total HT</th>
                </tr>
                <tr>
                    <td>Article</td>
                    <td>20%</td>
                    <td>3,99</td>
                    <td>1</td>
                    <td>3,99</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div id="summary">
            <div id="note">
                <h4>Note :</h4>
                <p>Information complémentaire à ajouter.</p>
            </div>
            <div id="total">
                <table border="1">
                    <tr>
                        <td>Total HT</td>
                        <td>3,99</td>
                    </tr>
                    <tr>
                        <td>Total TVA 20%</td>
                        <td>0,80</td>
                    </tr>
                    <tr>
                        <td>Total TTC</td>
                        <td>4,79</td>
                    </tr>
                </table>
            </div>
        </div>

        <div id="footer">
            <p>Société à responsabilité limité (SARL) - Capital de 1 000 000 € - SIRET: 87564738493127 <br>
                NAF-APE: 6202A - Num. TVA: FR28987856541</p>
        </div>
    </div>

</body>

</html>
