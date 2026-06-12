<?php

require_once __DIR__ . "/Model.php";


function getAllLivraisons(){
    $sql = "SELECT livraisons.*,
                users.nom AS user_nom,
                users.prenom AS user_prenom,
                conteneurs.nom AS conteneur_nom
            FROM livraisons
            JOIN users ON livraisons.user_id = users.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            ORDER BY livraisons.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);

    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);

}


function addLivraison($adresse, $dateLivraison, $statut, $user_id, $conteneur_id){
    $sql = "INSERT INTO livraisons(adresse, dateLivraison, statut, user_id, conteneur_id)
            VALUES(:adresse, :dateLivraison, :statut, :user_id, :conteneur_id)";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'adresse' => $adresse,
                'dateLivraison' => $dateLivraison,
                'statut' => $statut,
                'user_id' => $user_id,
                'conteneur_id' => $conteneur_id 
            ]);
}

function updateLivraison($id, $adresse, $dateLivraison, $statut, $user_id, $conteneur_id){
    $sql = "UPDATE livraisons
            SET adresse = :adresse,
                dateLivraison = :dateLivraison,
                statut = :statut,
                user_id = :user_id,
                conteneur_id = :conteneur_id
            WHERE id = :id";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'adresse' => $adresse,
                'dateLivraison' => $dateLivraison,
                'statut' => $statut,
                'user_id' => $user_id,
                'conteneur_id' => $conteneur_id, 
                'id' => $id
            ]);
}


function deleteLivraison($id){
    $sql = "DELETE FROM livraisons
            WHERE id = :id";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'id' => $id
            ]);
}

?>