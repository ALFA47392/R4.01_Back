<?php

include '../SQL/Variables_SQL.php';

function LireParticiper($linkpdo, $idmatchhokey) {
    try {
        $stmt = $linkpdo->prepare($sql_select_FDM);
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


function createParticiper($linkpdo, $numero_licence, $id_match_hockey, $titulaire, $notation, $poste) {
    try {
        $stmt = $linkpdo->prepare($sql_create_FDM);
 
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


function updateParticiper($linkpdo, $numero_de_licence, $id_match_hockey, $titulaire, $notation, $poste) {
    try {
        $stmt = $linkpdo->prepare($sql_update_FDM);
        $stmt->execute([
            ':numero_de_licence' => $numero_de_licence,
            ':id_match_hockey' => $id_match_hockey,
            ':titulaire' => $titulaire,
            ':notation' => $notation,
            ':poste' => $poste
        ]);

        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'data' => "Mise à jour réussie"];
        } else {
            return ['success' => false, 'data' => "Aucune ligne modifiée"];
        }
    } catch (Exception $e) {
        return ['success' => false, 'data' => $e->getMessage()];
    }
}

//--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

function deleteParticiper($linkpdo, $numero_de_licence, $id_match_hockey) {
    try {
        $stmt = $linkpdo->prepare($sql_delete_FDM);
        $stmt->execute([
            ':numero_de_licence' => $numero_de_licence,
            ':id_match_hockey' => $id_match_hockey
        ]);

        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'data' => "Suppression réussie"];
        } else {
            return ['success' => false, 'data' => "Aucune ligne supprimée"];
        }
    } catch (Exception $e) {
        return ['success' => false, 'data' => $e->getMessage()];
    }
}

?>
