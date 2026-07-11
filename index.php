<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/index.css">
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
      <h1 class="page-header__title">Dashboard</h1>
      <p class="page-header__subtitle">Velkommen tilbage! Her er en oversigt over dine support-sager.</p>
    </header>

    <!--
      TODO: When backend API is connected, replace hardcoded stats below
      with live data. The JS in js/index.js will automatically toggle
      stat-card--focus classes based on the numbers in these elements.
    -->
    <section class="stats-grid" aria-label="Statistik">
      <article class="stat-card" id="stat-open">
        <span class="stat-card__label">Åbne</span>
        <span class="stat-card__value">18</span>
        <span class="stat-card__sublabel"><strong class="stat-card__urgent-count">4</strong> hastende</span>
      </article>
      <article class="stat-card" id="stat-pending">
        <span class="stat-card__label">Afventer</span>
        <span class="stat-card__value">8</span>
        <span class="stat-card__sublabel">Venter på svar</span>
      </article>
      <article class="stat-card" id="stat-resolved">
        <span class="stat-card__label">Løst</span>
        <span class="stat-card__value">221</span>
        <span class="stat-card__sublabel">Denne måned</span>
      </article>
    </section>

    <section class="card">
      <h2 class="card__title" id="table-heading">Seneste Sager</h2>
      <div class="filter-bar" role="search" aria-label="Filtrér sager">
        <div class="filter-bar__group">
          <label for="filter-status" class="filter-bar__label">Status</label>
          <select id="filter-status" class="form-field filter-bar__select">
            <option value="">Alle</option>
            <option value="not-started">Ikke Startet</option>
            <option value="open">Åben</option>
            <option value="pending">Afventer</option>
            <option value="resolved">Løst</option>
          </select>
        </div>
        <div class="filter-bar__group">
          <label for="filter-assigned" class="filter-bar__label">Tildelt</label>
          <select id="filter-assigned" class="form-field filter-bar__select">
            <option value="">Alle</option>
            <option value="Jens Clausen">Jens Clausen</option>
            <option value="Malene Gydesen">Malene Gydesen</option>
            <option value="Daniel Weiss">Daniel Weiss</option>
            <option value="Jonas Greve">Jonas Greve</option>
            <option value="Karin Weber">Karin Weber</option>
            <option value="Hanne Lund">Hanne Lund</option>
          </select>
        </div>
        <button class="btn btn--secondary filter-bar__reset">Nulstil</button>
      </div>
      <div class="table-container">
        <table class="data-table" aria-labelledby="table-heading">
          <thead class="data-table__head">
            <tr class="data-table__row">
              <th scope="col" class="data-table__header">ID</th>
              <th scope="col" class="data-table__header">Titel</th>
              <th scope="col" class="data-table__header">Lokation</th>
              <th scope="col" class="data-table__header">Status</th>
              <th scope="col" class="data-table__header">Prioritet</th>
              <th scope="col" class="data-table__header">Tildelt</th>
              <th scope="col" class="data-table__header">Oprettelsesdato</th>
            </tr>
          </thead>
          <tbody id="ticket-table-body">
            <tr class="data-table__row" data-ticket-id="247" data-description="" data-title="Projektør virker ikke i lokale 101" data-type="t2" data-location="Lokale 101" data-status="open" data-priority="high" data-assigned="Jens Clausen" data-created-date="2026-05-21" role="button" tabindex="0">
              <td class="data-table__cell">#247</td>
              <td class="data-table__cell">Projektør virker ikke i lokale 101</td>
              <td class="data-table__cell">Lokale 101</td>
              <td class="data-table__cell"><span class="badge" data-badge="status:open">Åben</span></td>
              <td class="data-table__cell"><span class="priority" data-badge="priority:high">Høj</span></td>
              <td class="data-table__cell">Jens Clausen</td>
              <td class="data-table__cell">2026-05-21</td>
            </tr>
            <tr class="data-table__row" data-ticket-id="246" data-description="" data-title="Elever har login-problemer" data-type="t1" data-location="Serverrum" data-status="pending" data-priority="medium" data-assigned="Malene Gydesen" data-created-date="2026-05-21" role="button" tabindex="0">
              <td class="data-table__cell">#246</td>
              <td class="data-table__cell">Elever har login-problemer</td>
              <td class="data-table__cell">Serverrum</td>
              <td class="data-table__cell"><span class="badge" data-badge="status:pending">Afventer</span></td>
              <td class="data-table__cell"><span class="priority" data-badge="priority:medium">Mellem</span></td>
              <td class="data-table__cell">Malene Gydesen</td>
              <td class="data-table__cell">2026-05-21</td>
            </tr>
            <tr class="data-table__row" data-ticket-id="245" data-description="" data-title="WiFi-forbindelse afbryder ofte" data-type="t3" data-location="Bygning C" data-status="not-started" data-priority="low" data-assigned="Daniel Weiss" data-created-date="2026-05-20" role="button" tabindex="0">
              <td class="data-table__cell">#245</td>
              <td class="data-table__cell">WiFi-forbindelse afbryder ofte</td>
              <td class="data-table__cell">Bygning C</td>
              <td class="data-table__cell"><span class="badge" data-badge="status:open">Åben</span></td>
              <td class="data-table__cell"><span class="priority" data-badge="priority:low">Lav</span></td>
              <td class="data-table__cell">Daniel Weiss</td>
              <td class="data-table__cell">2026-05-20</td>
            </tr>
            <tr class="data-table__row" data-ticket-id="244" data-description="" data-title="Anmodning om software-installation" data-type="t4" data-location="IT-Kontor" data-status="resolved" data-priority="low" data-assigned="Jens Clausen" data-created-date="2026-05-20" role="button" tabindex="0">
              <td class="data-table__cell">#244</td>
              <td class="data-table__cell">Anmodning om software-installation</td>
              <td class="data-table__cell">IT-Kontor</td>
              <td class="data-table__cell"><span class="badge" data-badge="status:resolved">Løst</span></td>
              <td class="data-table__cell"><span class="priority" data-badge="priority:low">Lav</span></td>
              <td class="data-table__cell">Jens Clausen</td>
              <td class="data-table__cell">2026-05-20</td>
            </tr>
            <tr class="data-table__row" data-ticket-id="243" data-description="" data-title="Papirstop i printer - Kontoret" data-type="other" data-location="Kontoret" data-status="resolved" data-priority="medium" data-assigned="Malene Gydesen" data-created-date="2026-05-19" role="button" tabindex="0">
              <td class="data-table__cell">#243</td>
              <td class="data-table__cell">Papirstop i printer - Kontoret</td>
              <td class="data-table__cell">Kontoret</td>
              <td class="data-table__cell"><span class="badge" data-badge="status:resolved">Løst</span></td>
              <td class="data-table__cell"><span class="priority" data-badge="priority:medium">Mellem</span></td>
              <td class="data-table__cell">Malene Gydesen</td>
              <td class="data-table__cell">2026-05-19</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <div class="modal" id="edit-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-heading">

    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="modal-title-heading">Redigér Sag</h2>
        <button class="modal__close" id="modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="modal-id" class="form-group__label">Sags-ID</label>
          <input type="text" id="modal-id" class="form-field" disabled>
        </div>
        <div class="form-group">
          <label for="modal-title" class="form-group__label">Titel</label>
          <input type="text" id="modal-title" class="form-field">
        </div>
        <div class="form-group">
          <label for="modal-type" class="form-group__label">Type</label>
          <select id="modal-type" class="form-field">
            <option value="t1">T1</option>
            <option value="t2">T2</option>
            <option value="t3">T3</option>
            <option value="t4">T4</option>
            <option value="other">Andet</option>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-description" class="form-group__label">Beskrivelse</label>
          <textarea id="modal-description" class="form-field" rows="4" placeholder="Beskrivelse af sagen..."></textarea>
        </div>
        <div class="form-group">
          <label for="modal-location" class="form-group__label">Lokation</label>
          <input type="text" id="modal-location" class="form-field">
        </div>
        <div class="form-group">
          <label for="modal-status" class="form-group__label">Status</label>
          <select id="modal-status" class="form-field">
            <option value="not-started">Ikke Startet</option>
            <option value="open">Åben</option>
            <option value="pending">Afventer</option>
            <option value="resolved">Løst</option>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-priority" class="form-group__label">Prioritet</label>
          <select id="modal-priority" class="form-field">
            <option value="high">Høj</option>
            <option value="medium">Mellem</option>
            <option value="low">Lav</option>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-assigned" class="form-group__label">Tildelt Medarbejder</label>
          <select id="modal-assigned" class="form-field">
            <option value="">Vælg tekniker</option>
            <option value="Jens Clausen">Jens Clausen</option>
            <option value="Malene Gydesen">Malene Gydesen</option>
            <option value="Daniel Weiss">Daniel Weiss</option>
            <option value="Jonas Greve">Jonas Greve</option>
            <option value="Karin Weber">Karin Weber</option>
            <option value="Hanne Lund">Hanne Lund</option>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-created-date" class="form-group__label">Oprettelsesdato</label>
          <input type="text" id="modal-created-date" class="form-field" disabled>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="modal-cancel">Annullér</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script src="js/index.js"></script>
</body>
</html>
