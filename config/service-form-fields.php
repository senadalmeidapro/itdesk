<?php

/**
 * Champs personnalisés du formulaire de contact, par service.
 *
 * Chaque entrée correspond à un slug du catalogue config/public-services.php.
 * Dès que le visiteur sélectionne le service concerné, ces champs s'affichent
 * dynamiquement. Les réponses sont stockées dans la colonne JSON
 * `contact_messages.form_data` et servent au back-office (Filament), à l'e-mail
 * de notification et à la description du ticket lors de la conversion.
 *
 * Types supportés : text | number | select | textarea.
 */

return [

    'maintenance-depannage' => [
        [
            'name' => 'equipment_type',
            'label' => "Type d'équipement concerné",
            'type' => 'select',
            'options' => [
                'poste' => 'PC / poste fixe',
                'portable' => 'Portable',
                'imprimante' => 'Imprimante',
                'peripherique' => 'Périphérique',
                'serveur' => 'Serveur',
                'plusieurs' => 'Plusieurs équipements',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'equipment_count',
            'label' => "Nombre d'équipements concernés",
            'type' => 'number',
            'placeholder' => 'Ex. : 3',
            'required' => true,
        ],
        [
            'name' => 'intervention_mode',
            'label' => "Mode d'intervention souhaité",
            'type' => 'select',
            'options' => [
                'sur_place' => 'Sur place',
                'a_distance' => 'À distance',
                'indifferent' => 'Peu importe / à définir',
            ],
            'required' => true,
        ],
        [
            'name' => 'urgency',
            'label' => "Degré d'urgence",
            'type' => 'select',
            'options' => [
                'normale' => 'Normale (sous quelques jours)',
                'rapide' => 'Rapide (sous 24-48 h)',
                'urgente' => 'Urgente (dès que possible)',
            ],
            'required' => true,
        ],
    ],

    'reseaux-connectivite' => [
        [
            'name' => 'location',
            'label' => 'Type de lieu',
            'type' => 'select',
            'options' => [
                'maison' => 'Particulier / maison',
                'bureaux' => 'Bureaux / entreprise',
                'atelier' => 'Atelier / dépôt',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'user_count',
            'label' => "Nombre d'utilisateurs / d'appareils connectés",
            'type' => 'number',
            'placeholder' => 'Ex. : 12',
            'required' => true,
        ],
        [
            'name' => 'current_setup',
            'label' => 'Matériel actuel',
            'type' => 'select',
            'options' => [
                'box_fai' => 'La box de mon opérateur (FAI)',
                'routeur_pro' => 'Routeur / matériel professionnel',
                'aucun' => 'Aucun matériel existant',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'wifi_issue',
            'label' => 'Le besoin principal',
            'type' => 'select',
            'options' => [
                'couverture' => 'Couverture insuffisante (zones mortes)',
                'lenteur' => 'Connexions lentes ou instables',
                'installation' => 'Nouvelle installation',
                'securisation' => 'Sécurisation / cloisonnement des réseaux (VLAN)',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
    ],

    'support-helpdesk' => [
        [
            'name' => 'issue_type',
            'label' => 'Type de demande',
            'type' => 'select',
            'options' => [
                'logiciel' => 'Logiciel / application',
                'materiel' => 'Matériel / périphérique',
                'compte' => 'Compte / accès / e-mail',
                'installation' => 'Installation / configuration',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'users_affected',
            'label' => 'Personnes concernées',
            'type' => 'select',
            'options' => [
                'moi' => 'Moi seul',
                'quelques' => 'Quelques utilisateurs',
                'equipe' => "Toute l'équipe",
                'clients' => 'Aussi des clients externes',
            ],
            'required' => true,
        ],
        [
            'name' => 'blocks_work',
            'label' => "Cela bloque-t-il l'activité ?",
            'type' => 'select',
            'options' => [
                'oui' => "Oui, cela bloque l'activité",
                'partiellement' => 'Partiellement',
                'non' => 'Non, simple question / besoin d\'aide',
            ],
            'required' => true,
        ],
        [
            'name' => 'availability',
            'label' => 'Quand intervenir ?',
            'type' => 'select',
            'options' => [
                'des_que_possible' => 'Dès que possible',
                'heures_bureau' => 'Pendant les heures de bureau',
                'soir_weekend' => 'Soir / week-end',
                'flexible' => 'Flexible',
            ],
            'required' => true,
        ],
    ],

    'vente-installation' => [
        [
            'name' => 'equipment_needed',
            'label' => 'Équipement souhaité',
            'type' => 'select',
            'options' => [
                'poste' => 'PC / poste fixe',
                'portable' => 'Portable',
                'serveur' => 'Serveur',
                'imprimante' => 'Imprimante / multifonction',
                'reseau' => 'Équipement réseau (routeur, switch, Wi-Fi)',
                'plusieurs' => 'Plusieurs équipements (parc complet)',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'quantity',
            'label' => 'Quantité estimée',
            'type' => 'number',
            'placeholder' => 'Ex. : 2',
            'required' => true,
        ],
        [
            'name' => 'budget_range',
            'label' => 'Enveloppe budgétaire',
            'type' => 'select',
            'options' => [
                'moins_500' => 'Moins de 500 €',
                '500_1500' => 'Entre 500 € et 1 500 €',
                '1500_5000' => 'Entre 1 500 € et 5 000 €',
                'plus_5000' => 'Plus de 5 000 €',
                'indecis' => 'Je ne sais pas encore',
            ],
            'required' => true,
        ],
        [
            'name' => 'options',
            'label' => 'Services complémentaires',
            'type' => 'select',
            'options' => [
                'migration' => 'Transfert / migration de mes données',
                'recyclage' => 'Reprise et recyclage de l\'ancien matériel',
                'les_deux' => 'Les deux',
                'aucun' => 'Aucun / à discuter',
            ],
            'required' => true,
        ],
    ],

    'sauvegarde-securite' => [
        [
            'name' => 'device_count',
            'label' => 'Nombre de postes / serveurs à protéger',
            'type' => 'number',
            'placeholder' => 'Ex. : 8',
            'required' => true,
        ],
        [
            'name' => 'server_or_pcs',
            'label' => 'Postes ou serveurs ?',
            'type' => 'select',
            'options' => [
                'serveurs' => 'Oui, des serveurs',
                'postes_seuls' => 'Non, uniquement des postes',
                'ne_sais_pas' => 'Je ne sais pas',
            ],
            'required' => true,
        ],
        [
            'name' => 'current_backup',
            'label' => 'Sauvegarde actuelle',
            'type' => 'select',
            'options' => [
                'aucune' => 'Aucune sauvegarde',
                'manuel' => 'Manuelle / disque externe',
                'cloud' => 'Sauvegarde cloud existante',
                'nas' => 'Sauvegarde NAS / locale',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
        [
            'name' => 'concern',
            'label' => 'Votre préoccupation principale',
            'type' => 'select',
            'options' => [
                'perte' => 'Perte de données',
                'menaces' => 'Menaces / virus / ransomware',
                'comptes' => 'Sécuriser mes accès et mots de passe',
                'rgpd' => 'Conformité RGPD / données sensibles',
                'autre' => 'Autre',
            ],
            'required' => true,
        ],
    ],

    'accompagnement-formation' => [
        [
            'name' => 'audience',
            'label' => 'Qui est concerné ?',
            'type' => 'select',
            'options' => [
                'moi' => 'Moi seul',
                'groupe' => 'Un petit groupe (2 à 5)',
                'equipe' => 'Toute une équipe',
                'famille' => 'Famille / entourage',
            ],
            'required' => true,
        ],
        [
            'name' => 'level',
            'label' => 'Niveau actuel',
            'type' => 'select',
            'options' => [
                'debutant' => 'Débutant',
                'intermediaire' => 'Intermédiaire',
                'confirme' => 'Confirmé',
            ],
            'required' => true,
        ],
        [
            'name' => 'topics',
            'label' => 'Sujets / outils à travailler',
            'type' => 'textarea',
            'placeholder' => 'Ex. : e-mail, tableur, prise en main d\'un logiciel métier…',
            'required' => false,
            'column' => 'full',
        ],
        [
            'name' => 'format',
            'label' => 'Format souhaité',
            'type' => 'select',
            'options' => [
                'sur_place' => 'Sur place',
                'visio' => 'À distance (visio)',
                'hybride' => 'Hybride / selon disponibilité',
            ],
            'required' => true,
        ],
    ],

];
