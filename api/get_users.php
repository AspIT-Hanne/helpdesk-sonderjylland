<?php 

function getUserData()
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    try {
        // Saml data med joins fra de forskellige tabeller
        
        $table = "users";
        $rows = "users.id, users.username, users.email, userRole.name AS role_name, userRole.color AS role_color, userStatus.name AS status_name, userStatus.color AS status_color";
        $join = "LEFT JOIN userRole ON users.userRole_id = userRole.id 
                LEFT JOIN userStatus ON users.userStatus_id = userStatus.id";

        $result = $dbcon->getDataWithJoins($table, $rows, $join);
        
        return $result;

    } catch (Exception $e) {
        die($e->getMessage());
    }

}

function getUserRoles()
{
    global $dbcon;
    require_once __DIR__ . '/../includes/phpheader.php';

    try {
        $table = "userRole";

        $result = $dbcon->getAllData($table);

        return $result;
    } catch (Exception $e) {
        die($e->getMessage());
    }

}


?>