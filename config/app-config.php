<?php


return [

    'cache' => [

        'use-cache' => false,

        'cache-live-time' => 30,

        'clients_cache' => false
    ],

    'api-cache' => [
        'use-cache' => false,

        'cache-live-time' => 30
    ],

    'tickets' => [

        'prefix' => 'TCK',

        'status' => [
            'non-traite',
            'encours-diagnostique',
            'encours-de-reparation',
            'retour-non-reparable',
            'retour-devis-non-confirme',
            'retour-livre',
            'en-attent-de-devis',
            'devis-confirme',
            'a-preparer',
            'pret-a-livre'
        ],
        'etats' => ['non-diagnostiquer', 'reparable', 'non-reparable']
    ],

    'clients' => [

        'prefix' => 'CLT',
    ],

];
