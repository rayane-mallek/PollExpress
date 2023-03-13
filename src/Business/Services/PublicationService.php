<?php 

namespace TheFeed\Business\Services;

use Framework\Storage\RepositoryManager;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Exception\ServiceException;

class PublicationService {

    protected $publicationRepository;
    protected $utilisateurRepository;

    public function __construct(RepositoryManager $repositoryManager)
    {
        $this->publicationRepository = $repositoryManager->getRepository(Publication::class);
        $this->utilisateurRepository = $repositoryManager->getRepository(Utilisateur::class);
    }

    public function getAllPublications() 
    {
        return $this->publicationRepository->getAll();
    }

    public function createNewPublication(string $message, int $idUtilisateur, bool $allowNull = true)
    {
        if (strlen($message) > 250 || strlen($message) < 1) {
            throw new ServiceException("Le message doit contenir entre 1 et 250 caractères.");
        }

        $utilisateur = $this->utilisateurRepository->get($idUtilisateur);
        if (is_null($utilisateur)){
            throw new ServiceException("L'utilisateur n'existe pas.");
        }

        $publication = Publication::create($message, $utilisateur);
            
        $this->publicationRepository->create($publication);
    }

    public function getPublicationsFrom(int $idUtilisateur)
    {
        $utilisateur = $this->publicationRepository->getPublicationsFrom($idUtilisateur);
        if (is_null($utilisateur)){
            throw new ServiceException("L'utilisateur n'existe pas.");
        }

        return $this->publicationRepository->getPublicationsFrom($idUtilisateur);
    }
}