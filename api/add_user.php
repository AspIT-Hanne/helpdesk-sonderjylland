<?php 

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$username = $transferdata['name'] ?? '';
$password = $transferdata['password'] ?? '';
$email = $transferdata['email'] ?? '';
$role = $transferdata['role'] ?? '';

addUser($username, $password, $email, $role);



function addUser($username, $password, $email, $role)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    $table = "users";

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