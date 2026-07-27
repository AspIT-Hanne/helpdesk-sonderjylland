import { addUser } from '../api/add_user.js';
import { updateUser } from '../api/update_user.js';

function initUserFilters() {
  const searchInput = document.getElementById('user-search');
  const roleSelect = document.getElementById('filter-role');
  const tbody = document.getElementById('user-table-body');
  if (!tbody) return;

  function filterUsers() {
    const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const role = roleSelect ? roleSelect.value : '';

    const rows = tbody.querySelectorAll('.data-table__row');
    rows.forEach((row) => {
      const cells = row.querySelectorAll('.data-table__cell');
      const name = cells[0] ? cells[0].textContent.toLowerCase() : '';
      const email = cells[1] ? cells[1].textContent.toLowerCase() : '';
      const rowRole = cells[2] ? cells[2].textContent : '';

      const matchesSearch = !query || name.includes(query) || email.includes(query);
      const matchesRole = !role || rowRole === role;

      row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
  }

  if (searchInput) searchInput.addEventListener('input', filterUsers);
  if (roleSelect) roleSelect.addEventListener('change', filterUsers);
}

function initUserActions() {
  const tbody = document.getElementById('user-table-body');
  if (!tbody) return;

  tbody.addEventListener('click', (event) => {
    const btn = event.target.closest('.action-btn');
    if (!btn) return;

    const row = btn.closest('.data-table__row');
    if (!row) return;

    if (btn.classList.contains('action-btn--danger')) {
      openDeleteUserModal(row);
    } else {
      openUserModal(row.dataset.userId);
    }
  });
}

function openDeleteUserModal(row) {
  const modal = document.getElementById('user-delete-modal');
  const message = document.getElementById('user-delete-modal-message');
  if (!modal) return;

  const nameCell = row.querySelector('.data-table__cell');
  const name = nameCell ? nameCell.textContent : 'denne bruger';

  if (message) message.textContent = `Er du sikker på, at du vil slette ${name}? Denne handling kan ikke fortrydes.`;

  modal.setAttribute('data-delete-user-id', row.dataset.userId);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeDeleteUserModal() {
  const modal = document.getElementById('user-delete-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-delete-user-id');
  document.body.classList.remove('modal-open');
}

function confirmDeleteUser() {
  const modal = document.getElementById('user-delete-modal');
  if (!modal) return;

  const userId = modal.getAttribute('data-delete-user-id');
  const row = document.querySelector(`tr[data-user-id="${userId}"]`);
  if (row) row.remove();

  closeDeleteUserModal();
}

function initDeleteUserModal() {
  const closeBtn = document.getElementById('user-delete-modal-close');
  const cancelBtn = document.getElementById('user-delete-modal-cancel');
  const confirmBtn = document.getElementById('user-delete-modal-confirm');
  const modal = document.getElementById('user-delete-modal');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeDeleteUserModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeDeleteUserModal);
  if (confirmBtn) confirmBtn.addEventListener('click', confirmDeleteUser);

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeDeleteUserModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeDeleteUserModal();
    }
  });
}

function openUserModal(userId) {
  const modal = document.getElementById('user-edit-modal');
  if (!modal) return;

  const idInput = document.getElementById('user-modal-id');
  const nameInput = document.getElementById('user-modal-name');
  const emailInput = document.getElementById('user-modal-email');
  const passwordInput = document.getElementById('user-modal-password');
  const roleSelect = document.getElementById('user-modal-role');
  const statusInput = document.getElementById('user-modal-status');
  const statusLabel = modal.querySelector('.toggle__label-text');

  const row = document.querySelector(`tr[data-user-id="${userId}"]`);
  if (row) {
    if (idInput) idInput.value = `#${userId}`;
    if (nameInput) nameInput.value = row.dataset.name || '';
    if (emailInput) emailInput.value = row.dataset.email || '';
    if (passwordInput) passwordInput.value = '';
    if (roleSelect) roleSelect.value = row.dataset.role || '';
    if (statusInput) statusInput.checked = row.dataset.status === 'Aktiv';
    if (statusLabel) statusLabel.textContent = row.dataset.status === 'Aktiv' ? 'Aktiv' : 'Inaktiv';
  }

  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');

  const saveBtn = modal.querySelector('.modal__footer .btn--primary');
  if (saveBtn) saveBtn.addEventListener('click', handleUpdateUserSubmit);
}

function closeUserModal() {
  const modal = document.getElementById('user-edit-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  document.body.classList.remove('modal-open');
}

function initUserModal() {
  const closeBtn = document.getElementById('user-modal-close');
  const cancelBtn = document.getElementById('user-modal-cancel');
  const modal = document.getElementById('user-edit-modal');
  const statusInput = document.getElementById('user-modal-status');
  const statusLabel = modal ? modal.querySelector('.toggle__label-text') : null;

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeUserModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeUserModal);

  if (statusInput && statusLabel) {
    statusInput.addEventListener('change', () => {
      statusLabel.textContent = statusInput.checked ? 'Aktiv' : 'Inaktiv';
    });
  }

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeUserModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeUserModal();
    }
  });
}

