<?php
session_start();

require_once __DIR__ . '/../model/livraisonDB.php';
require_once __DIR__ . '/../model/conteneurDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectClientDeliveries(){
    header('Location: /SenLogis/clientDemandes');
    exit;
}

function isValidDeliveryId($id){
    return !empty($id) && ctype_digit((string) $id);
}

if (!isset($_POST['btnConfirmClientDelivery'])) {
    redirectClientDeliveries();
}

$livraisonId = trim($_POST['livraison_id'] ?? '');

if (!isValidDeliveryId($livraisonId)) {
    $_SESSION['error'] = 'Livraison invalide.';
    redirectClientDeliveries();
}

$livraison = getLivraisonByUserAndId($livraisonId, $_SESSION['user_id']);

if (!$livraison) {
    $_SESSION['error'] = 'Cette livraison est introuvable pour votre compte.';
    redirectClientDeliveries();
}

if ($livraison['statut'] !== 'livree') {
    $_SESSION['error'] = 'Vous pouvez confirmer uniquement une livraison arrivee.';
    redirectClientDeliveries();
}

updateStatutLivraison($livraisonId, 'confirmee');
updateConteneurStatut($livraison['conteneur_id'], 'disponible');

$_SESSION['success'] = 'Reception confirmee. Vous pouvez maintenant noter la livraison.';
redirectClientDeliveries();
?>
