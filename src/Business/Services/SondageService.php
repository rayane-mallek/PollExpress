<?php

namespace TheFeed\Business\Services;

use Framework\Service\UserSessionManager;
use Framework\Storage\RepositoryManager;
use TheFeed\Business\Entity\Sondage;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Exception\ServiceException;

class SondageService {

    protected $sondageRepository;
    protected $utilisateurRepository;
    protected UserSessionManager $sessionManager;


    public function __construct(RepositoryManager $repositoryManager, UserSessionManager $sessionManager)
    {
        $this->sondageRepository = $repositoryManager->getRepository(Sondage::class);
        $this->utilisateurRepository = $repositoryManager->getRepository(Utilisateur::class);
        $this->sessionManager = $sessionManager;

    }

    public function getAllSondages()
    {
        return $this->sondageRepository->getAll();
    }

    public function createNewSondage(string $titre, int $idUtilisateur, bool $allowNull = true)
    {
        if (strlen($titre) > 250 || strlen($titre) < 1) {
            throw new ServiceException("Le titre doit contenir entre 1 et 250 caractères.");
        }

        $utilisateur = $this->utilisateurRepository->get($idUtilisateur);
        if (is_null($utilisateur)){
            throw new ServiceException("L'utilisateur n'existe pas.");
        }

        $sondage = Sondage::create($titre, $utilisateur);

        $this->sondageRepository->create($sondage);
    }

    public function getSondagesFrom(int $idUtilisateur)
    {
        $utilisateur = $this->sondageRepository->getPublicationsFrom($idUtilisateur);
        if (is_null($utilisateur)){
            throw new ServiceException("L'utilisateur n'existe pas.");
        }

        return $this->sondageRepository->getPublicationsFrom($idUtilisateur);
    }

    public function getUserId()
    {
        return $this->sessionManager->get('id');
    }
}