function initSagerFilters() {
  const searchInput = document.getElementById('sager-search');
  const statusSelect = document.getElementById('filter-status');
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
  if (statusSelect) statusSelect.addEventListener('change', filterSager);
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
    if (createdDateInput) createdDateInput.value = row.dataset.createdDate || '';
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

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeSagerEditModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeSagerEditModal);

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

function openSagerDeleteModal(row) {
  const modal = document.getElementById('sager-delete-modal');
  const message = document.getElementById('sager-delete-modal-message');
  if (!modal) return;

  const title = row.dataset.title || 'denne sag';

  if (message) message.textContent = `Er du sikker på, at du vil slette "${title}"? Denne handling kan ikke fortrydes.`;

  modal.setAttribute('data-delete-ticket-id', row.dataset.ticketId);
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

function confirmSagerDelete() {
  const modal = document.getElementById('sager-delete-modal');
  if (!modal) return;

  const ticketId = modal.getAttribute('data-delete-ticket-id');
  const row = document.querySelector(`tr[data-ticket-id="${ticketId}"]`);
  if (row) row.remove();

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
  if (statusSelect) statusSelect.value = 'ikke-startet';
  if (prioritySelect) prioritySelect.value = 'medium';
  if (assignedSelect) assignedSelect.value = '';
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
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
  const location = document.getElementById('sager-create-location');
  const status = document.getElementById('sager-create-status');
  const priority = document.getElementById('sager-create-priority');
  const assigned = document.getElementById('sager-create-assigned');
  const created = document.getElementById('sager-create-created');

   // Brug af den importerede addUser funktion
    const result = await addTicket(title.value, description.value, location.value, type.value, priority.value, assigned.value, status.value)
      .then(result => {
        if (result === true) {
            closeCreateSagModal();
            showBottomMessage(`Sag ${title.value} oprettet`, 'success');
            setTimeout(() => {
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
    const btn = event.target.closest('.action-btn');
    if (!btn) return;

    const row = btn.closest('.data-table__row');
    if (!row) return;

    if (btn.classList.contains('action-btn--danger')) {
      openSagerDeleteModal(row);
    } else {
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
