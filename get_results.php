<?php
// get_results.php
header('Content-Type: application/json');

// Obtener datos del frontend
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$access_token = $input['access_token'] ?? '';
$centreNumber = $input['centreNumber'] ?? '';
$candidateNumber = $input['candidateNumber'] ?? '';
$assessmentCode = $input['assessmentCode'] ?? '';
$keyAssessmentDay = $input['keyAssessmentDay'] ?? '';
$sitting = $input['sitting'] ?? '';
$deliveryMethod = $input['deliveryMethod'] ?? '';

if (empty($access_token)) {
    echo json_encode(['error' => 'Token no proporcionado']);
    exit;
}

// ========================================================================
// Construcción dinámica de la URL con el endpoint real de Cambridge
// ========================================================================
$apiUrl = "https://apis.cambridgeassessment.org.uk/ce/v1/results?centreNumber={$centreNumber}";

// Ahora candidateNumber se agrega dinámicamente solo si el usuario lo escribió
if (!empty($candidateNumber)) {
    $apiUrl .= "&candidateNumber={$candidateNumber}";
}
if (!empty($keyAssessmentDay)) {
    $apiUrl .= "&keyAssessmentDay={$keyAssessmentDay}";
}
if (!empty($assessmentCode)) {
    $apiUrl .= "&assessmentCode={$assessmentCode}";
}
if (!empty($sitting)) {
    $apiUrl .= "&sitting={$sitting}";
}
if (!empty($deliveryMethod)) {
    $apiUrl .= "&deliveryMethod={$deliveryMethod}";
}

$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$access_token}",
    "Content-Type: application/json",
    "Accept: application/json"
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode >= 200 && $httpcode < 300) {
    echo $response;
} else {
    http_response_code($httpcode);
    echo json_encode([
        'error' => 'Error al consultar la API de Resultados',
        'http_code' => $httpcode,
        'details' => json_decode($response)
    ]);
}
?>