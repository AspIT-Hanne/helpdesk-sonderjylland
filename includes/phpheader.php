<?php
  // 1. Fejllogning
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  ini_set('log_errors', 1);
  ini_set('error_log', __DIR__ . '/php_error.log');

  // 2. Sæt sessionslevetid til 7 timer (7 * 60 * 60 = 25200 sekunder)
  $lifetime = 7 * 60 * 60;
  ini_set('session.gc_maxlifetime', $lifetime);

// 3. Opret privat sessionsmappe i roden (ét niveau op fra /includes)
  $sessionPath = __DIR__ . '/../sessions'; 
  if (!is_dir($sessionPath)) {
      mkdir($sessionPath, 0777, true);
  }
  session_save_path($sessionPath);

  // 4. Sæt cookie-parametre for sessionen
  session_set_cookie_params([
      'lifetime' => $lifetime,
      'path'     => '/',
      'secure'   => isset($_SERVER['HTTPS']),
      'httponly' => true,
      'samesite' => 'Lax'
  ]);

  // 5. Start sessionen (skal altid ske EFTER indstillingerne ovenfor)
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // 6. Tjek hvilken side brugeren er på
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
      // Role: Bruger
      1 => [
        'show' => 'hidden',
        'settings' => 'hidden',
        'restricted' => 'disabled'
      ],
      // Role: Læserettigheder
      4 => [
        'show' => 'hidden',
        'settings' => 'hidden',
        'restricted' => 'disabled'
      ],
      // Role: Tekniker
      2 => [
        'show' => '',
        'settings' => 'hidden',
        'restricted' => ''
      ],
      // Role: Admin
      3 => [
        'show' => '',
        'settings' => '',
        'restricted' => ''
      ]
    ];
  }
  


   