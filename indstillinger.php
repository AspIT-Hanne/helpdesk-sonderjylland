<?php 

include_once realpath(__DIR__ ) . "/includes/phpheader.php";
include "api/get_features.php";

if($_SESSION['userRole_id'] == 1 || $_SESSION['userRole_id'] == 2 || $_SESSION['userRole_id'] == 3 || $_SESSION['userRole_id'] == 5)
{
  $role = $_SESSION['userRole_id'];
}
else
{
  $role = 4;
}

$data = getFeaturesData();

?>

<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Indstillinger | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/indstillinger.css">
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
      <h1 class="page-header__title">Indstillinger</h1>
      <p class="page-header__subtitle">Administrer tilgængelige typer, statusser, prioriteter og roller.</p>
    </header>

    <div class="tabs" id="tabs-container" role="tablist" aria-label="Indstillinger">
      
    </div>

    <div class="filter-bar">
      <button class="btn btn--primary" id="add-settings-btn">Tilføj Type</button>
      <div class="filter-bar__group">
        <label for="settings-search" class="filter-bar__label visually-hidden">Søg</label>
        <input type="search" id="settings-search" class="form-field" placeholder="Søg efter navn...">
      </div>
    </div>

    <section class="card">
      <div class="table-container" id="settings-table-container"></div>
    </section>

    <section class="card superadmin-only <?php echo ($_SESSION['userRole_id'] === 5) ? '' : 'hidden'; ?>" id="settings-superadmin-section">
      <h3 class="card__title">Ny release</h3>
      <form id="create-ticket-form">
        <div class="form-group">
          <label for="create-release" class="form-group__label">Version</label>
          <input type="text" id="create-release" class="form-field" placeholder="Versionsnummer..." required>
        </div>
        <div class="form-group">
          <label for="create-description" class="form-group__label">Beskrivelse</label>
          <textarea id="create-description" class="form-field" rows="4" placeholder="Indhold i denne version..." required></textarea>
        </div>
        <div class="form-group">
          <button type="button" class="btn btn--primary" id="add-feature-btn">Opret ny version</button>
          <button type="button" class="btn btn--secondary" id="cancel-feature-btn">Annullēr</button>
        </div>
      </form>
  </section>
  <section class="card superadmin-only <?php echo ($_SESSION['userRole_id'] === 5) ? '' : 'hidden'; ?>" id="features-superadmin-section">
    <h3 class="card__title">Tidligere releases</h3>
            
      <div class="table-container" id="settings-previous-features">
        <table class="data-table" aria-label="Sager">
          <thead class="data-table__head">
            <tr class="data-table__row">
              <th scope="col" class="data-table__header">ID</th>
              <th scope="col" class="data-table__header">Version</th>
              <th scope="col" class="data-table__header">Beskrivelse</th>
              <th scope="col" class="data-table__header">Oprettet d.</th>
          
            </tr>
          </thead>
          <tbody id="sager-table-body">
            <?php
            foreach ($data as $feature) {
              echo "<tr class='data-table__row' data-ticket-id='{$feature['id']}' data-description='{$feature['content']}' data-type='{$feature['version']}' data-created-date='{$feature['created_at']}'>";
              echo "<td class='data-table__cell'>#{$feature['id']}</td>";
              echo "<td class='data-table__cell'>{$feature['version']}</td>";
              echo "<td class='data-table__cell'>{$feature['content']}</td>";
              echo "<td class='data-table__cell'>" . date_format(new DateTime($feature['created_at']), "d-m-y") . "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <div class="modal" id="settings-create-modal" role="dialog" aria-modal="true" aria-labelledby="settings-create-modal-title">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="settings-create-modal-title">Tilføj Type</h2>
        <button class="modal__close" id="settings-create-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="create-settings-name" class="form-group__label">Navn</label>
          <input type="text" id="create-settings-name" class="form-field">
        </div>
        <div class="form-group" id="create-settings-desc-group">
          <label for="create-settings-desc" class="form-group__label">Beskrivelse</label>
          <input type="text" id="create-settings-desc" class="form-field" placeholder="f.eks. t1, t2...">
        </div>
        <div class="form-group">
          <label class="form-group__label">Farve</label>
          <div class="color-picker" id="create-settings-color-picker"></div>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="settings-create-modal-cancel">Annuller</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <div class="modal" id="settings-edit-modal" role="dialog" aria-modal="true" aria-labelledby="settings-edit-modal-title">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="settings-edit-modal-title">Redigér Type</h2>
        <button class="modal__close" id="settings-edit-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="edit-settings-id" class="form-group__label">ID</label>
          <input type="text" id="edit-settings-id" class="form-field" disabled>
        </div>
        <div class="form-group">
          <label for="edit-settings-name" class="form-group__label">Navn</label>
          <input type="text" id="edit-settings-name" class="form-field">
        </div>
        <div class="form-group" id="edit-settings-desc-group">
          <label for="edit-settings-desc" class="form-group__label">Beskrivelse</label>
          <input type="text" id="edit-settings-desc" class="form-field">
        </div>
        <div class="form-group">
          <label class="form-group__label">Farve</label>
          <div class="color-picker" id="edit-settings-color-picker"></div>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="settings-edit-modal-cancel">Annuller</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <div class="modal modal--danger" id="settings-delete-modal" role="alertdialog" aria-modal="true" aria-labelledby="settings-delete-modal-title">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="settings-delete-modal-title">Slet</h2>
        <button class="modal__close" id="settings-delete-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <p id="settings-delete-modal-message">Er du sikker på, at du vil slette dette? Denne handling kan ikke fortrydes.</p>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="settings-delete-modal-cancel">Annuller</button>
        <button class="btn btn--danger" id="settings-delete-modal-confirm">Slet</button>
      </footer>
    </div>
  </div>



  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script type="module" src="js/indstillinger.js"></script>
</body>
</html>
