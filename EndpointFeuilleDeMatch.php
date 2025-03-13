<?php
include 'connexionBD.php';
include 'functions.php';
include 'FunctionsFeuilleDeMatch.php';
// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD']; 

switch ($http_method){ 
    case "GET" : 
        //Récupération des données dans l’URL si nécessaire
        if(!isset($_GET['id'])) { 
            //Appel de la fonction de lecture des phrases 
            $matchingData = LireFeuilleMatch($linkpdo);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        } else {
            $idmatchhokey = htmlspecialchars($_GET['id']);
            //Appel de la fonction de lecture des phrases 
            $matchingData = LireParticiper($linkpdo, $idmatchhokey);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        }
    break; 

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    case "POST" : 
        // Récupération des données dans le corps 
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);

        $reponse = createParticiper(
            $linkpdo, 
            $data['numero_licence'], 
            $data['id_match_hockey'], 
            $data['titulaire'], 
            $data['notation'], 
            $data['poste']
        );

        if ($reponse['success']) {
            deliver_response(201, "Données créées avec succès.", $reponse['data']);
        } else {
            deliver_response(404, "Not Found", null);
        }
    break;


    case "PATCH" :
        if(!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Récupération des données dans le corps 
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
    
            $reponse = patchChuckFact($linkpdo, $id, $data['phrase'], $data['vote'], $data['faute'], $data['signalement']);
    
            if ($reponse['success']) {
                deliver_response(201, "Données mises à jour avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        }
    break;

    case "PUT" :
        if(!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Récupération des données dans le corps 
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
    
            $reponse = putChuckFact($linkpdo, $id, $data['phrase'], $data['vote'], $data['faute'], $data['signalement']);
    
            if ($reponse['success']) {
                deliver_response(201, "Données mises à jour avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        }
    break;
    case "DELETE" :
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $reponse = deleteChuckFact($linkpdo, $id);
            
            if ($reponse['success']) {
                deliver_response(200, "Données id:'$id' supprimée avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        } else {
            deliver_response(400, "Paramètre id invalide", null);
        }
    break;
    case "OPTIONS" :
        deliver_response(201, "Normalement c'est ça lol", null);
    break;
}

?>