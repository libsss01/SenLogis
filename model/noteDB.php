<?php

require_once __DIR__ . "/Model.php";

function getAllNotes(){
    $sql = "SELECT notes.*,
                users.nom AS user_nom,
                users.prenom AS user_prenom,
                livraisons.adresse AS livraison_adresse,
                livraisons.dateLivraison,
                conteneurs.nom AS conteneur_nom
            FROM notes
            JOIN users ON notes.user_id = users.id
            JOIN livraisons ON notes.numLivraison = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            ORDER BY notes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getNotesByUser($user_id){
    $sql = "SELECT notes.*,
                livraisons.adresse AS livraison_adresse,
                livraisons.dateLivraison,
                livraisons.statut AS livraison_statut,
                conteneurs.nom AS conteneur_nom
            FROM notes
            JOIN livraisons ON notes.numLivraison = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE notes.user_id = :user_id
            ORDER BY notes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'user_id' => $user_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getNoteByUserAndLivraison($user_id, $numLivraison){
    $sql = "SELECT *
            FROM notes
            WHERE user_id = :user_id
            AND numLivraison = :numLivraison";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'user_id' => $user_id,
        'numLivraison' => $numLivraison
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

function addNote($note, $numLivraison, $user_id){
    $sql = "INSERT INTO notes(note, numLivraison, user_id)
            VALUES(:note, :numLivraison, :user_id)";

    try {
        $db = getConnexion();
        $requeteSecurisee = $db->prepare($sql);
        $requeteSecurisee->execute([
            'note' => $note,
            'numLivraison' => $numLivraison,
            'user_id' => $user_id
        ]);

        return $db->lastInsertId();
    } catch (PDOException $error) {
        return false;
    }
}

function updateNote($id, $note, $numLivraison, $user_id){
    $sql = "UPDATE notes
            SET note = :note,
                numLivraison = :numLivraison,
                user_id = :user_id
            WHERE id = :id";

    try {
        $requeteSecurisee = getConnexion()->prepare($sql);
        return $requeteSecurisee->execute([
            'note' => $note,
            'numLivraison' => $numLivraison,
            'user_id' => $user_id,
            'id' => $id
        ]);
    } catch (PDOException $error) {
        return false;
    }
}

function deleteNote($id){
    $sql = "DELETE FROM notes
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
