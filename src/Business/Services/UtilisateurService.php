<?php

namespace TheFeed\Business\Services;

use Framework\Service\UserSessionManager;
use Framework\Storage\RepositoryManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TheFeed\Business\Entity\Utilisateur;
use TheFeed\Business\Exception\ServiceException;

class UtilisateurService {

    protected $repository;
    protected string $salt;
    protected string $pictureDir;
    protected UserSessionManager $sessionManager;

    public function __construct(RepositoryManager $repositoryManager, string $salt, $pictureDir, UserSessionManager $sessionManager)
    {
        $this->repository = $repositoryManager->getRepository(Utilisateur::class);
        $this->salt = $salt;
        $this->pictureDir = $pictureDir;
        $this->sessionManager = $sessionManager;
    }

    public function getUtilisateur(int $id, bool $allowNull = true) 
    {
        $utilisateur = $this->repository->get($id);
        if ($allowNull || !is_null($utilisateur)) {
            return $utilisateur;
        }

        throw new ServiceException("L'utilisateur n'existe pas.");
    }

    public function getUtilisateurByLogin(string $login, bool $allowNull = true) 
    {
        $utilisateur = $this->repository->getByLogin($login);
        if ($allowNull || !is_null($utilisateur)) {
            return $utilisateur;
        }

        throw new ServiceException("L'utilisateur n'existe pas.");
    }

    public function getUtilisateurByAdresseEmail(string $adresseMail, bool $allowNull = true) 
    {
        $utilisateur = $this->repository->getByAdresseMail($adresseMail);
        if ($allowNull || !is_null($utilisateur)) {
            return $utilisateur;
        }

        throw new ServiceException("L'utilisateur n'existe pas.");
    }

    public function createUtilisateur(string $login, string $password, string $adresseMail, UploadedFile $file)
    {
        if (is_null($login) || is_null($password) || is_null($adresseMail) || is_null($file)) { // aucune donnée null
            throw new ServiceException("Veuillez renseigner tous les champs.");
        }

        if (strlen($login) > 20 || strlen($login) < 4) {
            throw new ServiceException("Le login doit être compris entre 4 et 20 caractères.");
        }

        if (!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,20}$#', $password)) {
            throw new ServiceException("Le mot de passe doit être compris entre 8 et 20 caractères, posséder une minuste, une majuscule et un nombre.");
        }

        if (!filter_var($adresseMail, FILTER_VALIDATE_EMAIL)) {
            throw new ServiceException("L'adresse mail est invalide.");
        }

        if (!is_null($this->getUtilisateurByLogin($login))) {
            throw new ServiceException("Un utilisateur existe déjà avec ce login.");
        }

        if (!is_null($this->getUtilisateurByAdresseEmail($adresseMail))) {
            throw new ServiceException("Un utilisateur utilise déjà cette adresse mail.");
        }

        $extension = $file->guessExtension();
        if (!in_array($extension, ["png", "jpg", "jpeg"])) {
            throw new ServiceException("L'image doit être au format png, jpg ou jpeg.");
        }
        
        $saltedPassword = $this->salt . $password;
        $cryptedPassword = password_hash($saltedPassword, PASSWORD_BCRYPT);
        
        $fileName = hash('sha256', $file->getClientOriginalName()) . '.' . $extension;
        $file->move($this->pictureDir, $fileName);

        $this->repository->create(Utilisateur::create($login, $cryptedPassword, $adresseMail, $fileName));
    }

    public function connexion(string $login, string $password) 
    {
        $utilisateur = $this->repository->getByLogin($login);
        if (is_null($utilisateur)){
            throw new ServiceException("Login ou mot de passe incorrect.");
        }

        $saltedPassword = $this->salt . $password;

        if (password_verify($saltedPassword, $utilisateur->getPassword())) {
            $this->sessionManager->set('id', $utilisateur->getIdUtilisateur());
        } else {
            throw new ServiceException("Login ou mot de passe incorrect.");
        }
    }

    public function getUserId()
    {
        return $this->sessionManager->get('id');
    }

    public function estConnecte()
    {
        return $this->sessionManager->has('id');
    }

    public function deconnexion()
    {
        $this->sessionManager->remove('id');
    }
}
