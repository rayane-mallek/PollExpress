<?php

namespace TheFeed\Storage\SQL;

use DateTime;
use Framework\Storage\Repository;
use PDO;
use TheFeed\Business\Entity\Publication;
use TheFeed\Business\Entity\Utilisateur;

class PublicationRepositorySQL implements Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll() : array
    {
        $publications = [];

        $query = $this->pdo->prepare("SELECT * FROM publications p JOIN utilisateurs u ON u.idUtilisateur = p.idAuteur ORDER BY date DESC");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $publication) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($publication['idAuteur']);
            $utilisateur->setLogin($publication["login"]);
            $utilisateur->setProfilePictureName($publication["profilePictureName"]);
            
            $pub = new Publication();
            $pub->setIdPublication($publication['idPublication']);
            $pub->setMessage($publication['message']);
            $pub->setDate(new DateTime($publication['date']));
            $pub->setUtilisateur($utilisateur);

            $publications[] = $pub;
        }

        return $publications;
    }

    public function get($id)
    {
        $query = $this->pdo->prepare("SELECT idPublication, message, date, idUtilisateur, login, profilePictureName FROM publications p JOIN utilisateurs u ON p.idAuteur = u.idUtilisateur WHERE idPublication = :id");
        $query->execute([
            'id' => $id
        ]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $utilisateur = new Utilisateur();
        $utilisateur->setIdUtilisateur($result['idAuteur']);
        $utilisateur->setLogin($result["login"]);
        $utilisateur->setProfilePictureName($result["profilePictureName"]);

        return Publication::create($result['message'], $utilisateur);
    }

    public function create($publication) {
        $query = $this->pdo->prepare("INSERT INTO publications (message, date, idAuteur) VALUES (:message, :date, :idAuteur)");
        $query->execute([
            'message'     => $publication->getMessage(),
            'date'        => $publication->getDateFormateeSQL(),
            'idAuteur'    => $publication->getUtilisateur()->getIdUtilisateur()
        ]);
    }

    public function update($publication)
    {
        $query = $this->pdo->prepare("UPDATE publications SET message = :message WHERE idPublication = :id");
        $query->execute([
                'message' => $publication->getMessage(),
                'id'      => $publication->getIdPublication()
        ]);
    }

    public function remove($publication)
    {
        $query = $this->pdo->prepare("DELETE FROM publications WHERE idPublication = :id");
        $query->execute([
            'id' => $publication->getIdPublication()
        ]);
    }

    public function getPublicationsFrom(int $idUtilisateur)
    {
        $publications = [];

        $query = $this->pdo->prepare("SELECT * FROM publications p JOIN utilisateurs u ON u.idUtilisateur = p.idAuteur WHERE idUtilisateur = :idUtilisateur ORDER BY date DESC");
        $query->execute([
            'idUtilisateur' => $idUtilisateur
        ]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $publication) {
            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($publication['idAuteur']);
            $utilisateur->setLogin($publication["login"]);
            $utilisateur->setProfilePictureName($publication["profilePictureName"]);
            
            $pub = new Publication();
            $pub->setIdPublication($publication['idPublication']);
            $pub->setMessage($publication['message']);
            $pub->setDate(new DateTime($publication['date']));
            $pub->setUtilisateur($utilisateur);

            $publications[] = $pub;
        }

        return $publications;
    }
}
