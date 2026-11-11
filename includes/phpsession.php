<?php
    // 1. Sæt sessionslevetid til 7 timer (7 * 60 * 60 = 25200 sekunder)
  $lifetime = 7 * 60 * 60;
  ini_set('session.gc_maxlifetime', $lifetime);

  // 2. Opret privat sessionsmappe i roden (ét niveau op fra /includes)
  $sessionPath = __DIR__ . '/../sessions'; 
  if (!is_dir($sessionPath)) {
      mkdir($sessionPath, 0777, true);
  }
  session_save_path($sessionPath);

  // 3. Sæt cookie-parametre for sessionen
  session_set_cookie_params([
      'lifetime' => $lifetime,
      'path'     => '/',
      'secure'   => isset($_SERVER['HTTPS']),
      'httponly' => true,
      'samesite' => 'Lax'
  ]);

  // 4. Start sessionen (skal altid ske EFTER indstillingerne ovenfor)
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // 5. Tjek hvilken side brugeren er på
  $current_page = basename($_SERVER['PHP_SELF']);

  // Hvis brugeren IKKE er logget ind, og IKKE allerede er på login-siden, så send dem til login 
  if (!isset($_SESSION['userid']) && $current_page != 'login.php') {
      header('Location: login.php');
      exit;
  }
  ?>