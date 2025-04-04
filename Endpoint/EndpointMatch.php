<?php
include ('../Functions/functions.php');
include ('../Functions/FunctionsMatch.php');
include ('../Functions/FunctionsGeneral.php');

// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD']; 

// Récupération du token Bearer depuis les headers
$token = get_bearer_token();

// Vérification validité de l'authentification
verif_auth($token, $auth);

switch ($http_method){ 
    case "GET" : 
        // Récupération des données dans l’URL si nécessaire
        if(!isset($_GET['id'])) { 
            // Appel de la fonction de lecture des phrases 
            $reponse = LireListeMatch($linkpdo);
            if ($reponse['success']==false) {
                deliver_response(404, "Aucune donnée trouvée.", null);
            } else {
                deliver_response(200, "Succès", $reponse['data']);
            }
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Appel de la fonction de lecture des phrases 
            $reponse = LireMatch($linkpdo, $id);
            if ($reponse['success']==false) {
                deliver_response(404, "Aucune match trouvée.", null);
            } else {
                deliver_response(200, "Succès", $reponse['data']);
            }
        }
    break; 
    
    case "POST" : 
        // Récupération des données dans le corps 
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, associative: true);

        $reponse = CréerMatch($linkpdo, $data['Id_Match_Hockey'], $data['Date_Heure_match'], $data['Nom_equipe_adverse'], $data['Lieu_de_rencontre'], $data['ScoreMatch']);

        if ($reponse['success']==true) {
            deliver_response(201, "Données créées avec succès.", $reponse['data']);
        } else {
            deliver_response(400, "Erreur lors de la création des données.", null);
        }
    break;
    
    case "PATCH" :
        if(!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Récupération des données dans le corps 
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, associative: true);
    
            $reponse = patchMatch($linkpdo, $id, $data['Date_Heure_match'], $data['Nom_equipe_adverse'], $data['Lieu_de_rencontre'], $data['ScoreMatch']);
    
            if ($reponse['success']==true) {
                deliver_response(200, "Données mises à jour avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Match non trouvé ou erreur dans la modification", null);
            }
        }
    break;

    case "DELETE" :    
        if (!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id = htmlspecialchars($_GET['id']);
            $reponse = deleteMatch($linkpdo, $id);
            
            if ($reponse['success']==true) {
                deliver_response(200, "Match avec l'ID $id supprimé avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Match non trouvé", null);
            }
        }
    break;

    default:
    // Méthode HTTP non autorisée
    deliver_response(405, "Méthode non autorisée", null);
    break;
}
?>
