<?php
require_once __DIR__ . "/Model.php";

function getAllPaiements(){
    $sql = "SELECT * FROM paiements ORDER BY id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

function addPaiement($montant, $methode, $datePaiement, $commande_id = null){
    $sql = "INSERT INTO paiements(montant, methode, datePaiement, commande_id)
            VALUES(:montant, :methode, :datePaiement, :commande_id)";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'montant' => $montant,
        'methode' => $methode,
        'datePaiement' => $datePaiement,
        'commande_id' => $commande_id ?: null
    ]);
}

function updatePaiement($id, $montant, $methode, $datePaiement, $commande_id = null){
    $sql = "UPDATE paiements
            SET montant = :montant,
                methode = :methode,
                datePaiement = :datePaiement,
                commande_id = :commande_id
            WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'montant' => $montant,
        'methode' => $methode,
        'datePaiement' => $datePaiement,
        'commande_id' => $commande_id ?: null,
        'id' => $id
    ]);
}

function deletePaiement($id){
    $sql = "DELETE FROM paiements WHERE id = :id";
    
    $requeteSecurisee = getConnexion()->prepare($sql);

    return $requeteSecurisee->execute([
        'id' => $id
    ]);
}
?>