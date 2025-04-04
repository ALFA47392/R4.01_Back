<?php
include_once ('../SQL/Variables_SQL.php');

// Fonction pour lire la liste de tous les joueurs avec leurs statistiques
function LireListeJoueur($linkpdo) {
    global $sql_joueurs_base, $sql_total_matchs_avant_maintenant, $sql_postes_joueur, $sql_titularisations_joueur,
           $sql_victoires_joueur, $sql_moyenne_notation, $sql_matchs_joueur, $sql_stats_equipe;

    try {
        // Date actuelle pour les comparaisons
        $dateActuelle = date('Y-m-d H:i:s');

        // Récupérer tous les joueurs
        $stmtJoueurs = $linkpdo->prepare($sql_joueurs_base);
        $stmtJoueurs->execute();
        $joueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer le nombre total de matchs
        $stmtTotalMatches = $linkpdo->prepare($sql_total_matchs_avant_maintenant);
        $stmtTotalMatches->execute([$dateActuelle]);
        $totalMatches = $stmtTotalMatches->fetchColumn();

        // Parcourir chaque joueur pour récupérer ses statistiques
        foreach ($joueurs as &$joueur) {
            $licence = $joueur['Numero_de_licence'];

            // Récupérer le poste préféré du joueur
            $stmtPostes = $linkpdo->prepare($sql_postes_joueur);
            $stmtPostes->execute([$licence]);
            $joueur['Postes_Preferes'] = $stmtPostes->fetchColumn() ?: 'Non spécifié';

            // Récupérer le nombre de titularisations du joueur
            $stmtTitularisations = $linkpdo->prepare($sql_titularisations_joueur);
            $stmtTitularisations->execute([$licence, $dateActuelle]);
            $joueur['Titularisations'] = $stmtTitularisations->fetchColumn();
            $joueur['Remplacements'] = $totalMatches - $joueur['Titularisations'];

            // Calculer le pourcentage de victoires du joueur
            $stmtVictoires = $linkpdo->prepare($sql_victoires_joueur);
            $stmtVictoires->execute([$licence, $dateActuelle]);
            $victoires = $stmtVictoires->fetchColumn();
            $joueur['PourcentageVictoires'] = ($joueur['Titularisations'] > 0) ? round(($victoires / $joueur['Titularisations']) * 100, 2) : 0;

            // Récupérer la moyenne de notation du joueur
            $stmtNotation = $linkpdo->prepare($sql_moyenne_notation);
            $stmtNotation->execute([$licence, $dateActuelle]);
            $joueur['MoyenneNotation'] = round($stmtNotation->fetchColumn() ?: 0, 2);

            // Récupérer les matchs joués par le joueur
            $stmtMatchs = $linkpdo->prepare($sql_matchs_joueur);
            $stmtMatchs->execute([$licence, $dateActuelle]);
            $matchs = $stmtMatchs->fetchAll(PDO::FETCH_ASSOC);

            // Calculer le nombre de matchs consécutifs
            $joueur['MatchsConsecutifs'] = 0;
            if (count($matchs) > 1) {
                $current = 0;
                $max = 0;
                foreach ($matchs as $match) {
                    if ($match['Titulaire'] == 1) {
                        $current++;
                    } else {
                        $max = max($max, $current);
                        $current = 0;
                    }
                }
                $joueur['MatchsConsecutifs'] = max($max, $current);
            }
        }

        // Récupérer les statistiques globales de l'équipe
        $stmtStatsEquipe = $linkpdo->prepare($sql_stats_equipe);
        $stmtStatsEquipe->execute([$dateActuelle]);
        $statsEquipe = $stmtStatsEquipe->fetch(PDO::FETCH_ASSOC);

        // Calculer les pourcentages des victoires, défaites et nuls pour l'équipe
        $matchsJoues = $statsEquipe['MatchsJoues'] ?: 0;
        $statsEquipe['PourcentageVictoires'] = ($matchsJoues > 0) ? round(($statsEquipe['Victoires'] / $matchsJoues) * 100, 2) : 0;
        $statsEquipe['PourcentageDefaites'] = ($matchsJoues > 0) ? round(($statsEquipe['Defaites'] / $matchsJoues) * 100, 2) : 0;
        $statsEquipe['PourcentageNuls'] = ($matchsJoues > 0) ? round(($statsEquipe['MatchsNuls'] / $matchsJoues) * 100, 2) : 0;

        // Retourner les données des joueurs et de l'équipe
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => [
                'joueurs' => $joueurs,
                'stats_equipe' => $statsEquipe
            ]
        ];
    } catch (Exception $e) {
        // En cas d'erreur, retourner un message d'erreur avec les détails
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

// Fonction pour lire les statistiques d'un joueur spécifique
function LireStatsJoueur($linkpdo, $numeroLicence) {
    global $sql_info_joueur, $sql_postes_joueur_unique, $sql_titularisations_joueur_total, $sql_total_matchs_global;

    try {
        // Récupérer les informations du joueur
        $stmtJoueur = $linkpdo->prepare($sql_info_joueur);
        $stmtJoueur->execute([$numeroLicence]);

        if ($stmtJoueur->rowCount() === 0) {
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Joueur non trouvé.",
                'data' => null
            ];
        }

        $joueur = $stmtJoueur->fetch(PDO::FETCH_ASSOC);

        // Récupérer les postes préférés du joueur
        $stmtPostes = $linkpdo->prepare($sql_postes_joueur_unique);
        $stmtPostes->execute([$numeroLicence]);
        $joueur['Postes_Preferes'] = $stmtPostes->fetchColumn() ?: 'Non spécifié';

        // Récupérer les titularisations du joueur
        $stmtTitularisations = $linkpdo->prepare($sql_titularisations_joueur_total);
        $stmtTitularisations->execute([$numeroLicence]);
        $joueur['Titularisations'] = $stmtTitularisations->fetchColumn();

        // Récupérer le nombre total de matchs joués
        $stmtTotalMatches = $linkpdo->prepare($sql_total_matchs_global);
        $stmtTotalMatches->execute();
        $totalMatches = $stmtTotalMatches->fetchColumn();

        // Calculer les matchs consécutifs pour le joueur
        $joueur['MatchsConsecutifs'] = calculateConsecutiveMatches($linkpdo, $numeroLicence);

        // Retourner les données du joueur
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $joueur
        ];
    } catch (Exception $e) {
        // En cas d'erreur, retourner un message d'erreur avec les détails
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}
?>
