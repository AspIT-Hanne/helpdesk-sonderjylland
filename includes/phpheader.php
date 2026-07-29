<?php
    if (session_status() === PHP_SESSION_NONE) 
    {
        session_start();
    }
    
   // Tving PHP til at vise fejlen i loggen og ikke på skærmen
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/php_error.log');

    include realpath(__DIR__ . '/..') . "/includes/connect.php";

    $dbcon = new DbOperations;

    // Globalt array til at bruge til at sætte rettigheder på klasser afhængigt af rolle
    $permissions = [
      1 => [
        'show' => 'hidden',
        'restricted' => 'disabled'
      ],
      4 => [
        'show' => 'hidden',
        'restricted' => 'disabled'
      ],
      2 => [
        'show' => '',
        'restricted' => ''
      ],
      3 => [
          'show' => '',
          'active' => ''
      ]
    ];