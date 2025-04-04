<?php
include_once '../SQL/Variables_SQL.php';

function LireListematch($linkpdo) {
    try{
        global $sql_select_liste_match;

        $stmt = $linkpdo->prepare($sql_select_liste_match);
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
        global $sql_select_match;

        $stmt = $linkpdo->prepare($sql_select_match);
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
        global $sql_creer_match;
        
        $stmt = $linkpdo->prepare($sql_creer_match);
        $stmt->execute(array(
        'Id_Match_Hockey'=>$idMatch,
        'Date_Heure_match' => $Date_Heure_match,
        'Nom_equipe_adverse' => $Nom_equipe_adverse,
        'Lieu_de_rencontre' => $Lieu_de_rencontre,
        'ScoreMatch' => $scoreMatch));
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

        $sql_modif_match = "UPDATE Match_Hockey SET " . implode(", ", $fields) . " WHERE id_match_hockey = :idMatch";
        $stmt = $linkpdo->prepare($sql_modif_match);
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
        global $sql_delete_match;
        
        $stmt = $linkpdo->prepare($sql_delete_match);
        $stmt->execute(['idMatch'=>$id]);

        global $sql_delete_match_FDM;
        
        $stmt = $linkpdo->prepare($sql_delete_match_FDM);
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