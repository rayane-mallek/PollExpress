<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Publication;
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

        $query = $this->pdo->prepare("SELECT * FROM sondages s JOIN utilisateurs u ON u.idUtilisateur = s.idAuteur ORDER BY date DESC");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $sondage) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($sondage['idAuteur']);
            $utilisateur->setLogin($sondage["login"]);
            $utilisateur->setProfilePictureName($sondage["profilePictureName"]);

            $sdg = new Sondage();
            $sdg->setIdSondage($sondage['idPublication']);
            $sdg->setTitre($sondage['message']);
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
        $utilisateur->setIdUtilisateur($result['idAuteur']);
        $utilisateur->setLogin($result["login"]);
        $utilisateur->setProfilePictureName($result["profilePictureName"]);

        return Sondage::create($result['titre'], $utilisateur);
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
            'id' => $sondage->getIdPublication()
        ]);
    }

    public function getSondagesFrom(int $idUtilisateur)
    {
        $sondages = [];

        $query = $this->pdo->prepare("SELECT * FROM sondages s JOIN utilisateurs u ON u.idUtilisateur = s.idAuteur WHERE idUtilisateur = :idUtilisateur ORDER BY date DESC");
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
            $sdg->setIdSondage($sondage['idPublication']);
            $sdg->setTitre($sondage['message']);
            $sdg->setUtilisateur($utilisateur);

            $sondages[] = $sdg;
        }

        return $sondages;
    }
}
