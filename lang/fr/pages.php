<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain all keys that are used by specific
    | pages in the application.
    |
    */

    'auth' => [
        'confirm_password' => [
            'title'       => 'Confirmez votre mot de passe',
            'description' => "Ceci est une zone sécurisée de l'application. Veuillez confirmer votre mot de passe avant de continuer.",
            'action'      => 'Confirmer le mot de passe',
        ],
        'forgot_password' => [
            'title'       => 'Mot de passe oublié',
            'description' => 'Entrez votre e-mail pour recevoir un lien de réinitialisation du mot de passe',
            'email_link'  => 'Envoyer le lien de réinitialisation par e-mail',
            'return_to'   => 'Ou, retour à',
        ],
        'login' => [
            'title'           => 'Connexion à votre compte',
            'description'     => 'Entrez votre e-mail et votre mot de passe ci-dessous pour vous connecter',
            'forgot_password' => 'Mot de passe oublié ?',
            'remember_me'     => 'Se souvenir de moi',
            'no_account'      => "Vous n'avez pas de compte ?",
            'action'          => 'Se connecter',
        ],
        'register' => [
            'title'       => 'Créer un compte',
            'description' => 'Entrez vos informations ci-dessous pour créer votre compte',
            'action'      => 'Créer un compte',
            'has_account' => 'Vous avez déjà un compte ?',
        ],
        'reset_password' => [
            'title'       => 'Réinitialiser le mot de passe',
            'description' => 'Veuillez entrer votre nouveau mot de passe ci-dessous',
            'action'      => 'Réinitialiser le mot de passe',
        ],
        'verify_email' => [
            'title'        => 'Vérifiez votre adresse e-mail',
            'description'  => "Veuillez vérifier votre adresse e-mail à l'aide du code que nous vous avons envoyé.",
            'resend_email' => "Renvoyer l'e-mail de vérification",
            'not_you'      => "Ce n'est pas vous ?",
        ],
        'setup' => [
            'not_ready' => [
                'title'       => 'Compte non configuré',
                'description' => "Votre compte n'a pas encore été configuré, veuillez contacter votre administrateur pour commencer",
                'not_you'     => "Ce n'est pas vous ?",
            ],
            'step_one' => [
                'title'       => 'Société',
                'description' => 'Créez votre première société pour organiser vos affaires',
            ],
            'step_two' => [
                'title'       => 'Année fiscale',
                'description' => 'Saisissez la période fiscale pour démarrer vos activités',
            ],
        ],
    ],

    'banners' => [
        'admin' => [
            'index' => [
                'title' => 'Bannières',
            ],
            'create' => [
                'title'       => 'Créer une bannière',
                'description' => 'Créez une nouvelle bannière à afficher sur le site',
            ],
            'edit' => [
                'title'       => 'Mettre à jour une bannière',
                'description' => 'Modifiez une bannière à afficher sur le site',
            ],
        ],
    ],

    'contractors' => [
        'index' => [
            'title' => 'Sous traitance',
        ],
        'create' => [
            'title'       => 'Nouveau sous traitant',
            'description' => 'Ajouter un nouveau sous traitant',
        ],
        'edit' => [
            'title'       => 'Détails du sous traitant',
            'description' => 'Mettez à jour les détails du sous traitant',
            'ends_at'     => [
                'title'       => 'Fin de collaboration',
                'description' => 'Cette action mettra fin à la collaboration avec le sous-traitant',
                'actions'     => [
                    'update' => 'Finir la collaboration',
                    'delete' => 'Reprendre la collaboration',
                ],
            ],
        ],
    ],

    'employees' => [
        'index' => [
            'title' => 'Resources humaines',
        ],
        'create' => [
            'title'       => 'Nouveau salarié',
            'description' => 'Ajouter un nouveau salarié',
        ],
        'edit' => [
            'title'       => 'Détails du salarié',
            'description' => 'Mettez à jour les détails du salarié',
            'ends_at'     => [
                'title'       => 'Date de sortie',
                'description' => 'Cette action va couper les budgets du salarié à la date de sortie',
                'actions'     => [
                    'delete' => 'Supprimer la date de sortie',
                ],
            ],
        ],
    ],

    'dashboard' => [
        'admin' => [
            'index' => [
                'title' => 'Tableau de bord',
            ],
        ],
        'index' => [
            'title' => 'Tableau de bord',
        ],
    ],

    'expenses' => [
        'budgets' => [
            'index' => [
                'title' => 'Budgets',
            ],
            'create' => [
                'title'       => 'Nouveau budget',
                'description' => 'Créez un nouveau budget',
            ],
            'edit' => [
                'title'       => 'Détails de budget',
                'description' => 'Mettez à jour les détails du budget',
            ],
        ],
        'charges' => [
            'index' => [
                'title' => 'Frais ponctuels',
            ],
            'create' => [
                'title'       => 'Nouveau frais ponctuel',
                'description' => 'Créez un nouveau frais ponctuel',
            ],
            'edit' => [
                'title'       => 'Détails de frais ponctuel',
                'description' => 'Mettez à jour les détails du frais ponctuel',
            ],
        ],
        'management' => [
            'index' => [
                'title' => 'Contrôle de gestion',
                'real'  => 'réel',
                'delta' => 'écart',
            ],
        ],
    ],

    'settings' => [
        'title'   => 'Paramètres',
        'profile' => [
            'title'       => 'Paramètres du profil',
            'information' => [
                'title'            => 'Informations du profil',
                'description'      => 'Mettez à jour votre nom et votre adresse e-mail',
                'unverified_email' => "Votre adresse e-mail n'est pas vérifiée.",
                'verify_email'     => 'Cliquez ici pour vérifier.',
            ],
            'delete' => [
                'title'       => 'Supprimer le compte',
                'description' => 'Cette action est irréversible',
            ],
        ],
        'security' => [
            'title'    => 'Paramètres de sécurité',
            'password' => [
                'title'       => 'Mot de passe',
                'description' => "Assurez-vous d'utiliser un mot de passe long et aléatoire pour protéger votre compte",
            ],
        ],
        'appearance' => [
            'title'       => "Paramètres d'apparence",
            'description' => "Mettez à jour les paramètres d'apparence de votre compte",
            'colors'      => [
                'light' => 'Clair',
                'auto'  => 'Système',
                'dark'  => 'Sombre',
            ],
        ],
    ],

    'teams' => [
        'index' => [
            'title' => 'Sociétés',
        ],
        'create' => [
            'title'       => 'Nouvelle société',
            'description' => 'Créez une nouvelle société pour vous et vos utilisateurs',
        ],
        'edit' => [
            'title'       => 'Détails de la société',
            'description' => 'Mettez à jour les détails de la société',
        ],
        'accounting_periods' => [
            'index' => [
                'title' => 'Années fiscales',
            ],
            'create' => [
                'title'       => 'Nouvelle année fiscale',
                'description' => 'Créez une nouvelle année fiscale pour gérer vos affaires',
            ],
            'edit' => [
                'title'       => "Détails de l'année fiscale",
                'description' => "Mettez à jour les détails de l'année fiscale",
            ],
        ],
        'project_departments' => [
            'index' => [
                'title' => 'Directions de projets',
            ],
            'create' => [
                'title'       => 'Nouvelle direction de projets',
                'description' => 'Créez une nouvelle direction de projets pour organiser vos affaires',
            ],
            'edit' => [
                'title'       => 'Détails de direction de projets',
                'description' => 'Mettez à jour les détails de la direction de projets',
            ],
        ],
        'expenses' => [
            'categories' => [
                'index' => [
                    'title' => 'Catégories de dépenses',
                ],
                'create' => [
                    'title'       => 'Nouvelle catégorie de dépenses',
                    'description' => 'Créez une nouvelle catégorie de dépenses pour organiser vos affaires',
                ],
                'edit' => [
                    'title'       => 'Détails de catégorie de dépenses',
                    'description' => 'Mettez à jour les détails de la catégorie de dépenses',
                ],
            ],
            'items' => [
                'index' => [
                    'title' => 'Postes de dépenses',
                ],
                'create' => [
                    'title'       => 'Nouvelle poste de dépenses',
                    'description' => 'Créez un nouveau poste de dépenses pour organiser vos affaires',
                ],
                'edit' => [
                    'title'       => 'Détails de poste de dépenses',
                    'description' => 'Mettez à jour les détails du poste de dépenses',
                ],
            ],
            'sub_categories' => [
                'index' => [
                    'title' => 'Sous-catégories de dépenses',
                ],
                'create' => [
                    'title'       => 'Nouvelle sous-catégorie de dépenses',
                    'description' => 'Créez une nouvelle sous-catégorie de dépenses pour organiser vos affaires',
                ],
                'edit' => [
                    'title'       => 'Détails de sous-catégorie de dépenses',
                    'description' => 'Mettez à jour les détails de la sous-catégorie de dépenses',
                ],
            ],
        ],
    ],

    'users' => [
        'members' => [
            'index' => [
                'title' => 'Membres',
            ],
            'create' => [
                'title'       => 'Nouveau membre',
                'description' => 'Créez un nouveau membre et attribuez-lui un accès spécifique',
            ],
            'edit' => [
                'title'       => 'Détails du membre',
                'description' => 'Mettez à jour les informations du membre',
            ],
        ],

    ],

    'clients' => [
        'index' => [
            'title' => 'Clients',
        ],
        'create' => [
            'title' => 'Créer un client',
        ],
        'update' => [
            'title' => 'Mettre à jour un client',
        ],
    ],

    'deals' => [
        'commercials' => [
            'index' => [
                'title' => 'Affaires commerciales',
            ],

            'create' => [
                'title'       => 'Créer une affaire commerciale',
                'description' => 'Créez une nouvelle affaire commerciale pour gérer vos transactions',
            ],
            'edit' => [
                'title'           => 'Mettre à jour une affaire commerciale',
                'description'     => 'Modifiez les détails d\'une affaire commerciale existante',
                'validate_button' => 'Passer l’affaire au statut validé',
            ],

            'validate' => [
                'title' => 'Validation de l’affaire :name',
                'save'  => 'Valider l’affaire',
            ],
            'schedule' => 'Échéancier',
        ],

        'billings' => [
            'index' => [
                'title' => 'Factures',
            ],
            'edit' => [
                'title'       => 'Mettre à jour une facture',
                'description' => 'Modifiez les détails d\'une facture existante',
            ],
            'postpone_schedule' => [
                'title'       => 'Reporter les factures',
                'description' => 'Combien de mois voulez-vous reporter les factures à partir de cette échéance ?',
            ],
            'schedule_totals' => [
                'total'     => 'Total échéancier',
                'principal' => 'Montant principal',
            ],
            'schedule'                    => 'Échéancier',
            'schedule_status_placeholder' => 'Sélectionner un statut',
        ],

    ],

];
