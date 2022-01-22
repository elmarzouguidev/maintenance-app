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

        'status' => ['new', 'ouvert', 'envoyer', 'annuler', 'attent-devis', 'confirme', 'encours', 'finished']
    ],

    'clients' => [

        'prefix' => 'CLT',
    ],

];
