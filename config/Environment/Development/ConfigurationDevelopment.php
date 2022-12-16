<?php

namespace Config\Environment\Development;

use Config\ConfigurationGlobal;
use Framework\Storage\SQL\RepositoryManagerSQLite;

class ConfigurationDevelopment extends ConfigurationGlobal {

    const repositoryManager = [
        "manager" => RepositoryManagerSQLite::class,
        "dataSourceParameters" => [
            'file' => __DIR__."/database_development",
        ]
    ];

    const debug = true;
}
