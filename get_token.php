<?php
// get_token.php

// Permitir que el frontend lea la respuesta como JSON
header('Content-Type: application/json');

// Credenciales de tu aplicación en Cambridge
$client_id = 'a54701e41e924329a761ee503fd41647';
// REEMPLAZA ESTO CON EL SECRETO QUE COPIASTE DE TU PORTAL
$client_secret = '842AC14C975e4070AFd57069B261a915'; 

$url = 'https://apis.cambridgeassessment.org.uk/v1/cambridge.id/token';

// Preparar los datos en el formato x-www-form-urlencoded
$data = http_build_query([
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'client_credentials',
    'scope' => 'openid'
]);

// Iniciar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

// Ejecutar petición y obtener código de estado
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Devolver la misma respuesta y código HTTP de Cambridge hacia nuestro frontend
http_response_code($http_code);
echo $response;
?>