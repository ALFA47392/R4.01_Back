<?php
require("conf.php");

// CONNEXION A LA BD
try {
    // Création de la connexion PDO
    $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
    // Définir le mode d'erreur de PDO à exception
    $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<div class="success">Connexion réussie à la base de données.</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    echo '<div class="error">Erreur de connexion : ' . $e->getMessage() . '</div>';
}

// MISE EN PLACE BD
$sql = file_get_contents("./SQL/Creation_BD.sql");
try{
    $linkpdo->exec($sql);
    echo '<div class="success">Mise en place de la BD réussie</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    echo '<div class="error">Erreur de mise en place de la BD : ' . $e->getMessage() . '</div>';
}

$sql = file_get_contents("./SQL/jeuDeTest.sql");
try{
    $linkpdo->exec($sql);
    echo '<div class="success">Insert du jeu de test de la BD réussie</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    echo '<div class="error">Erreur du insert du jeu de test de la BD : ' . $e->getMessage() . '</div>';
}


?>
