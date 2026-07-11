<?php include "includes/phpheader.php"; ?>

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

    <div class="tabs" role="tablist" aria-label="Indstillinger">
      <button type="button" class="tabs__tab tabs__tab--active" data-tab="types" role="tab" aria-selected="true">Typer</button>
      <button type="button" class="tabs__tab" data-tab="statuses" role="tab" aria-selected="false">Statusser</button>
      <button type="button" class="tabs__tab" data-tab="priorities" role="tab" aria-selected="false">Prioriteter</button>
      <button type="button" class="tabs__tab" data-tab="roles" role="tab" aria-selected="false">Roller</button>
    </div>

    <div class="filter-bar">
      <div class="filter-bar__group">
        <label for="settings-search" class="filter-bar__label visually-hidden">Søg</label>
        <input type="search" id="settings-search" class="form-field" placeholder="Søg efter navn...">
      </div>
      <button class="btn btn--primary" id="add-settings-btn">Tilføj Type</button>
    </div>

    <section class="card">
      <div class="table-container" id="settings-table-container"></div>
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
        <div class="form-group" id="create-settings-code-group">
          <label for="create-settings-code" class="form-group__label">Beskrivelse</label>
          <input type="text" id="create-settings-code" class="form-field" placeholder="f.eks. t1, t2...">
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
        <div class="form-group" id="edit-settings-code-group">
          <label for="edit-settings-code" class="form-group__label">Beskrivelse</label>
          <input type="text" id="edit-settings-code" class="form-field">
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
