<?php
function LireListematch($linkpdo) {
    try{
        $query = "SELECT * FROM Match_Hockey";
        $stmt = $linkpdo->prepare($query);
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

function LireMatch ($linkpdo,$id) {
    try{
        $req = "SELECT * FROM Match_Hockey WHERE Id_Match_Hockey = :Id_Match_Hockey";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['Id_Match_Hockey'=>$id]);

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

function CréerJoueur($linkpdo,$numLicence,$nom,$prenom,$dateNaissance,$taille,$poids,$statut){
    try{
        $req = "INSERT INTO joueur (`Numero_de_licence`, `Nom`, `Prenom`, `Date_de_naissance`, `Taille`, `Poids`, `Statut`) VALUES (:Numero_de_licence, :Nom, :Prenom, :Date_de_naissance, :Taille, :Poids, :Statut)";
        $stmt = $linkpdo->prepare($req);
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
        // Vérifier si le joueur existe en utilisant LireJoueur
        $joueurExistant = LireJoueur($linkpdo, $id);
        
        if (!$joueurExistant['success'] || empty($joueurExistant['data'])) {
            return [
                'success' => false,
                'status_code' => 404,
                'status_message' => "Joueur non trouvé",
                'data' => null
            ];
        }

        // Construction dynamique de la requête
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

        $req = "UPDATE joueur SET " . implode(", ", $fields) . " WHERE Numero_de_licence = :id";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute($params);

        // Récupérer les nouvelles données après la mise à jour
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


?>