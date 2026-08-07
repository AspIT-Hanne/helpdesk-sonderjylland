<?php 

require_once __DIR__ . '/../includes/phpheader.php';

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
//Hvis der ikke er valgt en bruger at oprette sagen for, indsættes den bruger der er logget ind som opretter
//Start med at fjerne et evt. mellemrum, så vi er sikre på at strengen er helt tom, hvis der ikke står noget i den
$rawCreatedBy = trim($transferdata['createdby'] ?? '');
$createdBy = empty($rawCreatedBy) ? $_SESSION['username'] : $rawCreatedBy;
//$createdBy = empty($transferdata['createdby']) ? $_SESSION['userid'] : '';
$status = $transferdata['status'] ?? '';

addTicket($title, $description, $location, $type, $priority, $assigned, $createdBy, $status);

function addTicket($title, $description, $location, $type, $priority, $assigned, $createdBy, $status)
{
    global $dbcon;
    

    $table = "tickets";
    $now = new DateTime();
    $createDate = $now->format('Y-m-d H:i:s');

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

    if($createdBy){
        try {
            $getcreatedBy = $dbcon->getDataByField('users', 'username', $createdBy);
            if (!$getcreatedBy) {
                throw new Exception("Den tildelte bruger blev ikke fundet i databasen.");
            }
            $created_by = $getcreatedBy['id'];
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    try {
        
        $data = [
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'ticketCategory_id' => $ticketCategory_id,
            'ticketPriority_id' => $ticketPriority_id,
            'assigned_to' => $assigned_to,
            'created_by' => $created_by,
            'ticketStatus_id' => $ticketStatus_id,
            'created_at' => $createDate,
            'location_id' => $_SESSION['location_id']
        ];

        $result = $dbcon->insertData($table, $data);
        
        // Send true eller resultatet tilbage som JSON til JavaScript
        echo json_encode($result);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => $e->getMessage(), 'error' => $e->getMessage()]);
        exit;
    }

}
?>