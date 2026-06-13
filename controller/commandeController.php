<?php
session_start();

require_once __DIR__ . '/../model/commandeDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectCommandes(){
    header('Location: /SenLogis/views/pages/admin/commandes/liste.php');
    exit;
}

function isValid($id){
    return !empty($id) && ctype_digit((string)$id);
}

if (isset($_POST['btnAddCommande'])) {
    $numero = trim($_POST['numero'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($numero) || empty($statut) || empty($user_id)) {
        $_SESSION['error'] = 'Le numéro, le statut et le client sont obligatoires';
        redirectCommandes();
    }

    if (!isValid($user_id)) {
        $_SESSION['error'] = 'Client invalide';
        redirectCommandes();
    }

    if (addCommande($numero, $description, $statut, $user_id)) {
        $_SESSION['success'] = 'Commande créée avec succès !';
    } else {
        $_SESSION['error'] = "Erreur lors de la création de la commande";
    }

    redirectCommandes();
}

if (isset($_POST['btnUpdateCommande'])) {
    $id = trim($_POST['id'] ?? '');
    $numero = trim($_POST['numero'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($id) || empty($numero) || empty($statut) || empty($user_id)) {
        $_SESSION['error'] = 'Tous les champs requis doivent être remplis';
        redirectCommandes();
    }

    if (!isValid($id) || !isValid($user_id)) {
        $_SESSION['error'] = 'Identifiants invalides';
        redirectCommandes();
    }

    if (updateCommande($id, $numero, $description, $statut, $user_id)) {
        $_SESSION['success'] = 'Commande modifiée avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification';
    }

    redirectCommandes();
}

if (isset($_POST['btnDeleteCommande'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValid($id)) {
        $_SESSION['error'] = 'Identifiant invalide';
        redirectCommandes();
    }

    if (deleteCommande($id)) {
        $_SESSION['success'] = 'Commande supprimée avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression';
    }

    redirectCommandes();
}

redirectCommandes();
?>