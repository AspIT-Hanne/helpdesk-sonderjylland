<?php 

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);

// Hent værdierne ud fra det dekodede array
$version = $transferdata['version'] ?? '';
$content = $transferdata['content'] ?? '';

addFeature($version, $content);



function addFeature($version, $content)
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    $table = "systemFeatures";


    try {

        // Hash passwordet før det gemmes i databasen
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $data = [
            'version' => $version,
            'content' => $content
            'created_at' => date('Y-m-d H:i:s')
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