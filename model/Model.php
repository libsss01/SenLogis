<?php
// connexion de l'app avec la base de données
function getConnexion()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "senlogisbd";

    $sql = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        return new PDO($sql, $user, $password);
    } catch (PDOException $error) {
        die("Erreur connexion BD: " . $error->getMessage());
    }
}

// Retourne une connexion PDO en utilisant la fonction getConnexion()
function getModelDB()
{
    return getConnexion();
}

// Exécute une requête préparée et retourne le statement
function model_query(string $sql, array $params = [])
{
    $db = getModelDB();
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

