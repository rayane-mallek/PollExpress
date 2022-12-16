<?php

namespace Config\Environment\Production;

use Config\ConfigurationGlobal;
use Framework\Storage\SQL\RepositoryManagerMySQL;

class ConfigurationProduction extends ConfigurationGlobal {

    const repositoryManager = [
        "manager" => RepositoryManagerMySQL::class,
        "dataSourceParameters" => [
            'hostname' => 'webinfo.iutmontp.univ-montp2.fr',
            'database' => 'mallekr',
            'login' => 'mallekr',
            'password' => '02092002'
        ]
    ];

    const debug = false;
}
