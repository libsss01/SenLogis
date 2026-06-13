<?php

require_once __DIR__ . "/Model.php";

function getAllCommandes(){
    $sql = "SELECT commandes.*,
                users.nom AS user_nom,
                users.prenom AS user_prenom
            FROM commandes
            JOIN users ON commandes.user_id = users.id
            ORDER BY commandes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function addCommande($numero, $description, $statut, $user_id){
    $sql = "INSERT INTO commandes(numero, description, statut, user_id)
            VALUES(:numero, :description, :statut, :user_id)";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'numero' => $numero,
        'description' => $description,
        'statut' => $statut,
        'user_id' => $user_id
    ]);
}

function updateCommande($id, $numero, $description, $statut, $user_id){
    $sql = "UPDATE commandes
            SET numero = :numero,
                description = :description,
                statut = :statut,
                user_id = :user_id
            WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'numero' => $numero,
        'description' => $description,
        'statut' => $statut,
        'user_id' => $user_id,
        'id' => $id
    ]);
}

function deleteCommande($id){
    $sql = "DELETE FROM commandes 
            WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'id' => $id
    ]);
}
?>