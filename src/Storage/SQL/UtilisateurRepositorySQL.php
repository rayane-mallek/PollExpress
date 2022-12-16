<?php

namespace TheFeed\Storage\SQL;

use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Utilisateur;

class UtilisateurRepositorySQL implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll() : array
    {
        return [];
    }

    public function get($id)
    {
        $query = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = :id");
        $query->execute([
            'id' => $id
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!is_array($result)) {
            return null;
        }

        $utilisateur = Utilisateur::create($result['login'], $result['password'], $result['adresseMail'], $result['profilePictureName']);
        $utilisateur->setIdUtilisateur($id);

        return $utilisateur;
    }

    public function getByLogin($login)
    {
        $query = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $query->execute([
            'login' => $login
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!is_array($result)) {
            return null;
        }

        $utilisateur = Utilisateur::create($result['login'], $result['password'], $result['adresseMail'], $result['profilePictureName']);
        $utilisateur->setIdUtilisateur($result['idUtilisateur']);

        return $utilisateur;
    }

    public function getByAdresseMail($adresseMail)
    {
        $query = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE adresseMail = :adresseMail");
        $query->execute([
            'adresseMail' => $adresseMail
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!is_array($result)) {
            return null;
        }

        $utilisateur = Utilisateur::create($result['login'], $result['password'], $result['adresseMail'], $result['profilePictureName']);
        $utilisateur->setIdUtilisateur($result['idUtilisateur']);

        return $utilisateur;
    }

    public function create($utilisateur) {
        $query = $this->pdo->prepare("INSERT INTO utilisateurs (login, password, adresseMail, profilePictureName) VALUES (:login, :password, :adresseMail, :profilePictureName)");
        $query->execute([
            'login'              => $utilisateur->getLogin(),
            'password'           => $utilisateur->getPassword(),
            'adresseMail'        => $utilisateur->getAdresseMail(),
            'profilePictureName' => $utilisateur->getProfilePictureName()
        ]);
    }

    public function update($utilisateur)
    {
        
    }

    public function remove($utilisateur)
    {
        $query = $this->pdo->prepare("DELETE FROM utilisateurs WHERE idUtilisateur = :id");
        $query->execute([
            'id' => $utilisateur->getIdUtilisateur()
        ]);
    }
}
