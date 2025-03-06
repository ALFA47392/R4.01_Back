<?php
function LireListeJoueur($linkpdo) {
    try{
        $query = "SELECT * FROM joueur";
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
function LireJoueur ($linkpdo,$id) {
    try{
        $req = "SELECT * FROM joueur WHERE Numero_de_licence = :Numero_de_licence";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(['Numero_de_licence'=>$id]);

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
        'Statut' => $statut,
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

function patchJoueur($linkpdo, $id, $nom, $prenom, $dateNaissance, $taille, $poids, $statut){
    try {
        $req = "UPDATE joueur SET Nom=:Nom,Prenom=:Prenom,Date_de_naissance=:Date_de_naissance,Taille=:Taille,Poids=:Poids,Statut=:Statut WHERE Numero_de_licence=:Numero_de_licence";
        $stmt = $linkpdo->prepare($req);
        $stmt->execute(array(
            'Nom' => $nom,
            'Prenom' => $prenom,
            'Date_de_naissance' => $dateNaissance,
            'Taille' => $taille,
            'Poids' => $poids,
            'Statut' => $statut,
            'Numero_de_licence' => $id
        ));
    }catch(Exception){
        return [
            'success' => false,
            'data' => null
        ];
    }
}

?>