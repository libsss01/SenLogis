<?php
session_start();

require_once __DIR__ . '/../model/paiementDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectPaiements(){
    header('Location: /SenLogis/views/pages/admin/paiements/liste.php');
    exit;
}

function isValid($id){
    return !empty($id) && ctype_digit((string)$id);
}

if (isset($_POST['btnAddPaiement'])) {
    $montant = trim($_POST['montant'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $datePaiement = trim($_POST['datePaiement'] ?? '');
    $commande_id = trim($_POST['commande_id'] ?? '');

    if (empty($montant) || empty($methode) || empty($datePaiement)) {
        $_SESSION['error'] = 'Les champs Montant, Méthode et Date sont requis';
        redirectPaiements();
    }

    if (!empty($commande_id) && !isValid($commande_id)) {
        $_SESSION['error'] = 'ID de commande invalide';
        redirectPaiements();
    }

    if (addPaiement($montant, $methode, $datePaiement, $commande_id)) {
        $_SESSION['success'] = 'Simulation de paiement réussie !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la création du paiement';
    }
    redirectPaiements();
}

if (isset($_POST['btnUpdatePaiement'])) {
    $id = trim($_POST['id'] ?? '');
    $montant = trim($_POST['montant'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $datePaiement = trim($_POST['datePaiement'] ?? '');
    $commande_id = trim($_POST['commande_id'] ?? '');

    if (empty($id) || empty($montant) || empty($methode) || empty($datePaiement)) {
        $_SESSION['error'] = 'Tous les champs requis doivent être remplis';
        redirectPaiements();
    }

    if (!isValid($id) || (!empty($commande_id) && !isValid($commande_id))) {
        $_SESSION['error'] = 'Identifiants invalides détectés';
        redirectPaiements();
    }

    if (updatePaiement($id, $montant, $methode, $datePaiement, $commande_id)) {
        $_SESSION['success'] = 'Paiement modifié avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification';
    }
    redirectPaiements();
}

if (isset($_POST['btnDeletePaiement'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValid($id)) {
        $_SESSION['error'] = 'Identifiant invalide';
        redirectPaiements();
    }

    if (deletePaiement($id)) {
        $_SESSION['success'] = 'Paiement supprimé avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression';
    }
    redirectPaiements();
}

redirectPaiements();
?>