<?php

namespace Framework\Storage;

abstract class RepositoryManager {

    protected $repositories = [];
 
    public abstract function registerRepository($entityClass, $repositoryClass);
 
    public function registerRepositories($repositoriesData)
    {
        foreach($repositoriesData as $class => $repository) {
            $this->registerRepository($class, $repository);
        }   
    }
 
    public function getRepository($entityClass) 
    {   
        return $this->repositories[$entityClass];
    }
 }
  