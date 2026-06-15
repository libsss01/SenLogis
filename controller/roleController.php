<?php
session_start();
require_once __DIR__ . '/../model/userDB.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_POST['btnChooseRole'])) {
    header('Location: ../choisirRole');
    exit;
}

// Recupération du role_id via le formulaire, si ce dernier est nulll on le définis à 0
$roleId = (int) ($_POST['role_id'] ?? 0);

// Vérification du role_id si il n'est pas 1 ou 2
if (!in_array($roleId, [1, 2], true)) {
    $_SESSION['error'] = 'Type de compte invalide.';
    header('Location: ../choisirRole');
    exit;
}

// Mise a jour du role de l'utilisateur apres connexion
if (!updateUserRole($_SESSION['user_id'], $roleId)) {
    $_SESSION['error'] = 'Impossible de sauvegarder votre type de compte.';
    header('Location: ../choisirRole');
    exit;
}

// affectation de la variable de session avec le roleId définis aprezs connexion
$_SESSION['user_role_id'] = $roleId;
unset($_SESSION['error']);


// condition pour personnaliser l'affichage de la vue selon le role de l'utilisateur avec l'opérateur de coercion
$redirect = $roleId === 2 ? '../dashboardProprietaire' : '../clientDashboard';
header('Location: ' . $redirect);
exit;
?>
