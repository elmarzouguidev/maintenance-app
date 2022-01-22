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

        'status' => ['non-traite', 'encours-diagnostique', 'retour', 'retour-livre', 'en-attent-de-devis', 'devis-confirme', 'a-preparer', 'encours-de-reparation', 'pre-a-livre'],
        'etats' => ['non-diagnostiquer', 'reparable', 'non-reparable']
    ],

    'clients' => [

        'prefix' => 'CLT',
    ],

];
