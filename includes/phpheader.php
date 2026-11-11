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
    include_once realpath(__DIR__ . '/..') . "/includes/connect.php";

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

  // 7. Tjek om vi er på localhost (XAMPP) eller online på webhotellet
  $is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');

  // Definer din base URL (tilpas navnet 'helpdesk-sonderjylland' hvis din XAMPP-mappe hedder noget andet)
  $base_url = $is_local ? '/helpdesk-sonderjylland/' : '/';

  // Gør den tilgængelig som en konstant i al din PHP-kode
  if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
  }
  
  // Tjek om det aktuelle script ligger i en 'api'-mappe
  $is_api = (strpos($_SERVER['PHP_SELF'], '/api/') !== false);

  // KUN hvis det IKKE er et API-kald, må headeren udskrive HTML og JavaScript til browseren
  if (!$is_api):
  ?>
  <script>
      window.BASE_URL = "<?php echo BASE_URL; ?>";
  </script>
  <?php endif; 

  // Håndtering af nye release versioner og indhold

  // Sørg for at den KUN kører på almindelige sider, og IKKE på API-kald
  if (isset($dbcon) && (empty($is_api) || $is_api === false)) {
    
    // Hent den seneste feature fra databasen
    $latestFeature = method_exists($dbcon, 'getSortedData') ? $dbcon->getSortedData("systemFeatures", "id DESC") : null;

    if ($latestFeature && isset($latestFeature[0])) {
        $latestVersion = $latestFeature[0]['version'] ?? '1.0';
        $latestContent = $latestFeature[0]['content'] ?? '';
        
        $userSeenVersion = $_COOKIE['seen_feature_version'] ?? '';
        $showPopup = ($userSeenVersion !== $latestVersion);

        if ($showPopup):
?>

 <!-- Opret HTML til bunden af siden for at vise den seneste release, hvis brugeren ikke har set den endnu -->
<div id="bottom-message-bar" class="success">
    <div id="bottom-message-text">
        <strong>🚀 Nyeste release (v. <?= htmlspecialchars($latestVersion) ?>):</strong> <?= nl2br(htmlspecialchars($latestContent)) ?>
    </div>
    <button id="bottom-message-close" title="Luk">&times;</button>
</div>

<script>
  // Sæt den aktuelle version som en global variabel, så vi kan bruge den i JavaScript
  window.CURRENT_FEATURE_VERSION = "<?php echo $latestVersion; ?>";

  document.addEventListener("DOMContentLoaded", function() {
    const bar = document.getElementById('bottom-message-bar');
    const closeBtn = document.getElementById('bottom-message-close');

    setTimeout(() => {
        bar.classList.add('show');
    }, 100);
    
    // Når brugeren klikker på luk-knappen, gemmes den aktuelle version i en cookie, og baren fjernes
    closeBtn.addEventListener('click', function() {
        document.cookie = "seen_feature_version=" + window.CURRENT_FEATURE_VERSION + "; path=/; max-age=" + (60*60*24*365) + "; SameSite=Lax";
        bar.classList.remove('show');
        setTimeout(() => bar.remove(), 300);
    });
});
</script>
<?php 
        endif;
    }
} 
?>
  


   