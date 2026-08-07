<?php
include_once __DIR__ . '/includes/phpheader.php';

$errorMsg = '';
$errorType = 'warning';

// Tjek om der er sendt data fra formularen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
            session_start();
            $userid = $dbcon->verifyLogin($username, $password);

            if ($userid) {
                 // Login succesfuldt: Hent brugerdata (vi skal primært bruge rollen til at sætte tilladelser) og sæt sessionen
                $userdata = $dbcon->getDataByID('users', $userid);
               
                $_SESSION['logged_in'] = true;
                $_SESSION['userid'] = $userid;
                $_SESSION['username'] = $username;
                $_SESSION['userRole_id'] = $userdata['userRole_id'];
                $_SESSION['location_id'] = $userdata['location_id'];
                
                // Send brugeren videre til forsiden
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION = array();
            session_destroy();
            $errorMsg = $e->getMessage();
            $errorType = 'error';
        }
    } 
    else if(empty($username)){
        $errorMsg = 'Udfyld venligst brugernavn.';
        $errorType = 'warning';
    }
    else
    {
        $errorMsg = 'Udfyld venligst brugernavn.';
        $errorType = 'warning';
    }

}
?>
<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <?php include "includes/sidebar.php"; ?>

    <main class="main">
        <div class="mobile-header">
            <button class="hamburger" id="hamburger" aria-label="Åbn menu">
                <img src="assets/menu.svg" alt="" class="hamburger__icon">
            </button>
        </div>

        <header class="page-header">
            <h1 class="page-header__title">Login til AspIT Demo Help Desk - Sønderjylland</h1>
            <p class="page-header__subtitle">For at få adgang til sager, skal du logge ind her.</p>
        </header>

         <section class="card">
            <form id="login-form" method="post">
                <div class="form-group">
                    <label for="login-username" class="form-group__label">Brugernavn</label>
                    <input type="text" id="login-username" name="username" class="form-field" required>
                </div>

                <div class="form-group">
                    <label for="login-password" class="form-group__label">Adgangskode</label>
                    <input type="password" id="login-password" name="password" class="form-field" required>
                </div>

                <button type="submit" class="btn btn--primary">Log ind</button>
        </form>
    </div>
    
    <script src="js/shared.js"></script>

    <?php if (!empty($errorMsg)): ?>
        <script>
            showBottomMessage('<?php echo addslashes($errorMsg); ?>', 'warning');
        </script>
    <?php endif; ?>
</body>
</html>