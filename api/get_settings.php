<?php 
// Output buffer start. Opsamler al output, som phpheader (og connect, som includes i phpheader) måtte generere.
// Udfordringen er, at når jeg bruger header() i linje 9, så må der ikke have været noget output først, så for at undgå det, 
// bruger jeg en output buffer, som opsamler det hele og så sletter det i linje 7.
ob_start();
    require_once('../includes/phpheader.php');
ob_end_clean();

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