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
            'starts_at' => 'debut',
            'ends_at'   => 'fin',
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

    'contractor' => [
        'name' => [
            'one'  => 'sous-traitant',
            'many' => 'sous-traitants',
        ],
        'fields' => [
            'deleted_at' => 'archivé le',
            'email'      => 'e-mail',
            'first_name' => 'prénom',
            'full_name'  => 'nom complet',
            'is_trashed' => 'est archivé',
            'last_name'  => 'nom',
            'phone'      => 'téléphone',
        ],
    ],

    'employee' => [
        'name' => [
            'one'  => 'salarié',
            'many' => 'salariés',
        ],
        'fields' => [
            'deleted_at' => 'archivé le',
            'email'      => 'e-mail',
            'ends_at'    => 'fin',
            'first_name' => 'prénom',
            'full_name'  => 'nom complet',
            'is_trashed' => 'est archivé',
            'last_name'  => 'nom',
            'phone'      => 'téléphone',
            'starts_at'  => 'debut',
        ],
    ],

    'expense' => [
        'budget' => [
            'name' => [
                'one'  => 'budget',
                'many' => 'budgets',
            ],
            'fields' => [
                'amount'       => 'montant annuel',
                'deleted_at'   => 'archivé le',
                'ends_at'      => 'fin',
                'expense_item' => 'poste',
                'is_trashed'   => 'est archivé',
                'starts_at'    => 'début',
                'type'         => 'type',
            ],
        ],
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
        'charge' => [
            'name' => [
                'one'  => 'frais ponctuel',
                'many' => 'frais ponctuels',
            ],
            'fields' => [
                'amount'       => 'montant',
                'charged_at'   => 'date',
                'deleted_at'   => 'archivé le',
                'expense_item' => 'poste',
                'is_trashed'   => 'est archivé',
                'type'         => 'type',
            ],
        ],
        'item' => [
            'name' => [
                'one'  => 'poste de dépenses',
                'many' => 'postes de dépenses',
            ],
            'fields' => [
                'deleted_at'           => 'archivée le',
                'is_trashed'           => 'est archivée',
                'expense_category'     => 'catégorie',
                'expense_sub_category' => 'sous-catégorie',
                'name'                 => 'nom',
            ],
        ],
        'sub_category' => [
            'name' => [
                'one'  => 'sous-catégorie de dépenses',
                'many' => 'sous-catégories de dépenses',
            ],
            'fields' => [
                'deleted_at'       => 'archivée le',
                'is_trashed'       => 'est archivée',
                'expense_category' => 'catégorie',
                'name'             => 'nom',
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

    'deal' => [
        'commercial' => [
            'name' => [
                'one'  => 'affaire',
                'many' => 'affaires',
            ],
            'fields' => [
                'amount'                => 'montant',
                'code'                  => 'Codification',
                'client_id'             => 'client',
                'duration_in_months'    => 'Durée (MOIS)',
                'ends_at'               => 'date de fin',
                'name'                  => 'nom',
                'ordered_at'            => 'Prise de commande',
                'project_department_id' => 'direction de projets',
                'reference'             => 'référence',
                'reference_commande'    => 'Référence de commande',
                'schedule'              => 'planning',
                'starts_at'             => 'Démarrage',
                'status'                => 'statut',
                'success_rate'          => '% Réussite',
                'team_id'               => 'société',
                'deal_id'               => 'Rattacher affaire existante',
                'total_sales'           => 'Totaux CA',
                'targeted_turnover'     => 'CA ciblé',
                'schedule_data'         => [
                    'date'   => 'Date',
                    'amount' => 'Montant',
                ],
            ],
        ],
        'billing' => [
            'name' => [
                'one'  => 'facture',
                'many' => 'factures',
            ],
            'fields' => [
                'amount'                => 'montant',
                'code'                  => 'Codification',
                'client_id'             => 'client',
                'duration_in_months'    => 'Durée (MOIS)',
                'ends_at'               => 'date de fin',
                'name'                  => 'nom',
                'ordered_at'            => 'Prise de commande',
                'project_department_id' => 'direction de projets',
                'reference'             => 'référence',
                'schedule'              => 'planning',
                'starts_at'             => 'Démarrage',
                'status'                => 'statut',
                'success_rate'          => '% Réussite',
                'team_id'               => 'société',
                'deal_id'               => 'Rattacher affaire existante',
                'total_sales'           => 'Totaux CA',
                'schedule_data'         => [
                    'title'  => 'titre de l\'échéance',
                    'date'   => 'Date',
                    'amount' => 'Montant',
                    'status' => 'Statut',
                ],
            ],
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
            'logo'       => 'logo',
            'settings'   => 'paramètres',
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
            'last_name'             => 'nom',
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

    'comment' => [
        'name' => [
            'one'  => 'commentaire',
            'many' => 'commentaires',
        ],
        'fields' => [
            'id'         => 'ID',
            'message'    => 'message',
            'model_type' => 'type de modèle',
            'model_id'   => 'ID du modèle',
            'creator_id' => 'créateur',
            'created_at' => 'créé le',
            'updated_at' => 'modifié le',
            'is_edited'  => 'modifié',
        ],
    ],

    'flow' => [
        'name'     => 'Contrôle de flux',
        'category' => [
            'name' => [
                'one'  => 'catégorie de flux',
                'many' => 'catégories de flux',
            ],
            'fields' => [
                'name'       => 'nom',
                'team_id'    => 'société',
                'created_at' => 'créé le',
                'updated_at' => 'modifié le',
            ],
        ],
        'charge' => [
            'name' => [
                'one'  => 'mouvement de flux',
                'many' => 'mouvements de flux',
            ],
            'fields' => [
                'team_id'          => 'société',
                'flow_category_id' => 'catégorie de flux',
                'amount_in_cents'  => 'montant (centimes)',
                'amount'           => 'montant',
                'charged_at'       => 'date du mouvement',
                'created_at'       => 'créé le',
                'updated_at'       => 'modifié le',
            ],
        ],
    ],

];
