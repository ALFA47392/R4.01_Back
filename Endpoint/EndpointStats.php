<?php
include '../connexionBD.php';
include '../Functions/functions.php';
include '../Functions/functionsStats.php';
include '../Functions/functionsGeneral.php';

// Récupération de la méthode HTTP
$http_method = $_SERVER['REQUEST_METHOD']; 

// Récupération et vérification du token Bearer
$token = get_bearer_token();
verif_auth($token, $auth);

switch ($http_method){ 
    case "GET" : 
        // Si 'id' est passé en paramètre, on récupère les stats d'un joueur
        if(!isset($_GET['id'])) { 
            $matchingData = LireListeJoueur($linkpdo);
            deliver_response(200, "Stats de l'équipe bien transmise", $matchingData);
        } else {
            $numeroLicence = htmlspecialchars($_GET['id']);  // Sécurisation de l'ID
            $matchingData = LireStatsJoueur($linkpdo, $numeroLicence);
            deliver_response(200, "Stat du joueur bien transmise", $matchingData);
        }
    break; 

    case "POST" : 
        // Pas d'action définie pour POST pour le moment
    break;

    case "PATCH" :
        // Pas d'action définie pour PATCH pour le moment
    break;

    case "PUT" :
        // Pas d'action définie pour PUT pour le moment
    break;

    case "DELETE" :    
        // Pas d'action définie pour DELETE pour le moment
    break;

    case "OPTIONS" :
        // Méthode non gérée, retourne un 405
        deliver_response(405, "Méthode non autorisée pour OPTIONS", null);
    break;
}
?>
