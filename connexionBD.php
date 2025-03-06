<?php
require("conf.php");

// CONNEXION A LA BD
try {
    // Création de la connexion PDO
    $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
    // Définir le mode d'erreur de PDO à exception
    $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo '<div class="success">Connexion réussie à la base de données.</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    //echo '<div class="error">Erreur de connexion : ' . $e->getMessage() . '</div>';
}


?>
