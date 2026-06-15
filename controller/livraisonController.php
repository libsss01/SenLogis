<?php
session_start();

require_once __DIR__ . '/../model/livraisonDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectLivraisons()
{
    header('Location: /SenLogis/listeLivraisons');
    exit;
}

function isValid($id)
{
    return !empty($id) && ctype_digit((string)$id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error'] = 'Action non autorisee : les administrateurs peuvent seulement consulter les livraisons.';
    redirectLivraisons();
}

if (isset($_POST['btnAddLivraison'])) {
    $adresse = trim($_POST['adresse'] ?? '');
    $dateLivraison = trim($_POST['dateLivraison'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');
    $conteneur_id = trim($_POST['conteneur_id'] ?? '');

    if (empty($adresse) || empty($dateLivraison) || empty($statut) || empty($user_id) || empty($conteneur_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectLivraisons();
    }

    if (!isValid($user_id) || !isValid($conteneur_id)) {
        $_SESSION['error'] = 'Erreur interne, veuillez réessayer !';
        redirectLivraisons();
    }

    $livraison = addLivraison($adresse, $dateLivraison, $statut, $user_id, $conteneur_id);

    if (!$livraison) {
        $_SESSION['error'] = 'Erreur de création, veuillez réessayer !';
        redirectLivraisons();
    } else {
        $_SESSION['success'] = 'Livraison créée avec succès !';
        redirectLivraisons();
    }
}

if (isset($_POST['btnUpdateLivraison'])) {
    $id = trim($_POST['id'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $dateLivraison = trim($_POST['dateLivraison'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');
    $conteneur_id = trim($_POST['conteneur_id'] ?? '');

    if (empty($id) || empty($adresse) || empty($dateLivraison) || empty($statut) || empty($user_id) || empty($conteneur_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectLivraisons();
    }

    if (!isValid($user_id) || !isValid($conteneur_id) || !isValid($id)) {
        $_SESSION['error'] = 'Erreur interne, veuillez réessayer !';
        redirectLivraisons();
    }

    $livraison = updateLivraison($id, $adresse, $dateLivraison, $statut, $user_id, $conteneur_id);

    if (!$livraison) {
        $_SESSION['error'] = 'Erreur lors de la modification, veuillez réessayer !';
        redirectLivraisons();
    } else {
        $_SESSION['success'] = 'Livraison modifiée avec succès !';
        redirectLivraisons();
    }
}

if (isset($_POST['btnDeleteLivraison'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValid($id)) {
        $_SESSION['error'] = 'Erreur interne, veuillez réessayer !';
        redirectLivraisons();
    }

    $livraison = deleteLivraison($id);

    if (!$livraison) {
        $_SESSION['error'] = 'Erreur lors de la suppression, veuillez réessayer !';
        redirectLivraisons();
    } else {
        $_SESSION['success'] = 'Livraison supprimée avec succès !';
        redirectLivraisons();
    }
}

redirectLivraisons();

?>
