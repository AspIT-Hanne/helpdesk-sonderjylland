<?php 
// Tving PHP til at vise fejlen direkte på skærmen i stedet for at gemme den i en log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');
// Inkluder din hoved-header fil
// Du skal muligvis justere stien, f.eks. '../includes/phpheader.php'
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
    // Send en pæn fejlbesked som JSON, i stedet for at lade PHP crashe
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?>