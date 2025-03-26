<?php
require_once 'conf.php';

try {
    $linkpdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $login, $mdp);
    $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}


?>
