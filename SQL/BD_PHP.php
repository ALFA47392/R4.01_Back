<?php
include '../connexionBD.php';
// MISE EN PLACE BD
$sql = file_get_contents("./SQL/Creation_BD.sql");
try{
    $linkpdo->exec($sql);
    echo '<div class="success">Mise en place de la BD réussie</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    echo '<div class="error">Erreur de mise en place de la BD : ' . $e->getMessage() . '</div>';
}

// INSERT JEU DE TEST
$sql = file_get_contents("./SQL/jeuDeTest.sql");
try{
    $linkpdo->exec($sql);
    echo '<div class="success">Insert du jeu de test de la BD réussie</div>';
} catch (Exception $e) {
    // Gestion des erreurs de connexion
    echo '<div class="error">Erreur du insert du jeu de test de la BD : ' . $e->getMessage() . '</div>';
}
?>