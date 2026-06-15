<?php
session_start();

require_once __DIR__ . '/../model/userDB.php';
require_once __DIR__ . '/../model/conteneurDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectUsers(){
    header('Location: /SenLogis/listeUsers');
    exit;
}

function isValidUserId($id){
    return !empty($id) && ctype_digit((string) $id);
}

if (isset($_POST['btnAddUser'])) {
    $_SESSION['error'] = 'Action non autorisee : un administrateur peut seulement bloquer ou reactiver un utilisateur.';
    redirectUsers();
}

if (isset($_POST['btnUpdateUser'])) {
    $_SESSION['error'] = 'Action non autorisee : un administrateur peut seulement bloquer ou reactiver un utilisateur.';
    redirectUsers();
}

if (isset($_POST['btnBlockUser'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValidUserId($id)) {
        $_SESSION['error'] = 'Identifiant utilisateur invalide.';
        redirectUsers();
    }

    if ((int) $id === (int) $_SESSION['user_id']) {
        $_SESSION['error'] = 'Vous ne pouvez pas bloquer votre propre compte admin.';
        redirectUsers();
    }

    if (blockUser($id)) {
        updateConteneursStatutByProprietaire($id, 'indisponible');
        $_SESSION['success'] = 'Utilisateur bloque avec succes. Ses conteneurs sont maintenant indisponibles.';
    } else {
        $_SESSION['error'] = 'Erreur lors du blocage de l utilisateur.';
    }

    redirectUsers();
}

if (isset($_POST['btnActivateUser'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValidUserId($id)) {
        $_SESSION['error'] = 'Identifiant utilisateur invalide.';
        redirectUsers();
    }

    if (activateUser($id)) {
        updateConteneursStatutByProprietaire($id, 'disponible');
        $_SESSION['success'] = 'Utilisateur reactive avec succes. Ses conteneurs sont de nouveau disponibles.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la reactivation de l utilisateur.';
    }

    redirectUsers();
}

redirectUsers();

?>
