import { addTicket } from '../api/add_ticket.js';
import { deleteTicket } from '../api/delete_ticket.js';
import { updateTicket } from '../api/update_ticket.js';

function initSagerFilters() {
  const searchInput = document.getElementById('sager-search');
  const statusSelect = document.getElementById('filter-status');
  const resetFilterBtn = document.querySelector('.filter-bar__reset');
  const tbody = document.getElementById('sager-table-body');
  if (!tbody) return;

  function filterSager() {
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const status = statusSelect ? statusSelect.value : '';

    const rows = tbody.querySelectorAll('.data-table__row');
    rows.forEach((row) => {
      const id = (row.dataset.ticketId || '').toLowerCase();
      const title = (row.dataset.title || '').toLowerCase();
      const location = (row.dataset.location || '').toLowerCase();
      const assigned = (row.dataset.assigned || '').toLowerCase();
      const rowStatus = row.dataset.status || '';

      const matchesSearch = !query || id.includes(query) || title.includes(query) || location.includes(query) || assigned.includes(query);
      const matchesStatus = !status || rowStatus === status;

      row.style.display = matchesSearch && matchesStatus ? '' : 'none';
    });
  }

  if (searchInput) searchInput.addEventListener('input', filterSager);
  if (statusSelect) statusSelect.addEventListener('change', () => filterDataTable('status', statusSelect.value));
    if (resetFilterBtn) resetFilterBtn.addEventListener('click', () => {
    filterDataTable('status', '');
    if (statusSelect) statusSelect.value = "";
  });
}

function openSagerEditModal(ticketId) {
  const modal = document.getElementById('sager-edit-modal');
  if (!modal) return;

  const idInput = document.getElementById('sager-modal-id');
  const titleInput = document.getElementById('sager-modal-title');
  const typeSelect = document.getElementById('sager-modal-type');
  const descriptionInput = document.getElementById('sager-modal-description');
  const locationInput = document.getElementById('sager-modal-location');
  const statusSelect = document.getElementById('sager-modal-status');
  const prioritySelect = document.getElementById('sager-modal-priority');
  const assignedSelect = document.getElementById('sager-modal-assigned');
  const createdDateInput = document.getElementById('sager-modal-created-date');

  const row = document.querySelector(`tr[data-ticket-id="${ticketId}"]`);
  if (row) {
    if (idInput) idInput.value = `#${ticketId}`;
    if (titleInput) titleInput.value = row.dataset.title || '';
    if (typeSelect) typeSelect.value = row.dataset.type || '';
    if (descriptionInput) descriptionInput.value = row.dataset.description || '';
    if (locationInput) locationInput.value = row.dataset.location || '';
    if (statusSelect) statusSelect.value = row.dataset.status || '';
    if (prioritySelect) prioritySelect.value = row.dataset.priority || '';
    if (assignedSelect) assignedSelect.value = row.dataset.assigned || '';
    if (createdDateInput && row.dataset.createdDate) {
      // Udvælg dato-delen (fjern tiden hvis den er der)
      const datePart = row.dataset.createdDate.split(' ')[0]; // Giver '2026-07-28'
      
      // Del op i år, måned og dag, og vend dem om til '28-07-2026'
      const parts = datePart.split('-');
      if (parts.length === 3) {
        createdDateInput.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
      } else {
        createdDateInput.value = datePart;
      }
    } else if (createdDateInput) {
      createdDateInput.value = '';
    }
  
  }

  modal.setAttribute('data-current-ticket-id', ticketId);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeSagerEditModal() {
  const modal = document.getElementById('sager-edit-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-current-ticket-id');
  document.body.classList.remove('modal-open');
}

function initSagerEditModal() {
  const closeBtn = document.getElementById('sager-modal-close');
  const cancelBtn = document.getElementById('sager-modal-cancel');
  const modal = document.getElementById('sager-edit-modal');
  const saveBtn = modal.querySelector('.modal__footer .btn--primary');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeSagerEditModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeSagerEditModal);
  if (saveBtn) saveBtn.addEventListener('click', handleUpdateSagSubmit);

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeSagerEditModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeSagerEditModal();
    }
  });
}

