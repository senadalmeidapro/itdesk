<?php

/**
 * Catalogue public des services TAKTIC.
 *
 * Chaque entrée alimente la page index (/services) et la page dédiée
 * (/services/{slug}). La clé `icon` fait référence à un composant
 * resources/views/components/icon-*.blade.php. La clé `illustration`
 * fait référence à une illustration dédiée (resources/views/components/scene-*.blade.php).
 * La clé `tone` alterne l'accentuation visuelle brand (indigo) / flow (cyan).
 */

return [

    'services' => [

        [
            'slug' => 'maintenance-depannage',
            'name' => 'Intervention & maintenance',
            'short' => 'Dépannage et entretien préventif de vos postes, imprimantes et périphériques.',
            'headline' => 'Votre parc, au point, toute l\'année.',
            'description' => 'De la panne du matin au plan d\'entretien préventif, nous prenons en charge le quotidien de vos équipements informatiques. Diagnostic, réparation, remplacement de composants et mises à jour : votre matériel est suivi, donc fiable.',
            'icon' => 'wrench',
            'illustration' => 'maintenance',
            'tone' => 'brand',
            'metrics' => [
                ['value' => '24 h', 'label' => 'délai moyen d\'intervention'],
                ['value' => '98 %', 'label' => 'de taux de résolution'],
                ['value' => '1 h 45', 'label' => 'de temps de réponse moyen'],
            ],
            'features' => [
                'Diagnostic et dépannage matériel et logiciel',
                'Entretien préventif de votre parc (préventif annuel)',
                'Remplacement et nettoyage de composants',
                'Mise à jour des systèmes et des applications',
                'Reprise en garantie et lien constructeur',
                'Maintenance à distance ou sur place',
            ],
        ],

        [
            'slug' => 'reseaux-connectivite',
            'name' => 'Réseaux & connectivité',
            'short' => 'Installation, sécurisation et supervision de vos réseaux Wi-Fi et filaires.',
            'headline' => 'Un réseau stable et rapide, du salon au serveur.',
            'description' => 'Connexions qui sautent, zones sans Wi-Fi, lenteurs : nous concevons, installons et supervisons votre réseau pour que tout le monde reste connecté, en toute sécurité, chez vous comme au bureau.',
            'icon' => 'server',
            'illustration' => 'reseaux',
            'tone' => 'flow',
            'metrics' => [
                ['value' => '100 %', 'label' => 'des locaux couverts en Wi-Fi'],
                ['value' => '0', 'label' => 'coupure sans préavis'],
                ['value' => '24/7', 'label' => 'supervision possible'],
            ],
            'features' => [
                'Installation et optimisation de la box et du Wi-Fi',
                'Câblage et raccordement de vos locaux',
                'Pare-feu et cloisonnement des réseaux',
                'Supervision et alerte en cas de panne',
                'VLAN, VLAN invité et accès sécurisés',
                'Intervention chez les particuliers et les entreprises',
            ],
        ],

        [
            'slug' => 'support-helpdesk',
            'name' => 'Support & helpdesk',
            'short' => 'Une équipe réactive pour vos incidents et vos questions, avec un suivi en ligne.',
            'headline' => 'Une question ? Une équipe, un suivi en ligne.',
            'description' => 'Incident bloquant ou simple question sur un logiciel : notre helpdesk répond et suit chaque demande dans un espace en ligne. Vous savez toujours où en est votre dossier, et qui s\'en occupe.',
            'icon' => 'lifebuoy',
            'illustration' => 'helpdesk',
            'tone' => 'brand',
            'metrics' => [
                ['value' => '1 h 45', 'label' => 'de temps de réponse moyen'],
                ['value' => '6 j/7', 'label' => 'jours de disponibilité'],
                ['value' => '100 %', 'label' => 'des demandes suivies en ligne'],
            ],
            'features' => [
                'Assistance à distance et sur site',
                'Suivi de ticket dans votre espace client',
                'Accompagnement sur vos outils métier',
                'Helpdesk avec niveau de service (SLA) contractuel',
                'Historique complet de vos interventions',
                'Partenaires et utilisateurs prioritaires 24/7',
            ],
        ],

        [
            'slug' => 'vente-installation',
            'name' => 'Vente & installation',
            'short' => 'Le bon matériel, au bon prix, installé et configuré par nos soins.',
            'headline' => 'Du conseil à l\'installation, sans fausse note.',
            'description' => 'Besoin d\'un ordinateur, d\'un serveur ou de périphériques ? Nous sélectionnons du matériel adapté à votre usage et à votre budget, nous l\'installons, et nous recyclons votre ancien équipement.',
            'icon' => 'cube',
            'illustration' => 'vente',
            'tone' => 'flow',
            'metrics' => [
                ['value' => 'Aucun', 'label' => 'surcoût caché, devis ferme'],
                ['value' => '100 %', 'label' => 'installation incluse au devis'],
                ['value' => '100 %', 'label' => 'de l\'ancien matériel recyclé'],
            ],
            'features' => [
                'Conseil et devis matériel personnalisé',
                'Ordinateurs, serveurs et périphériques',
                'Installation et configuration complètes',
                'Transfert et migration de vos données',
                'Reprise et recyclage de l\'ancien matériel',
                'Suivi de garantie constructeur',
            ],
        ],

        [
            'slug' => 'sauvegarde-securite',
            'name' => 'Sauvegarde & sécurité',
            'short' => 'Protection de vos données et de votre activité contre la perte et les menaces.',
            'headline' => 'Vos données dorment tranquilles, vous aussi.',
            'description' => 'Perte de fichiers, ransomware, panne de disque : nous mettons en place des sauvegardes automatisées et une protection complète de vos postes et de vos comptes, avec un plan de reprise si le pire arrive.',
            'icon' => 'cloud',
            'illustration' => 'securite',
            'tone' => 'brand',
            'metrics' => [
                ['value' => '3-2-1', 'label' => 'règle de sauvegarde appliquée'],
                ['value' => 'R1', 'label' => 'heure de restauration possible'],
                ['value' => '24/7', 'label' => 'protection des postes et serveurs'],
            ],
            'features' => [
                'Sauvegarde automatisée, locale et cloud',
                'Antivirus et protection des postes',
                'Sécurisation des mots de passe et 2FA',
                'Mise à jour de sécurité suivie',
                'Plan de reprise après incident (PRA/DRP)',
                'Tests de restauration réguliers',
            ],
        ],

        [
            'slug' => 'accompagnement-formation',
            'name' => 'Accompagnement & formation',
            'short' => 'Gagnez en autonomie avec des sessions pratiques adaptées à votre niveau.',
            'headline' => 'Le numérique, sans appréhension.',
            'description' => 'De la prise en main d\'un nouvel outil à la formation d\'une équipe, nous vous accompagnons pour que l\'informatique devienne un allié du quotidien — pas une corvée.',
            'icon' => 'book-open',
            'illustration' => 'formation',
            'tone' => 'flow',
            'metrics' => [
                ['value' => '1:1', 'label' => 'ou en petit groupe'],
                ['value' => '100 %', 'label' => 'de sessions pratiques'],
                ['value' => '+24 h', 'label' => 'de support documentaire inclus'],
            ],
            'features' => [
                'Formation individuelle ou en petit groupe',
                'Prise en main de vos outils métier',
                'Bonnes pratiques de sécurité au quotidien',
                'Mise en place, puis délégation en autonomie',
                'Tutoriels et fiches pratiques en support',
                'Accompagnement au bon usage du cloud',
            ],
        ],

    ],

];
