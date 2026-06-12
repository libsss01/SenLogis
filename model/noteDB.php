<?php
require_once __DIR__ . "/Model.php";

function getAllNotes(){
    $sql = "SELECT notes.*,
                users.nom AS user_nom,
                users.prenom AS user_prenom
            FROM notes
            JOIN users ON notes.user_id = users.id
            ORDER BY notes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function addNote($note, $commentaire, $user_id){
    $sql = "INSERT INTO notes(note, commentaire, user_id)
            VALUES(:note, :commentaire, :user_id)";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'note' => $note,
        'commentaire' => $commentaire,
        'user_id' => $user_id
    ]);
}

function updateNote($id, $note, $commentaire, $user_id){
    $sql = "UPDATE notes
            SET note = :note,
                commentaire = :commentaire,
                user_id = :user_id
            WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'note' => $note,
        'commentaire' => $commentaire,
        'user_id' => $user_id,
        'id' => $id
    ]);
}

function deleteNote($id){
    $sql = "DELETE FROM notes 
            WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'id' => $id
    ]);
}
?>