<?php
include ('../Functions/functions.php');
include ('../Functions/FunctionsStats.php');
include ('../Functions/FunctionsGeneral.php');

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
            deliver_response(200, "Stats de l'équipe bien transmise", $matchingData['data']);
        } else {
            $numeroLicence = htmlspecialchars($_GET['id']);  // Sécurisation de l'ID
            $matchingData = LireStatsJoueur($linkpdo, $numeroLicence);
            deliver_response(200, "Stat du joueur bien transmise", $matchingData['data']);
        }
    break; 

    default:
    // Méthode HTTP non autorisée
    deliver_response(405, "Méthode non autorisée", null);
    break;
}
?>
