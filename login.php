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
            $userid = $dbcon->verifyLogin($username, $password);

            if ($userid) {
                // Login succesfuldt: Sæt sessionen
                $_SESSION['logged_in'] = true;
                $_SESSION['userid'] = $userid;
                $_SESSION['username'] = $username;
                
                // Send brugeren videre til forsiden
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
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

    <div class="login-container">
        <h2>Log ind</h2>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Brugernavn</label>
                <input type="text" id="username" name="username">
            </div>
            
            <div class="form-group">
                <label for="password">Adgangskode</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit">Log ind</button>
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