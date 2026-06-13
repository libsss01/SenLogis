<?php

require_once __DIR__ . "/Model.php";

function getAllPaiements(){
    $sql = "SELECT paiements.*,
                commandes.date AS commande_date,
                commandes.statut AS commande_statut,
                users.nom AS user_nom,
                users.prenom AS user_prenom,
                livraisons.adresse AS livraison_adresse,
                conteneurs.nom AS conteneur_nom
            FROM paiements
            JOIN commandes ON paiements.commande_id = commandes.id
            JOIN users ON commandes.user_id = users.id
            JOIN livraisons ON commandes.livraison_id = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            ORDER BY paiements.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getPaiementsByUser($user_id){
    $sql = "SELECT paiements.*,
                commandes.id AS commande_id,
                commandes.date AS commande_date,
                commandes.statut AS commande_statut,
                livraisons.adresse AS livraison_adresse,
                livraisons.dateLivraison,
                livraisons.statut AS livraison_statut,
                conteneurs.nom AS conteneur_nom
            FROM paiements
            JOIN commandes ON paiements.commande_id = commandes.id
            JOIN livraisons ON commandes.livraison_id = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE commandes.user_id = :user_id
            ORDER BY paiements.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'user_id' => $user_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function getPaiementsByProprietaire($proprietaire_id){
    $sql = "SELECT paiements.*,
                commandes.id AS commande_id,
                commandes.date AS commande_date,
                commandes.statut AS commande_statut,
                users.nom AS client_nom,
                users.prenom AS client_prenom,
                livraisons.adresse AS livraison_adresse,
                livraisons.dateLivraison,
                livraisons.statut AS livraison_statut,
                conteneurs.nom AS conteneur_nom
            FROM paiements
            JOIN commandes ON paiements.commande_id = commandes.id
            JOIN users ON commandes.user_id = users.id
            JOIN livraisons ON commandes.livraison_id = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE conteneurs.proprietaire_id = :proprietaire_id
            ORDER BY paiements.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'proprietaire_id' => $proprietaire_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function addPaiement($montant, $methode, $statut, $reference, $commande_id){
    $sql = "INSERT INTO paiements(montant, methode, statut, reference, commande_id)
            VALUES(:montant, :methode, :statut, :reference, :commande_id)";

    try {
        $db = getConnexion();
        $requeteSecurisee = $db->prepare($sql);
        $requeteSecurisee->execute([
            'montant' => $montant,
            'methode' => $methode,
            'statut' => $statut,
            'reference' => $reference,
            'commande_id' => $commande_id
        ]);

        return $db->lastInsertId();
    } catch (PDOException $error) {
        return false;
    }
}

function updatePaiement($id, $montant, $methode, $statut, $reference, $commande_id){
    $sql = "UPDATE paiements
            SET montant = :montant,
                methode = :methode,
                statut = :statut,
                reference = :reference,
                commande_id = :commande_id
            WHERE id = :id";

    try {
        $requeteSecurisee = getConnexion()->prepare($sql);
        return $requeteSecurisee->execute([
            'montant' => $montant,
            'methode' => $methode,
            'statut' => $statut,
            'reference' => $reference,
            'commande_id' => $commande_id,
            'id' => $id
        ]);
    } catch (PDOException $error) {
        return false;
    }
}

function deletePaiement($id){
    $sql = "DELETE FROM paiements
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
