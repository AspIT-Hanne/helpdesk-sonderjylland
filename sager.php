<?php 
  include "api/get_tickets.php";

  $data = getTicketData(); 
  
  $status = getStatus();
  
  ?>

<!DOCTYPE html>
<html lang="da">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sager | IT Support</title>
  <link rel="stylesheet" href="css/shared.css">
  <link rel="stylesheet" href="css/sager.css">
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
      <h1 class="page-header__title">Sagsstyring</h1>
      <p class="page-header__subtitle">Se, filtrér og administrér alle support-sager.</p>
    </header>

    <div class="filter-bar">
      <div class="filter-bar__group">
        <label for="sager-search" class="filter-bar__label visually-hidden">Søg sager</label>
        <input type="search" id="sager-search" class="form-field" placeholder="Søg sager efter ID, titel eller tildelt...">
      </div>
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
      <button class="btn btn--primary" id="add-sag-btn">Tilføj Sag</button>
    </div>

    <section class="card">
      <div class="table-container">
        <table class="data-table" aria-label="Sager">
          <thead class="data-table__head">
            <tr class="data-table__row">
              <th scope="col" class="data-table__header">ID</th>
              <th scope="col" class="data-table__header">Titel</th>
              <th scope="col" class="data-table__header">Type</th>
              <th scope="col" class="data-table__header">Lokation</th>
              <th scope="col" class="data-table__header">Status</th>
              <th scope="col" class="data-table__header">Prioritet</th>
              <th scope="col" class="data-table__header">Tildelt</th>
              <th scope="col" class="data-table__header">Dato</th>
              <th scope="col" class="data-table__header">Handlinger</th>
            </tr>
          </thead>
          <tbody id="sager-table-body">
            <?php  
            foreach($data as $ticket)
            {
              echo "<tr class='data-table__row' data-ticket-id='{$ticket['id']}' data-description='' data-title='{$ticket['title']}' data-type='{$ticket['category_name']}' data-location='{$ticket['location']}' data-status='{$ticket['status_name']}' data-priority='{$ticket['priority_name']}' data-assigned='{$ticket['assignedTo_name']}' data-created-date='{$ticket['created_at']}'>";
              echo "<td class='data-table__cell'>#{$ticket['id']}</td>";
              echo "<td class='data-table__cell'>{$ticket['title']}</td>";
              echo "<td class='data-table__cell'><span class='badge' data-badge='type:{$ticket['category_name']}'>{$ticket['category_name']}</span></td>";
              echo "<td class='data-table__cell'>{$ticket['location']}</td>";
              echo "<td class='data-table__cell'><span class='badge' data-badge='status:{$ticket['status_name']}'>{$ticket['status_name']}</span></td>";
              echo "<td class='data-table__cell'><span class='priority' data-badge='priority:{$ticket['priority_name']}'>{$ticket['priority_name']}</span></td>";
              echo "<td class='data-table__cell'>{$ticket['assignedTo_name']}</td>";
              echo "<td class='data-table__cell'>" . date_format(new DateTime($ticket['created_at']), "d-m-y") . "</td>";
              echo "<td class='data-table__cell'>
                      <button type='button' class='action-btn' aria-label='Redigér sag {$ticket['id']}'>
                        <img src='assets/pencil.svg' alt='' class='action-btn__icon'>
                      </button>
                      <button type='button' class='action-btn action-btn--danger' aria-label='Slet sag {$ticket['id']}'>
                        <img src='assets/trash.svg' alt='' class='action-btn__icon'>
                      </button>
                    </td>
                </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <div class="modal" id="sager-edit-modal" role="dialog" aria-modal="true" aria-labelledby="sager-modal-title-heading">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="sager-modal-title-heading">Redigér Sag</h2>
        <button class="modal__close" id="sager-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="sager-modal-id" class="form-group__label">Sags-ID</label>
          <input type="text" id="sager-modal-id" class="form-field" disabled>
        </div>
        <div class="form-group">
          <label for="sager-modal-title" class="form-group__label">Titel</label>
          <input type="text" id="sager-modal-title" class="form-field">
        </div>
        <div class="form-group">
          <label for="sager-modal-type" class="form-group__label">Type</label>
          <select id="sager-modal-type" class="form-field">
            <option value="t1">T1</option>
            <option value="t2">T2</option>
            <option value="t3">T3</option>
            <option value="t4">T4</option>
            <option value="other">Andet</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-modal-description" class="form-group__label">Beskrivelse</label>
          <textarea id="sager-modal-description" class="form-field" rows="4" placeholder="Beskrivelse af sagen..."></textarea>
        </div>
        <div class="form-group">
          <label for="sager-modal-location" class="form-group__label">Lokation</label>
          <input type="text" id="sager-modal-location" class="form-field">
        </div>
        <div class="form-group">
          <label for="sager-modal-status" class="form-group__label">Status</label>
          <select id="sager-modal-status" class="form-field">
            <option value="not-started">Ikke Startet</option>
            <option value="open">Åben</option>
            <option value="pending">Afventer</option>
            <option value="resolved">Løst</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-modal-priority" class="form-group__label">Prioritet</label>
          <select id="sager-modal-priority" class="form-field">
            <option value="high">Høj</option>
            <option value="medium">Mellem</option>
            <option value="low">Lav</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-modal-assigned" class="form-group__label">Tildelt Medarbejder</label>
          <select id="sager-modal-assigned" class="form-field">
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
          <label for="sager-modal-created-date" class="form-group__label">Oprettelsesdato</label>
          <input type="text" id="sager-modal-created-date" class="form-field" disabled>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="sager-modal-cancel">Annullér</button>
        <button class="btn btn--primary">Gem ændringer</button>
      </footer>
    </div>
  </div>

  <div class="modal modal--danger" id="sager-delete-modal" role="alertdialog" aria-modal="true" aria-labelledby="sager-delete-modal-title">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="sager-delete-modal-title">Slet Sag</h2>
        <button class="modal__close" id="sager-delete-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <p id="sager-delete-modal-message">Er du sikker på, at du vil slette denne sag? Denne handling kan ikke fortrydes.</p>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="sager-delete-modal-cancel">Annullér</button>
        <button class="btn btn--danger" id="sager-delete-modal-confirm">Slet Sag</button>
      </footer>
    </div>
  </div>

  <div class="modal" id="sager-create-modal" role="dialog" aria-modal="true" aria-labelledby="sager-create-modal-title-heading">
    <div class="modal__container">
      <header class="modal__header">
        <h2 class="modal__title" id="sager-create-modal-title-heading">Opret Ny Sag</h2>
        <button class="modal__close" id="sager-create-modal-close" aria-label="Luk">&times;</button>
      </header>
      <div class="modal__body">
        <div class="form-group">
          <label for="sager-create-title" class="form-group__label">Titel</label>
          <input type="text" id="sager-create-title" class="form-field" placeholder="Kort beskrivelse af problemet..." required>
        </div>
        <div class="form-group">
          <label for="sager-create-type" class="form-group__label">Type</label>
          <select id="sager-create-type" class="form-field" required>
            <option value="">Vælg en kategori</option>
            <option value="t1">T1</option>
            <option value="t2">T2</option>
            <option value="t3">T3</option>
            <option value="t4">T4</option>
            <option value="other">Andet</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-create-description" class="form-group__label">Beskrivelse</label>
          <textarea id="sager-create-description" class="form-field" rows="4" placeholder="Giv detaljeret information om problemet..."></textarea>
        </div>
        <div class="form-group">
          <label for="sager-create-location" class="form-group__label">Lokation</label>
          <input type="text" id="sager-create-location" class="form-field" placeholder="Hvor er problemet?">
        </div>
        <div class="form-group">
          <label for="sager-create-status" class="form-group__label">Status</label>
          <select id="sager-create-status" class="form-field">
            <option value="not-started" selected>Ikke Startet</option>
            <option value="open">Åben</option>
            <option value="pending">Afventer</option>
            <option value="resolved">Løst</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-create-priority" class="form-group__label">Prioritet</label>
          <select id="sager-create-priority" class="form-field">
            <option value="high">Høj</option>
            <option value="medium" selected>Mellem</option>
            <option value="low">Lav</option>
          </select>
        </div>
        <div class="form-group">
          <label for="sager-create-assigned" class="form-group__label">Tildelt Medarbejder</label>
          <select id="sager-create-assigned" class="form-field">
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
          <input type="text" id="sager-create-created" class="form-field" hidden disabled>
        </div>
        <div class="form-group">
          <label for="sager-create-date" class="form-group__label">Oprettelsesdato</label>
          <input type="text" id="sager-create-date" class="form-field" disabled>
        </div>
      </div>
      <footer class="modal__footer">
        <button class="btn btn--secondary" id="sager-create-modal-cancel">Annullér</button>
        <button class="btn btn--primary" id="sager-create-modal-save">Opret Sag</button>
      </footer>
    </div>
  </div>

  <script src="js/shared.js"></script>
  <script src="js/badges.js"></script>
  <script src="js/sager.js" type="module"></script>
</body>
</html>
