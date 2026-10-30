<?php
// get_calendar.php
header('Content-Type: application/json');

// Leer los datos enviados por el frontend (Token y parámetros)
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['access_token'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Falta el token de acceso']);
    exit;
}

$token = $input['access_token'];

// Parámetros de consulta
$centreNumber = urlencode($input['centreNumber'] ?? '');
$examType = urlencode($input['examType'] ?? '');
$assessmentCode = urlencode($input['assessmentCode'] ?? '');
$year = urlencode($input['year'] ?? '');
$sitting = urlencode($input['sitting'] ?? '');

// Nuevos campos
$startDate = urlencode($input['startDate'] ?? '');
$endDate = urlencode($input['endDate'] ?? '');

// URL base de Producción
$baseUrl = 'https://apis.cambridgeassessment.org.uk/ce/v1/exam-calendar';

// Construir la URL completa agregando los nuevos parámetros
$url = "{$baseUrl}?centreNumber={$centreNumber}&examType={$examType}&assessmentCode={$assessmentCode}&year={$year}&sitting={$sitting}&startDate={$startDate}&endDate={$endDate}";

// Iniciar cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$token}",
    "Accept: application/json"
]);

// Ejecutar petición
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Devolver la respuesta al frontend
http_response_code($http_code);
echo $response;
?>