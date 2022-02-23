<div class="text" style="padding: 0 2.5em;">
    <p>Cher client,</p><br>
    <p> Veuillez trouver en pièce jointe le devis {{$data->code}} .</p>
    <p> Détails de devis :</p>
    <p><strong>N° de devis:</strong> {{$data->code}}</p>
    <p><strong>Date:</strong> {{$data->estimate_date->format('d-m-Y')}}</p>
    <p><strong> Montant: </strong> MAD {{ $data->formated_price_total }}</p>
    <p>
        Si vous n'avez toujours pas communiqué votre ICE, merci de nous l'envoyer en
        réponse à ce mail ou à l'adresse contact@casamaintenance.ma.

        Notre service reste à votre disposition pour toute demande.
    </p>
</div>
