<?php 
  include_once __DIR__ . '/includes/phpheader.php'; 
  
  include "api/get_tickets.php";
  
  $statusser = getStatus();
  
  ?>

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
      <a href="index.php" class="kanban-header__back" aria-label="Til Dashboard">
        <img src="assets/house.svg" alt="" class="kanban-header__icon">
      </a>
      <img src="assets/logo.svg" alt="AspIT" class="kanban-header__logo">
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
      <?php
        $column = 0;
        foreach ($statusser as $status)
          { 
            $ticketsStatus = getTicketByStatus($status['id']);
          ?>
            <section class="kanban-column" data-column="<?= $status['name']; ?>">
              <header class="kanban-column__header kanban-column__header--<?= $status['name']; ?>">
                <h2 class="kanban-column__title"><?= $status['name']; ?></h2>
                <span class="kanban-column__count"><?= $column; ?></span>
              </header>
              <div class="kanban-column__cards">
              <?php
                foreach ($ticketsStatus as $thisTicket)
                  { ?>
                    <article class="kanban-card" data-created-date="<?= date_format(new DateTime($thisTicket['created_at']), 'd-m-y'); ?>">
                      <h3 class="kanban-card__title" data-badge="priority:<?= $thisTicket['priority_name']; ?>" data-badge-color="<?= $thisTicket['priority_color']; ?>"><span><?= $thisTicket['title']; ?></span><span><?= $thisTicket['priority_name']; ?></span></h3>
                      <div class="kanban-card__meta">
                        <span class="kanban-card__author">Oprettet af: <?= $thisTicket['createdBy_name']; ?></span>
                        <span class="kanban-card__author"><?php if(empty($thisTicket['assignedTo_name'])) { echo "Endnu ikke tildelt";} else { echo "Tildelt: {$thisTicket['assignedTo_name']}"; }?></span>
                        
                      </div>
                      <div class="kanban-card__meta">
                        <span class="badge badge--category" data-badge="type:<?= $thisTicket['category_name']; ?>" data-badge-color="<?= $thisTicket['category_color']; ?>"><?= $thisTicket['category_name']; ?></span>
                        <span class="kanban-card__author">Oprettet: <?= date_format(new DateTime($thisTicket['created_at']), 'd-m-y'); ?></span>
                      </div>
                    </article>
              <?php } ?>
              </div>
            </section>

              
      <?php 
        $column++;
      } ?>
                
               
      
    </main>
  </div>
  <script src="js/badges.js"></script>
  <script src="js/sagstavle.js"></script>
</body>
</html>
