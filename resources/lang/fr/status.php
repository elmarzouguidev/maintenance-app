<?php
return [

    'notifications' => [
        'created' => 'New ticket has been opened successfully.',
        'updated' => 'Ticket has been updated successfully.',
    ],

    /*'statuses' => [
        \App\Constants\Status::TICKET_STATUS['non-traite'] => 'Non traité',
        \App\Constants\Status::TICKET_STATUS['encours-diagnostique'] => 'En cours de diagnostic',
        \App\Constants\Status::TICKET_STATUS['encours-de-reparation'] => 'En cours de réparation',
        \App\Constants\Status::TICKET_STATUS['retour-non-reparable'] => 'Retour non réparable',
        \App\Constants\Status::TICKET_STATUS['retour-devis-non-confirme'] => 'Retour Devis non réparable',
        \App\Constants\Status::TICKET_STATUS['retour-livre'] => 'Retour livré',
        \App\Constants\Status::TICKET_STATUS['en-attent-de-devis'] => 'En attente de devis',
        \App\Constants\Status::TICKET_STATUS['en-attente-de-bon-de-command'] => 'En attente de bon de command',
        \App\Constants\Status::TICKET_STATUS['devis-confirme'] => 'Devis Confirmé',
        \App\Constants\Status::TICKET_STATUS['a-reparer'] => 'à réparer',
        \App\Constants\Status::TICKET_STATUS['pret-a-etre-livre'] => 'Prêt à être livré',
        \App\Constants\Status::TICKET_STATUS['pret-a-etre-facture'] => 'Prêt à être Facturé',
    ],*/

    'statuses' => [
        \App\Constants\Status::NON_TRAITE => 'Non traité',
        \App\Constants\Status::EN_COURS_DE_DIAGNOSTIC => 'En cours de diagnostic',
        \App\Constants\Status::EN_COURS_DE_REPARATION => 'En cours de réparation',
        \App\Constants\Status::RETOUR_NON_REPARABLE => 'Retour non réparable',
        \App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME => 'Retour Devis non réparable',
        \App\Constants\Status::RETOUR_LIVRE => 'Retour livré',
        \App\Constants\Status::EN_ATTENTE_DE_DEVIS => 'En attente de devis',
        \App\Constants\Status::EN_ATTENTE_DE_BON_DE_COMMAND => 'En attente de bon de command',
        \App\Constants\Status::DEVIS_CONFIRME => 'Devis Confirmé',
        \App\Constants\Status::A_REPARER => 'à réparer',
        \App\Constants\Status::PRET_A_ETRE_LIVRE => 'Prêt à être livré',
        \App\Constants\Status::PRET_A_ETRE_FACTURE => 'Prêt à être Facturé',
    ],
];
