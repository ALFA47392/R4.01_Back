<?php

include_once '../SQL/Variables_SQL.php';

function LireListeJoueur($linkpdo) {
    try{
        global $sql_select_un_joueur;

        $stmt = $linkpdo->prepare($sql_select_un_joueur);
        $stmt->execute();
            
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    }catch(Exception){
        return [
            'success' => false,
            'status_code' => 404,
            'status_message' => "Not Found",
            'data' => null
        ];
    }
}

function LireJoueur($linkpdo, $id) {
    try {
        global $sql_select_joueurs;
        $stmt = $linkpdo->prepare($sql_select_joueurs);
        $stmt->execute(['Numero_de_licence' => $id]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Vérifier si aucun joueur n'a été trouvé
        if (empty($data)) {
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Aucun joueur trouvé avec ce numéro de licence.",
                'data' => null
            ];
        }

        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Données récupérées avec succès.",
            'data' => $data
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur interne du serveur : " . $e->getMessage(),
            'data' => null
        ];
    }
}


function CréerJoueur($linkpdo,$numLicence,$nom,$prenom,$dateNaissance,$taille,$poids,$statut){
    try{
        global $sql_create_joueur;
        $stmt = $linkpdo->prepare($sql_create_joueur);
        $stmt->execute(array(
        'Numero_de_licence'=>$numLicence,
        'Nom' => $nom,
        'Prenom' => $prenom,
        'Date_de_naissance' => $dateNaissance,
        'Taille' => $taille,
        'Poids' => $poids,
        'Statut' => $statut
    ));

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'success' => true,
            'data' => $data,
        ];
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}

function patchJoueur($linkpdo, $id, $nom, $prenom, $dateNaissance, $taille, $poids, $statut) {
    try {
        global $sqlPatchJoueurBase;

        $joueurExistant = LireJoueur($linkpdo, $id);
        if (!$joueurExistant['success'] || empty($joueurExistant['data'])) {
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Joueur non trouvé",
                'data' => null
            ];
        }

        $fields = [];
        $params = ['id' => $id];

        if ($nom !== null) {
            $fields[] = "Nom = :Nom";
            $params['Nom'] = $nom;
        }

        if ($prenom !== null) {
            $fields[] = "Prenom = :Prenom";
            $params['Prenom'] = $prenom;
        }

        if ($dateNaissance !== null) {
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

        if (empty($fields)) {
            return [
                'success' => false,
                'status_code' => 400,
                'status_message' => "Aucun champ à mettre à jour",
                'data' => null
            ];
        }

        $sql = $sqlPatchJoueurBase . implode(", ", $fields) . " WHERE Numero_de_licence = :id";
        $stmt = $linkpdo->prepare($sql);
        $stmt->execute($params);

        $joueurMisAJour = LireJoueur($linkpdo, $id);
        return [
            'success' => true,
            'status_code' => 200,
            'status_message' => "Joueur mis à jour avec succès",
            'data' => $joueurMisAJour['data']
        ];

    } catch (Exception $e) {
        return [
            'success' => false,
            'status_code' => 500,
            'status_message' => "Erreur : " . $e->getMessage(),
            'data' => null
        ];
    }
}

function deleteJoueur($linkpdo,$id){
    try{
        global $sql_delete_joueur;
        $stmt = $linkpdo->prepare($sql_delete_joueur);
        $stmt->execute(['id'=>$id]);
    
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return [
            'success' => true,
            'data' => $data,
        ];
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}

?>