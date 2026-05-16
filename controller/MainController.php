<?php
require_once __DIR__ . '/../model/Model.php';

function processLogin()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email et mot de passe requis';
        header('Location: login.php');
        exit;
    }

    $stmt = model_query("SELECT * FROM users WHERE email = ?", [$email]);
    $user = $stmt->fetch();

    // Si l'utilisateur n'existe pas
    if (!$user) {
        $_SESSION['error'] = 'Utilisateur non trouvé. Veuillez vous enregistrer.';
        $_SESSION['redirect_register'] = true;
        header('Location: login.php');
        exit;
    }

    // Si le mot de passe est incorrect
    if (!password_verify($password, $user['motDePasse'])) {
        $_SESSION['error'] = 'Mot de passe incorrect';
        header('Location: login.php');
        exit;
    }

    // Connexion réussie
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role_id'] = $user['role_id'];
    $_SESSION['user_name'] = $user['nom'];
    unset($_SESSION['error']);
    unset($_SESSION['redirect_register']);

    // Redirection selon le rôle_id
    $dashboard = [
        3 => 'admin.php',           // admin
        2 => 'dashboard_owner.php', // proprietaire_conteneur
        1 => 'user.php'             // client
    ];

    $redirect = $dashboard[$_SESSION['user_role_id']] ?? 'user.php';
    header('Location: ' . $redirect);
    exit;
}

function processRegister()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Tous les champs requis';
        header('Location: register.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
        header('Location: register.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Minimum 6 caractères';
        header('Location: register.php');
        exit;
    }

    $stmt = model_query("SELECT id FROM users WHERE email = ?", [$email]);
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Email déjà existant';
        header('Location: register.php');
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    model_query("INSERT INTO users (nom, email, motDePasse, role_id) VALUES (?, ?, ?, ?)", 
                [$name, $email, $hashed, 1]);

    $_SESSION['success'] = 'Inscription réussie ! Connectez-vous.';
    header('Location: login.php');
    exit;
}

function processLogout()
{
    session_destroy();
    header('Location: index.php');
    exit;
}
