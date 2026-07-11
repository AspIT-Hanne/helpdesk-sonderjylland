<?php 
// Tving PHP til at vise fejlen i loggen og ikke på skærmen
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

require_once('../includes/phpheader.php');

header('Content-Type: application/json');

try {
    // Saml alle data fra de forskellige tabeller
    $settingsData = [
        'types'      => $dbcon->getAllData("ticketCategory"),
        'statuses'   => $dbcon->getAllData("ticketStatus"),
        'priorities' => $dbcon->getAllData("ticketPriority"),
        'roles'      => $dbcon->getAllData("userRole")
    ];

    echo json_encode($settingsData);
} catch (Exception $e) {
    // Send en fejlbesked som JSON. Hvis ikke, fejler indstillinger.js, når den skal generere HTML-kode, fordi den forventer et JSON-formatteret svar.
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>