<?php

require_once __DIR__ . "/Model.php";

function getAllCommandes()
{
    $sql = "SELECT commandes.*,
                users.nom AS user_nom,
                users.prenom AS user_prenom,
                livraisons.adresse AS livraison_adresse,
                livraisons.dateLivraison,
                livraisons.statut AS livraison_statut,
                conteneurs.nom AS conteneur_nom
            FROM commandes
            JOIN users ON commandes.user_id = users.id
            JOIN livraisons ON commandes.livraison_id = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            ORDER BY commandes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function addCommande($date, $statut, $methode, $user_id, $livraison_id)
{
    $sql = "INSERT INTO commandes(date, statut, methode, user_id, livraison_id)
            VALUES(:date, :statut, :methode, :user_id, :livraison_id)";

    $db = getConnexion();
    $requeteSecurisee = $db->prepare($sql);
    $requeteSecurisee->execute([
        'date' => $date,
        'statut' => $statut,
        'methode' => $methode,
        'user_id' => $user_id,
        'livraison_id' => $livraison_id
    ]);

    return $db->lastInsertId();
}

function updateCommande($id, $date, $statut, $methode, $user_id, $livraison_id)
{
    $sql = "UPDATE commandes
            SET date = :date,
                statut = :statut,
                methode = :methode,
                user_id = :user_id,
                livraison_id = :livraison_id
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'date' => $date,
        'statut' => $statut,
        'methode' => $methode,
        'user_id' => $user_id,
        'livraison_id' => $livraison_id,
        'id' => $id
    ]);
}

function updateCommandeStatut($id, $statut){
    $sql = "UPDATE commandes
            SET statut = :statut
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'statut' => $statut,
        'id' => $id
    ]);
}

function commandeHasPaiements($commande_id){
    $sql = "SELECT COUNT(*) AS total
            FROM paiements
            WHERE commande_id = :commande_id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'commande_id' => $commande_id
    ]);

    $result = $requeteSecurisee->fetch(PDO::FETCH_ASSOC);
    return $result && $result['total'] > 0;
}

function deleteCommande($id)
{
    $sql = "DELETE FROM commandes
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

function getCommandesByUser($user_id)
{
    $sql = "SELECT commandes.*,
                livraisons.adresse,
                livraisons.dateLivraison,
                livraisons.statut AS livraison_statut,
                conteneurs.nom AS conteneur_nom
            FROM commandes
            JOIN livraisons ON commandes.livraison_id = livraisons.id
            JOIN conteneurs ON livraisons.conteneur_id = conteneurs.id
            WHERE commandes.user_id = :user_id
            ORDER BY commandes.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'user_id' => $user_id
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function updateStatutCommandeByLivraison($livraison_id, $statut){
    $sql = "UPDATE commandes
            SET statut = :statut
            WHERE livraison_id = :livraison_id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'statut' => $statut,
        'livraison_id' => $livraison_id
    ]);
}

?>
