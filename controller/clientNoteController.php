<?php
session_start();

require_once __DIR__ . '/../model/noteDB.php';
require_once __DIR__ . '/../model/livraisonDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 1) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectClientNotes(){
    header('Location: /SenLogis/clientDemandes');
    exit;
}

function isValidClientNoteId($id){
    return !empty($id) && ctype_digit((string) $id);
}

function isValidClientNoteValue($note){
    return ctype_digit((string) $note) && $note >= 1 && $note <= 5;
}

if (isset($_POST['btnAddClientNote'])) {
    $note = trim($_POST['note'] ?? '');
    $numLivraison = trim($_POST['numLivraison'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (empty($note) || empty($numLivraison)) {
        $_SESSION['error'] = 'La note et la livraison sont requises.';
        redirectClientNotes();
    }

    if (!isValidClientNoteValue($note) || !isValidClientNoteId($numLivraison)) {
        $_SESSION['error'] = 'Note ou livraison invalide.';
        redirectClientNotes();
    }

    $livraison = getLivraisonByUserAndId($numLivraison, $user_id);

    if (!$livraison) {
        $_SESSION['error'] = 'Cette livraison est introuvable pour votre compte.';
        redirectClientNotes();
    }

    if ($livraison['statut'] !== 'confirmee') {
        $_SESSION['error'] = 'Vous pouvez noter uniquement apres confirmation de reception.';
        redirectClientNotes();
    }

    if (getNoteByUserAndLivraison($user_id, $numLivraison)) {
        $_SESSION['error'] = 'Vous avez deja note cette livraison.';
        redirectClientNotes();
    }

    if (addNote($note, $numLivraison, $user_id)) {
        $_SESSION['success'] = 'Merci, votre note a ete enregistree.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l enregistrement de la note.';
    }

    redirectClientNotes();
}

redirectClientNotes();

?>
