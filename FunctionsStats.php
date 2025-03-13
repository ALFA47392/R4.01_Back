<?php
function LireListeJoueur($linkpdo) {
    try {
        $dateActuelle = date('Y-m-d H:i:s');
        
        // Récupération des joueurs de base
        $queryJoueurs = "SELECT Numero_de_licence, Nom, Prenom, Statut FROM Joueur";
        $stmtJoueurs = $linkpdo->prepare($queryJoueurs);
        $stmtJoueurs->execute();
        $joueurs = $stmtJoueurs->fetchAll(PDO::FETCH_ASSOC);

        // Récupération du nombre total de matchs joués avant la date actuelle
        $queryTotalMatches = "SELECT COUNT(*) FROM Match_Hockey WHERE Date_Heure_match < ?";
        $stmtTotalMatches = $linkpdo->prepare($queryTotalMatches);
        $stmtTotalMatches->execute([$dateActuelle]);
        $totalMatches = $stmtTotalMatches->fetchColumn();

        foreach ($joueurs as &$joueur) {
            $licence = $joueur['Numero_de_licence'];
            
            // Récupérer les postes préférés
            $queryPostes = "SELECT GROUP_CONCAT(DISTINCT Poste ORDER BY Poste SEPARATOR ', ') AS Postes FROM Participer WHERE Numero_de_licence = ?";
            $stmtPostes = $linkpdo->prepare($queryPostes);
            $stmtPostes->execute([$licence]);
            $joueur['Postes_Preferes'] = $stmtPostes->fetchColumn() ?: 'Non spécifié';
            
            // Nombre de titularisations avant la date actuelle
            $queryTitularisations = "SELECT COUNT(*) FROM Participer P JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey WHERE P.Numero_de_licence = ? AND P.Titulaire = 1 AND M.Date_Heure_match < ?";
            $stmtTitularisations = $linkpdo->prepare($queryTitularisations);
            $stmtTitularisations->execute([$licence, $dateActuelle]);
            $joueur['Titularisations'] = $stmtTitularisations->fetchColumn();
            
            // Nombre de remplacements (Total de matchs avant la date actuelle - Titularisations)
            $joueur['Remplacements'] = $totalMatches - $joueur['Titularisations'];
            
            // Pourcentage de victoires avant la date actuelle
            $queryVictoires = "
                SELECT COUNT(*) FROM Participer P
                JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey
                WHERE P.Numero_de_licence = ? AND P.Titulaire = 1
                AND M.Date_Heure_match < ?
                AND CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS SIGNED) > CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS SIGNED)";
            $stmtVictoires = $linkpdo->prepare($queryVictoires);
            $stmtVictoires->execute([$licence, $dateActuelle]);
            $victoires = $stmtVictoires->fetchColumn();
            $joueur['PourcentageVictoires'] = ($joueur['Titularisations'] > 0) ? round(($victoires / $joueur['Titularisations']) * 100, 2) : 0;
            
            // Moyenne des notes
            $queryNotation = "SELECT AVG(Notation) FROM Participer P JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey WHERE P.Numero_de_licence = ? AND M.Date_Heure_match < ?";
            $stmtNotation = $linkpdo->prepare($queryNotation);
            $stmtNotation->execute([$licence, $dateActuelle]);
            $joueur['MoyenneNotation'] = round($stmtNotation->fetchColumn() ?: 0, 2);

            // Récupération des matchs joués par le joueur, triés par date décroissante
            $queryMatchs = "SELECT M.Id_Match_Hockey, P.Titulaire FROM Participer P JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey WHERE P.Numero_de_licence = ? AND M.Date_Heure_match < ? ORDER BY M.Date_Heure_match DESC";
            $stmtMatchs = $linkpdo->prepare($queryMatchs);
            $stmtMatchs->execute([$licence, $dateActuelle]);
            $matchs = $stmtMatchs->fetchAll(PDO::FETCH_ASSOC);

            // Calcul des matchs consécutifs
            $joueur['MatchsConsecutifs'] = 0;
            if (count($matchs) > 1) {
                $currentConsecutifs = 0;
                $maxConsecutifs = 0;
                
                // Parcourir les matchs joués par le joueur
                foreach ($matchs as $match) {
                    if ($match['Titulaire'] == 1) {
                        $currentConsecutifs++;
                    } else {
                        $maxConsecutifs = max($maxConsecutifs, $currentConsecutifs);
                        $currentConsecutifs = 0;
                    }
                }
                $joueur['MatchsConsecutifs'] = max($joueur['MatchsConsecutifs'], $currentConsecutifs); // Mettre à jour avec le dernier bloc de matchs consécutifs
            }
        }

        // Récupération des statistiques de l'équipe avant la date actuelle
        $queryStatsEquipe = "
            SELECT 
                COUNT(*) AS MatchsJoues,
                SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) > CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS Victoires,
                SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) < CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS Defaites,
                SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) = CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS MatchsNuls
            FROM Match_Hockey WHERE Date_Heure_match < ?";

        $stmtStatsEquipe = $linkpdo->prepare($queryStatsEquipe);
        $stmtStatsEquipe->execute([$dateActuelle]);
        $statsEquipe = $stmtStatsEquipe->fetch(PDO::FETCH_ASSOC);

        // Calcul des pourcentages
        $matchsJoues = $statsEquipe['MatchsJoues'] ?: 0;
        $statsEquipe['PourcentageVictoires'] = ($matchsJoues > 0) ? round(($statsEquipe['Victoires'] / $matchsJoues) * 100, 2) : 0;
        $statsEquipe['PourcentageDefaites'] = ($matchsJoues > 0) ? round(($statsEquipe['Defaites'] / $matchsJoues) * 100, 2) : 0;
        $statsEquipe['PourcentageNuls'] = ($matchsJoues > 0) ? round(($statsEquipe['MatchsNuls'] / $matchsJoues) * 100, 2) : 0;

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
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

