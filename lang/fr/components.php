<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Components Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain all keys that are used by specific
    | components in the application.
    |
    */

    'app' => [
        'alert_dialog' => [
            'title' => [
                'default'     => 'Confirmer',
                'success'     => 'Confirmer',
                'warning'     => 'Attention',
                'destructive' => 'Attention',
            ],
        ],
    ],

    'settings' => [
        'profile' => [
            'delete_dialog' => [
                'title'       => 'Êtes-vous sûr de vouloir supprimer votre compte ?',
                'description' => 'Une fois votre compte supprimé, toutes ses ressources et données seront également supprimées de manière permanente. Veuillez saisir votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.',
                'action'      => 'Supprimer votre compte',
            ],
        ],
    ],

    'ui' => [
        'custom' => [
            'combobox' => [
                'selected' => '1 élément sélectionné|:count éléments sélectionnés',
                'empty'    => 'rien à afficher',
            ],
            'data_table' => [
                'selected'      => ':selected sur :rows ligne(s) sélectionnée(s)',
                'empty'         => 'rien à afficher',
                'rows_per_page' => 'lignes par page',
                'pages'         => 'page :current sur :total',
            ],
            'filters' => [
                'sheet' => [
                    'title'       => 'Filtrer la liste',
                    'description' => 'Obtenez uniquement les éléments dont vous avez besoin',
                ],
            ],
            'input' => [
                'media' => [
                    'upload' => 'Ajouter un fichier',
                    'delete' => 'Supprimer le fichier',
                ],
            ],
            'multi_select' => [
                'empty'    => 'rien à afficher',
                'reset'    => 'réinitialiser',
                'selected' => '1 sélectionné|:count sélectionnés',
            ],
        ],
    ],
];
