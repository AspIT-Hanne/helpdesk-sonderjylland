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
        <a href="brugere.html" class="sidebar__item">
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
      <h1 class="page-header__title">Opret Ny Sag</h1>
      <p class="page-header__subtitle">Opret en ny support-sag på vegne af en bruger.</p>
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
            <option value="t1">T1</option>
            <option value="t2">T2</option>
            <option value="t3">T3</option>
            <option value="t4">T4</option>
            <option value="other">Andet</option>
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
          <select id="create-status" class="form-field">
            <option value="not-started" selected>Ikke Startet</option>
            <option value="open">Åben</option>
            <option value="pending">Afventer</option>
            <option value="resolved">Løst</option>
          </select>
        </div>
        <div class="form-group">
          <label for="create-priority" class="form-group__label">Prioritet</label>
          <select id="create-priority" class="form-field">
            <option value="high">Høj</option>
            <option value="medium" selected>Mellem</option>
            <option value="low">Lav</option>
          </select>
        </div>
        <div class="form-group">
          <label for="create-assigned" class="form-group__label">Tildelt Medarbejder</label>
          <select id="create-assigned" class="form-field">
            <option value="">Vælg modtager</option>
            <option value="Jens Clausen">Jens Clausen</option>
            <option value="Malene Gydesen">Malene Gydesen</option>
            <option value="Daniel Weiss">Daniel Weiss</option>
            <option value="Jonas Greve">Jonas Greve</option>
            <option value="Karin Weber">Karin Weber</option>
            <option value="Hanne Lund">Hanne Lund</option>
          </select>
        </div>
        <div class="form-group">
          <label for="create-date" class="form-group__label">Oprettelsesdato</label>
          <input type="text" id="create-date" class="form-field" disabled>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn--primary">Opret Sag</button>
          <button type="reset" class="btn btn--secondary">Annullēr</button>
        </div>
      </form>
    </section>
  </main>

  <script src="js/shared.js"></script>
  <script src="js/opret-sag.js"></script>
</body>
</html>
