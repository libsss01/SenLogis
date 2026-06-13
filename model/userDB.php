<?php
require_once __DIR__ . "/Model.php";

/**
 * Creer un compte utilisateur.
 */
function createUser($nom, $prenom, $email, $motDePasse, $telephone = null, $roleId = 1){
    $sql = "INSERT INTO users (nom, prenom, email, motDePasse, telephone, role_id, etat)
            VALUES (:nom, :prenom, :email, :motDePasse, :telephone, :role_id, 'Actif')";

    $motDePasseHash = password_hash($motDePasse, PASSWORD_DEFAULT);

    $db = getConnexion();
    $requeteSecurisee = $db->prepare($sql);
    $requeteSecurisee->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'motDePasse' => $motDePasseHash,
        'telephone' => $telephone,
        'role_id' => $roleId
    ]);

    return $db->lastInsertId();
}

/**
 * Recuperer un utilisateur via son email.
 */
function getUserByEmail($email){
    $sql = "SELECT * FROM users WHERE email = :email";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'email' => $email
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Authentifier un utilisateur actif.
 */
function loginUser($email, $password){
    $user = getUserByEmail($email);

    if (!$user) {
        return [
            'success' => false,
            'message' => 'Aucun compte trouve avec cet email.',
            'user' => null
            ];
    }

    if ($user['etat'] !== 'Actif') {
        return [
            'success' => false,
            'message' => 'Votre compte est bloque. Contactez un administrateur.',
            'user' => null
        ];
    }

    if (!password_verify($password, $user['motDePasse'])) {
        return [
            'success' => false,
            'message' => 'Mot de passe incorrect.',
            'user' => null
        ];
    }

     return [
        'success' => true,
        'message' => 'Connexion reussie.',
        'user' => $user
    ];
}

/**
 * Recuperer tous les utilisateurs selon leur etat.
 */
function getAllUsers($etat = 'Actif'){
    $sql = "SELECT users.*, roles.nom AS role_nom
            FROM users
            INNER JOIN roles ON roles.id = users.role_id
            WHERE users.etat = :etat
            ORDER BY users.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'etat' => $etat
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Recuperer tous les utilisateurs, actifs et bloques.
 */
function getAllUsersForAdmin(){

    $sql = "SELECT users.*, roles.nom AS role_nom
            FROM users
            INNER JOIN roles ON roles.id = users.role_id
            ORDER BY users.id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compter les lignes d'une table autorisee pour le dashboard.
 */
function countTableRows($table){
    $allowedTables = ['users', 'conteneurs', 'livraisons', 'commandes', 'paiements', 'notes'];

    if (!in_array($table, $allowedTables, true)) {
        return 0;
    }

    $sql = "SELECT COUNT(*) AS total FROM " . $table;
    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute();

    $result = $requeteSecurisee->fetch(PDO::FETCH_ASSOC);
    return $result ? (int) $result['total'] : 0;
}

/**
 * Recuperer les utilisateurs actifs ayant un role precis.
 */
function getUsersByRole($roleId, $etat = 'Actif'){
    $sql = "SELECT users.*, roles.nom AS role_nom
            FROM users
            INNER JOIN roles ON roles.id = users.role_id
            WHERE users.role_id = :role_id
            AND users.etat = :etat
            ORDER BY users.nom ASC";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'role_id' => $roleId,
        'etat' => $etat
    ]);

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Recuperer un utilisateur via son id.
 */
function getUserById($id){
    $sql = "SELECT * FROM users WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    $requeteSecurisee->execute([
        'id' => $id
    ]);

    return $requeteSecurisee->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Modifier les informations principales d'un utilisateur.
 */
function updateUser($id, $nom, $prenom, $email, $telephone, $roleId){
    $sql = "UPDATE users
            SET nom = :nom,
                prenom = :prenom,
                email = :email,
                telephone = :telephone,
                role_id = :role_id
            WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'role_id' => $roleId,
        'id' => $id
    ]);
}

/**
 * Bloquer un utilisateur sans le supprimer physiquement.
 */
function blockUser($id){
    $sql = "UPDATE users SET etat = 'Bloque' WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'id' => $id
    ]);
}

/**
 * Reactiver un utilisateur bloque.
 */
function activateUser($id){
    $sql = "UPDATE users SET etat = 'Actif' WHERE id = :id";

    $requeteSecurisee = getConnexion()->prepare($sql);
    return $requeteSecurisee->execute([
        'id' => $id
    ]);
}
