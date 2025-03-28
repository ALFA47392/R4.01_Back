<?php
// Inclusion des fichiers nécessaires à la connexion et aux fonctions
include '../connexionBD.php';
include '../Functions/functions.php';
include '../Functions/FunctionsFeuilleDeMatch.php';

// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method) { 
    case "GET":
        // Vérification si un ID est fourni dans l'URL
        if (!isset($_GET['id'])) { 
            // Récupération de toutes les feuilles de match
            $matchingData = LireFeuilleMatch($linkpdo);
            // Vérification si des données ont été récupérées
            if ($matchingData) {
                deliver_response(200, "Succès", $matchingData);
            } else {
                deliver_response(500, "Erreur serveur lors de la récupération des données", null);
            }
        } else {
            // Récupération et sécurisation de l'ID fourni
            $idmatchhokey = htmlspecialchars($_GET['id']);
            // Récupération des données pour un match spécifique
            $matchingData = LireParticiper($linkpdo, $idmatchhokey);
            if ($matchingData) {
                deliver_response(200, "Succès", $matchingData);
            } else {
                deliver_response(404, "Aucune donnée trouvée pour cet ID", null);
            }
        }
        break;

    case "POST":
        // Récupération et décodage des données JSON envoyées
        $postedData = file_get_contents('php://input');
        $data = json_decode(trim($postedData), true);
    
        // Vérification de la présence des données requises
        if (!isset($data['numero_de_licence'], $data['id_match_hockey'], $data['titulaire'], $data['notation'], $data['poste'])) {
            deliver_response(400, "Données manquantes", null);
            exit;
        }
    
        // Appel de la fonction pour ajouter une participation
        $reponse = createParticiper(
            $linkpdo, 
            $data['numero_de_licence'], 
            $data['id_match_hockey'], 
            $data['titulaire'], 
            $data['notation'], 
            $data['poste']
        );
    
        // Vérification du succès de l'opération
        if ($reponse['success']) {
            deliver_response(201, "Données créées avec succès", null);
        } else {
            deliver_response(500, "Erreur serveur", $reponse['data']);
        }
        break;

    case 'PATCH':
        // Récupération et décodage des données JSON envoyées
        $postedData = file_get_contents('php://input');
        $data = json_decode(trim($postedData), true);

        // Vérification de la présence des données requises
        if (!isset($data['numero_de_licence'], $data['id_match_hockey'], $data['titulaire'], $data['notation'], $data['poste'])) {
            deliver_response(400, "Données manquantes", null);
            exit;
        }

        // Appel de la fonction pour mettre à jour une participation
        $reponse = updateParticiper(
            $linkpdo,
            $data['numero_de_licence'],
            $data['id_match_hockey'],
            $data['titulaire'],
            $data['notation'],
            $data['poste']
        );

        // Vérification du succès de la mise à jour
        if ($reponse['success']) {
            deliver_response(200, "Mise à jour réussie", $reponse['data']);
        } else {
            deliver_response(400, "Aucune mise à jour effectuée (données inchangées ou introuvables)", null);
        }
        break;

    case 'DELETE':
        // Vérification de la présence des paramètres nécessaires
        if (!isset($_GET['numero_de_licence'], $_GET['id_match_hockey'])) {
            deliver_response(400, "Données manquantes", null);
            exit;
        }
    
        // Appel de la fonction pour supprimer une participation
        $reponse = deleteParticiper($linkpdo, $_GET['numero_de_licence'], $_GET['id_match_hockey']);
    
        // Vérification si la suppression a bien eu lieu
        if ($reponse['success']) {
            deliver_response(200, "Suppression réussie", $reponse['data']);
        } else {
            deliver_response(404, "Aucune donnée trouvée pour suppression", null);
        }
        break;

    default:
        // Gestion des méthodes HTTP non autorisées
        deliver_response(405, "Méthode non autorisée", null);
        break;
}    
?>
