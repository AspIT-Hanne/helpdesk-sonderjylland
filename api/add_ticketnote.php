<?php 

include_once __DIR__ . '/../includes/phpsession.php';

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$ticketText = $transferdata['text'] ?? '';
$ticketId = $transferdata['ticket_id'] ?? '';
$createdBy = $_SESSION['userid'] ?? '';
$createdAt = date('Y-m-d H:i:s'); // Brug den aktuelle dato og tid
$locationId = $_SESSION['location_id'] ?? '';  

addTicketnote($ticketText, $ticketId, $createdBy, $createdAt, $locationId);



function addTicketnote($text, $ticket_id, $created_by, $created_at, $location_id)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    $table = "note";

        try {
        
        $data = [
            'notetext' => $text,
            'ticket_id' => $ticket_id,
            'created_by' => $created_by,
            'created_at' => $created_at,
            'location_id' => $location_id
        ];


        $result = $dbcon->insertData($table, $data);
        
        // Send true eller resultatet tilbage som JSON til JavaScript
        echo json_encode($result);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => $e->getMessage(), 'error' => $e->getMessage()]);
                echo "PHP Fejl: " . $e->getMessage();
        exit;
    }

}
?>