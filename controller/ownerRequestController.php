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
    header('Location: /SenLogis/demandesProprietaire');
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

if (!$livraison) {
    $_SESSION['error'] = 'Cette demande est introuvable.';
    redirectOwner();
}

if (isset($_POST['btnAcceptOwnerRequest'])) {
    if ($livraison['statut'] !== 'en_attente') {
        $_SESSION['error'] = 'Seule une demande en attente peut etre acceptee.';
        redirectOwner();
    }

    updateStatutLivraison($livraison_id, 'validee');
    updateStatutCommandeByLivraison($livraison_id, 'confirmee');
    updateConteneurStatut($livraison['conteneur_id'], 'en_livraison');

    $_SESSION['success'] = 'Demande acceptee avec succes.';
    redirectOwner();
}

if (isset($_POST['btnRejectOwnerRequest'])) {
    if ($livraison['statut'] !== 'en_attente') {
        $_SESSION['error'] = 'Seule une demande en attente peut etre refusee.';
        redirectOwner();
    }

    updateStatutLivraison($livraison_id, 'annulee');
    updateStatutCommandeByLivraison($livraison_id, 'annulee');
    updateConteneurStatut($livraison['conteneur_id'], 'disponible');

    $_SESSION['success'] = 'Demande refusee. Le conteneur est de nouveau disponible.';
    redirectOwner();
}

if (isset($_POST['btnMarkDeliveredOwnerRequest'])) {
    if (!in_array($livraison['statut'], ['validee', 'en_cours'], true)) {
        $_SESSION['error'] = 'Cette livraison ne peut pas encore etre marquee comme arrivee.';
        redirectOwner();
    }

    updateStatutLivraison($livraison_id, 'livree');

    $_SESSION['success'] = 'Livraison marquee comme arrivee. Le client doit maintenant confirmer la reception.';
    redirectOwner();
}

redirectOwner();

?>
