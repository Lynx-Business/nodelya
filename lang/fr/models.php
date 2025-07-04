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

    'media' => [
        'fields' => [

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
