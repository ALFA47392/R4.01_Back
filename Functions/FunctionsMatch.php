<?php
function LireListematch($linkpdo) {
    try{
        $query = "SELECT * FROM Match_Hockey";
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

function LireMatch ($linkpdo,$id) {
    try{
        $req = "SELECT * FROM Match_Hockey WHERE Id_Match_Hockey = :Id_Match_Hockey";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['Id_Match_Hockey'=>$id]);

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

function CréerMatch($linkpdo,$idMatch,$Date_Heure_match,$Nom_equipe_adverse,$Lieu_de_rencontre,$scoreMatch){
    try{
        $req = "INSERT INTO Match_Hockey (`Id_Match_Hockey`, `Date_Heure_match`, `Nom_equipe_adverse`, `Lieu_de_rencontre`, `ScoreMatch`) VALUES (:Id_Match_Hockey, :Date_Heure_match, :Nom_equipe_adverse, :Lieu_de_rencontre, :ScoreMatch)";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(array(
        'Id_Match_Hockey'=>$idMatch,
        'Date_Heure_match' => $Date_Heure_match,
        'Nom_equipe_adverse' => $Nom_equipe_adverse,
        'Lieu_de_rencontre' => $Lieu_de_rencontre,
        'ScoreMatch' => $scoreMatch
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

function patchMatch($linkpdo, $idMatch, $Date_Heure_match, $Nom_equipe_adverse, $Lieu_de_rencontre, $scoreMatch) {
    try {
        // Vérifier si le joueur existe en utilisant LireJoueur
        $matchExistant = LireMatch($linkpdo, $idMatch);
        
        if (!$matchExistant['success'] || empty($matchExistant['data'])) {
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Match non trouvé",
                'data' => null
            ];
        }

        // Construction dynamique de la requête
        $fields = [];
        $params = ['idMatch' => $idMatch];

        if (isset($Date_Heure_match)) {
            $fields[] = "Date_Heure_match = :Date_Heure_match";
            $params['Date_Heure_match'] = $Date_Heure_match;
        }
        if (isset($Nom_equipe_adverse)) {
            $fields[] = "Nom_equipe_adverse = :Nom_equipe_adverse";
            $params['Nom_equipe_adverse'] = $Nom_equipe_adverse;
        }
        if (isset($Lieu_de_rencontre)) {
            $fields[] = "Lieu_de_rencontre = :Lieu_de_rencontre";
            $params['Lieu_de_rencontre'] = $Lieu_de_rencontre;
        }
        if (isset($scoreMatch)) {
            $fields[] = "ScoreMatch = :ScoreMatch";
            $params['ScoreMatch'] = $scoreMatch;
        }

        if (empty($fields)) {
            return [
                'success' => false,
                'status_code' => 400,
                'status_message' => "Aucun champ à mettre à jour",
                'data' => null
            ];
        }

        $req = "UPDATE Match_Hockey SET " . implode(", ", $fields) . " WHERE id_match_hockey = :idMatch";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute($params);

        // Récupérer les nouvelles données après la mise à jour
        $matchMisAJour = LireMatch($linkpdo, $idMatch);
        

        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Joueur mis à jour avec succès",
            'data' => $matchMisAJour['data']
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

function deleteMatch($linkpdo,$id){
    // Vérifier si le joueur existe en utilisant LireJoueur
    $matchExistant = LireMatch($linkpdo, $id);
        
    if (!$matchExistant['success'] || empty($matchExistant['data'])) {
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Match non trouvé",
            'data' => null
        ];
    }

    try{
        $req = "DELETE FROM match_hockey WHERE Id_match_hockey = :idMatch";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['idMatch'=>$id]);

        $req2 = "DELETE FROM Participer WHERE Id_match_hockey = :idMatch";
        $stmt = $linkpdo->prepare($req2);
        $stmt->execute(['idMatch'=>$id]);
    
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