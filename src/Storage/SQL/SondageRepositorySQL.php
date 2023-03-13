<?php

namespace TheFeed\Storage\SQL;

use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Sondage;
use TheFeed\Business\Entity\Utilisateur;

class SondageRepositorySQL implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll() : array
    {
        $sondages = [];

        $query = $this->pdo->prepare("SELECT * FROM sondages s JOIN utilisateurs u ON u.idUtilisateur = s.idAuteur");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $sondage) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($sondage['idAuteur']);
            $utilisateur->setLogin($sondage["login"]);
            $utilisateur->setProfilePictureName($sondage["profilePictureName"]);

            $sdg = new Sondage();
            $sdg->setIdSondage($sondage['idSondage']);
            $sdg->setTitre($sondage['titre']);
            $sdg->setUtilisateur($utilisateur);

            $sondages[] = $sdg;
        }

        return $sondages;
    }

    public function get($id)
    {
        $query = $this->pdo->prepare("SELECT idSondage, titre, idUtilisateur, login, profilePictureName FROM sondages s JOIN utilisateurs u ON s.idAuteur = u.idUtilisateur WHERE idSondage = :id");
        $query->execute([
            'id' => $id
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($result['idUtilisateur']);
        $utilisateur->setLogin($result["login"]);
        $utilisateur->setProfilePictureName($result["profilePictureName"]);

        return Sondage::create($result['titre'], $utilisateur, $result['idSondage']);
    }

    public function create($sondage) {
        $query = $this->pdo->prepare("INSERT INTO sondages (titre, idAuteur) VALUES (:titre, :idAuteur)");
        $query->execute([
            'titre'     => $sondage->getTitre(),
            'idAuteur'    => $sondage->getUtilisateur()->getIdUtilisateur()
        ]);
    }

    public function update($sondage)
    {
        $query = $this->pdo->prepare("UPDATE sondages SET titre = :titre WHERE idSondage = :id");
        $query->execute([
            'titre' => $sondage->getTitre(),
            'id' => $sondage->getIdSondage()
        ]);
    }

    public function remove($sondage)
    {
        $query = $this->pdo->prepare("DELETE FROM sondages WHERE idSondage = :id");
        $query->execute([
            'id' => $sondage->getIdSondage()
        ]);
    }

    public function getSondagesFrom(int $idUtilisateur)
    {
        $sondages = [];

        $query = $this->pdo->prepare("SELECT * FROM sondages s JOIN utilisateurs u ON u.idUtilisateur = s.idAuteur WHERE idUtilisateur = :idUtilisateur");
        $query->execute([
            'idUtilisateur' => $idUtilisateur
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $sondage) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($sondage['idAuteur']);
            $utilisateur->setLogin($sondage["login"]);
            $utilisateur->setProfilePictureName($sondage["profilePictureName"]);

            $sdg = new Sondage();
            $sdg->setIdSondage($sondage['idSondage']);
            $sdg->setTitre($sondage['titre']);
            $sdg->setUtilisateur($utilisateur);

            $sondages[] = $sdg;
        }

        return $sondages;
    }

    public function getNbOui(int $idSondage) : int
    {
        $query = $this->pdo->prepare("SELECT COUNT(*) FROM votes WHERE idSondage = :idSondage AND choix = true");
        $query->execute([
            'idSondage' => $idSondage
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['COUNT(*)'];
    }

    public function getNbNon(int $idSondage) : int
    {
        $query = $this->pdo->prepare("SELECT COUNT(*) FROM votes WHERE idSondage = :idSondage AND choix = false");
        $query->execute([
            'idSondage' => $idSondage
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result['COUNT(*)'];
    }

    public function voteOui(int $idSondage, int $idUtilisateur)
    {
        $query = $this->pdo->prepare("INSERT INTO votes (idSondage, idVotant, choix) VALUES (:idSondage, :idUtilisateur, true)");
        $query->execute([
            'idSondage' => $idSondage,
            'idUtilisateur' => $idUtilisateur
        ]);
    }

    public function voteNon(int $idSondage, int $idUtilisateur)
    {
        $query = $this->pdo->prepare("INSERT INTO votes (idSondage, idVotant, choix) VALUES (:idSondage, :idUtilisateur, false)");
        $query->execute([
            'idSondage' => $idSondage,
            'idUtilisateur' => $idUtilisateur
        ]);
    }

}
