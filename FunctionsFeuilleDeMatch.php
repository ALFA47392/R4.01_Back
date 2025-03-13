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
        // Test de la connexion à la base de données
        var_dump($linkpdo);  // Vérifie que la connexion est bien établie
        
        // Vérification des données reçues
        var_dump($numero_licence, $id_match_hockey, $titulaire, $notation, $poste);  // Affiche les données envoyées à la fonction
        
        $req = "INSERT INTO `Participer` (`Numero_Licence`, `Id_Match_Hockey`, `Titulaire`, `Notation`, `Poste`) VALUES (:numero_licence, :id_match_hockey, :titulaire, :notation, :poste)";
        
        // Test de la requête
        var_dump($req);  // Affiche la requête SQL pour vérification
        
        $stmt = $linkpdo->prepare($req);
        
        // Vérification si la préparation de la requête a échoué
        if ($stmt === false) {
            throw new Exception("Échec de la préparation de la requête SQL. Erreur : " . implode(", ", $linkpdo->errorInfo()));
        }

        $executeResult = $stmt->execute(array(
            'numero_licence' => $numero_licence,
            'id_match_hockey' => $id_match_hockey,
            'titulaire' => $titulaire,
            'notation' => $notation,
            'poste' => $poste
        ));
        
        // Vérification du résultat de l'exécution
        if (!$executeResult) {
            throw new Exception("Échec de l'exécution de la requête SQL. Erreur : " . implode(", ", $stmt->errorInfo()));
        }

        // Test de l'exécution
        var_dump($stmt->rowCount());  // Affiche le nombre de lignes affectées
        
        return [
            'success' => true,
            'data' => null,
        ];
    } catch (PDOException $e) {
        // Affiche le message d'erreur détaillé pour la base de données
        throw new Exception("Erreur PDO : " . $e->getMessage());
    } catch (Exception $e) {
        // Affiche des erreurs plus explicites
        throw new Exception("Erreur générale : " . $e->getMessage());
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
