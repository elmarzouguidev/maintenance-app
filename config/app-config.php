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

    'ticket.status' =>  ['new', 'ouvert', 'envoyer', 'annuler', 'attent-devis', 'confirme', 'encours-reparation', 'finalizer-reparation']
];
