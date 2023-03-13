<?php

namespace TheFeed\Business\Entity;

class Utilisateur {

    private int $idUtilisateur;
    private string $login;
    private string $password;
    private string $adresseMail;
    private string $profilePictureName;

    public function __construct()
    {

    }


    /**
     * @return mixed
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    /**
     * @param mixed $idUtilisateur
     */
    public function setIdUtilisateur(int $idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin(string $login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getAdresseMail()
    {
        return $this->adresseMail;
    }

    /**
     * @param mixed $adresseMail
     */
    public function setAdresseMail(string $adresseMail)
    {
        $this->adresseMail = $adresseMail;
    }

    /**
     * @return mixed
     */
    public function getProfilePictureName()
    {
        return $this->profilePictureName;
    }

    /**
     * @param mixed $profilePictureName
     */
    public function setProfilePictureName(string $profilePictureName)
    {
        $this->profilePictureName = $profilePictureName;
    }

    public static function create(string $login, string $password, string $adresseMail, string $profilePictureName)
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setLogin($login);
        $utilisateur->setPassword($password);
        $utilisateur->setAdresseMail($adresseMail);
        $utilisateur->setProfilePictureName($profilePictureName);

        return $utilisateur;
    }

}