<?php
session_start();

require_once __DIR__ . '/../model/paiementDB.php';
require_once __DIR__ . '/../model/commandeDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectPaiements(){
    header('Location: /SenLogis/listePaiements');
    exit;
}

function isValidPaiementId($id){
    return !empty($id) && ctype_digit((string) $id);
}

function isValidMontant($montant){
    return is_numeric($montant) && $montant >= 0;
}

function syncCommandePaiementStatut($commande_id, $statutPaiement){
    if ($statutPaiement === 'valide') {
        updateCommandeStatut($commande_id, 'payee');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error'] = 'Action non autorisee : les administrateurs peuvent seulement consulter les paiements.';
    redirectPaiements();
}

if (isset($_POST['btnAddPaiement'])) {
    $montant = trim($_POST['montant'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $reference = trim($_POST['reference'] ?? '');
    $commande_id = trim($_POST['commande_id'] ?? '');

    if ($montant === '' || empty($methode) || empty($statut) || empty($reference) || empty($commande_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectPaiements();
    }

    if (!isValidMontant($montant) || !isValidPaiementId($commande_id)) {
        $_SESSION['error'] = 'Montant ou commande invalide.';
        redirectPaiements();
    }

    if (addPaiement($montant, $methode, $statut, $reference, $commande_id)) {
        syncCommandePaiementStatut($commande_id, $statut);
        $_SESSION['success'] = 'Paiement ajoute avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l ajout du paiement. Verifiez la reference et la commande.';
    }

    redirectPaiements();
}

if (isset($_POST['btnUpdatePaiement'])) {
    $id = trim($_POST['id'] ?? '');
    $montant = trim($_POST['montant'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $reference = trim($_POST['reference'] ?? '');
    $commande_id = trim($_POST['commande_id'] ?? '');

    if (empty($id) || $montant === '' || empty($methode) || empty($statut) || empty($reference) || empty($commande_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectPaiements();
    }

    if (!isValidPaiementId($id) || !isValidMontant($montant) || !isValidPaiementId($commande_id)) {
        $_SESSION['error'] = 'Donnees de paiement invalides.';
        redirectPaiements();
    }

    if (updatePaiement($id, $montant, $methode, $statut, $reference, $commande_id)) {
        syncCommandePaiementStatut($commande_id, $statut);
        $_SESSION['success'] = 'Paiement modifie avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification du paiement.';
    }

    redirectPaiements();
}

if (isset($_POST['btnDeletePaiement'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValidPaiementId($id)) {
        $_SESSION['error'] = 'Identifiant de paiement invalide.';
        redirectPaiements();
    }

    if (deletePaiement($id)) {
        $_SESSION['success'] = 'Paiement supprime avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression du paiement.';
    }

    redirectPaiements();
}

redirectPaiements();

?>
