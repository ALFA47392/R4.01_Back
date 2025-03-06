<?php
function LireListeJoueur($linkpdo) {
    try{
        $query = "SELECT * FROM joueur";
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
function LireJoueur ($linkpdo,$id) {
    try{
        $req = "SELECT * FROM joueur WHERE Numero_de_licence = :Numero_de_licence";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['Numero_de_licence'=>$id]);

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

function Créer($linkpdo,$phrase){
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

?>