<?php
session_start();

require_once __DIR__ . '/../model/conteneurDB.php';
require_once __DIR__ . '/../model/livraisonDB.php';
require_once __DIR__ . '/../model/commandeDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectClient(){
    header('Location: /SenLogis/clientConteneurs');
    exit;
}

function isValidClientId($id)
{
    return !empty($id) && ctype_digit((string) $id);
}

if (isset($_POST['btnCreateClientRequest'])) {
    $adresse = trim($_POST['adresse'] ?? '');
    $dateLivraison = trim($_POST['dateLivraison'] ?? '');
    $methode = trim($_POST['methode'] ?? '');
    $conteneur_id = trim($_POST['conteneur_id'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($adresse) || empty($dateLivraison) || empty($methode) || empty($conteneur_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectClient();
    }

    if (!isValidClientId($conteneur_id)) {
        $_SESSION['error'] = 'Conteneur invalide.';
        redirectClient();
    }

    $conteneur = getConteneurById($conteneur_id);

    if (!$conteneur || $conteneur['statut'] !== 'disponible') {
        $_SESSION['error'] = 'Ce conteneur n est plus disponible.';
        redirectClient();
    }

    $livraison_id = addLivraisonAndReturnId($adresse, $dateLivraison, 'en_attente', $user_id, $conteneur_id);

    if (!$livraison_id) {
        $_SESSION['error'] = 'Impossible de creer la livraison.';
        redirectClient();
    }

    $commande_id = addCommande(date('Y-m-d'), 'en_attente', $methode, $user_id, $livraison_id);

    if (!$commande_id) {
        $_SESSION['error'] = 'Livraison creee, mais commande non creee.';
        redirectClient();
    }

    updateConteneurStatut($conteneur_id, 'reserve');

    $_SESSION['success'] = 'Votre demande a ete envoyee. Une commande et une livraison en attente ont ete creees.';
    redirectClient();
}

redirectClient();

?>
