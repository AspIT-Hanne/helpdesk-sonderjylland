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
