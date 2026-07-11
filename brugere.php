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
  <nav class="sidebar" id="sidebar">
    <div class="sidebar__header">
      <img src="assets/logo.svg" alt="AspIT" class="sidebar__logo">
      <button class="sidebar__close" id="sidebar-close" aria-label="Luk menu">&times;</button>
    </div>
    <ul class="sidebar__nav">
      <li>
        <a href="index.html" class="sidebar__item">
          <img src="assets/house.svg" alt="" class="sidebar__icon">
          Dashboard
        </a>
      </li>
      <li>
        <a href="sager.html" class="sidebar__item">
          <img src="assets/file.svg" alt="" class="sidebar__icon">
          Sager
        </a>
      </li>
      <li>
        <a href="sagstavle.html" class="sidebar__item">
          <img src="assets/board.svg" alt="" class="sidebar__icon">
          Sagstavle
        </a>
      </li>
      <li>
        <a href="brugere.html" class="sidebar__item sidebar__item--active">
          <img src="assets/users.svg" alt="" class="sidebar__icon">
          Brugere
        </a>
      </li>
      <li>
        <a href="opret-sag.html" class="sidebar__item sidebar__item--cta">
          <img src="assets/plus.svg" alt="" class="sidebar__icon">
          Opret Sag
        </a>
      </li>
      <li>
        <a href="indstillinger.html" class="sidebar__item">
          <img src="assets/settings.svg" alt="" class="sidebar__icon">
          Indstillinger
        </a>
      </li>
    </ul>
  </nav>

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
          <option value="Admin">Admin</option>
          <option value="Tekniker">Tekniker</option>
          <option value="Læserettigheder">Læserettigheder</option>
          <option value="Bruger">Bruger</option>
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
            <tr class="data-table__row" data-user-id="1" data-name="Jens Clausen" data-email="jens.clausen@skole.dk" data-role="Admin" data-status="active">
              <td class="data-table__cell">Jens Clausen</td>
              <td class="data-table__cell">jens.clausen@skole.dk</td>
              <td class="data-table__cell">Admin</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:active">Aktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Jens Clausen">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Jens Clausen">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
            <tr class="data-table__row" data-user-id="2" data-name="Malene Gydesen" data-email="malene.gydesen@skole.dk" data-role="Admin" data-status="active">
              <td class="data-table__cell">Malene Gydesen</td>
              <td class="data-table__cell">malene.gydesen@skole.dk</td>
              <td class="data-table__cell">Admin</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:active">Aktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Malene Gydesen">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Malene Gydesen">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
            <tr class="data-table__row" data-user-id="3" data-name="Daniel Weiss" data-email="daniel.weiss@skole.dk" data-role="Tekniker" data-status="active">
              <td class="data-table__cell">Daniel Weiss</td>
              <td class="data-table__cell">daniel.weiss@skole.dk</td>
              <td class="data-table__cell">Tekniker</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:active">Aktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Daniel Weiss">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Daniel Weiss">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
            <tr class="data-table__row" data-user-id="4" data-name="Karin Weber" data-email="karin.weber@skole.dk" data-role="Tekniker" data-status="inactive">
              <td class="data-table__cell">Karin Weber</td>
              <td class="data-table__cell">karin.weber@skole.dk</td>
              <td class="data-table__cell">Tekniker</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:inactive">Inaktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Karin Weber">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Karin Weber">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
            <tr class="data-table__row" data-user-id="5" data-name="Hanne Lund" data-email="hanne.lund@skole.dk" data-role="Læserettigheder" data-status="active">
              <td class="data-table__cell">Hanne Lund</td>
              <td class="data-table__cell">hanne.lund@skole.dk</td>
              <td class="data-table__cell">Læserettigheder</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:active">Aktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Hanne Lund">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Hanne Lund">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
            <tr class="data-table__row" data-user-id="6" data-name="Jonas Greve" data-email="jonas.greve@skole.dk" data-role="Tekniker" data-status="active">
              <td class="data-table__cell">Jonas Greve</td>
              <td class="data-table__cell">jonas.greve@skole.dk</td>
              <td class="data-table__cell">Tekniker</td>
              <td class="data-table__cell"><span class="badge" data-badge="user-status:active">Aktiv</span></td>
              <td class="data-table__cell">
                <button type="button" class="action-btn" aria-label="Redigér Jonas Greve">
                  <img src="assets/pencil.svg" alt="" class="action-btn__icon">
                </button>
                <button type="button" class="action-btn action-btn--danger" aria-label="Slet Jonas Greve">
                  <img src="assets/trash.svg" alt="" class="action-btn__icon">
                </button>
              </td>
            </tr>
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
          <label for="user-modal-password" class="form-group__label">Adgangskode</label>
          <input type="password" id="user-modal-password" class="form-field">
        </div>
        <div class="form-group">
          <label for="user-modal-role" class="form-group__label">Rolle</label>
          <select id="user-modal-role" class="form-field">
            <option value="Admin">Admin</option>
            <option value="Tekniker">Tekniker</option>
            <option value="Læserettigheder">Læserettigheder</option>
            <option value="Bruger">Bruger</option>
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

  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script src="js/brugere.js"></script>
</body>
</html>
