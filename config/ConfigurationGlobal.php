<?php

namespace Config;

use Symfony\Component\DependencyInjection\Reference;
use TheFeed\Application\PublicationController;
use TheFeed\Application\SondageController;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Sondage;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Services\SondageService;
use TheFeed\Storage\SQL\PublicationRepositorySQL;
use TheFeed\Business\Services\PublicationService;
use TheFeed\Business\Services\UtilisateurService;
use TheFeed\Application\UtilisateurController;
use TheFeed\Storage\SQL\SondageRepositorySQL;
use TheFeed\Storage\SQL\UtilisateurRepositorySQL;

class ConfigurationGlobal {
    const appRoot = __DIR__ . '/../src';

    const views = "View";

    const repositories = [
        Publication::class => PublicationRepositorySQL::class,
        Utilisateur::class => UtilisateurRepositorySQL::class,
        Sondage::class => SondageRepositorySQL::class,
    ];

    const controllers = [
        "publication_controller" => PublicationController::class,
        "utilisateur_controller" => UtilisateurController::class,
        "sondage_controller" => SondageController::class,
    ];

    const routes = [
        "app_feed" => [
            "path" => '/',
            "parameters" => [
               "_controller" => "sondage_controller::listSondages",
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
        "app_submit_sondage" => [
            "path" => '/sondage',
            "parameters" => [
                "_controller" => "sondage_controller::submitSondage",
            ],
            "methods" => ["POST"]
        ],
        "app_sondage_oui" => [
            "path" => '/sondage/oui/{idSondage}/{idUtilisateur}',
            "parameters" => [
                "_controller" => "sondage_controller::voteOui",
                "idSondage" => null,
                "idUtilisateur" => 1
            ],
            "methods" => ["GET"]
        ],
        "app_sondage_non" => [
            "path" => '/non/{idSondage}/{idUtilisateur}',
            "parameters" => [
                "_controller" => "sondage_controller::voteNon",
                "idSondage" => null,
                "idUtilisateur" => 1
            ],
            "methods" => ["GET"]
        ],
        "app_create_sondage" => [
            "path" => '/create_sondage',
            "parameters" => [
                "_controller" => "sondage_controller::getCreateSondage",
            ],
            "methods" => ["GET"]
        ],
        "app_list_sondages" => [
            "path" => '/sondages',
            "parameters" => [
                "_controller" => "sondage_controller::listSondages",
            ],
            "methods" => ["GET"]
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

        $container->register('sondage_service', SondageService::class)
            ->setArguments([$container->get('repository_manager'), new Reference('session_manager')]);

        $container->register('utilisateur_service', UtilisateurService::class)
            ->setArguments([$container->get('repository_manager'), '%salt%', '%profilePictures%', new Reference('session_manager')]);
    }
}
