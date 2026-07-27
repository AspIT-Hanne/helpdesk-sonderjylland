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
$username = $transferdata['name'] ?? '';
$email = $transferdata['email'] ?? '';
$role = $transferdata['role'] ?? '';
$status = $transferdata['status'] ?? '';

updateUser($id, $username, $email, $role, $status);



function updateUser($id, $username, $email, $role, $status)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';


    try {
        $userrole = $dbcon->getDataByField('userRole', 'name', $role);
        if (!$userrole) {
            throw new Exception("Brugerrollen blev ikke fundet i databasen.");
        }
        $userRole_id = $userrole['id'];
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }

    try {
        $userstatus = $dbcon->getDataByField('userStatus', 'name', $status);
        if (!$userstatus) {
            throw new Exception("Brugerstatus blev ikke fundet i databasen.");
        }
        $userStatus_id = $userstatus['id'];
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }

    try {
        $table = "users";
        
        $data = [
            'username' => $username,
            'email' => $email,
            'userRole_id' => $userRole_id,
            'userStatus_id' => $userStatus_id
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