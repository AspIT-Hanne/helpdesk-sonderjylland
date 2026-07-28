<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$table = $transferdata['table'] ?? '';
$id = $transferdata['id'] ?? '';
$name = $transferdata['name'] ?? '';
$description = $transferdata['description'] ?? '';
$color = $transferdata['color'] ?? '';

updateSettings($table, $id, $name, $description, $color);



function updateSettings($table, $id, $name, $description, $color)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';


    try {
               
        $data = [
            'name' => $name,
            'description' => $description,
            'color' => $color
        ];

        $result = $dbcon->updateData($table, $id, $data);
        
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