async function handleUpdateSagSubmit() {

  const id = document.getElementById('sager-modal-id');
  const title = document.getElementById('sager-modal-title');
  const type = document.getElementById('sager-modal-type');
  const description = document.getElementById('sager-modal-description');
  const place = document.getElementById('sager-modal-location');
  const status = document.getElementById('sager-modal-status');
  const priority = document.getElementById('sager-modal-priority');
  const assigned = document.getElementById('sager-modal-assigned');
  
  if (title && !title.value.trim()) {
    showBottomMessage('Titel er et påkrævet felt.', 'warning');
    name.focus();
    return;
  }

  if (type && !type.value.trim()) {
    showBottomMessage('Type er et påkrævet felt.', 'warning');
    type.focus();
    return;
  }

  const result = await updateTicket(id.value, title.value, type.value, description.value, place.value, status.value, priority.value, assigned.value)
    .then(result => {
      if (result === true) {
          closeSagerEditModal();
          showBottomMessage(`Sag ${title.value} opdateret`, 'success');
          setTimeout(() => {
            location.reload();
          }, 2000);
      }
      else
      {
        showBottomMessage('Der opstod en fejl ved ændring af sagen i databasen.' + result.error, 'error');
      }
    })
    .catch(error => {
      showBottomMessage('Der opstod en uventet fejl: ' + error, 'error');
    });
}

function openSagerDeleteModal(row) {
  const modal = document.getElementById('sager-delete-modal');
  const message = document.getElementById('sager-delete-modal-message');
  if (!modal) return;

  const title = row.dataset.title || 'denne sag';

  if (message) message.textContent = `Er du sikker på, at du vil slette "${title}"? Denne handling kan ikke fortrydes.`;

  modal.setAttribute('data-delete-ticket-id', row.dataset.ticketId);
  modal.setAttribute('data-delete-ticket-title', title);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeSagerDeleteModal() {
  const modal = document.getElementById('sager-delete-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-delete-ticket-id');
  document.body.classList.remove('modal-open');
}

async function confirmSagerDelete() {
  const modal = document.getElementById('sager-delete-modal');
  if (!modal) return;

  const ticketId = modal.getAttribute('data-delete-ticket-id');
  const title = modal.getAttribute('data-delete-ticket-title');

  const result = await deleteTicket(ticketId)
        .then(result => {
          if (result === true) {
              closeSagerDeleteModal();
              showBottomMessage(`${title} er blevet slettet.`, 'success');
              
              setTimeout(() => {
                location.reload();
              }, 2000);
          }
          else
          {
            showBottomMessage('Der opstod en fejl ved sletning af sagen i databasen: ' + result.error, 'error');
          }
        })
        .catch(error => {
          showBottomMessage('Der opstod en uventet fejl: ' + error, 'error');
        });
    closeSagerDeleteModal();

  closeSagerDeleteModal();
}

function initSagerDeleteModal() {
  const closeBtn = document.getElementById('sager-delete-modal-close');
  const cancelBtn = document.getElementById('sager-delete-modal-cancel');
  const confirmBtn = document.getElementById('sager-delete-modal-confirm');
  const modal = document.getElementById('sager-delete-modal');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeSagerDeleteModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeSagerDeleteModal);
  if (confirmBtn) confirmBtn.addEventListener('click', confirmSagerDelete);

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeSagerDeleteModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeSagerDeleteModal();
    }
  });
}

