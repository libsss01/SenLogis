<?php
session_start();

require_once __DIR__ . '/../model/noteDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: /SenLogis/login.php');
    exit;
}

function redirectNotes(){
    header('Location: /SenLogis/listeNotes');
    exit;
}

function isValidNoteId($id){
    return !empty($id) && ctype_digit((string) $id);
}

function isValidNoteValue($note){
    return ctype_digit((string) $note) && $note >= 1 && $note <= 5;
}

if (isset($_POST['btnAddNote'])) {
    $note = trim($_POST['note'] ?? '');
    $numLivraison = trim($_POST['numLivraison'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($note) || empty($numLivraison) || empty($user_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectNotes();
    }

    if (!isValidNoteValue($note) || !isValidNoteId($numLivraison) || !isValidNoteId($user_id)) {
        $_SESSION['error'] = 'Note, livraison ou utilisateur invalide.';
        redirectNotes();
    }

    if (addNote($note, $numLivraison, $user_id)) {
        $_SESSION['success'] = 'Note ajoutee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de l ajout de la note.';
    }

    redirectNotes();
}

if (isset($_POST['btnUpdateNote'])) {
    $id = trim($_POST['id'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $numLivraison = trim($_POST['numLivraison'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($id) || empty($note) || empty($numLivraison) || empty($user_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis.';
        redirectNotes();
    }

    if (!isValidNoteId($id) || !isValidNoteValue($note) || !isValidNoteId($numLivraison) || !isValidNoteId($user_id)) {
        $_SESSION['error'] = 'Donnees de note invalides.';
        redirectNotes();
    }

    if (updateNote($id, $note, $numLivraison, $user_id)) {
        $_SESSION['success'] = 'Note modifiee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification de la note.';
    }

    redirectNotes();
}

if (isset($_POST['btnDeleteNote'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValidNoteId($id)) {
        $_SESSION['error'] = 'Identifiant de note invalide.';
        redirectNotes();
    }

    if (deleteNote($id)) {
        $_SESSION['success'] = 'Note supprimee avec succes.';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression de la note.';
    }

    redirectNotes();
}

redirectNotes();

?>