async function handleUpdateUserSubmit() {

  const id = document.getElementById('user-modal-id');
  const name = document.getElementById('user-modal-name');
  const email = document.getElementById('user-modal-email');
  const role = document.getElementById('user-modal-role');
  
  const statusInput = document.getElementById('user-modal-status');
  const status = statusInput.checked ? "Aktiv" : "Inaktiv";

  if (name && !name.value.trim()) {
    alert('Navn er et påkrævet felt.');
    name.focus();
    return;
  }

  if (email && !email.value.trim()) {
    alert('E-mail er et påkrævet felt.');
    email.focus();
    return;
  }

   // Brug af den importerede updateUser funktion
  const result = await updateUser(id.value, name.value, email.value, role.value, status)
    .then(result => {
      if (result === true) {
          alert(`Bruger opdateret:\nNavn: ${name ? name.value : ''}\nEmail: ${email ? email.value : ''}\nRolle: ${role ? role.value : ''}\nStatus: ${status ? status : ''}`);
          closeUserModal();
          // Jonas kan du tilføje en funktionalitet, som indlæser alle brugere igen, så man kan se ændringerne?
      }
      else
      {
        console.log("API result: " + result);
        alert('Der opstod en fejl ved ændring af brugeren i databasen.');
      }
    })
    .catch(error => {
      console.error('Fejl:', error);
      alert('Der opstod en uventet fejl.');
    });
}

function openCreateUserModal() {
  const modal = document.getElementById('user-create-modal');
  if (!modal) return;

  const nameInput = document.getElementById('create-user-modal-name');
  const emailInput = document.getElementById('create-user-modal-email');
  const passwordInput = document.getElementById('create-user-modal-password');
  const roleSelect = document.getElementById('create-user-modal-role');
  const statusInput = document.getElementById('create-user-modal-status');
  const statusLabel = document.getElementById('create-user-modal-status-label');

  if (nameInput) nameInput.value = '';
  if (emailInput) emailInput.value = '';
  if (passwordInput) passwordInput.value = '';
  const confirmInput = document.getElementById('create-user-modal-password-confirm');
  if (confirmInput) confirmInput.value = '';
  if (roleSelect) roleSelect.value = 'Admin';
  if (statusInput) statusInput.checked = true;
  if (statusLabel) statusLabel.textContent = 'Aktiv';

  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeCreateUserModal() {
  const modal = document.getElementById('user-create-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  document.body.classList.remove('modal-open');
}

async function handleCreateUserSubmit() {
  const password = document.getElementById('create-user-modal-password');
  const confirm = document.getElementById('create-user-modal-password-confirm');

  if (password && confirm && password.value !== confirm.value) {
    alert('Adgangskoderne er ikke ens. Tast venligst samme adgangskode i begge felter.');
    confirm.focus();
    return;
  }

  if (password && password.value.length < 1) {
    alert('Adgangskoden må ikke være tom.');
    password.focus();
    return;
  }

  const name = document.getElementById('create-user-modal-name');
  const email = document.getElementById('create-user-modal-email');
  const role = document.getElementById('create-user-modal-role');

  if (name && !name.value.trim()) {
    alert('Navn er et påkrævet felt.');
    name.focus();
    return;
  }

  if (email && !email.value.trim()) {
    alert('E-mail er et påkrævet felt.');
    email.focus();
    return;
  }

   // Brug af den importerede addUser funktion
  const result = await addUser(name.value, email.value, role.value, password.value)
    .then(result => {
      if (result === true) {
          alert(`Bruger oprettet:\nNavn: ${name ? name.value : ''}\nEmail: ${email ? email.value : ''}\nRolle: ${role ? role.value : ''}`);
          closeCreateUserModal();
          // Jonas kan du tilføje en funktionalitet, som indlæser alle brugere igen, så man også kan se den nyeste bruger?
      } else {
        alert('Der opstod en fejl ved oprettelse af brugeren i databasen.');
      }
    })
    .catch(error => {
      console.error('Fejl:', error);
      alert('Der opstod en uventet fejl.');
    });
}

function initCreateUserModal() {
  const closeBtn = document.getElementById('create-user-modal-close');
  const cancelBtn = document.getElementById('create-user-modal-cancel');
  const modal = document.getElementById('user-create-modal');
  const statusInput = document.getElementById('create-user-modal-status');
  const statusLabel = document.getElementById('create-user-modal-status-label');

  if (!modal) return;

  if (closeBtn) closeBtn.addEventListener('click', closeCreateUserModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeCreateUserModal);

  const saveBtn = modal.querySelector('.modal__footer .btn--primary');
  if (saveBtn) saveBtn.addEventListener('click', handleCreateUserSubmit);

  if (statusInput && statusLabel) {
    statusInput.addEventListener('change', () => {
      statusLabel.textContent = statusInput.checked ? 'Aktiv' : 'Inaktiv';
    });
  }

  modal.addEventListener('click', (event) => {
    if (event.target === modal || event.target.classList.contains('modal')) {
      closeCreateUserModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) {
      closeCreateUserModal();
    }
  });
}

function initAddUserButton() {
  const addBtn = document.getElementById('add-user-btn');
  if (!addBtn) return;
  addBtn.addEventListener('click', openCreateUserModal);
}

(function init() {
  initUserFilters();
  initUserActions();
  initUserModal();
  initCreateUserModal();
  initDeleteUserModal();
  initAddUserButton();
})();
