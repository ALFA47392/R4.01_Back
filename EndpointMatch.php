<?php
include 'connexionBD.php';
include 'functions.php';
include 'functionsMatch.php';
// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD']; 

switch ($http_method){ 
    case "GET" : 
        //Récupération des données dans l’URL si nécessaire
        if(!isset($_GET['id'])) { 
            //Appel de la fonction de lecture des phrases 
            $matchingData=LireListeMatch($linkpdo);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        } else {
            $id=htmlspecialchars($_GET['id']);
            //Appel de la fonction de lecture des phrases 
            $matchingData=LireMatch($linkpdo,$id);
            //Réponse à afficher
            deliver_response(200, "Succès", $matchingData);
        }
    break; 
    case "POST" : 
        // Récupération des données dans le corps 
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData,associative: true);

        $reponse = CréerMatch($linkpdo, $data['Id_Match_Hockey'],$data['Date_Heure_match'],$data['Nom_equipe_adverse'],$data['Lieu_de_rencontre'],$data['ScoreMatch']);

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
    
            $reponse = patchMatch($linkpdo,$id,$data['Date_Heure_match'],$data['Nom_equipe_adverse'],$data['Lieu_de_rencontre'],$data['ScoreMatch']);
    
            if ($reponse['success']) {
                deliver_response(201, "Données crées avec succès.", $reponse['data']);
            } else {
                deliver_response(404, "Not Found", null);
            }
        }
        
    break;
    case "PUT" :

    break;

    case "DEL" :
        if (isset($_GET['id'])) {
            $id = htmlspecialchars($_GET['id']);
            $reponse = deleteMatch($linkpdo, $id);
            
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

