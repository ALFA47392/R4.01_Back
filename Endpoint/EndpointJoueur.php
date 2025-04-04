<?php
include ('../Functions/functions.php');
include ('../Functions/FunctionsJoueurs.php');
include ('../Functions/FunctionsGeneral.php');

$http_method = $_SERVER['REQUEST_METHOD']; 

// Récupération du token Bearer depuis les headers
$token = get_bearer_token();

// Vérification validité de l'authentification
verif_auth($token, $auth);

switch ($http_method){ 
    case "GET" : 
        // Récupération des données dans l’URL si nécessaire
        if (!isset($_GET['id'])) { 
            // Appel de la fonction de lecture des joueurs
            $matchingData = LireListeJoueur($linkpdo);
            // Réponse à afficher
            deliver_response(200, "Succès", $matchingData['data']);
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Appel de la fonction de lecture du joueur
            $matchingData = LireJoueur($linkpdo, $id);
            // Réponse à afficher
            if ($matchingData['success']==true) {
                deliver_response(200, "Succès", $matchingData['data']);
            } else {
                deliver_response(404, "Joueur non trouvé", null);
            }
        }
    break; 
    
       
    
    case "POST" : 
        // Récupération des données dans le corps 
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);

        // Vérification de la présence des données requises
        if (
            !isset(
                $data['numLicence'],
                $data['Nom'],
                $data['Prenom'],
                $data['DateNaissance'],
                $data['Taille'],
                $data['Poids'],
                $data['Statut']
            )
        ) {
            deliver_response(400, "Données manquantes", null);
            exit;
        }

        $reponse = CréerJoueur($linkpdo, $data['numLicence'], $data['Nom'], $data['Prenom'], $data['DateNaissance'], $data['Taille'], $data['Poids'], $data['Statut']);

        if ($reponse['success']==true) {
            deliver_response(201, "Données créées avec succès.", $reponse['data']);
        } else {
            deliver_response(400, "Erreur lors de la création des données.", null);
        }
    break;
    
    case "PATCH" :
        if (!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id = htmlspecialchars($_GET['id']);
            // Récupération des données dans le corps 
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, associative: true);
    
            $reponse = patchJoueur($linkpdo, $id, $data['Nom'], $data['Prenom'], $data['DateNaissance'], $data['Taille'], $data['Poids'], $data['Statut']);
    
            if ($reponse['success']) {
                deliver_response(200, "Données mises à jour avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Joueur non trouvé ou erreur dans la modification", null);
            }
        }
    break;

    case "DELETE" :
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $reponse = deleteJoueur($linkpdo, $id);
            
            if ($reponse['success']) {
                deliver_response(200, "Joueur avec l'ID $id supprimé avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Joueur non trouvé", null);
            }
        } else {
            deliver_response(400, "Paramètre id invalide", null);
        }
    break;

    default:
    // Méthode HTTP non autorisée
    deliver_response(405, "Méthode non autorisée", null);
    break;
}
?>
