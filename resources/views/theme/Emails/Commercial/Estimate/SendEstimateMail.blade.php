<div class="text" style="padding: 0 2.5em;">

    <h3>DEVIS N° : {{$data->code}}</h3>

    <p>Cher client,</p><br>

    <p> Veuillez trouver en pièce jointe le devis {{$data->code}} .</p>

    <p> Détails de devis :</p><br>
    <p><strong>N° de devis:</strong> {{$data->code}}</p><br>
    <p><strong>Date:</strong> {{$data->estimate_date->format('d-m-Y')}}</p><br>
    <p><strong> Montant: </strong> MAD {{ $data->formated_price_total }}</p><br>

    <p>Si vous n'avez toujours pas communiqué votre ICE, merci de nous l'envoyer en
        réponse à ce mail ou à l'adresse contact@casamaintenance.ma.

        Notre service reste à votre disposition pour toute demande.</p>
</div>
