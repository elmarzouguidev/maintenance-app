<?php


return [

    'cache' => [

        'use-cache' => false,

        'cache-live-time' => 30
    ],

    'api-cache' => [
        'use-cache' => false,

        'cache-live-time' => 30
    ],

    'tickets' => [

        'prefix' => 'TCK',

        'status' => ['non-traite', 'encours-diagnostique', 'retour', 'retour-livre', 'en-attent-de-devis', 'devis-confirme', 'ticket-a-preparer','encours-de-reparation', 'pre-a-livre']
    ],

    'clients' => [

        'prefix' => 'CLT',
    ],

];
