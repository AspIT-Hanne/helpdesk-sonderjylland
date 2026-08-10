import { updateTicket } from '../api/update_ticket.js';

function openModal(ticketId) {
  const modal = document.getElementById('edit-modal');
  const idDisplay = document.getElementById('modal-id');
  const titleInput = document.getElementById('modal-title');
  const typeSelect = document.getElementById('modal-type');
  const descriptionInput = document.getElementById('modal-description');
  const locationInput = document.getElementById('modal-location');
  const statusSelect = document.getElementById('modal-status');
  const prioritySelect = document.getElementById('modal-priority');
  const createdBySelect = document.getElementById('modal-created-by');
  const assignedSelect = document.getElementById('modal-assigned');
  const createdDateDisplay = document.getElementById('modal-created-date');

  const row = document.querySelector(`tr[data-ticket-id="${ticketId}"]`);


  if (row) {
    if (idDisplay) idDisplay.value = `#${ticketId}`;
    if (titleInput) titleInput.value = row.dataset.title || '';
    if (typeSelect) typeSelect.value = row.dataset.type || '';
    if (descriptionInput) descriptionInput.value = row.dataset.description || '';
    if (locationInput) locationInput.value = row.dataset.location || '';
    if (statusSelect) statusSelect.value = row.dataset.status || '';
    if (prioritySelect) prioritySelect.value = row.dataset.priority || '';
    if (createdBySelect) createdBySelect.value = row.dataset.createdBy || '';
    if (assignedSelect) assignedSelect.value = row.dataset.assigned || '';
    if (createdDateDisplay && row.dataset.createdDate) {
      // Udvælg dato-delen (fjern tiden hvis den er der)
      const datePart = row.dataset.createdDate.split(' ')[0]; // Giver '2026-07-28'
      
      // Del op i år, måned og dag, og vend dem om til '28-07-2026'
      const parts = datePart.split('-');
      if (parts.length === 3) {
        createdDateDisplay.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
      } else {
        createdDateDisplay.value = datePart;
      }
    } else if (createdDateDisplay) {
      createdDateDisplay.value = '';
    }
  }

  modal.setAttribute('data-current-ticket-id', ticketId);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeModal() {
  const modal = document.getElementById('edit-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-current-ticket-id');
  document.body.classList.remove('modal-open');
}

function initTableRows() {
  const filterStatus = document.getElementById('filter-status');
  const filterAssigned = document.getElementById('filter-assigned');
  const resetFilterBtn = document.querySelector('.filter-bar__reset');

  if (filterStatus) filterStatus.addEventListener('change', () => filterDataTable('status', filterStatus.value));
  if (filterAssigned) filterAssigned.addEventListener('change', () => filterDataTable('tildelt', filterAssigned.value));
  if (resetFilterBtn) resetFilterBtn.addEventListener('click', () => {
    filterDataTable('status', '');
    filterDataTable('tildelt', '');
    if (filterStatus) filterStatus.value = "";
    if (filterAssigned) filterAssigned.value = "";
  });
  
  const tbody = document.getElementById('ticket-table-body');
  if (!tbody) return;

  tbody.addEventListener('click', (event) => {
    const row = event.target.closest('tr');
    if (!row) return;
    const id = row.getAttribute('data-ticket-id');
    openModal(id);
  });

  tbody.addEventListener('keydown', (event) => {
    const row = event.target.closest('tr');
    if (!row) return;
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      const id = row.getAttribute('data-ticket-id');
      openModal(id);
    }
  });
}

function initModal() {
  const closeBtn = document.getElementById('modal-close');
  const cancelBtn = document.getElementById('modal-cancel');
  const modal = document.getElementById('edit-modal');
  const saveBtn = modal.querySelector('.modal__footer .btn--primary');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
  if (saveBtn) saveBtn.addEventListener('click', handleUpdateSagSubmit);

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeModal();
    }
  });
}

async function handleUpdateSagSubmit() {

  const id = document.getElementById('modal-id');
  const title = document.getElementById('modal-title');
  const type = document.getElementById('modal-type');
  const description = document.getElementById('modal-description');
  const place = document.getElementById('modal-location');
  const status = document.getElementById('modal-status');
  const priority = document.getElementById('modal-priority');
  const assigned = document.getElementById('modal-assigned');
  
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
          closeModal();
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

function updateDashboardStats() {
  const openCard = document.getElementById('stat-open');
  if (openCard) {
    const urgentCount = parseInt(openCard.querySelector('.stat-card__urgent-count').textContent, 10) || 0;
    openCard.classList.toggle('stat-card--focus', urgentCount > 0);
  }

  const pendingCard = document.getElementById('stat-pending');
  if (pendingCard) {
    const pendingCount = parseInt(pendingCard.querySelector('.stat-card__value').textContent, 10) || 0;
    pendingCard.classList.toggle('stat-card--focus', pendingCount > 0);
  }

  const resolvedCard = document.getElementById('stat-resolved');
  if (resolvedCard) {
    // TODO: Add recent count check when backend is connected.
    // For now, resolved card has no conditional colouring.
  }
}

(function init() {
  initTableRows();
  initModal();
  updateDashboardStats();
})();
