<?php
session_start();

require_once __DIR__ . '/../model/commandeDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectCommandes()
{
    header('Location: /SenLogis/listeCommandes');
    exit;
}

function isValidCommandeId($id)
{
    return !empty($id) && ctype_digit((string) $id);
}

if (isset($_POST['btnAddCommande'])) {
    $date = trim($_POST['date'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');
    $livraison_id = trim($_POST['livraison_id'] ?? '');

    if (empty($date) || empty($statut) || empty($methode) || empty($user_id) || empty($livraison_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectCommandes();
    }

    if (!isValidCommandeId($user_id) || !isValidCommandeId($livraison_id)) {
        $_SESSION['error'] = 'Utilisateur ou livraison invalide.';
        redirectCommandes();
    }

    if (addCommande($date, $statut, $methode, $user_id, $livraison_id)) {
        $_SESSION['success'] = 'Commande ajoutee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l ajout de la commande.';
    }

    redirectCommandes();
}

if (isset($_POST['btnUpdateCommande'])) {
    $id = trim($_POST['id'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');
    $livraison_id = trim($_POST['livraison_id'] ?? '');

    if (empty($id) || empty($date) || empty($statut) || empty($methode) || empty($user_id) || empty($livraison_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectCommandes();
    }

    if (!isValidCommandeId($id) || !isValidCommandeId($user_id) || !isValidCommandeId($livraison_id)) {
        $_SESSION['error'] = 'Identifiant invalide.';
        redirectCommandes();
    }

    if (updateCommande($id, $date, $statut, $methode, $user_id, $livraison_id)) {
        $_SESSION['success'] = 'Commande modifiee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification de la commande.';
    }

    redirectCommandes();
}

if (isset($_POST['btnDeleteCommande'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValidCommandeId($id)) {
        $_SESSION['error'] = 'Identifiant de commande invalide.';
        redirectCommandes();
    }

    if (commandeHasPaiements($id)) {
        $_SESSION['error'] = 'Impossible de supprimer cette commande car elle possede deja un paiement.';
        redirectCommandes();
    }

    if (deleteCommande($id)) {
        $_SESSION['success'] = 'Commande supprimee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression de la commande.';
    }

    redirectCommandes();
}

redirectCommandes();

?>
