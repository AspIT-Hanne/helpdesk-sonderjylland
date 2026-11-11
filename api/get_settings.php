<?php 
require_once(__DIR__ . '/../includes/phpsession.php');


header('Content-Type: application/json');

// 3. Inkluder databaseforbindelsen (så $dbcon er tilgængelig)
include_once __DIR__ . '/../includes/connect.php';
$dbcon = new DbOperations();

// 4. Sæt headeren til JSON
header('Content-Type: application/json');

try {
        $table = "settings";
        $rows = "settings.id, settings.category_id, settings.active, settings.location_id, settingsCategory.tab_key AS category_name";
        $join = "LEFT JOIN settingsCategory ON settings.category_id = settingsCategory.id"; 
        $where = "settings.location_id = {$_SESSION['location_id']}";

        // $result = $dbcon->getDataWithJoinsWhere($table, $rows, $join, $where);
    // Saml alle data fra de forskellige tabeller
    $settingsData = [
        'settings'   => $dbcon->getDataWithJoinsWhere($table, $rows, $join, $where),
        'types'      => $dbcon->getAllDataByField("ticketCategory", "location_id", "{$_SESSION['location_id']}"),
        'statuses'   => $dbcon->getAllDataByField("ticketStatus", "location_id", "{$_SESSION['location_id']}"),
        'priorities' => $dbcon->getAllDataByField("ticketPriority", "location_id", "{$_SESSION['location_id']}"),
        'roles'      => $dbcon->getAllDataByField("userRole", "location_id", "{$_SESSION['location_id']}")
    ];

    echo json_encode($settingsData);
} catch (Exception $e) {
    // Send en fejlbesked som JSON. Hvis ikke, fejler indstillinger.js, når den skal generere HTML-kode, fordi den forventer et JSON-formatteret svar.
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

?> 
