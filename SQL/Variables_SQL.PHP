<?php
// FEUILLE DE MATCH
$sql_select_FDM = "SELECT * FROM Participer WHERE Id_Match_Hockey = :idmatchhokey";

$sql_create_FDM = "INSERT INTO `Participer` (`Numero_de_licence`, `Id_Match_Hockey`, `Titulaire`, `Notation`, `Poste`) 
        VALUES (:numero_de_licence, :id_match_hockey, :titulaire, :notation, :poste)";

$sql_update_FDM = "UPDATE `Participer` SET Titulaire = :titulaire, Notation = :notation, Poste = :poste
WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";

$sql_delete_FDM = "DELETE FROM `Participer` WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";

// JOUEUR


// MATCH


// PARTICIPER


//STATS


// GENERAL
  
    
?>