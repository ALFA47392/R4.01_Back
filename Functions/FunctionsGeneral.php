<?php

$auth = 'http://127.0.0.1/R4.01_Auth/Endpoint/EndpointAuth.php'; // Remplacez par l'URL du service d'authentification

function get_authorization_header(){
	$headers = null;

	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} else if (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}

	return $headers;
}

function get_bearer_token() {
    $headers = get_authorization_header();
    
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            if($matches[1]=='null') //$matches[1] est de type string et peut contenir 'null'
                return null;
            else
                return $matches[1];
        }
    }
    return null;
}

function verif_auth($token, $auth) {
    if (!$token) {
        deliver_response(401, "Accès refusé : Aucun token fourni", null);
        exit;
    }
    
    // Vérification de l'authentification via une requête HTTP avec file_get_contents
    $options = [
        "http" => [
            "header" => "Authorization: Bearer $token\r\n" .
                        "Content-Type: application/json\r\n",
            "method" => "GET" // ou une autre méthode selon ton besoin
        ]
    ];
    
    $context = stream_context_create($options); // Créer le contexte avec les en-têtes HTTP
    $response = file_get_contents($auth, false, $context); // Envoi de la requête HTTP à l'URL d'authentification
    
    // Vérifie si la requête a réussi
    if ($response === FALSE) {
        deliver_response(403, "Accès refusé : Erreur de communication avec le service d'authentification", null);
        exit;
    }
    
    // Récupération du code de réponse HTTP
    $http_code = $http_response_header[0]; // Le code de statut HTTP se trouve dans le premier élément de ce tableau
    
    // Extrait le code HTTP du tableau de la réponse
    preg_match('{HTTP\/\d+\.\d+ (\d+)}', $http_code, $matches);
    $http_code = $matches[1] ?? 500; // Si le code n'est pas trouvé, retourne 500 par défaut
    
    // Vérification si le code HTTP retourné est 200 (succès)
    if ($http_code != 201) {
        deliver_response(403, "Accès refusé : Token invalide", null);
        exit;
    }
}
?>