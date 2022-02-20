<?php
return [

    'notifications' => [
        'created' => 'New ticket has been opened successfully.',
        'updated' => 'Ticket has been updated successfully.',
    ],

    'statuses' => [
        \App\Constants\Status::TICKET_STATUS['non-traite'] => 'Non traité',
        \App\Constants\Status::TICKET_STATUS['encours-diagnostique'] => 'En cours de diagnostic',
        \App\Constants\Status::TICKET_STATUS['encours-de-reparation'] => 'En cours de réparation',
        \App\Constants\Status::TICKET_STATUS['retour-non-reparable'] => 'Retour non réparable',
        \App\Constants\Status::TICKET_STATUS['retour-devis-non-confirme'] => 'Retour Devis non réparable',
        \App\Constants\Status::TICKET_STATUS['retour-livre'] => 'Retour livré',
        \App\Constants\Status::TICKET_STATUS['en-attent-de-devis'] => 'En attente de devis',
        \App\Constants\Status::TICKET_STATUS['en-attent-de-bc'] => 'En attente de bon de command',
        \App\Constants\Status::TICKET_STATUS['devis-confirme'] => 'Devis Confirmé',
        \App\Constants\Status::TICKET_STATUS['a-preparer'] => 'à réparer',
        \App\Constants\Status::TICKET_STATUS['pret-a-livre'] => 'Prêt à être livré',
        \App\Constants\Status::TICKET_STATUS['pret-a-facture'] => 'Prêt à être Facturé',
    ],
];
