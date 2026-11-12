<?php
// 1. Fejllogning
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_error.log');

// 2. Databaseforbindelse
include_once __DIR__ . '/connect.php';
$dbcon = new DbOperations();

// 3. Globalt array til rettigheder afhængigt af rolle
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

// 4. Tjek om vi er på localhost (XAMPP) eller online
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');
$base_url = $is_local ? '/helpdesk-sonderjylland/' : '/';

if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}

// Tjek om det aktuelle script ligger i en 'api'-mappe
$is_api = (strpos($_SERVER['PHP_SELF'], '/api/') !== false);

// KUN hvis det IKKE er et API-kald, må headeren udskrive JavaScript til browseren
if (!$is_api):
?>
<script>
    window.BASE_URL = "<?php echo BASE_URL; ?>";
</script>
<?php endif; ?>

<?php
// 5. Håndtering af nye release versioner (feature-popup)
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
<!-- HTML til bunden af siden for at vise den seneste release -->
<div id="bottom-message-bar" class="warning">
    <div id="bottom-message-text">
        <strong>🚀 Nyeste release (v. <?= htmlspecialchars($latestVersion) ?>):</strong> <?= nl2br(htmlspecialchars($latestContent)) ?>
    </div>
    <button id="bottom-message-close" title="Luk">&times;</button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    window.CURRENT_FEATURE_VERSION = "<?php echo $latestVersion; ?>";
    const bar = document.getElementById('bottom-message-bar');
    const closeBtn = document.getElementById('bottom-message-close');

    setTimeout(() => {
        if (bar) bar.classList.add('show');
    }, 100);
    
    if (closeBtn && bar) {
        closeBtn.addEventListener('click', function() {
            document.cookie = "seen_feature_version=" + window.CURRENT_FEATURE_VERSION + "; path=/; max-age=" + (60*60*24*365) + "; SameSite=Lax";
            bar.classList.remove('show');
            setTimeout(() => bar.remove(), 300);
        });
    }
});
</script>
<?php 
        endif;
    }
} 
?>