<?php
    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }
    
   // Tving PHP til at vise fejlen i loggen og ikke på skærmen
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/php_error.log');

    include realpath(__DIR__ . '/..') . "/includes/connect.php";

    $dbcon = new DbOperations;