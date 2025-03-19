<?php
include '../connexionBD.php';
include '../Functions/functions.php';
include '../Functions/functionsStats.php';
// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD']; 

switch ($http_method){ 
    case "GET" : 
        //Récupération des données dans l’URL si nécessaire
        if(!isset($_GET['id'])) { 
            //Appel de la fonction de lecture des phrases 
            $matchingData=LireListeJoueur($linkpdo);
            //Réponse à afficher
            deliver_response(200, "Stats de l'équipe bien transmise", $matchingData);
        } else {
            $matchingData=LireStatsJoueur($linkpdo,$numeroLicence);
            deliver_response(200, "Stat du joueurs bien transmise", $matchingData);
        }
    break; 
    case "POST" : 
    
    case "PATCH" :

    break;
    case "PUT" :

    break;

    case "DELETE" :    

    break;


    case "OPTIONS" :
        deliver_response(404,"Aucune fonction(s) associée(s)",null);
    break;
}

?>

