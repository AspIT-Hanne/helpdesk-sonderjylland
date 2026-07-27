<?php 

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$title = $transferdata['title'] ?? '';
$description = $transferdata['description'] ?? '';
$location = $transferdata['location'] ?? '';
$type = $transferdata['type'] ?? '';
$priority = $transferdata['priority'] ?? '';
$assigned = $transferdata['assigned'] ?? '';
$created = $transferdata['created'] ?? '';
$status = $transferdata['status'] ?? '';

addTicket($title, $description, $location, $type, $priority, $assigned, $created, $status);

function addTicket($title, $description, $location, $type, $priority, $assigned, $created, $status)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    $table = "tickets";

    try {
        $ticketCategory = $dbcon->getDataByField('ticketCategory', 'name', $type);
        if (!$ticketCategory) {
            throw new Exception("Sagstypen blev ikke fundet i databasen.");
        }
        $ticketCategory_id = $ticketCategory['id'];
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }

    try {
        $ticketPriority = $dbcon->getDataByField('ticketPriority', 'name', $priority);
        if (!$ticketPriority) {
            throw new Exception("Prioritet blev ikke fundet i databasen.");
        }
        $ticketPriority_id = $ticketPriority['id'];
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }

    try {

        // Hash passwordet før det gemmes i databasen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $data = [
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email,
            'userRole_id' => $userRole_id,
            'userStatus_id' => 1
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