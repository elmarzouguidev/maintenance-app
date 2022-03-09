<div class="text" style="padding: 0 2.5em;">
    <p style="color: red">alert de suppression,</p>
    <p> Veuillez trouver en pièce jointe le devis <strong>{{$data->code}}</strong> .</p>
    <p> Détails de devis :</p>
    <ul>
        <li><strong>N° de devis:</strong> {{$data->code}}</li>
        <li><strong>Date:</strong> {{$data->estimate_date->format('d-m-Y')}}</li>
        <li><strong>Montant: </strong> MAD {{ $data->formated_price_total }}</li>
    </ul>
    <p>
        en cas d'urgence ce document a été envoyé par mail
    </p>
</div>
