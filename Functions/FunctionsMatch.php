<?php
// Inclusion du fichier contenant les variables SQL utilisées pour les requêtes
include_once ('../SQL/Variables_SQL.php');

// Fonction pour récupérer la liste de tous les matchs
function LireListematch($linkpdo) {
    try {
        global $sql_select_liste_match;

        // Préparation et exécution de la requête SQL pour récupérer la liste des matchs
        $stmt = $linkpdo->prepare($sql_select_liste_match);
        $stmt->execute();
            
        // Récupération des résultats sous forme de tableau associatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retour des données avec un message de succès
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    } catch (Exception) {
        // En cas d'erreur, retour d'un message d'erreur
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}

// Fonction pour récupérer un match spécifique par son identifiant
function LireMatch($linkpdo, $id) {
    try {
        global $sql_select_match;

        // Préparation de la requête pour récupérer un match par son ID
        $stmt = $linkpdo->prepare($sql_select_match);
        $stmt->execute(['Id_Match_Hockey' => $id]);

        // Récupération des résultats sous forme de tableau associatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si des résultats ont été récupérés
        if (empty($data)) {
            // Si aucun match n'est trouvé, retour d'une réponse d'erreur
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Aucun match trouvé pour cet ID.",
                'data' => null
            ];
        }

        // Retour des données du match
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];

    } catch (Exception $e) {
        // En cas d'exception, retour d'un message d'erreur
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

// Fonction pour créer un nouveau match
function CréerMatch($linkpdo, $idMatch, $Date_Heure_match, $Nom_equipe_adverse, $Lieu_de_rencontre, $scoreMatch) {
    try {
        global $sql_creer_match;

        // Préparation et exécution de la requête d'insertion pour créer un match
        $stmt = $linkpdo->prepare($sql_creer_match);
        $stmt->execute(array(
            'Id_Match_Hockey' => $idMatch,
            'Date_Heure_match' => $Date_Heure_match,
            'Nom_equipe_adverse' => $Nom_equipe_adverse,
            'Lieu_de_rencontre' => $Lieu_de_rencontre,
            'ScoreMatch' => $scoreMatch
        ));

        // Récupération des données après l'insertion (si nécessaire)
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Si l'insertion est réussie, retour d'une réponse avec les données du match
        if ($data) {
            deliver_response(200, "Match créé avec succès.", $data);
            return [
                'success' => true,
                'data' => $data,
            ];
        } else {
            // Si aucune donnée n'a été insérée, renvoyer une réponse d'échec
            deliver_response(400, "Aucune donnée insérée, peut-être que le match existe déjà ?", null);
            return [
                'success' => false,
                'data' => null,
            ];
        }

    } catch (Exception $e) {
        // Gestion des erreurs en cas d'exception
        deliver_response(500, "Erreur interne du serveur : " . $e->getMessage(), null);
        return [
            'success' => false,
            'data' => null
        ];
    }
}


// Fonction pour mettre à jour un match existant
function patchMatch($linkpdo, $idMatch, $Date_Heure_match, $Nom_equipe_adverse, $Lieu_de_rencontre, $scoreMatch) {
    try {
        global $sqlPatchMatchBase;

        // Vérification si le match existe avant de le mettre à jour
        $matchExistant = LireMatch($linkpdo, $idMatch);
        if (!$matchExistant['success'] || empty($matchExistant['data'])) {
            // Retour si le match n'existe pas
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Match non trouvé",
                'data' => null
            ];
        }

        // Tableau pour stocker les champs à mettre à jour et leurs nouvelles valeurs
        $fields = [];
        $params = ['idMatch' => $idMatch];

        // Vérification et ajout des champs modifiés dans la requête SQL
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

        // Si aucun champ n'est renseigné pour mise à jour, retour d'une erreur
        if (empty($fields)) {
            return [
                'success' => false,
                'status_code' => 400,
                'status_message' => "Aucun champ à mettre à jour",
                'data' => null
            ];
        }

        // Construction de la requête SQL de mise à jour
        $sql = $sqlPatchMatchBase . implode(", ", $fields) . " WHERE id_match_hockey = :idMatch";
        $stmt = $linkpdo->prepare($sql);
        $stmt->execute($params);  // Exécution de la mise à jour

        // Récupération du match après mise à jour
        $matchMisAJour = LireMatch($linkpdo, $idMatch);
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Match mis à jour avec succès",
            'data' => $matchMisAJour['data']
        ];

    } catch (Exception $e) {
        // Gestion des erreurs et retour d'un message d'erreur
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

// Fonction pour supprimer un match
function deleteMatch($linkpdo, $id) {
    // Vérification si le match existe avant de le supprimer
    $matchExistant = LireMatch($linkpdo, $id);
        
    if (!$matchExistant['success'] || empty($matchExistant['data'])) {
        // Retour si le match n'existe pas
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Match non trouvé",
            'data' => null
        ];
    }

    try {
        global $sql_delete_match;

        // Exécution de la requête de suppression du match
        $stmt = $linkpdo->prepare($sql_delete_match);
        $stmt->execute(['idMatch' => $id]);

        global $sql_delete_match_FDM;
        
        // Exécution d'une autre requête pour supprimer les données associées au match
        $stmt = $linkpdo->prepare($sql_delete_match_FDM);
        $stmt->execute(['idMatch' => $id]);
    
        // Retour des données après la suppression (si nécessaire)
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return [
            'success' => true,
            'data' => $data,
        ];
    } catch (Exception) {
        // En cas d'erreur, retour d'une réponse d'échec
        return [
            'success' => false,
            'data' => null
        ];
    }
}
?>
