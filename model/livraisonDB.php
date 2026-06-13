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

function getLivraisonsByUser($user_id){
    $sql = "SELECT livraisons.*,
                conteneurs.nom AS conteneur_nom,
                conteneurs.position AS conteneur_position
            FROM livraisons
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE livraisons.user_id = :user_id
            ORDER BY livraisons.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'user_id' => $user_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getLivraisonByUserAndId($livraison_id, $user_id){
    $sql = "SELECT *
            FROM livraisons
            WHERE id = :livraison_id
            AND user_id = :user_id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'livraison_id' => $livraison_id,
        'user_id' => $user_id
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

function getDemandesLivraisonByProprietaire($proprietaire_id){
    $sql = "SELECT livraisons.*,
                commandes.id AS commande_id,
                commandes.statut AS commande_statut,
                commandes.methode AS commande_methode,
                users.nom AS client_nom,
                users.prenom AS client_prenom,
                conteneurs.nom AS conteneur_nom,
                conteneurs.position AS conteneur_position
            FROM livraisons
            JOIN commandes ON commandes.livraison_id = livraisons.id
            JOIN users ON livraisons.user_id = users.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE conteneurs.proprietaire_id = :proprietaire_id
            AND livraisons.statut = 'en_attente'
            ORDER BY livraisons.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'proprietaire_id' => $proprietaire_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getLivraisonForProprietaire($livraison_id, $proprietaire_id){
    $sql = "SELECT livraisons.*
            FROM livraisons
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE livraisons.id = :livraison_id
            AND conteneurs.proprietaire_id = :proprietaire_id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'livraison_id' => $livraison_id,
        'proprietaire_id' => $proprietaire_id
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

function updateStatutLivraison($id, $statut){
    $sql = "UPDATE livraisons
            SET statut = :statut
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'statut' => $statut,
        'id' => $id
    ]);
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

function addLivraisonAndReturnId($adresse, $dateLivraison, $statut, $user_id, $conteneur_id){
    $sql = "INSERT INTO livraisons(adresse, dateLivraison, statut, user_id, conteneur_id)
            VALUES(:adresse, :dateLivraison, :statut, :user_id, :conteneur_id)";

    $db = getConnexion();
    $requeteSecurisee = $db->prepare($sql);
    $requeteSecurisee->execute([
        'adresse' => $adresse,
        'dateLivraison' => $dateLivraison,
        'statut' => $statut,
        'user_id' => $user_id,
        'conteneur_id' => $conteneur_id
    ]);

    return $db->lastInsertId();
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
