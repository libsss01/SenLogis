<?php

require_once __DIR__ . "/Model.php";



function getAllConteneurs(){
    $sql = "SELECT *
    FROM conteneurs
    ORDER BY id DESC";

    $requeteSecurisee = getConnexion()->prepare($sql);

    $requeteSecurisee->execute();

    return $requeteSecurisee->fetchAll(PDO::FETCH_ASSOC);

}


function addConteneur($nom, $statut, $position){
    $sql = "INSERT INTO conteneurs(nom, statut, position)
            VALUES(:nom, :statut, :position)";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'nom' => $nom,
                'statut' => $statut,
                'position' => $position 
            ]);
}

function updateConteneur($id, $nom, $statut, $position){
    $sql = "UPDATE conteneurs
            SET nom = :nom,
                statut = :statut,
                position = :position
            WHERE id = :id";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'nom' => $nom,
                'statut' => $statut,
                'position' => $position,
                'id' => $id
            ]);
}


function deleteConteneur($id){
    $sql = "DELETE FROM conteneurs 
            WHERE id = :id";
    
            $requeteSecurisee = getConnexion()->prepare($sql);

            return $requeteSecurisee->execute([
                'id' => $id
            ]);
}

?>