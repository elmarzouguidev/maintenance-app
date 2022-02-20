<?php

namespace App\Constants;

class Status
{
    public const TICKET_STATUS = [
        'non-traite' => 1,
        'encours-diagnostique' => 2,
        'encours-de-reparation' => 3,
        'retour-non-reparable' => 4,
        'retour-devis-non-confirme' => 5,
        'retour-livre' => 6,
        'en-attent-de-devis' => 7,
        'en-attent-de-bc' => 8,
        'devis-confirme' => 9,
        'a-preparer' => 10,
        'pret-a-livre' => 11,
        'pret-a-facture' => 12
    ];
}
