<?php
// get_token_results.php
header('Content-Type: application/json');

// Leer las credenciales exclusivas para la API de Candidate Results
$client_id = getenv('RESULTS_CLIENT_ID');
$client_secret = getenv('RESULTS_CLIENT_SECRET');

// URL de autenticación de Cambridge (verifica si usan la misma o una URL de testing diferente)
$auth_url = "https://authenticate.cambridgeenglish.org/oauth2/token";

$ch = curl_init($auth_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret
]));

// Los headers de OAuth2 estándar
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
    echo $response;
} else {
    http_response_code($httpcode);
    echo json_encode(['error' => 'Error al obtener el token de Resultados', 'details' => json_decode($response)]);
}
?>