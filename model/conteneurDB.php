<?php

require_once __DIR__ . "/Model.php";



function getAllConteneurs(){
    $sql = "SELECT conteneurs.*,
                users.nom AS proprietaire_nom,
                users.prenom AS proprietaire_prenom
            FROM conteneurs
            INNER JOIN users ON users.id = conteneurs.proprietaire_id
            ORDER BY conteneurs.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);

    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);

}


function getConteneursByProprietaire($proprietaire_id){
    $sql = "SELECT *
            FROM conteneurs
            WHERE proprietaire_id = :proprietaire_id
            ORDER BY id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);

    $requeteSecurisee->execute([
        'proprietaire_id' => $proprietaire_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getConteneursDisponibles(){
    $sql = "SELECT conteneurs.*,
                users.nom AS proprietaire_nom,
                users.prenom AS proprietaire_prenom
            FROM conteneurs
            INNER JOIN users ON users.id = conteneurs.proprietaire_id
            WHERE conteneurs.statut = 'disponible'
            ORDER BY conteneurs.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);

    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getConteneurById($id){
    $sql = "SELECT *
            FROM conteneurs
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'id' => $id
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

function updateConteneurStatut($id, $statut){
    $sql = "UPDATE conteneurs
            SET statut = :statut
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'statut' => $statut,
        'id' => $id
    ]);
}


function addConteneur($nom, $statut, $position, $proprietaire_id){
    $sql = "INSERT INTO conteneurs(nom, statut, position, proprietaire_id)
            VALUES(:nom, :statut, :position, :proprietaire_id)";
    
        try {
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'nom' => $nom,
                'statut' => $statut,
                'position' => $position,
                'proprietaire_id' => $proprietaire_id
            ]);
        } catch (PDOException $error) {
            return false;
        }
}

function updateConteneur($id, $nom, $statut, $position, $proprietaire_id){
    $sql = "UPDATE conteneurs
            SET nom = :nom,
                statut = :statut,
                position = :position,
                proprietaire_id = :proprietaire_id
            WHERE id = :id";
    
        try {
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'nom' => $nom,
                'statut' => $statut,
                'position' => $position,
                'proprietaire_id' => $proprietaire_id,
                'id' => $id
            ]);
        } catch (PDOException $error) {
            return false;
        }
}


function deleteConteneur($id){
    $sql = "DELETE FROM conteneurs 
            WHERE id = :id";
    
        try {
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'id' => $id
            ]);
        } catch (PDOException $error) {
            return false;
        }
}

?>
