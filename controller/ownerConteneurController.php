<?php
session_start();

require_once __DIR__ . '/../model/conteneurDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectOwnerDashboard()
{
    header('Location: /SenLogis/dashboard_owner.php#mes-conteneurs');
    exit;
}

function isValidOwnerConteneurStatut($statut)
{
    return in_array($statut, ['disponible', 'maintenance'], true);
}

if (isset($_POST['btnAddOwnerConteneur'])) {
    $nom = trim($_POST['nom'] ?? '');
    $statut = trim($_POST['statut'] ?? '');
    $position = trim($_POST['position'] ?? '');
    $proprietaire_id = $_SESSION['user_id'];

    if (empty($nom) || empty($statut) || empty($position)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectOwnerDashboard();
    }

    if (!isValidOwnerConteneurStatut($statut)) {
        $_SESSION['error'] = 'Statut de conteneur invalide.';
        redirectOwnerDashboard();
    }

    if (addConteneur($nom, $statut, $position, $proprietaire_id)) {
        $_SESSION['success'] = 'Conteneur ajoute avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l ajout du conteneur. Verifiez que le nom est unique.';
    }

    redirectOwnerDashboard();
}

redirectOwnerDashboard();

?>
