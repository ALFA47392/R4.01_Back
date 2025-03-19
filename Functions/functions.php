<?php
include '../connexionBD.php';

// Récupération des données dans le corps 
$postedData = file_get_contents('php://input'); 
$data = json_decode($postedData,true); //Reçoit du json et renvoi une adaptation exploitable en php. Le paramètre true impose un tableau en retour et non un objet.

/// Envoi de la réponse au Client 
function deliver_response($status_code, $status_message, $data=null){ 
    /// Paramétrage de l'entête HTTP 
    http_response_code($status_code); 
    //Utilise un message standardisé en fonction du code HTTP 
    //header("HTTP/1.1 $status_code $status_message"); 
    //Permet de personnaliser le message associé au code HTTP 
    header("Content-Type:application/json; charset=utf-8");
    //Indique au client le format de la réponse 
    $response['status_code'] = $status_code; 
    $response['status_message'] = $status_message; 
    $response['data'] = $data; 
    /// Mapping de la réponse au format JSON 
    $json_response = json_encode($response);
    if($json_response===false) die('json encode ERROR : '.json_last_error_msg()); 
    /// Affichage de la réponse (Retourné au client) 
    echo $json_response; 
}

function readChuckFacts($linkpdo) {
    try{
        $query = "SELECT * FROM chuckn_facts";
        $stmt = $linkpdo->prepare($query);
        $stmt->execute();
            
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    }catch(Exception){
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}
    
function createChuckFact($linkpdo,$phrase){
    try{
        $req = "INSERT INTO `chuckn_facts` (`id`, `phrase`, `vote`, `date_ajout`, `date_modif`, `faute`, `signalement`) VALUES (:id, :phrase, :vote, :date_ajout, :date_modif, :faute, :signalement)";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(array(
        'id'=>null,
        'phrase' => $phrase,
        'vote' => null,
        'date_ajout' => date('Y-m-d H:i:s'),
        'date_modif' => date('Y-m-d H:i:s'),
        'faute' => null,
        'signalement' => null,
    ));

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'data' => $data,
        ];
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}

function patchChuckFact($linkpdo, $id, $phrase, $vote, $faute, $signalement){
    try {
        $req = "UPDATE";
    }catch(Exception){

    }
}
function putChuckFact($linkpdo, $id, $phrase, $vote, $faute, $signalement){
    try {
        $req = "UPDATE chuckn_facts SET phrase=:phrase,vote=:vote,date_modif=:date_modif,faute=:faute,signalement=:signalement WHERE id=:id";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(array(
            'phrase' => $phrase,
            'vote' => $vote,
            'date_modif' => date('Y-m-d H:i:s'),
            'faute' => $faute,
            'signalement' => $signalement,
            'id' => $id
        ));
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}
function deleteChuckFact($linkpdo,$id){
    try{
        $req = "DELETE FROM chuckn_facts WHERE id = :id";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['id'=>$id]);
    
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return [
            'success' => true,
            'data' => $data,
        ];
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}

?>