<?php
// CONNEXION
    $SQL_req_donnees_connexion = "SELECT * FROM Connexion WHERE Identifiant = :login AND MotsDePasse = :mdp";
    
// AJOUT UTILISATEUR
    $checkSQL_Identifiant = "SELECT COUNT(*) FROM Connexion WHERE Identifiant = :identifiant";
    $insertSQL_Identifiant = "INSERT INTO Connexion (Identifiant, MotsDePasse) VALUES (:identifiant, :motsdepasse)";

// PAGE EQUIPE
    // Requete SQL pour infos global sur les joueurs
    $sql_joueurs = "
        SELECT 
            J.Numero_de_licence,
            J.Nom, 
            J.Prenom, 
            J.Date_de_naissance,
            J.Taille,
            J.Poids,
            J.statut,
            GROUP_CONCAT(C.Texte SEPARATOR '<br>') AS Commentaires
        FROM Joueur J
        LEFT JOIN Commentaire C ON J.Numero_de_licence = C.Numero_de_licence
        GROUP BY J.Numero_de_licence
        ORDER BY J.NOM
    ";
    
    

// PAGE STATS
    // Requête SQL pour compter matchs seulement de l'équipe avec condition pour exclure les matchs futurs
    $sql_stats_equipe = "
        SELECT 
            COUNT(DISTINCT M.Id_Match_Hockey) AS MatchsJoues,
            COUNT(CASE 
                WHEN CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS UNSIGNED) > CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS UNSIGNED) THEN 1 
            END) AS Victoires,
            COUNT(CASE 
                WHEN CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS UNSIGNED) < CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS UNSIGNED) THEN 1 
            END) AS Defaites,
            COUNT(CASE 
                WHEN CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS UNSIGNED) = CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS UNSIGNED) THEN 1 
            END) AS MatchsNuls
        FROM Match_Hockey M
        WHERE M.Date_Heure_match <= :date_actuelle
    ";
    
    // Requête SQL pour récupérer les matchs d'un joueur dans l'ordre chronologique
    $sql_stats_joueurs = "
        SELECT 
            J.Nom, 
            J.Prenom, 
            J.Date_de_naissance,
            J.Statut,
            GROUP_CONCAT(DISTINCT P.Poste SEPARATOR ', ') AS Postes,
            COUNT(CASE WHEN P.Titulaire = 1 AND M.Date_Heure_match <= :date_actuelle THEN 1 END) AS Titularisations,  -- Titularisations avant la date actuelle
            COUNT(CASE 
                WHEN P.Titulaire = 1 
                AND CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS UNSIGNED) > CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS UNSIGNED)
                AND M.Date_Heure_match <= :date_actuelle THEN 1 END) AS Victoires,  -- Victoires avant la date actuelle
            COUNT(CASE 
                WHEN P.Titulaire = 1 
                AND CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS UNSIGNED) < CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS UNSIGNED)
                AND M.Date_Heure_match <= :date_actuelle THEN 1 END) AS Defaites,  -- Défaites avant la date actuelle
            AVG(P.Notation) AS Notation,
            GROUP_CONCAT(M.Id_Match_Hockey ORDER BY M.Date_Heure_match) AS MatchIds  -- Récupérer les IDs des matchs dans l'ordre chronologique
        FROM Joueur J
        LEFT JOIN Participer P ON J.Numero_de_licence = P.Numero_de_licence
        LEFT JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey
        WHERE M.Date_Heure_match <= :date_actuelle  -- Filtrer les matchs avant ou égaux à la date actuelle
        GROUP BY J.Numero_de_licence
        ORDER BY Titularisations DESC, Notation DESC;
    ";
    
    // Calcul du nombre total de matchs joués
    $sql_NB_matchs = "
        SELECT COUNT(M.Id_Match_Hockey) AS NB_M 
        FROM Match_Hockey M 
        WHERE M.Date_Heure_match <= NOW();
    ";
    
    $sql_DernierMatch_joue="SELECT Id_Match_Hockey FROM Match_Hockey WHERE Date_Heure_match <= :date_actuelle ORDER BY Date_Heure_match DESC LIMIT 1";
    
// PAGE JOUEUR
//Ajout
    $sql_insert_joueur = "INSERT INTO Joueur (Numero_de_licence, Nom, Prenom, Date_de_naissance, Taille, Poids, Statut) 
        VALUES (:Numero_de_licence, :nom, :prenom, :date_naissance, :taille, :poids, :statut)";

    $sql_insert_Commentaire = "INSERT INTO Commentaire (Texte, Numero_de_licence) 
                   VALUES (:Id_Commentaire, :Numero_de_licence)";
