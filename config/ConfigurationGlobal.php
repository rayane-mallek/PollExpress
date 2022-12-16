<?php

namespace Config;

use Symfony\Component\DependencyInjection\Reference;
use TheFeed\Application\PublicationController;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Storage\SQL\PublicationRepositorySQL;
use TheFeed\Business\Services\PublicationService;
use TheFeed\Business\Services\UtilisateurService;
use TheFeed\Application\UtilisateurController;
use TheFeed\Storage\SQL\UtilisateurRepositorySQL;

class ConfigurationGlobal {
    const appRoot = __DIR__ . '/../src';

    const views = "View";

    const repositories = [
        Publication::class => PublicationRepositorySQL::class,
        Utilisateur::class => UtilisateurRepositorySQL::class,
    ];

    const controllers = [
        "publication_controller" => PublicationController::class,
        "utilisateur_controller" => UtilisateurController::class,
    ];

    const routes = [
        "app_feed" => [
            "path" => '/',
            "parameters" => [
               "_controller" => "publication_controller::feed",
            ],
            "methods" => ["GET"]
        ],
        "app_submit_feedy" => [
            "path" => '/feedy',
            "parameters" => [
               "_controller" => "publication_controller::submitFeedy",
            ],
            "methods" => ["POST"]
        ],
        "app_inscription" => [
            "path" => '/inscription',
            "parameters" => [
               "_controller" => "utilisateur_controller::getInscription",
            ],
            "methods" => ["GET"]
        ],
        "app_submit_inscription" => [
            "path" => '/inscription',
            "parameters" => [
               "_controller" => "utilisateur_controller::submitInscription",
            ],
            "methods" => ["POST"]
        ],
        "app_connexion" => [
            "path" => '/connexion',
            "parameters" => [
               "_controller" => "utilisateur_controller::getConnexion",
            ],
            "methods" => ["GET"]
        ],
        "app_submit_connexion" => [
            "path" => '/connexion',
            "parameters" => [
               "_controller" => "utilisateur_controller::submitConnexion",
            ],
            "methods" => ["POST"]
        ],
        "app_submit_deconnexion" => [
            "path" => '/deconnexion',
            "parameters" => [
               "_controller" => "utilisateur_controller::submitDeconnexion",
            ],
            "methods" => ["GET"]
        ],
        "app_page_perso" => [
            "path" => '/utilisateurs/page/{idUtilisateur}',
            "parameters" => [
               "_controller" => "utilisateur_controller::getPagePerso",
               "idUtilisateur" => null
            ],
            "methods" => ["GET"]
        ]
    ];

    const parameters = [
        'salt' => 'dejgkerhgerlk:;ghù§hj;dsf',
        'profilePictures' => __DIR__ . '/../web/assets/img/utilisateurs',
    ];

    const listeners = [
        'framework_listener'
    ];

    public static function services($container) 
    {
        $container->register('publication_service', PublicationService::class)
            ->setArguments([$container->get('repository_manager')]);

        $container->register('utilisateur_service', UtilisateurService::class)
            ->setArguments([$container->get('repository_manager'), '%salt%', '%profilePictures%', new Reference('session_manager')]);
    }
}