function openCreateSagModal() {
  const modal = document.getElementById('sager-create-modal');
  if (!modal) return;

  const titleInput = document.getElementById('sager-create-title');
  const typeSelect = document.getElementById('sager-create-type');
  const descriptionInput = document.getElementById('sager-create-description');
  const locationInput = document.getElementById('sager-create-location');
  const statusSelect = document.getElementById('sager-create-status');
  const prioritySelect = document.getElementById('sager-create-priority');
  const assignedSelect = document.getElementById('sager-create-assigned');
  const dateInput = document.getElementById('sager-create-date');

  if (titleInput) titleInput.value = '';
  if (typeSelect) typeSelect.value = '';
  if (descriptionInput) descriptionInput.value = '';
  if (locationInput) locationInput.value = '';
  if (statusSelect) statusSelect.value = 'Ikke-startet';
  if (prioritySelect) prioritySelect.value = 'Medium';
  if (assignedSelect) assignedSelect.value = '';
  if (dateInput) {
    const [year, month, day] = new Date().toISOString().split('T')[0].split('-');
    const today = `${day}-${month}-${year}`;
    dateInput.value = today;
  }

  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeCreateSagModal() {
  const modal = document.getElementById('sager-create-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  document.body.classList.remove('modal-open');
}

async function handleCreateSagSubmit() {
  const title = document.getElementById('sager-create-title');
  const type = document.getElementById('sager-create-type');

  if (title && !title.value.trim()) {
    showBottomMessage('Titel er et påkrævet felt.', 'warning');
    title.focus();
    return;
  }

  if (type && !type.value.trim()) {
    showBottomMessage('Type er et påkrævet felt.', 'warning');
    type.focus();
    return;
  }

  const description = document.getElementById('sager-create-description');
  const place = document.getElementById('sager-create-location');
  const statusInput = document.getElementById('sager-create-status');
  // Sikrer at der står noget brugbart i status også selvom brugeren ikke har valgt noget
  const status = statusInput ? (statusInput.value || 'Ikke-startet') : 'Ikke-startet';
  
  const priorityInput = document.getElementById('sager-create-priority');
  // Sikrer at der står noget brugbart i priority også selvom brugeren ikke har valgt noget
  const priority = priorityInput ? (priorityInput.value || 'Medium') : 'Medium';
  
  const assigned = document.getElementById('sager-create-assigned');
  const created = document.getElementById('sager-create-created');

  console.log(created.value);

   // Brug af den importerede addTicket funktion
    const result = await addTicket(title.value, description.value, place.value, type.value, priority, created.value, assigned.value, status)
      .then(result => {
        if (result === true) {
          console.log("1. Kom ind i if-sætning");
            closeCreateSagModal();
            showBottomMessage(`Sag ${title.value} oprettet`, 'success');
            console.log("2. sætter timer til genindlæsning");
            setTimeout(() => {
              console.log("3. genindlæser nu");
              location.reload();
            }, 2000);
        } else {
          showBottomMessage('Der opstod en fejl ved oprettelse af sagen.' + result.error, 'error');
        }
      })
      .catch(error => {
        showBottomMessage('Der opstod en uventet fejl: ' + error, 'error');
      });
}

function initCreateSagModal() {
  const closeBtn = document.getElementById('sager-create-modal-close');
  const cancelBtn = document.getElementById('sager-create-modal-cancel');
  const modal = document.getElementById('sager-create-modal');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeCreateSagModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeCreateSagModal);

  const saveBtn = document.getElementById('sager-create-modal-save');
  if (saveBtn) saveBtn.addEventListener('click', handleCreateSagSubmit);

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeCreateSagModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeCreateSagModal();
    }
  });
}

function initSagerActions() {
  const tbody = document.getElementById('sager-table-body');
  if (!tbody) return;

  tbody.addEventListener('click', (event) => {
    const row = event.target.closest('.data-table__row');
    if (!row) return;
    
    const btn = event.target.closest('.action-btn');

    if (btn)
    {
      if (btn.classList.contains('action-btn--danger')) {
      openSagerDeleteModal(row);
      } else {
        openSagerEditModal(row.dataset.ticketId);
      }
    }
    else
    {
      openSagerEditModal(row.dataset.ticketId);
    }
    
  });
}

function initAddSagButton() {
  const addBtn = document.getElementById('add-sag-btn');
  if (!addBtn) return;
  addBtn.addEventListener('click', openCreateSagModal);
}

(function init() {
  initSagerFilters();
  initSagerActions();
  initSagerEditModal();
  initSagerDeleteModal();
  initCreateSagModal();
  initAddSagButton();
})();
