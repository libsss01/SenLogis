<?php
session_start();

require_once __DIR__ . '/../model/noteDB.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role_id'] != 3) {
    header('Location: ../public/views/pages/notes/liste.php');
    exit;
}

function redirectNotes(){
    header('Location: /SenLogis/views/pages/admin/notes/liste.php');
    exit;
}

function isValid($id){
    return !empty($id) && ctype_digit((string)$id);
}

if (isset($_POST['btnAddNote'])) {
    $note = trim($_POST['note'] ?? '');
    $commentaire = trim($_POST['commentaire'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($note) || empty($commentaire) || empty($user_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectNotes();
    }

    if (!isValid($user_id) || $note < 1 || $note > 5) {
        $_SESSION['error'] = 'Données invalides. La note doit être entre 1 et 5';
        redirectNotes();
    }

    if (addNote($note, $commentaire, $user_id)) {
        $_SESSION['success'] = 'Note ajoutée avec succès !';
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de la note";
    }
    redirectNotes();
}

if (isset($_POST['btnUpdateNote'])) {
    $id = trim($_POST['id'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $commentaire = trim($_POST['commentaire'] ?? '');
    $user_id = trim($_POST['user_id'] ?? '');

    if (empty($id) || empty($note) || empty($commentaire) || empty($user_id)) {
        $_SESSION['error'] = 'Tous les champs sont requis';
        redirectNotes();
    }

    if (!isValid($id) || !isValid($user_id) || $note < 1 || $note > 5) {
        $_SESSION['error'] = 'Données invalides. La note doit être entre 1 et 5';
        redirectNotes();
    }

    if (updateNote($id, $note, $commentaire, $user_id)) {
        $_SESSION['success'] = 'Note modifiée avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la modification de la note';
    }
    redirectNotes();
}

if (isset($_POST['btnDeleteNote'])) {
    $id = trim($_POST['id'] ?? '');

    if (!isValid($id)) {
        $_SESSION['error'] = 'Identifiant invalide';
        redirectNotes();
    }

    if (deleteNote($id)) {
        $_SESSION['success'] = 'Note supprimée avec succès !';
    } else {
        $_SESSION['error'] = 'Erreur lors de la suppression';
    }
    redirectNotes();
}

redirectNotes();
?>