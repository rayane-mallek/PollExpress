<?php

namespace TheFeed\Business\Entity;

use DateTime;

class Publication {

    private int $idPublication;
    private string $message;
    private DateTime $date; //Date de publication
    private Utilisateur $utilisateur;

    // un constructeur
    public function __construct()
    {

    }

    // un getter
    public function getMessage() {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getIdPublication()
    {
        return $this->idPublication;
    }

    /**
     * @param mixed $idPublication
     */
    public function setIdPublication(int $idPublication)
    {
        $this->idPublication = $idPublication;
    }

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    public function getDateFormatee()
    {
        return $this->date->format('d F Y');
    }

    public function getDateFormateeSQL()
    {
        return $this->date->format('Y-m-d H:i:s');
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
    public function setMessage(string $message) {
        $this->message = $message;
    }

    public static function create(string $message, Utilisateur $utilisateur)
    {
        $publication = new Publication();
        $publication->setMessage($message);
        $publication->setUtilisateur($utilisateur);
        $publication->setDate(new DateTime());

        return $publication;
    }
}
