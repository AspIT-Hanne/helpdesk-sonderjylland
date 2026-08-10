<?php 

header('Content-Type: application/json');

global $dbcon;
require_once __DIR__ . '/../includes/phpheader.php';

try {
    $tabs = $dbcon->getAllData('settingsCategory'); 
    
    echo json_encode(['success' => true, 'data' => $tabs]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>