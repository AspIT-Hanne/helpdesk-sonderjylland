<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$id = $transferdata['id'] ?? '';
$title = $transferdata['title'] ?? '';
$type = $transferdata['type'] ?? '';
$description = $transferdata['description'] ?? '';
$location = $transferdata['place'] ?? '';
$status = $transferdata['status'] ?? '';
$priority = $transferdata['priority'] ?? '';
$assigned = $transferdata['assigned'] ?? '';

updateUser($id, $title, $type, $description, $location, $status, $priority, $assigned);



function updateUser($id, $title, $type, $description, $location, $status, $priority, $assigned)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';


    try {
        $ticketCategory = $dbcon->getDataByField('ticketCategory', 'name', $type);
        if (!$ticketCategory) {
            throw new Exception("Kategorien blev ikke fundet i databasen.");
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
        $ticketStatus = $dbcon->getDataByField('ticketStatus', 'name', $status);
        if (!$ticketStatus) {
            throw new Exception("Status blev ikke fundet i databasen.");
        }
        $ticketStatus_id = $ticketStatus['id'];
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
    
    if($assigned){
        try {
            $assignedTo = $dbcon->getDataByField('users', 'username', $assigned);
            if (!$assignedTo) {
                throw new Exception("Den tildelte tekniker blev ikke fundet i databasen.");
            }
            $assigned_to = $assignedTo['id'];
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
    else {
        $assigned_to = NULL;
    }

    try {
        $table = "tickets";
        
        $data = [
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'ticketCategory_id' => $ticketCategory_id,
            'ticketPriority_id' => $ticketPriority_id,
            'assigned_to' => $assigned_to,
            'ticketStatus_id' => $ticketStatus_id,
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