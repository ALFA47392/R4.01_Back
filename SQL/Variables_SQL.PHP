<?php
// FEUILLE DE MATCH
$sql_select_FDM = "SELECT * FROM Participer WHERE Id_Match_Hockey = :idmatchhokey";

$sql_create_FDM = "INSERT INTO `Participer` (`Numero_de_licence`, `Id_Match_Hockey`, `Titulaire`, `Notation`, `Poste`) 
        VALUES (:numero_de_licence, :id_match_hockey, :titulaire, :notation, :poste)";

$sql_update_FDM = "UPDATE `Participer` SET Titulaire = :titulaire, Notation = :notation, Poste = :poste
WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";

$sql_delete_FDM = "DELETE FROM `Participer` WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";


// JOUEUR
$sql_select_un_joueur = "SELECT * FROM Joueur";

$sql_select_joueurs = "SELECT * FROM Joueur WHERE Numero_de_licence = :Numero_de_licence";

$sql_create_joueur = "INSERT INTO Joueur (`Numero_de_licence`, `Nom`, `Prenom`, `Date_de_naissance`, `Taille`, `Poids`, `Statut`) VALUES (:Numero_de_licence, :Nom, :Prenom, :Date_de_naissance, :Taille, :Poids, :Statut)";

$sqlPatchJoueurBase = "UPDATE Joueur SET ";  // Le WHERE est ajouté plus tard

$sqlVerifierExistenceJoueur = "SELECT * FROM Joueur WHERE Numero_de_licence = ?";

$sql_delete_joueur = "DELETE FROM Joueur WHERE Numero_de_licence = :id";


// MATCH
$sql_select_liste_match = "SELECT * FROM Match_Hockey";

$sql_select_match = "SELECT * FROM Match_Hockey WHERE Id_Match_Hockey = :Id_Match_Hockey";

$sql_creer_match = "INSERT INTO Match_Hockey (`Id_Match_Hockey`, `Date_Heure_match`, `Nom_equipe_adverse`, `Lieu_de_rencontre`, `ScoreMatch`) VALUES (:Id_Match_Hockey, :Date_Heure_match, :Nom_equipe_adverse, :Lieu_de_rencontre, :ScoreMatch)";

$sqlPatchMatchBase = "UPDATE Match_Hockey SET ";

$sql_delete_match = "DELETE FROM Match_Hockey WHERE Id_Match_Hockey = :idMatch";

$sql_delete_match_FDM = "DELETE FROM Participer WHERE Id_Match_Hockey = :idMatch";


// PARTICIPER
$sql_select_FDM = "SELECT * FROM Participer WHERE Id_Match_Hockey = :idmatchhockey";

$sql_create_participer = "INSERT INTO `Participer` (`Numero_de_licence`, `Id_Match_Hockey`, `Titulaire`, `Notation`, `Poste`) 
        VALUES (:numero_de_licence, :id_match_hockey, :titulaire, :notation, :poste)";

$sql_update_participer = "UPDATE Participer SET Titulaire = :titulaire, Notation = :notation, Poste = :poste
                WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";

$sql_delete_participer = "DELETE FROM `Participer` WHERE Numero_de_licence = :numero_de_licence AND Id_Match_Hockey = :id_match_hockey";


//STATS
$sql_joueurs_base = "SELECT Numero_de_licence, Nom, Prenom, Statut FROM Joueur";

$sql_total_matchs_avant_maintenant = "SELECT COUNT(*) FROM Match_Hockey WHERE Date_Heure_match < ?";

$sql_postes_joueur = "SELECT GROUP_CONCAT(DISTINCT Poste ORDER BY Poste SEPARATOR ', ') AS Postes 
                      FROM Participer WHERE Numero_de_licence = ?";

$sql_titularisations_joueur = "SELECT COUNT(*) 
                               FROM Participer P 
                               JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey 
                               WHERE P.Numero_de_licence = ? 
                               AND P.Titulaire = 1 
                               AND M.Date_Heure_match < ?";

$sql_victoires_joueur = "SELECT COUNT(*) 
                         FROM Participer P
                         JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey
                         WHERE P.Numero_de_licence = ?
                         AND P.Titulaire = 1
                         AND M.Date_Heure_match < ?
                         AND CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', 1) AS SIGNED) > CAST(SUBSTRING_INDEX(M.ScoreMatch, '-', -1) AS SIGNED)";

$sql_moyenne_notation = "SELECT AVG(Notation) 
                         FROM Participer P 
                         JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey 
                         WHERE P.Numero_de_licence = ? 
                         AND M.Date_Heure_match < ?";

$sql_matchs_joueur = "SELECT M.Id_Match_Hockey, P.Titulaire 
                      FROM Participer P 
                      JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey 
                      WHERE P.Numero_de_licence = ? 
                      AND M.Date_Heure_match < ? 
                      ORDER BY M.Date_Heure_match DESC";

$sql_stats_equipe = "
    SELECT 
        COUNT(*) AS MatchsJoues,
        SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) > CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS Victoires,
        SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) < CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS Defaites,
        SUM(CASE WHEN CAST(SUBSTRING_INDEX(ScoreMatch, '-', 1) AS SIGNED) = CAST(SUBSTRING_INDEX(ScoreMatch, '-', -1) AS SIGNED) THEN 1 ELSE 0 END) AS MatchsNuls
    FROM Match_Hockey 
    WHERE Date_Heure_match < ?
";


// Requêtes pour LireStatsJoueur
$sql_info_joueur = "SELECT Numero_de_licence, Nom, Prenom, Statut FROM Joueur WHERE Numero_de_licence = ?";

$sql_postes_joueur_unique = "SELECT GROUP_CONCAT(DISTINCT Poste ORDER BY Poste SEPARATOR ', ') AS Postes 
                             FROM Participer WHERE Numero_de_licence = ?";

$sql_titularisations_joueur_total = "SELECT COUNT(*) 
                                     FROM Participer P 
                                     JOIN Match_Hockey M ON P.Id_Match_Hockey = M.Id_Match_Hockey 
                                     WHERE P.Numero_de_licence = ? 
                                     AND P.Titulaire = 1";

$sql_total_matchs_global = "SELECT COUNT(*) FROM Match_Hockey WHERE Date_Heure_match < NOW()";
?>