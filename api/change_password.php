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
$password = $transferdata['password'] ?? '';

changePassword($id, $password);



function changePassword($id, $password)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';


    try {
        $user = $dbcon->getDataByField('users', 'id', $id);
        if (!$user) {
            throw new Exception("Brugeren blev ikke fundet i databasen.");
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'error' => $e->getMessage()
        ]);
        exit;
    }
    
    $table = 'users';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $data = [
            'password' => $hashedPassword
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