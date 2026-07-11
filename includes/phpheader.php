<?php

    include realpath(__DIR__ . '/..') . "/includes/connect.php";

    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }

    $dbcon = new DbOperations;

?>