<?php
   // Tving PHP til at vise fejlen i loggen og ikke på skærmen
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/php_error.log');

    // Start sessionen først (hvis den ikke allerede kører)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Tjek hvilken side brugeren er på
  $current_page = basename($_SERVER['PHP_SELF']);

  // Hvis brugeren IKKE er logget ind, og IKKE allerede er på login-siden, så send dem til login
  
  if (!isset($_SESSION['userid']) && $current_page != 'login.php') {
      header('Location: login.php');
      exit;
  }
  else
  {
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
  }
  


   