function LireStatsJoueur($linkpdo, $numeroLicence) {
    try {
        // Vérification de la connexion PDO
        echo "Connexion à la base de données réussie.<br>";

        // Requête pour récupérer les informations du joueur par numéro de licence
        $queryJoueur = "SELECT Numero_de_licence, Nom, Prenom, Statut FROM Joueur WHERE Numero_de_licence = ?";
        $stmtJoueur = $linkpdo->prepare($queryJoueur);
        $stmtJoueur->execute([$numeroLicence]);
        
        // Si aucun joueur n'est trouvé
        if ($stmtJoueur->rowCount() === 0) {
            echo "Aucun joueur trouvé avec la licence: $numeroLicence<br>";
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Joueur non trouvé.",
                'data' => null
            ];
        }
        
        // Récupération du joueur
        $joueur = $stmtJoueur->fetch(PDO::FETCH_ASSOC);
        echo "Joueur trouvé : " . $joueur['Nom'] . " " . $joueur['Prenom'] . "<br>";

        // Requête pour récupérer les postes préférés du joueur
        $queryPostes = "SELECT GROUP_CONCAT(DISTINCT Poste ORDER BY Poste SEPARATOR ', ') AS Postes FROM Participer WHERE Numero_de_licence = ?";
        $stmtPostes = $linkpdo->prepare($queryPostes);
        $stmtPostes->execute([$numeroLicence]);
        $joueur['Postes_Preferes'] = $stmtPostes->fetchColumn() ?: 'Non spécifié';
        echo "Postes préférés récupérés : " . $joueur['Postes_Preferes'] . "<br>";

        // Récupération du nombre de titularisations
        $queryTitularisations = "SELECT COUNT(*) FROM Participer P JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey WHERE P.Numero_de_licence = ? AND P.Titulaire = 1";
        $stmtTitularisations = $linkpdo->prepare($queryTitularisations);
        $stmtTitularisations->execute([$numeroLicence]);
        $joueur['Titularisations'] = $stmtTitularisations->fetchColumn();
        echo "Titularisations : " . $joueur['Titularisations'] . "<br>";

        // Récupération du nombre total de matchs joués
        $queryTotalMatches = "SELECT COUNT(*) FROM Match_Hockey WHERE Date_Heure_match < NOW()";
        $stmtTotalMatches = $linkpdo->prepare($queryTotalMatches);
        $stmtTotalMatches->execute();
        $totalMatches = $stmtTotalMatches->fetchColumn();
        echo "Total de matchs joués avant la date actuelle : $totalMatches<br>";

        // Calcul du nombre de matchs consécutifs
        $joueur['MatchsConsecutifs'] = calculateConsecutiveMatches($linkpdo, $numeroLicence);
        echo "Matchs consécutifs : " . $joueur['MatchsConsecutifs'] . "<br>";

        // Retourner les données du joueur
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $joueur
        ];
        
    } catch (Exception $e) {
        // Message d'erreur si une exception se produit
        echo "Erreur serveur : " . $e->getMessage() . "<br>";
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}


?>