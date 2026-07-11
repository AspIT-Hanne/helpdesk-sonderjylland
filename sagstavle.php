<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sagstavle | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/sagstavle.css">
</head>
<body>
  <div class="kanban-page">
    <header class="kanban-header">
      <img src="assets/logo.svg" alt="AspIT" class="kanban-header__logo">
      <a href="index.php" class="kanban-header__back" aria-label="Til Dashboard">
        <img src="assets/house.svg" alt="" class="kanban-header__icon">
      </a>
    </header>

    <section class="stats-grid" aria-label="Statistik">
      <article class="stat-card" id="stat-open">
        <span class="stat-card__label">Åbne</span>
        <span class="stat-card__value">0</span>
        <span class="stat-card__sublabel"><strong class="stat-card__urgent-count">0</strong> hastende</span>
      </article>
      <article class="stat-card" id="stat-pending">
        <span class="stat-card__label">Afventer</span>
        <span class="stat-card__value">0</span>
        <span class="stat-card__sublabel">Venter på svar</span>
      </article>
      <article class="stat-card" id="stat-resolved">
        <span class="stat-card__label">Løst</span>
        <span class="stat-card__value">0</span>
        <span class="stat-card__sublabel">0 seneste 30 dage</span>
      </article>
    </section>

    <main class="kanban-board">
      <section class="kanban-column" data-column="open">
        <header class="kanban-column__header kanban-column__header--open">
          <h2 class="kanban-column__title">Åbne</h2>
          <span class="kanban-column__count">0</span>
        </header>
        <div class="kanban-column__cards">
          <article class="kanban-card" data-created-date="2026-06-10">
            <h3 class="kanban-card__title">Projektør virker ikke i lokale 101</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Jens Clausen</span>
              <span class="priority" data-badge="priority:high">Høj</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Hardware</span>
          </article>
          <article class="kanban-card" data-created-date="2026-06-09">
            <h3 class="kanban-card__title">WiFi-forbindelse afbryder ofte</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Daniel Weiss</span>
              <span class="priority" data-badge="priority:low">Lav</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Netværk</span>
          </article>
        </div>
      </section>

      <section class="kanban-column" data-column="progress">
        <header class="kanban-column__header kanban-column__header--progress">
          <h2 class="kanban-column__title">I Gang</h2>
          <span class="kanban-column__count">0</span>
        </header>
        <div class="kanban-column__cards">
          <article class="kanban-card" data-created-date="2026-06-08">
            <h3 class="kanban-card__title">Email synkroniserer ikke på mobil</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Karin Weber</span>
              <span class="priority" data-badge="priority:medium">Mellem</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Software</span>
          </article>
        </div>
      </section>

      <section class="kanban-column" data-column="pending">
        <header class="kanban-column__header kanban-column__header--pending">
          <h2 class="kanban-column__title">Afventer</h2>
          <span class="kanban-column__count">0</span>
        </header>
        <div class="kanban-column__cards">
          <article class="kanban-card" data-created-date="2026-06-07">
            <h3 class="kanban-card__title">PC #7 i computerlokalet starter ikke</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Hanne Lund</span>
              <span class="priority" data-badge="priority:high">Høj</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Hardware</span>
          </article>
        </div>
      </section>

      <section class="kanban-column" data-column="resolved">
        <header class="kanban-column__header kanban-column__header--resolved">
          <h2 class="kanban-column__title">Løst</h2>
          <span class="kanban-column__count">0</span>
        </header>
        <div class="kanban-column__cards">
          <article class="kanban-card" data-created-date="2026-06-05">
            <h3 class="kanban-card__title">Anmodning om software-installation</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Jonas Greve</span>
              <span class="priority" data-badge="priority:low">Lav</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Software</span>
          </article>
          <article class="kanban-card" data-created-date="2026-04-15">
            <h3 class="kanban-card__title">Papirstop i printer - Kontoret</h3>
            <div class="kanban-card__meta">
              <span class="kanban-card__author">Malene Gydesen</span>
              <span class="priority" data-badge="priority:medium">Mellem</span>
            </div>
            <span class="badge badge--category" data-badge="sagstavle:category">Hardware</span>
          </article>
        </div>
      </section>
    </main>
  </div>
  <script src="js/badges.js"></script>
  <script src="js/sagstavle.js"></script>
</body>
</html>
