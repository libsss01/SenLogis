<?php
session_start();

require_once __DIR__ . '/../model/userDB.php';

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

function isValidRoleId($roleId){
    return in_array((string) $roleId, ['1', '2', '3'], true);
}

if (isset($_POST['btnAddUser'])) {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $role_id = trim($_POST['role_id'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($prenom) || empty($nom) || empty($email) || empty($role_id) || empty($password)) {
        $_SESSION['error'] = 'Tous les champs obligatoires sont requis.';
        redirectUsers();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !isValidRoleId($role_id)) {
        $_SESSION['error'] = 'Email ou role invalide.';
        redirectUsers();
    }

    if (getUserByEmail($email)) {
        $_SESSION['error'] = 'Un compte existe deja avec cet email.';
        redirectUsers();
    }

    if (createUser($nom, $prenom, $email, $password, $telephone, $role_id)) {
        $_SESSION['success'] = 'Utilisateur ajoute avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l ajout de l utilisateur.';
    }

    redirectUsers();
}

if (isset($_POST['btnUpdateUser'])) {
    $id = trim($_POST['id'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $role_id = trim($_POST['role_id'] ?? '');

    if (empty($id) || empty($prenom) || empty($nom) || empty($email) || empty($role_id)) {
        $_SESSION['error'] = 'Tous les champs obligatoires sont requis.';
        redirectUsers();
    }

    if (!isValidUserId($id) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !isValidRoleId($role_id)) {
        $_SESSION['error'] = 'Donnees utilisateur invalides.';
        redirectUsers();
    }

    $existingUser = getUserByEmail($email);
    if ($existingUser && (int) $existingUser['id'] !== (int) $id) {
        $_SESSION['error'] = 'Cet email est deja utilise par un autre compte.';
        redirectUsers();
    }

    if (updateUser($id, $nom, $prenom, $email, $telephone, $role_id)) {
        $_SESSION['success'] = 'Utilisateur modifie avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification de l utilisateur.';
    }

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
        $_SESSION['success'] = 'Utilisateur bloque avec succes.';
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
        $_SESSION['success'] = 'Utilisateur reactive avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la reactivation de l utilisateur.';
    }

    redirectUsers();
}

redirectUsers();

?>
