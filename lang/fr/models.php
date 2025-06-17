<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain all keys related the the
    | application's various models.
    |
    */

    'accounting_period' => [
        'name' => [
            'one'  => 'année fiscale',
            'many' => 'années fiscales',
        ],
        'fields' => [
            'label'     => 'période',
            'starts_at' => 'date de debut',
            'ends_at'   => 'date de fin',
        ],
    ],

    'banner' => [
        'name' => [
            'one'  => 'bannière',
            'many' => 'bannières',
        ],
        'fields' => [
            'action'     => 'action',
            'is_enabled' => 'active',
            'message'    => 'message',
            'name'       => 'nom',
        ],
    ],

    'expense' => [
        'category' => [
            'name' => [
                'one'  => 'catégorie de dépenses',
                'many' => 'catégories de dépenses',
            ],
            'fields' => [
                'deleted_at' => 'archivée le',
                'is_trashed' => 'est archivée',
                'type'       => 'type',
                'name'       => 'nom',
            ],
        ],
    ],
    'client' => [
        'name' => [
            'one'  => 'client',
            'many' => 'clients',
        ],
        'fields' => [

            'name' => 'nom',

        ],
    ],

    'address' => [
        'name' => [
            'one'  => 'adresse',
            'many' => 'adresses',
        ],
        'fields' => [
            'address'            => 'adresse',
            'address_complement' => 'complément d\'adresse',
            'city'               => 'ville',
            'state'              => 'état',
            'postal_code'        => 'code postal',
            'country'            => 'pays',
        ],
    ],

    'media' => [
        'fields' => [
        ],
    ],

    'project_department' => [
        'name' => [
            'one'  => 'direction de projets',
            'many' => 'directions de projets',
        ],
        'fields' => [
            'deleted_at' => 'archivée le',
            'is_trashed' => 'est archivée',
            'name'       => 'nom',
        ],
    ],

    'permission' => [
        'name' => [
            'one'  => 'permission',
            'many' => 'permissions',
        ],
        'fields' => [
            'name' => 'nom',
        ],
    ],

    'role' => [
        'name' => [
            'one'  => 'rôle',
            'many' => 'rôles',
        ],
        'fields' => [
            'name' => 'nom',
        ],
    ],

    'team' => [
        'name' => [
            'one'  => 'société',
            'many' => 'sociétés',
        ],
        'fields' => [
            'deleted_at' => 'archivée le',
            'is_trashed' => 'est archivée',
            'name'       => 'nom',
        ],
    ],

    'user' => [
        'name' => [
            'one'  => 'utilisateur',
            'many' => 'utilisateurs',
        ],
        'fields' => [
            'avatar'                => 'avatar',
            'current_password'      => 'mot de passe actuel',
            'deleted_at'            => 'archivé le',
            'email'                 => 'e-mail',
            'first_name'            => 'prénom',
            'full_name'             => 'nom complet',
            'is_trashed'            => 'est archivé',
            'last_name'             => 'nom de famille',
            'owner'                 => 'propriétaire',
            'password'              => 'mot de passe',
            'password_confirmation' => 'confirmer le mot de passe',
            'phone'                 => 'téléphone',
        ],
        'member' => [
            'name' => [
                'one'  => 'membre',
                'many' => 'membres',
            ],
            'fields' => [
            ],
        ],
        'default' => [
            'full_name' => 'Utilisateur supprimé',
        ],
    ],

];
