<?php
session_start();

require_once __DIR__ . '/../model/livraisonDB.php';
require_once __DIR__ . '/../model/commandeDB.php';
require_once __DIR__ . '/../model/conteneurDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 2) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectOwner(){
    header('Location: /SenLogis/dashboard_owner.php#demandes-recues');
    exit;
}

function isValidOwnerRequestId($id)
{
    return !empty($id) && ctype_digit((string) $id);
}

$livraison_id = trim($_POST['livraison_id'] ?? '');

if (!isValidOwnerRequestId($livraison_id)) {
    $_SESSION['error'] = 'Demande invalide.';
    redirectOwner();
}

$livraison = getLivraisonForProprietaire($livraison_id, $_SESSION['user_id']);

if (!$livraison || $livraison['statut'] !== 'en_attente') {
    $_SESSION['error'] = 'Cette demande est introuvable ou deja traitee.';
    redirectOwner();
}

if (isset($_POST['btnAcceptOwnerRequest'])) {
    updateStatutLivraison($livraison_id, 'validee');
    updateStatutCommandeByLivraison($livraison_id, 'confirmee');
    updateConteneurStatut($livraison['conteneur_id'], 'en_livraison');

    $_SESSION['success'] = 'Demande acceptee avec succes.';
    redirectOwner();
}

if (isset($_POST['btnRejectOwnerRequest'])) {
    updateStatutLivraison($livraison_id, 'annulee');
    updateStatutCommandeByLivraison($livraison_id, 'annulee');
    updateConteneurStatut($livraison['conteneur_id'], 'disponible');

    $_SESSION['success'] = 'Demande refusee. Le conteneur est de nouveau disponible.';
    redirectOwner();
}

redirectOwner();

?>
