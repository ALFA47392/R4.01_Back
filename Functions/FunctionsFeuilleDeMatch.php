<?php
function LireFeuilleMatch($linkpdo) {
    try {
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
    } catch (Exception $e) {
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}

function LireParticiper($linkpdo, $idmatchhokey) {
    try {
        $query = "SELECT * FROM Participer WHERE Id_Match_Hockey = :idmatchhokey";
        $stmt = $linkpdo->prepare($query);
        $stmt->execute(['idmatchhokey' => $idmatchhokey]);
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function createParticiper($linkpdo, $numero_licence, $id_match_hockey, $titulaire, $notation, $poste) {
    try {
        $req = "INSERT INTO `Participer` (`Numero_de_licence`, `Id_Match_Hockey`, `Titulaire`, `Notation`, `Poste`) 
        VALUES (:numero_de_licence, :id_match_hockey, :titulaire, :notation, :poste)";

        $stmt = $linkpdo->prepare($req);
 
        $stmt->execute([
            ':numero_de_licence' => $numero_licence,
            ':id_match_hockey' => $id_match_hockey,
            ':titulaire' => $titulaire,
            ':notation' => $notation,
            ':poste' => $poste
        ]);
        

        return ['success' => true, 'data' => null];
    } catch (Exception $e) {
        error_log("Erreur SQL : " . $e->getMessage()); // Enregistre l'erreur dans les logs
        return ['success' => false, 'data' => $e->getMessage()];
    }
}
function MAJFeuilleMatch($linkpdo, $id, $phrase, $vote, $faute, $signalement) {
    try {
        $req = "UPDATE chuckn_facts SET phrase=:phrase, vote=:vote, date_modif=:date_modif, faute=:faute, signalement=:signalement WHERE id=:id";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(array(
            'phrase' => $phrase,
            'vote' => $vote,
            'date_modif' => date('Y-m-d H:i:s'),
            'faute' => $faute,
            'signalement' => $signalement,
            'id' => $id
        ));

        return [
            'success' => true,
            'data' => null,
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'data' => null
        ];
    }
}


function DELFeuilleMatch($linkpdo, $id) {
    try {
        $req = "DELETE FROM chuckn_facts WHERE id = :id";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['id' => $id]);
    
        return [
            'success' => true,
            'data' => null,
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'data' => null
        ];
    }
}
?>
