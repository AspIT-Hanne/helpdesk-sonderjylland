<?php
include_once __DIR__ . '/includes/phpsession.php';
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

    <!-- <section class="stats-grid" aria-label="Statistik">
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
    </section> -->

    <main class="kanban-board">
      <?php
      $column = 0;
      foreach ($statusser as $status) 
      {
        $isResolved = ($status['name'] === 'Løst');
        $hiddenClass = $isResolved ? 'hidden' : '';
        
        $ticketsStatus = getTicketByStatus($status['id']); 
        
        usort($ticketsStatus, function($a, $b) {
          // Sorter nyeste først (faldende / descending)
          return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        
        if($status['name'] === 'Ikke-startet' && count($ticketsStatus) > 5) {
          
          $counter = 2;
          $ticketsStatus1 = array_slice($ticketsStatus, 0, 5); // Begræns til de 5 nyeste sager
          $ticketsStatus2 = array_slice($ticketsStatus, 5); // Resten af sagerne
          $column = count($ticketsStatus1) + count($ticketsStatus2);
        }
        else
        {
          $ticketsStatus2 = $ticketsStatus;
          $counter = 1;
        }

        $i = 0;
        while($i < $counter)
        {
          if($status['name'] === 'Ikke-startet' && $i === 0) {
            $ticketsStatus = $ticketsStatus1;
          }
          else
          {
            $ticketsStatus = $ticketsStatus2;
          }

      ?>
          <section class="kanban-column <?php echo $hiddenClass; ?>" data-column="<?= $status['name']; ?>">
            <header class="kanban-column__header kanban-column__header--<?= $status['name']; ?>">
              <h2 class="kanban-column__title"><?= $status['name']; ?></h2>
              <span class="kanban-column__count"><?= $column; ?></span>
            </header>
            <div class="kanban-column__cards">
              <?php
              foreach ($ticketsStatus as $thisTicket) 
              { ?>
                <article class="kanban-card" data-created-date="<?= date_format(new DateTime($thisTicket['created_at']), 'd-m-y'); ?>">
                  <h3 class="kanban-card__title"><span><?= $thisTicket['title']; ?></span></h3>
                  <div class="badge badge--category"data-badge="priority:<?= $thisTicket['priority_name']; ?>" data-badge-color="<?= $thisTicket['priority_color']; ?>">
                    <?= $thisTicket['priority_name']; ?>
                  </div>
                  <div class="kanban-card__meta">
                    <span class="kanban-card__author">
                      Oprettet af: <?= $thisTicket['createdBy_name']; ?>
                    </span>
                    <?php if($thisTicket['status_name'] === 'Ikke-startet') { ?>
                    <span class="kanban-card__author">
                      Oprettet: <?= date_format(new DateTime($thisTicket['created_at']), 'd-m-y'); ?>
                    </span>
                    <?php } else if($thisTicket['status_name'] === 'Åben') { ?>
                      <span class="kanban-card__author">
                        <?php if (empty($thisTicket['assignedTo_name'])) {
                            echo "Endnu ikke tildelt";
                          } else {
                            echo "Tildelt: {$thisTicket['assignedTo_name']}";
                          } ?>
                      </span>
                    <?php } ?>
                  </div>
                </article>
              <?php 
              } ?>
            </div>
          </section>
         
        <?php 
          $i++;
        } ?>


      <?php
        $column++;
      } ?>



    </main>
  </div>
  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script src="js/sagstavle.js"></script>
</body>

</html>