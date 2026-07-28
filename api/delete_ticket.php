<?php 
// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$id = $transferdata['id'] ?? '';

deleteTicket($id);

function deleteTicket($id)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    try {
        $table = "tickets";
        
        $result = $dbcon->deleteData($table, $id);
        
        // Send true eller resultatet tilbage som JSON til JavaScript
        echo json_encode($result);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
         ]);
        exit;
    }

}
?>