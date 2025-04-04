<?php
// Inclusion du fichier contenant les variables SQL utilisées pour les requêtes
include_once '../SQL/Variables_SQL.php';

// Fonction pour lire les informations de participation d'un match spécifique
function LireParticiper($linkpdo, $idmatchhokey) {
    try {
        global $sql_select_FDM;
        
        // Préparation et exécution de la requête SQL pour récupérer les participations pour un match donné
        $stmt = $linkpdo->prepare($sql_select_FDM);
        $stmt->execute(['idmatchhokey' => $idmatchhokey]);
        
        // Récupération des résultats sous forme de tableau associatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retour des données des participations
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    } catch (Exception $e) {
        // En cas d'erreur, retour d'une réponse d'échec avec message d'erreur
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}

// Fonction pour créer une participation d'un joueur dans un match
function createParticiper($linkpdo, $numero_licence, $id_match_hockey, $titulaire, $notation, $poste) {
    try {
        global $sql_create_participer;

        // Préparation et exécution de la requête d'insertion pour enregistrer la participation
        $stmt = $linkpdo->prepare($sql_create_participer);

        // Récupération des résultats sous forme de tableau associatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
        $stmt->execute([
            ':numero_de_licence' => $numero_licence,
            ':id_match_hockey' => $id_match_hockey,
            ':titulaire' => $titulaire,
            ':notation' => $notation,
            ':poste' => $poste
        ]);

        // Retour de succès après l'insertion de la participation
        return ['success' => true, 'data' => $data];
    } catch (Exception $e) {
        // En cas d'erreur, log l'erreur SQL et retourne un message d'échec
        error_log("Erreur SQL : " . $e->getMessage());
        return ['success' => false, 'data' => $e->getMessage()];
    }
}

// Fonction pour mettre à jour la participation d'un joueur dans un match existant
function updateParticiper($linkpdo, $numero_de_licence, $id_match_hockey, $titulaire, $notation, $poste) {
    try {
        global $sql_update_participer;
        
        // Préparation et exécution de la requête de mise à jour pour la participation du joueur
        $stmt = $linkpdo->prepare($sql_update_participer);
        $stmt->execute([
            ':titulaire' => $titulaire,
            ':notation' => $notation,
            ':poste' => $poste,
            ':numero_de_licence' => $numero_de_licence,
            ':id_match_hockey' => $id_match_hockey
        ]);

        // Vérification si la mise à jour a affecté des lignes, retour d'un message approprié
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'data' => "Mise à jour réussie"];
        } else {
            return ['success' => false, 'data' => "Aucune ligne modifiée"];
        }

    } catch (Exception $e) {
        // Retour d'une erreur si une exception est levée
        return ['success' => false, 'data' => 'Erreur : ' . $e->getMessage()];
    }
}

// Fonction pour supprimer une participation d'un joueur à un match
function deleteParticiper($linkpdo, $numero_de_licence, $id_match_hockey) {
    try {
        global $sql_delete_participer;
        
        // Préparation et exécution de la requête de suppression pour la participation d'un joueur
        $stmt = $linkpdo->prepare($sql_delete_participer);
        $stmt->execute([
            ':numero_de_licence' => $numero_de_licence,
            ':id_match_hockey' => $id_match_hockey
        ]);

        // Vérification si la suppression a affecté des lignes, retour d'un message de succès ou échec
        if ($stmt->rowCount() > 0) {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    } catch (Exception $e) {
        // Retour d'une erreur en cas d'exception pendant la suppression
        return ['success' => false, 'data' => $e->getMessage()];
    }
}

?>