//Modif
    $sql_recup_info_joueur = "SELECT * FROM Joueur WHERE Numero_de_licence = :id";
    
    $sql_check_commentaire = "SELECT Id_Commentaire, Texte FROM Commentaire WHERE Numero_de_licence = ?";
    $sql_insert_commentaire = "INSERT INTO Commentaire (Texte, Numero_de_licence) VALUES (:commentaire, :id)";
    
    $sql_update_info_joueur = "UPDATE Joueur SET 
                Nom = :nom, 
                Prenom = :prenom, 
                Date_de_naissance = :date_naissance, 
                Taille = :taille, 
                Poids = :poids, 
                Statut = :statut 
            WHERE Numero_de_licence = :id";
            
    $sql_check_participation = "SELECT COUNT(*) AS participation FROM Participer WHERE Numero_de_licence = :id";
    
    $sql_delete_Partcicipation_P = "DELETE FROM Participer WHERE Numero_de_licence = :id";
    
    $sql_delete_Partcicipation_C = "DELETE FROM Commentaire WHERE Numero_de_licence = :id";
    
    $sql_delete_Partcicipation_J = "DELETE FROM Joueur WHERE Numero_de_licence = :id";

// MATCH
//Global
    $sql_Recup_matchs = "SELECT * FROM Match_Hockey ORDER BY Date_Heure_match DESC";
    
//Ajout
    $sql_insert_Match = "INSERT INTO Match_Hockey (Date_Heure_match, Nom_equipe_adverse, Lieu_de_rencontre, ScoreMatch) VALUES (:dateETheure, :EquipeAdverse, :Lieu, :Score)";
    
//Modifier
    $sql_joueurs_participation = "SELECT j.Numero_de_licence, j.Nom, j.Prenom, j.Taille, j.Poids, p.Titulaire, p.Poste, p.Notation FROM Joueur j INNER JOIN Participer p ON j.Numero_de_licence = p.Numero_de_licence WHERE p.Id_Match_Hockey = :id AND j.Statut = 'Actif'  ORDER BY j.Nom, j.Prenom";
    
    $sql_joueurs_non_inscrits = "SELECT j.Numero_de_licence, j.Nom, j.Prenom, j.Taille, j.Poids FROM Joueur j WHERE j.Numero_de_licence NOT IN (SELECT Numero_de_licence FROM Participer WHERE Id_Match_Hockey = :id)AND j.Statut = 'Actif' ORDER BY j.Nom, j.Prenom";
    
    $sql_Maj_info_match= "UPDATE Match_Hockey SET Date_Heure_match = :date_heure, Nom_equipe_adverse = :equipe_adverse, Lieu_de_rencontre = :lieu, ScoreMatch = :score WHERE Id_Match_Hockey = :id";
    
    $sql_Maj_Participation = "UPDATE Participer SET Titulaire = :titulaire, Poste = :poste, Notation = :notation WHERE Id_Match_Hockey = :id AND Numero_de_licence = :numero_de_licence";
    
    $sql_ajout_J_Match = "INSERT INTO Participer (Numero_de_licence, Id_Match_Hockey, Titulaire, Poste) VALUES (:numero_de_licence, :id, 0, :poste)";
    
    $sql_Match_info = "SELECT * FROM Match_Hockey WHERE Id_Match_Hockey = :id";
    
    $sql_Suppr_Joueur_Match ="DELETE FROM Participer WHERE Numero_de_licence = :player_id AND Id_Match_Hockey = :match_id";
    
    $sql_Match_Update_Score ="UPDATE Match_Hockey SET ScoreMatch = :score WHERE Id_Match_Hockey = :id";
    
    $sql_Match_Update_Note ="UPDATE Participer SET Notation = :notation WHERE Id_Match_Hockey = :id AND Numero_de_licence = :numero_de_licence";
    
//Supprimer
    $sql_suppr_match_participer = "DELETE FROM Participer WHERE Id_Match_Hockey = ?";
    
    $sql_suppr_match_match ="DELETE FROM Match_Hockey WHERE Id_Match_Hockey = ?";
    
    $sql_verif_match_passee ="SELECT Date_Heure_match FROM Match_Hockey WHERE Id_Match_Hockey = ?";

    
    
?>