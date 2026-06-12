<?php
// connexion de l'app avec la base de données
function getConnexion(){
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
