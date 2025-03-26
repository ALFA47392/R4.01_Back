<?php
include '../connexionBD.php';
include '../Functions/functions.php';
include '../Functions/functionsJoueurs.php';
include '../Functions/functionsGeneral.php';

$http_method = $_SERVER['REQUEST_METHOD']; 

// Récupération du token Bearer depuis les headers
$token = get_bearer_token();

//Vérification validité de l'authentification
verif_auth($token,$auth);

switch ($http_method){ 
    case "GET" : 
        //Récupération des données dans l’URL si nécessaire
        if(!isset($_GET['id'])) { 
            //Appel de la fonction de lecture des phrases 
            $matchingData=LireListeJoueur($linkpdo);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        } else {
            $id=htmlspecialchars($_GET['id']);
            //Appel de la fonction de lecture des phrases 
            $matchingData=LireJoueur($linkpdo,$id);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        }
    break; 
    case "POST" : 
        // Récupération des données dans le corps 
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData,associative: true);

        $reponse = CréerJoueur($linkpdo, $data['numLicence'],$data['Nom'],$data['Prenom'],$data['DateNaissance'],$data['Taille'],$data['Poids'],$data['Statut']);

        if ($reponse['success']) {
            deliver_response(201, "Données crées avec succès.", $reponse['data']);
        } else {
            deliver_response(404, "Not Found", null);
        }
    
    case "PATCH" :
        if(!isset($_GET['id'])) {
            deliver_response(400, "Paramètre id invalide", null);
        } else {
            $id=htmlspecialchars($_GET['id']);
            // Récupération des données dans le corps 
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData,associative: true);
    
            $reponse = patchJoueur($linkpdo,$id,$data['Nom'],$data['Prenom'],$data['DateNaissance'],$data['Taille'],$data['Poids'],$data['Statut']);
    
            if ($reponse['success']) {
                deliver_response(201, "Données crées avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        }
        
    break;
    case "PUT" :

    break;

    case "DELETE" :
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $reponse = deleteJoueur($linkpdo, $id);
            
            if ($reponse['success']) {
                deliver_response(200, "Données id:'$id' supprimée avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        }else{
            deliver_response(400, "Paramètre id invalide", null);

        }
    break;

    case "OPTIONS" :
        deliver_response(201,"Normalement c'est ça lol",null);
    break;
}

?>

