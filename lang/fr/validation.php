<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'            => 'Le champ :attribute doit être accepté.',
    'accepted_if'         => 'Le champ :attribute doit être accepté lorsque :other vaut :value.',
    'active_url'          => 'Le champ :attribute n\'est pas une URL valide.',
    'after'               => 'Le champ :attribute doit être une date postérieure au :date.',
    'after_date_timeline' => 'La date doit être postérieure à la date précédente.',
    'after_or_equal'      => 'Le champ :attribute doit être une date après ou égale à :date.',
    'alpha'               => 'Le champ :attribute doit seulement contenir des lettres.',
    'alpha_dash'          => 'Le champ :attribute doit seulement contenir des lettres, des chiffres et des tirets.',
    'alpha_num'           => 'Le champ :attribute doit seulement contenir des chiffres et des lettres.',
    'array'               => 'Le champ :attribute doit être un tableau.',
    'ascii'               => 'Le champ :attribute ne doit contenir que des caractères alphanumériques à un octet et des symboles.',
    'before'              => 'Le champ :attribute doit être une date antérieure au :date.',
    'before_or_equal'     => 'Le champ :attribute: doit être une date avant ou égale à :date.',
    'between'             => [
        'numeric' => 'La valeur de :attribute doit être comprise entre :min et :max.',
        'file'    => 'Le fichier :attribute doit avoir une taille entre :min et :max kilo-octets.',
        'string'  => 'Le texte :attribute doit avoir entre :min et :max caractères.',
        'array'   => 'Le tableau :attribute doit avoir entre :min et :max éléments.',
    ],
    'boolean'           => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'         => 'Le champ de confirmation :attribute ne correspond pas.',
    'current_password'  => 'Le mot de passe est incorrect.',
    'date'              => 'Le champ :attribute n\'est pas une date valide.',
    'date_equals'       => 'Le champ :attribute doit être une date égale à :date.',
    'date_format'       => 'Le champ :attribute ne correspond pas au format :format.',
    'decimal'           => 'Le champ :attribute doit avoir des décimales :decimal.',
    'declined'          => 'Le champ :attribute doit être refusé.',
    'declined_if'       => 'Le champ :attribute doit être refusé lorsque :other vaut :value.',
    'different'         => 'Les champs :attribute et :other doivent être différents.',
    'digits'            => 'Le champ :attribute doit avoir :digits chiffres.',
    'digits_between'    => 'Le champ :attribute doit avoir entre :min and :max chiffres.',
    'dimensions'        => 'Le champ :attribute a des dimensions d\'image non valides.',
    'distinct'          => 'Le champ a une valeur en double.',
    'doesnt_end_with'   => 'Le champ :attribute ne peut pas se terminer par l\'un des éléments suivants : :values.',
    'doesnt_start_with' => 'Le champ :attribute ne peut pas se commencer par l\'un des éléments suivants : :values.',
    'email'             => 'Le champ :attribute doit être une adresse email valide.',
    'ends_with'         => 'Le champ :attribute doit se terminer par l\'une des valeurs suivantes : :values.',
    'enum'              => 'Le champ :attribute sélectionné est invalide.',
    'exists'            => 'Le champ :attribute sélectionné est invalide.',
    'file'              => 'Le champ :attribute doit être un fichier.',
    'filled'            => 'Le champ :attribute est obligatoire.',
    'gt'                => [
        'array'   => 'Le champ :attribute doit avoir plus de :value éléments.',
        'file'    => 'Le champ :attribute doit être supérieur à :value kilobytes.',
        'numeric' => 'Le champ :attribute doit être supérieur à :value.',
        'string'  => 'Le champ :attribute doit être supérieur à :value caractères.',
    ],
    'gte' => [
        'array'   => 'Le champ :attribute doit avoir :value éléments ou plus.',
        'file'    => 'Le champ :attribute doit être supérieur ou égal :value kilobytes.',
        'numeric' => 'Le champ :attribute doit être supérieur ou égal :value.',
        'string'  => 'Le champ :attribute doit être supérieur ou égal :value caractères.',
    ],
    'image'     => 'Le champ :attribute doit être une image.',
    'in'        => 'Le champ :attribute est invalide.',
    'in_array'  => 'Le champ :attribute n\'existe pas dans :other.',
    'integer'   => 'Le champ :attribute doit être un entier (un nombre sans virgule).',
    'ip'        => 'Le champ :attribute doit être une adresse IP valide.',
    'ipv4'      => 'Le champ :attribute doit être une adresse IPv4 valide.',
    'ipv6'      => 'Le champ :attribute doit être une adresse IPv6 valide.',
    'json'      => 'Le champ :attribute doit être une chaîne JSON valide.',
    'lowercase' => 'Le champ :attribute doit être en minuscules.',
    'lt'        => [
        'array'   => 'Le champ :attribute doit avoir moins de :value éléments.',
        'file'    => 'Le champ :attribute doit être inférieur à :value kilobytes.',
        'numeric' => 'Le champ :attribute doit être inférieur à :value.',
        'string'  => 'Le champ :attribute doit être inférieur à :value caractères.',
    ],
    'lte' => [
        'array'   => 'Le champ :attribute ne doit pas avoir plus de :value éléments.',
        'file'    => 'Le champ :attribute doit être inférieur à or equal :value kilobytes.',
        'numeric' => 'Le champ :attribute doit être inférieur à or equal :value.',
        'string'  => 'Le champ :attribute doit être inférieur à or equal :value caractères.',
    ],
    'mac_address' => 'Le champ :attribute doit être une adresse MAC valide.',
    'max'         => [
        'array'   => 'Le tableau :attribute ne peut avoir plus de :max éléments.',
        'file'    => 'Le fichier :attribute ne peut être plus gros que :max kilo-octets.',
        'numeric' => 'La valeur de :attribute ne peut être supérieure à :max.',
        'string'  => 'Le texte de :attribute ne peut contenir plus de :max caractères.',
    ],
    'max_digits' => 'Le champ :attribute ne doit pas avoir plus de :max chiffres.',
    'mimes'      => 'Le champ :attribute doit être un fichier de type : :values.',
    'mimetypes'  => 'Le champ :attribute doit être un fichier de type : :values.',
    'min'        => [
        'array'   => 'Le tableau :attribute doit avoir au moins :min éléments.',
        'file'    => 'Le fichier :attribute doit être au moins de :min kilo-octets.',
        'numeric' => 'La valeur de :attribute doit être au moins de :min.',
        'string'  => 'Le texte du champ :attribute doit contenir au moins :min caractères.',
    ],
    'min_digits'       => 'Le champ :attribute doit avoir au moins :min chiffres.',
    'missing'          => 'Le champ :attribute doit être manquant.',
    'missing_if'       => 'Le champ :attribute doit être manquant lorsque :other vaut :value.',
    'missing_unless'   => 'Le champ :attribute doit être manquant sauf si :other est :value.',
    'missing_with'     => 'Le champ :attribute doit être manquant lorsque :value est présent.',
    'missing_with_all' => 'Le champ :attribute doit être manquant lorsque :values sont présents.',
    'multiple_of'      => 'Le champ :attribute doit être un multiple de :value.',
    'not_in'           => 'Le champ :attribute sélectionné n\'est pas valide.',
    'not_regex'        => 'Le champ :attribute a un format invalide.',
    'numeric'          => 'Le champ :attribute doit contenir un nombre.',
    'password'         => [
        'letters'       => 'Le champ :attribute doit contenir au moins une lettre.',
        'mixed'         => 'Le champ :attribute doit contenir au moins une majuscule et une minuscule.',
        'numbers'       => 'Le champ :attribute doit contenir au moins un chiffre.',
        'symbols'       => 'Le champ :attribute doit contenir au moins un symbole.',
        'uncompromised' => 'Le :attribute donné est apparu dans une fuite de données. Veuillez choisir un autre :attribute.',
    ],
    'present'              => 'Le champ :attribute doit être present.',
    'prohibited'           => 'Le champ :attribute est interdit.',
    'prohibited_if'        => 'Le champ :attribute est interdit lorsque :other vaut :value.',
    'prohibited_unless'    => 'Le champ :attribute est interdit sauf si :other est dans :values.',
    'prohibits'            => 'Le champ :attribute interdit la présence de :other.',
    'regex'                => 'Le format du champ :attribute est invalide.',
    'required'             => 'Le champ :attribute est obligatoire.',
    'required_array_keys'  => 'Le champ :attribute doit contenir des entrées pour : :values.',
    'required_if'          => 'Le champ :attribute est obligatoire quand la valeur de :other est :value.',
    'required_if_accepted' => 'Le champ :attribute est obligatoire lorsque :other est accepté.',
    'required_unless'      => 'Le champ :attribute est obligatoire sauf si :other est dans :values.',
    'required_with'        => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_with_all'    => 'Le champ :attribute est obligatoire quand :values est présent.',
    'required_without'     => 'Le champ :attribute est obligatoire quand :values n\'est pas présent.',
    'required_without_all' => 'Le champ :attribute est requis quand aucun de :values n\'est présent.',
    'same'                 => 'Les champs :attribute et :other doivent être identiques.',
    'size'                 => [
        'numeric' => 'La valeur de :attribute doit être :size.',
        'file'    => 'La taille du fichier de :attribute doit être de :size kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir :size caractères.',
        'array'   => 'Le tableau :attribute doit contenir :size éléments.',
    ],
    'starts_with' => 'Le :attribute doit commencer par l\'un des éléments suivants : :values.',
    'string'      => 'Le champ :attribute doit être une chaîne.',
    'timezone'    => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'      => 'Le champ :attribute a déjà été pris.',
    'uploaded'    => 'Le champ :attribute n\'a pas pu être téléchargé.',
    'uppercase'   => 'Le champ :attribute doit être en majuscule.',
    'url'         => 'Le champ :attribute doit être une URL valide.',
    'ulid'        => 'Le champ :attribute doit être un ULID valide.',
    'uuid'        => 'Le champ :attribute doit être un UUID valide.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
