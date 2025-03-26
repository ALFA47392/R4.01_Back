<?php
include '../connexionBD.php';
include '../Functions/functions.php';
include '../Functions/FunctionsFeuilleDeMatch.php';
include '../Functions/functionsGeneral.php';

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


    case "POST" :
        // Vérification de la méthode HTTP
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération et décodage des données JSON envoyées
            $postedData = file_get_contents('php://input');
            $data = json_decode(trim($postedData), true);
    
            // Vérification que les données existent
            if (!isset($data['numero_de_licence'], $data['id_match_hockey'], $data['titulaire'], $data['notation'], $data['poste'])) {
                echo json_encode(["status_code" => 400, "status_message" => "Données manquantes"]);
                exit;
            }
    
            // Appel de la fonction
            $reponse = createParticiper(
                $linkpdo, 
                $data['numero_de_licence'], 
                $data['id_match_hockey'], 
                $data['titulaire'], 
                $data['notation'], 
                $data['poste']
            );
    
            // Réponse en fonction du succès ou non
            if ($reponse['success']) {
                http_response_code(201);
                echo json_encode(["status_code" => 201, "status_message" => "Données créées avec succès"]);
            } else {
                http_response_code(500);
                echo json_encode(["status_code" => 500, "status_message" => "Erreur serveur", "error" => $reponse['data']]);
            }
        } else {
            http_response_code(405);
            echo json_encode(["status_code" => 405, "status_message" => "Méthode non autorisée"]);
        }
    break;




case 'PATCH':
    $postedData = file_get_contents('php://input');
    $data = json_decode(trim($postedData), true);

    if (!isset($data['numero_de_licence'], $data['id_match_hockey'], $data['titulaire'], $data['notation'], $data['poste'])) {
        deliver_response(400, "Données manquantes", null);
        exit;
    }

    $reponse = updateParticiper(
        $linkpdo,
        $data['numero_de_licence'],
        $data['id_match_hockey'],
        $data['titulaire'],
        $data['notation'],
        $data['poste']
    );

    if ($reponse['success']) {
        deliver_response(200, "Mise à jour réussie.", $reponse['data']);
    } else {
        deliver_response(404, "Aucune mise à jour effectuée.", null);
    }
    break;


    //--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    case 'DELETE':
        if (!isset($_GET['numero_de_licence'], $_GET['id_match_hockey'])) {
            deliver_response(400, "Données manquantes", null);
            exit;
        }
    
        $reponse = deleteParticiper($linkpdo, $_GET['numero_de_licence'], $_GET['id_match_hockey']);
    
        if ($reponse['success']) {
            deliver_response(200, "Suppression réussie.", $reponse['data']);
        } else {
            deliver_response(404, "Aucune suppression effectuée.", null);
        }
        break;
}    
?> 