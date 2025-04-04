<?php

// Inclusion du fichier contenant les variables SQL utilisées pour les requêtes
include_once ('../SQL/Variables_SQL.php');

// Fonction pour récupérer la liste de tous les joueurs
function LireListeJoueur($linkpdo) {
    try {
        // Utilisation de la requête SQL définie pour sélectionner les joueurs
        global $sql_select_un_joueur;

        // Préparation de la requête
        $stmt = $linkpdo->prepare($sql_select_un_joueur);  
        $stmt->execute();  // Exécution de la requête

        // Récupération des résultats sous forme de tableau associatif
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérification si des données ont été récupérées
        if (empty($data)) {
            // Si aucune donnée n'a été trouvée, renvoyer une réponse pour cela
            deliver_response(404, "Aucun joueur trouvé.", null);
        }

        // Si des données ont été récupérées, on les renvoie
        deliver_response(200, "Données récupérées avec succès.", $data);

        // Retour d'une réponse positive si des données sont présentes
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",  // Retour d'une réponse positive
            'data' => $data
        ];

    } catch (Exception $e) {
        // En cas d'erreur lors de l'exécution, retour d'une réponse d'erreur
        deliver_response(500, "Erreur lors de l'exécution de la requête SQL : " . $e->getMessage(), null);
        
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur interne du serveur : " . $e->getMessage(),  // Message d'erreur détaillé
            'data' => null
        ];
    }
}


// Fonction pour récupérer un joueur spécifique par son numéro de licence
function LireJoueur($linkpdo, $id) {
    try {
        global $sql_select_joueurs;
        // Préparation et exécution de la requête pour rechercher le joueur
        $stmt = $linkpdo->prepare($sql_select_joueurs);
        $stmt->execute(['Numero_de_licence' => $id]);  // Utilisation du numéro de licence comme critère de recherche

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Récupération des données sous forme de tableau associatif

        // Vérification si aucun joueur n'a été trouvé
        if (empty($data)) {
            // Retour d'une réponse d'erreur si le joueur n'existe pas
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Aucun joueur trouvé avec ce numéro de licence.",
                'data' => null
            ];
        }

        // Retour des données si le joueur a été trouvé
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    } catch (Exception $e) {
        // Gestion des erreurs en cas d'exception et retour d'un message d'erreur
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur interne du serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

// Fonction pour créer un nouveau joueur dans la base de données
function CréerJoueur($linkpdo, $numLicence, $nom, $prenom, $dateNaissance, $taille, $poids, $statut) {
    try {
        global $sql_create_joueur;

        // Préparation et exécution de la requête pour insérer un nouveau joueur
        $stmt = $linkpdo->prepare($sql_create_joueur);

        // Essayer d'exécuter la requête
        $stmt->execute(array(
            'Numero_de_licence' => $numLicence,
            'Nom' => $nom,
            'Prenom' => $prenom,
            'Date_de_naissance' => $dateNaissance,
            'Taille' => $taille,
            'Poids' => $poids,
            'Statut' => $statut
        ));

        // Vérification si une ligne a été insérée
        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {

            $joueurData = LireJoueur($linkpdo, $numLicence);  // Récupération des données du joueur inséré
            // Si la requête a modifié des données, renvoyer une réponse positive
            // Retour des données si le joueur a été trouvé
            return [
                'success' => true,
                'status_code' => 200,
                'status_message' => "Données récupérées avec succès.",
                'data' => $joueurData['data']
            ];
        } else {
            // Si aucune ligne n'a été insérée
            deliver_response(400, "Aucune donnée insérée, peut-être que le joueur existe déjà ?", null);
        }
        
    } catch (Exception $e) {
        // En cas d'exception, renvoyer une réponse d'erreur
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur interne du serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}


// Fonction pour mettre à jour les informations d'un joueur
function patchJoueur($linkpdo, $id, $nom, $prenom, $dateNaissance, $taille, $poids, $statut) {
    try {
        global $sqlPatchJoueurBase;

        // Vérification si le joueur existe avant de le mettre à jour
        $joueurExistant = LireJoueur($linkpdo, $id);
        if (!$joueurExistant['success'] || empty($joueurExistant['data'])) {
            // Retour si le joueur n'existe pas dans la base de données
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Joueur non trouvé",
                'data' => null
            ];
        }

        $fields = [];  // Tableau pour stocker les champs à mettre à jour
        $params = ['id' => $id];  // Paramètre de l'ID du joueur

        // Ajout des champs à mettre à jour si la nouvelle valeur est différente de null
        if ($nom !== null) {
            $fields[] = "Nom = :Nom";
            $params['Nom'] = $nom;
        }
        if ($prenom !== null) {
            $fields[] = "Prenom = :Prenom";
            $params['Prenom'] = $prenom;
        }
        if ($dateNaissance !== null) {
            // Conversion de la date de naissance si nécessaire
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateNaissance)) {
                $dateNaissance = DateTime::createFromFormat('d/m/Y', $dateNaissance)->format('Y-m-d');
            }
            $fields[] = "Date_de_naissance = :Date_de_naissance";
            $params['Date_de_naissance'] = $dateNaissance;
        }
        if ($taille !== null) {
            $fields[] = "Taille = :Taille";
            $params['Taille'] = $taille;
        }
        if ($poids !== null) {
            $fields[] = "Poids = :Poids";
            $params['Poids'] = $poids;
        }
        if ($statut !== null) {
            $fields[] = "Statut = :Statut";
            $params['Statut'] = $statut;
        }

        // Si aucun champ n'est fourni pour la mise à jour, retour d'une erreur
        if (empty($fields)) {
            return [
                'success' => false,
                'status_code' => 400,
                'status_message' => "Aucun champ à mettre à jour",
                'data' => null
            ];
        }

        // Construction dynamique de la requête SQL de mise à jour
        $sql = $sqlPatchJoueurBase . implode(", ", $fields) . " WHERE Numero_de_licence = :id";
        $stmt = $linkpdo->prepare($sql);
        $stmt->execute($params);  // Exécution de la requête

        // Retour du joueur mis à jour
        $joueurMisAJour = LireJoueur($linkpdo, $id);
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Joueur mis à jour avec succès",
            'data' => $joueurMisAJour['data']
        ];

    } catch (Exception $e) {
        // Gestion des erreurs et retour du message d'erreur
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

// Fonction pour supprimer un joueur de la base de données
function deleteJoueur($linkpdo, $id) {
    try {
        global $sql_delete_joueur;
        // Préparation et exécution de la requête de suppression
        $stmt = $linkpdo->prepare($sql_delete_joueur);
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Récupération des données (si nécessaire)

        return [
            'success' => true,
            'data' => $data,  // Retour de la donnée supprimée
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
