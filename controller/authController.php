<?php
session_start();
require_once __DIR__ . "/../model/userDB.php";

function keepRegisterOldValues($nom, $prenom, $email, $telephone){
    $_SESSION['old'] = [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone
    ];
}

if (isset($_POST["btnLogin"])) {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = 'Email et mot de passe requis';
        header('Location: ../login.php');
        exit;
    }

    $result = loginUser($email, $password);

    if (!$result['success']) {
        $_SESSION['error'] = $result['message'];
        header('Location: ../login.php');
        exit;
    }

    $user = $result["user"];

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["id"];
    $_SESSION["user_name"] = trim($user["prenom"] . ' ' . $user["nom"]);
    $_SESSION["user_email"] = $user["email"];
    $_SESSION["user_role_id"] = $user["role_id"];
    $_SESSION["user_telephone"] = $user["telephone"];
    $_SESSION["user_etat"] = $user["etat"];
    unset($_SESSION['error']);

    $dashboard = [
        3 => '../admin.php',
        2 => '../dashboard_owner.php',
        1 => '../user.php'
    ];

    $redirect = $dashboard[$_SESSION['user_role_id']] ?? '../user.php';
    header('Location: ' . $redirect);
    exit;
}

if (isset($_POST["btnRegister"])) {

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $telephone = $telephone ?: null;

    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirm_password)) {
        keepRegisterOldValues($nom, $prenom, $email, $telephone);
        $_SESSION['error'] = 'Tous les champs requis';
        header('Location: ../register.php');
        exit;
    }

    if ($password !== $confirm_password) {
        keepRegisterOldValues($nom, $prenom, $email, $telephone);
        $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
        header('Location: ../register.php');
        exit;
    }

    if (strlen($password) < 6) {
        keepRegisterOldValues($nom, $prenom, $email, $telephone);
        $_SESSION['error'] = 'Minimum 6 caracteres';
        header('Location: ../register.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        keepRegisterOldValues($nom, $prenom, '', $telephone);
        $_SESSION['error'] = 'Format email invalide.';
        header('Location: ../register.php');
        exit;
    }

    if (getUserByEmail($email)) {
        keepRegisterOldValues($nom, $prenom, '', $telephone);
        $_SESSION['error'] = 'Cet email est deja utilise. Connectez-vous ou utilisez un autre email';
        header('Location: ../register.php');
        exit;
    }

    $user = createUser($nom, $prenom, $email, $password, $telephone, 1);

    if (!$user) {
        keepRegisterOldValues($nom, $prenom, $email, $telephone);
        $_SESSION['error'] = 'Erreur ! Reessayez a nouveau';
        header('Location: ../register.php');
        exit;
    }

    unset($_SESSION['old']);
    $_SESSION['success'] = 'Inscription reussie ! Connectez-vous.';
    header('Location: ../login.php');
    exit;
}

if (isset($_GET["action"]) && $_GET["action"] == 'logout') {
    $_SESSION = [];
    session_destroy();
    header("Location: ../index.php");
    exit;
}

header('Location: ../login.php');
exit;
?>
