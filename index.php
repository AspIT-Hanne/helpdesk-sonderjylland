<?php 
  include_once __DIR__ . '/includes/phpheader.php'; 

  if (!isset($_SESSION['logged_in'])) {
      header('Location: login.php');
      exit;
  }
  else
  {
    if($_SESSION['userRole_id'] > 0 && $_SESSION['userRole_id'] < 4)
    {
      $role = $_SESSION['userRole_id'];
    }
    else
    {
      $role = 4;
    }

    include "api/get_tickets.php";

    $data = getMyTicketData(); 
    
    $technicians = getTechnicians();
    
    $status = getStatus();

    $categories = getCategory();

    $priorities = getPriority();
  }

  function getActiveTickets($data) {
    $active = array_filter($data, fn($ticket) => isset($ticket['ticketStatus_id']) && $ticket['ticketStatus_id'] != 4);
    return count($active);
  }

  function countHighPriorityTickets($data) {
    $priorityOne = array_filter($data, fn($ticket) => isset($ticket['ticketPriority_id']) && $ticket['ticketPriority_id'] == 1);
    return count($priorityOne);
  }

  function countTicketsByStatusTwo($data) {
    $statusTwo = array_filter($data, fn($ticket) => isset($ticket['ticketStatus_id']) && $ticket['ticketStatus_id'] == 2);
    return count($statusTwo);
  }

  function countRecentResolvedTickets($data) {
    $oneMonthAgo = strtotime('-1 month');
    
    $recentResolved = array_filter($data, fn($ticket) => 
        isset($ticket['ticketStatus_id'], $ticket['created_at']) && 
        $ticket['ticketStatus_id'] == 4 && 
        strtotime($ticket['created_at']) >= $oneMonthAgo
    );
    
    return count($recentResolved);
  }

  
  ?>

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
      <p class="page-header__subtitle">Velkommen, <?= $_SESSION['username']; ?>! Her er en oversigt over dine support-sager.</p>
    </header>

    <section class="stats-grid" aria-label="Statistik">
      <article class="stat-card" id="stat-open">
        <span class="stat-card__label">Åbne</span>
        <span class="stat-card__value"><?= getActiveTickets($data); ?></span>
        <span class="stat-card__sublabel"><strong class="stat-card__urgent-count"><?= countHighPriorityTickets($data); ?></strong> hastende</span>
      </article>
      <article class="stat-card" id="stat-pending">
        <span class="stat-card__label">Afventer</span>
        <span class="stat-card__value"><?= countTicketsByStatusTwo($data); ?></span>
        <span class="stat-card__sublabel">Venter på svar</span>
      </article>
      <article class="stat-card" id="stat-resolved">
        <span class="stat-card__label">Løst</span>
        <span class="stat-card__value"><?= countRecentResolvedTickets($data); ?></span>
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
            <?php 
              foreach($status as $thisstatus)
                {
                  echo "<option value='{$thisstatus['name']}'>{$thisstatus['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="filter-bar__group">
          <label for="filter-assigned" class="filter-bar__label">Tildelt</label>
          <select id="filter-assigned" class="form-field filter-bar__select">
            <option value="">Alle</option>
            <?php 
              foreach($technicians as $technician)
                {
                  echo "<option value='{$technician['username']}'>{$technician['username']}</option>";
                }
            ?>
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
              <th scope="col" class="data-table__header">Oprettet af</th>
              <th scope="col" class="data-table__header">Tildelt</th>
              <th scope="col" class="data-table__header">Oprettet d.</th>
            </tr>
          </thead>
          <tbody id="ticket-table-body">
          <?php
            foreach($data as $ticket)
            {
              if($ticket['ticketStatus_id'] != 4)
              {
                  echo "<tr class='data-table__row' data-ticket-id='{$ticket['id']}' data-description='' data-title='{$ticket['title']}' data-type='{$ticket['category_name']}' data-location='{$ticket['location']}' data-status='{$ticket['status_name']}' data-priority='{$ticket['priority_name']}' data-created-by='{$ticket['createdBy_name']}' data-assigned='{$ticket['assignedTo_name']}' data-created-date='{$ticket['created_at']}' role='button' tabindex='0'>";
                    echo "<td class='data-table__cell'>#{$ticket['id']}</td>";
                    echo "<td class='data-table__cell'>{$ticket['title']}</td>";
                    echo "<td class='data-table__cell'>{$ticket['location']}</td>";
                    echo "<td class='data-table__cell'><span class='badge' data-badge='status:{$ticket['status_name']}' data-badge-color='{$ticket['status_color']}'>{$ticket['status_name']}</span></td>";
                    echo "<td class='data-table__cell'><span class='priority' data-badge='priority:{$ticket['priority_name']}' data-badge-color='{$ticket['priority_color']}'>{$ticket['priority_name']}</span></td>";
                    echo "<td class='data-table__cell'>{$ticket['createdBy_name']}</td>";
                    echo "<td class='data-table__cell'>{$ticket['assignedTo_name']}</td>";
                    echo "<td class='data-table__cell'>" . date_format(new DateTime($ticket['created_at']), "d-m-y") . "</td>";
                echo "</tr>";
              }
            }
            ?>
           
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
          <select id="modal-type" class="form-field" <?= $permissions[$role]['restricted']; ?>>
          <?php 
              foreach($categories as $category)
                {
                  echo "<option value='{$category['name']}'>{$category['name']}</option>";
                }
            ?>
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
          <select id="modal-status" class="form-field" <?= $permissions[$role]['restricted']; ?>>
            <?php 
              foreach($status as $thisstatus)
                {
                  echo "<option value='{$thisstatus['name']}'>{$thisstatus['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-priority" class="form-group__label">Prioritet</label>
          <select id="modal-priority" class="form-field">
            <?php 
              foreach($priorities as $priority)
                {
                  echo "<option value='{$priority['name']}'>{$priority['name']}</option>";
                }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="modal-created-date" class="form-group__label">Oprettet af</label>
          <input type="text" id="modal-created-by" class="form-field" disabled>
        </div>
        <div class="form-group">
          <label for="modal-assigned" class="form-group__label">Tildelt Medarbejder</label>
          <select id="modal-assigned" class="form-field" <?= $permissions[$role]['restricted']; ?>>
            <option value=''>Vælg tekniker</option>
            <?php 
              foreach($technicians as $technician)
                {
                  echo "<option value='{$technician['username']}'>{$technician['username']}</option>";
                }
            ?>
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
  <script src="js/index.js" type="module"></script>
</body>
</html>
