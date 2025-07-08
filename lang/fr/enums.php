<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enums Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain all keys related to particular
    | enums.
    |
    */

    'deal' => [
        'status' => [
            'created'   => 'créée',
            'validated' => 'validée',
            'finished'  => 'terminée',
        ],

        'schedule_status' => [
            'paid'              => 'Payé',
            'invoiced'          => 'Facture émise',
            'uncertain'         => 'Incertain',
            'pending_invoicing' => 'En attente de facturation',
        ],
    ],

    'expense' => [
        'type' => [
            'general'    => 'budgets généraux',
            'employee'   => 'ressources humaines',
            'contractor' => 'sous traitance',
        ],
    ],

    'permission' => [
        'name' => [
            'client'   => 'clients',
            'expenses' => 'dépenses',
            'deal'     => 'Commerces/Facturations',
        ],
    ],

    'role' => [
        'name' => [
            'tester' => 'testeur',
            'owner'  => 'propriétaire',
            'member' => 'membre',
            'editor' => 'éditeur',
        ],
    ],

    'trashed' => [
        'filter' => [
            'with' => 'avec archivés',
            'only' => 'uniquement archivés',
        ],
    ],

];
