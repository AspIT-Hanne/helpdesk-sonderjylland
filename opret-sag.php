<?php 
  include "includes/phpheader.php"; 

  if (!isset($_SESSION['logged_in'])) 
  {
    header('Location: login.php');
    exit;
  }
  else
  {
    include "api/get_tickets.php";

    if($_SESSION['userRole_id'] > 0 && $_SESSION['userRole_id'] < 4)
    {
      $role = $_SESSION['userRole_id'];
    }
    else
    {
      $role = 4;
    }

    $data = getTicketData(); 
      
    $technicians = getTechnicians();
      
    $status = getStatus();

    $priorities = getPriority();

    $categories = getCategory();

    $users = getUsers();
  }
?>

<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Opret Sag | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/opret-sag.css">
</head>
<body>
 <?php include "includes/sidebar.php"; ?>

  <div class="overlay" id="overlay"></div>

  <main class="main">
    <div class="mobile-header">
      <button class="hamburger" id="hamburger" aria-label="Åbn menu">
        <img src="assets/menu.svg" alt="" class="hamburger__icon">
      </button>
    </div>

    <header class="page-header">
      <h1 class="page-header__title">Opret Ny Sag</h1>
      <p class="page-header__subtitle">Opret en ny support-sag</p>
    </header>

    <section class="card">
      <form id="create-ticket-form">
        <div class="form-group">
          <label for="create-title" class="form-group__label">Titel</label>
          <input type="text" id="create-title" class="form-field" placeholder="Kort beskrivelse af problemet..." required>
        </div>
        <div class="form-group">
          <label for="create-type" class="form-group__label">Type</label>
          <select id="create-type" class="form-field" required>
            <option value="">Vælg en kategori</option>
            <?php 
              foreach($categories as $category)
                {
                   if (!str_starts_with($category['name'], 'T') || $role == 2 || $role == 3) {
                      echo "<option value='{$category['name']}'>{$category['name']}</option>";
                  }

                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="create-description" class="form-group__label">Beskrivelse</label>
          <textarea id="create-description" class="form-field" rows="4" placeholder="Giv detaljeret information om problemet..."></textarea>
        </div>
        <div class="form-group">
          <label for="create-location" class="form-group__label">Lokation</label>
          <input type="text" id="create-location" class="form-field" placeholder="Hvor er problemet?">
        </div>
        <div class="form-group">
          <label for="create-status" class="form-group__label">Status</label>
          <select id="create-status" class="form-field" <?= $permissions[$role]['restricted']; ?>>
            <?php 
              foreach($status as $thisstatus)
                {
                  echo "<option value='{$thisstatus['name']}'>{$thisstatus['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="create-priority" class="form-group__label">Prioritet</label>
          <select id="create-priority" class="form-field">
            <?php 
              foreach($priorities as $priority)
                {
                  echo "<option value='{$priority['name']}'>{$priority['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group <?= $permissions[$role]['show']; ?>" >
          <input type="checkbox" class="show__users" id="chk-show-users">
          <label for="chk-show-users">Opret sag for bruger</label>
          <div class="created__by">
            <label for="create-createdby" class="form-group__label">Sag oprettes for</label>
            <select id="create-createdby" class="form-field">
              <option value="">Vælg bruger</option>
              <?php 
                foreach($users as $user)
                  {
                    echo "<option value='{$user['username']}'>{$user['username']}</option>";
                  }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group <?= $permissions[$role]['show']; ?>">
          <label for="create-assigned" class="form-group__label">Tildel Medarbejder</label>
          <select id="create-assigned" class="form-field" <?= $permissions[$role]['restricted']; ?>>
            <option value="">Vælg tekniker</option>
            <?php 
              foreach($technicians as $technician)
                {
                  echo "<option value='{$technician['username']}'>{$technician['username']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="create-date" class="form-group__label">Oprettelsesdato</label>
          <input type="text" id="create-date" class="form-field" disabled>
        </div>
        <div class="form-group">
          <button type="button" class="btn btn--primary" id="add-sag-btn">Opret Sag</button>
          <button type="button" class="btn btn--secondary">Annullēr</button>
        </div>
      </form>
    </section>
  </main>

  <script src="js/shared.js"></script>
  <script src="js/opret-sag.js" type="module"></script>
</body>
</html>
