<?php

    include "includes/connect.php";

    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }

    $dbcon = new DbOperations;

?>