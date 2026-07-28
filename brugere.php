<?php 

  include "api/get_users.php";

  $data = getUserData();

  $userroles = getUserRoles();
?>



<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Brugere | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/brugere.css">
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
      <h1 class="page-header__title">Brugeradministration</h1>
      <p class="page-header__subtitle">Administrer systembrugere, roller og rettigheder.</p>
    </header>

    <div class="filter-bar">
      <div class="filter-bar__group">
        <label for="user-search" class="filter-bar__label visually-hidden">Søg brugere</label>
        <input type="search" id="user-search" class="form-field" placeholder="Søg brugere efter navn eller email...">
      </div>
      <div class="filter-bar__group">
        <label for="filter-role" class="filter-bar__label">Rolle</label>
        <select id="filter-role" class="form-field filter-bar__select">
          <option value="">Alle</option>
            <?php 
              foreach($userroles as $thisrole)
                {
                  echo "<option value='{$thisrole['name']}'>{$thisrole['name']}</option>";
                }
            ?>
        </select>
      </div>
      <button class="btn btn--primary" id="add-user-btn">Tilføj Bruger</button>
    </div>

    <section class="card">
      <div class="table-container">
        <table class="data-table" aria-label="Brugere">
          <thead class="data-table__head">
            <tr class="data-table__row">
              <th scope="col" class="data-table__header">Navn</th>
              <th scope="col" class="data-table__header">Email</th>
              <th scope="col" class="data-table__header">Rolle</th>
              <th scope="col" class="data-table__header">Status</th>
              <th scope="col" class="data-table__header">Handlinger</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
           
            <?php 
              foreach($data as $user)
                {
                  echo "<tr class='data-table__row' data-user-id='{$user['id']}' data-name='{$user['username']}' data-email='{$user['email']}' data-role='{$user['role_name']}' data-status='{$user['status_name']}'>";
                    echo "<td class='data-table__cell'>{$user['username']}</td>";
                    echo "<td class='data-table__cell'>{$user['email']}</td>";
                    echo "<td class='data-table__cell'>{$user['role_name']}</td>";
                    echo "<td class='data-table__cell'><span class='badge' data-badge='user-status:{$user['status_name']}'>{$user['status_name']}</span></td>";
                    echo "<td class='data-table__cell'>";
                    echo "<button type='button' class='action-btn' aria-label='Redigér {$user['username']}'>
                            <img src='assets/pencil.svg' alt='' class='action-btn__icon'>
                          </button>";
                    echo "<button type='button' class='action-btn action-btn--danger'' aria-label='Slet {$user['username']}'>
                            <img src='assets/trash.svg' alt='' class='action-btn__icon'>
                          </button>";
                    echo "</td>";
                  echo "</tr>";

            }?>

          </tbody>
        </table>
      </div>
    </section>
  </main>

  <div class="modal" id="user-edit-modal" role="dialog" aria-modal="true" aria-labelledby="user-modal-title-heading">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="user-modal-title-heading">Redigér Bruger</h2>
        <button class="modal__close" id="user-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="user-modal-id" class="form-group__label">Bruger-ID</label>
          <input type="text" id="user-modal-id" class="form-field" disabled>
        </div>
        <div class="form-group">
          <label for="user-modal-name" class="form-group__label">Navn</label>
          <input type="text" id="user-modal-name" class="form-field">
        </div>
        <div class="form-group">
          <label for="user-modal-email" class="form-group__label">E-mail</label>
          <input type="text" id="user-modal-email" class="form-field">
        </div>
        <div class="form-group">
          <label for="user-modal-role" class="form-group__label">Rolle</label>
          <select id="user-modal-role" class="form-field">
            <?php 
              foreach($userroles as $thisrole)
                {
                  echo "<option value='{$thisrole['name']}'>{$thisrole['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-group__label">Status</label>
          <label class="toggle">
            <input type="checkbox" id="user-modal-status" class="toggle__input">
            <span class="toggle__track" aria-hidden="true">
              <span class="toggle__thumb"></span>
            </span>
            <span class="toggle__label-text">Aktiv</span>
          </label>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="user-modal-cancel">Annullér</button>
        <button class="btn btn--tertiary" id="user-modal-reset-password">Ændre password</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <div class="modal" id="user-create-modal" role="dialog" aria-modal="true" aria-labelledby="create-user-modal-title-heading">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="create-user-modal-title-heading">Tilføj Bruger</h2>
        <button class="modal__close" id="create-user-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="create-user-modal-name" class="form-group__label">Navn</label>
          <input type="text" id="create-user-modal-name" class="form-field">
        </div>
        <div class="form-group">
          <label for="create-user-modal-email" class="form-group__label">E-mail</label>
          <input type="text" id="create-user-modal-email" class="form-field">
        </div>
        <div class="form-group">
          <label for="create-user-modal-password" class="form-group__label">Adgangskode</label>
          <input type="password" id="create-user-modal-password" class="form-field">
        </div>
        <div class="form-group">
          <label for="create-user-modal-password-confirm" class="form-group__label">Bekræft Adgangskode</label>
          <input type="password" id="create-user-modal-password-confirm" class="form-field">
        </div>
        <div class="form-group">
          <label for="create-user-modal-role" class="form-group__label">Rolle</label>
          <select id="create-user-modal-role" class="form-field">
            <option value="Admin">Admin</option>
            <option value="Tekniker">Tekniker</option>
            <option value="Læserettigheder">Læserettigheder</option>
            <option value="Bruger">Bruger</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-group__label">Status</label>
          <label class="toggle">
            <input type="checkbox" id="create-user-modal-status" class="toggle__input">
            <span class="toggle__track" aria-hidden="true">
              <span class="toggle__thumb"></span>
            </span>
            <span class="toggle__label-text" id="create-user-modal-status-label">Aktiv</span>
          </label>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="create-user-modal-cancel">Annullér</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <div class="modal modal--danger" id="user-delete-modal" role="alertdialog" aria-modal="true" aria-labelledby="user-delete-modal-title">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="user-delete-modal-title">Slet Bruger</h2>
        <button class="modal__close" id="user-delete-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <p id="user-delete-modal-message">Er du sikker på, at du vil slette denne bruger? Denne handling kan ikke fortrydes.</p>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="user-delete-modal-cancel">Annullér</button>
        <button class="btn btn--danger" id="user-delete-modal-confirm">Slet Bruger</button>
      </footer>
    </div>
  </div>

  <div class="modal" id="change-password-modal" role="dialog" data-user-id="" aria-modal="true" aria-labelledby="change-password-modal-title-heading">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="change-password-modal-title-heading">Ændre password</h2>
        <button class="modal__close" id="change-password-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="change-password-modal-password" class="form-group__label">Ny adgangskode</label>
          <input type="password" id="change-password-modal-password" class="form-field">
        </div>
        <div class="form-group">
          <label for="change-password-modal-password-confirm" class="form-group__label">Bekræft ny adgangskode</label>
          <input type="password" id="change-password-modal-password-confirm" class="form-field">
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="change-password-modal-cancel">Annullér</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script src="js/brugere.js" type="module"></script>
</body>
</html>
