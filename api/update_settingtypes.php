<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Hent den rå JSON-data fra request kroppen
$json = file_get_contents('php://input');

// Konverter JSON til et associativt array i PHP
$transferdata = json_decode($json, true);



updateSettingTypes($transferdata);



function updateSettingTypes($dataarray)
{
    global $dbcon;
    $table = 'settings';
    $updatedRows = 0;
    require_once __DIR__ . '/../includes/phpheader.php';


    try {

        foreach ($dataarray as $item)
        {             
            $id = $item['id'];
            $data = [
               'active' => $item['active'] ?? 0
            ];

            $result = $dbcon->updateData($table, $id, $data);
            if($result)
            {
                $updatedRows++;
            }
            
        }
        
        
        if($updatedRows)
        {
            echo json_encode([
            'success' => true,
            'updated_count' => $updatedRows
            ]);
        }
        else
        {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => "Fejl ved opdatering af databasen."
            ]);
        }

        

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