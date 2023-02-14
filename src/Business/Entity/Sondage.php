<?php

namespace TheFeed\Business\Entity;

use DateTime;

class Sondage {

    private int $idSondage;
    private string $titre;
    private Utilisateur $utilisateur;

    // un constructeur
    public function __construct()
    {

    }

    // un getter
    public function getTitre() {
        return $this->titre;
    }

    /**
     * @return mixed
     */
    public function getIdSondage()
    {
        return $this->idSondage;
    }

    /**
     * @param mixed $idSondage
     */
    public function setIdSondage(int $idSondage)
    {
        $this->idSondage = $idSondage;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * @param Utilisateur $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
    }

    // un setter
    public function setTitre(string $titre) {
        $this->titre = $titre;
    }

    public static function create(string $titre, Utilisateur $utilisateur)
    {
        $sondage = new Sondage();
        $sondage->setTitre($titre);
        $sondage->setUtilisateur($utilisateur);

        return $sondage;
    }
}
