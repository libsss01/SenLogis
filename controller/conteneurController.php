<?php
session_start();

require_once __DIR__ . '/../model/conteneurDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectConteneurs(){
    header('Location: /SenLogis/listeConteneurs');
    exit;
}

function isValidId($id){
    return !empty($id) && ctype_digit((string) $id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['error'] = 'Action non autorisee : les administrateurs peuvent seulement consulter les conteneurs.';
    redirectConteneurs();
}

if (isset($_POST['btnAddConteneur'])) {

    $nom = trim($_POST['nom'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $proprietaire_id = trim($_POST['proprietaire_id'] ?? '');

    if (empty($nom) || empty($statut) || empty($position) || empty($proprietaire_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectConteneurs();
    }

    if (!isValidId($proprietaire_id)) {
        $_SESSION['error'] = 'Proprietaire invalide';
        redirectConteneurs();
    }

    if (addConteneur($nom, $statut, $position, $proprietaire_id)) {
        $_SESSION['success'] = 'Conteneur ajouté avec succès';
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout du conteneur";
    }

    redirectConteneurs();
}

if (isset($_POST['btnUpdateConteneur'])) {

    $id = $_POST['id'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $proprietaire_id = trim($_POST['proprietaire_id'] ?? '');

    if (!isValidId($id)) {
        $_SESSION['error'] = 'Identifiant du conteneur invalide';
        redirectConteneurs();
    }

    if (empty($nom) || empty($statut) || empty($position) || empty($proprietaire_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectConteneurs();
    }

    if (!isValidId($proprietaire_id)) {
        $_SESSION['error'] = 'Proprietaire invalide';
        redirectConteneurs();
    }

    if (updateConteneur($id, $nom, $statut, $position, $proprietaire_id)) {
        $_SESSION['success'] = 'Conteneur modifié avec succès';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification du conteneur';
    }

    redirectConteneurs();
}

redirectConteneurs();


?>
