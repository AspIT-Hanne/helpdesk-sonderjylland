const settingsData = {
  types: [
    { id: 1, name: 'T1', code: 't1', color: COLOR_PALETTE.red },
    { id: 2, name: 'T2', code: 't2', color: COLOR_PALETTE.green },
    { id: 3, name: 'T3', code: 't3', color: COLOR_PALETTE.amber },
    { id: 4, name: 'T4', code: 't4', color: COLOR_PALETTE.blue },
    { id: 5, name: 'Andet', code: 'other', color: COLOR_PALETTE.gray }
  ],
  statuses: [
    { id: 1, name: 'Ikke Startet', color: COLOR_PALETTE.gray },
    { id: 2, name: 'Åben', color: COLOR_PALETTE.green },
    { id: 3, name: 'Afventer', color: COLOR_PALETTE.amber },
    { id: 4, name: 'Løst', color: COLOR_PALETTE.orange }
  ],
  priorities: [
    { id: 1, name: 'Høj', color: COLOR_PALETTE.red },
    { id: 2, name: 'Mellem', color: COLOR_PALETTE.amber },
    { id: 3, name: 'Lav', color: COLOR_PALETTE.green }
  ],
  roles: [
    { id: 1, name: 'Admin', color: COLOR_PALETTE.purple },
    { id: 2, name: 'Tekniker', color: COLOR_PALETTE.teal },
    { id: 3, name: 'Læserettigheder', color: COLOR_PALETTE.blue },
    { id: 4, name: 'Bruger', color: COLOR_PALETTE.gray }
  ]
};

const TAB_LABELS = {
  types: { singular: 'Type', tabLabel: 'Typer', hasCode: true },
  statuses: { singular: 'Status', tabLabel: 'Statusser', hasCode: false },
  priorities: { singular: 'Prioritet', tabLabel: 'Prioriteter', hasCode: false },
  roles: { singular: 'Rolle', tabLabel: 'Roller', hasCode: false }
};

let activeKey = 'types';

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

function renderTab() {
  const container = document.getElementById('settings-table-container');
  if (!container) return;
  const items = settingsData[activeKey];
  const meta = TAB_LABELS[activeKey];

  const headers = ['Farve', 'Navn'];
  if (meta.hasCode) headers.push('Kode');
  headers.push('Handlinger');

  const headHtml = `<thead class="data-table__head"><tr class="data-table__row">` +
    headers.map((h) => `<th scope="col" class="data-table__header">${h}</th>`).join('') +
    `</tr></thead>`;

  const bodyHtml = `<tbody id="settings-table-body">` + items.map((item) => {
    const cells = [
      `<td class="data-table__cell"><span class="badge" data-badge-bg="${item.color.bg}" data-badge-fg="${item.color.text}">${escapeHtml(item.name)}</span></td>`,
      `<td class="data-table__cell">${escapeHtml(item.name)}</td>`
    ];
    if (meta.hasCode) {
      cells.push(`<td class="data-table__cell">${escapeHtml(item.code || '')}</td>`);
    }
    cells.push(
      `<td class="data-table__cell">` +
        `<button type="button" class="action-btn" data-action="edit" data-id="${item.id}" aria-label="Redigér ${escapeHtml(item.name)}">` +
          `<img src="assets/pencil.svg" alt="" class="action-btn__icon">` +
        `</button>` +
        `<button type="button" class="action-btn action-btn--danger" data-action="delete" data-id="${item.id}" aria-label="Slet ${escapeHtml(item.name)}">` +
          `<img src="assets/trash.svg" alt="" class="action-btn__icon">` +
        `</button>` +
      `</td>`
    );
    return `<tr class="data-table__row" data-id="${item.id}" data-name="${escapeHtml(item.name)}">${cells.join('')}</tr>`;
  }).join('') + `</tbody>`;

  container.innerHTML = `<table class="data-table" aria-label="${meta.tabLabel}">${headHtml}${bodyHtml}</table>`;
  applyBadges(container);
  applySearch();
}

function applySearch() {
  const searchInput = document.getElementById('settings-search');
  const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
  const tbody = document.getElementById('settings-table-body');
  if (!tbody) return;
  tbody.querySelectorAll('.data-table__row').forEach((row) => {
    const text = (row.dataset.name || '').toLowerCase();
    row.style.display = !query || text.includes(query) ? '' : 'none';
  });
}

function initSettingsFilters() {
  const searchInput = document.getElementById('settings-search');
  if (!searchInput) return;
  searchInput.addEventListener('input', applySearch);
}

function initTabs() {
  const tabs = document.querySelectorAll('.tabs__tab');
  if (!tabs.length) return;
  tabs.forEach((tab) => {
    tab.addEventListener('click', () => {
      tabs.forEach((t) => {
        t.classList.remove('tabs__tab--active');
        t.setAttribute('aria-selected', 'false');
      });
      tab.classList.add('tabs__tab--active');
      tab.setAttribute('aria-selected', 'true');
      activeKey = tab.dataset.tab;
      updateAddButtonLabel();
      renderTab();
    });
  });
}

function updateAddButtonLabel() {
  const btn = document.getElementById('add-settings-btn');
  if (!btn) return;
  btn.textContent = `Tilføj ${TAB_LABELS[activeKey].singular}`;
}

function initSettingsActions() {
  const container = document.getElementById('settings-table-container');
  if (!container) return;
  container.addEventListener('click', (event) => {
    const btn = event.target.closest('.action-btn');
    if (!btn) return;
    const id = Number(btn.dataset.id);
    if (btn.classList.contains('action-btn--danger')) {
      openDeleteModal(id);
    } else {
      openEditModal(id);
    }
  });
}

function renderColorPicker(containerId, selectedColor) {
  const container = document.getElementById(containerId);
  if (!container) return;
  container.innerHTML = Object.entries(COLOR_PALETTE).map(([name, color]) => {
    const checked = selectedColor && color.bg === selectedColor.bg ? ' checked' : '';
    return `<label class="color-picker__swatch">` +
      `<input type="radio" name="${containerId}-color" value="${name}" class="color-picker__input"${checked}>` +
      `<span class="color-picker__circle" style="background-color:${color.bg}"></span>` +
    `</label>`;
  }).join('');
}

function getSelectedColor(containerId) {
  const checked = document.querySelector(`input[name="${containerId}-color"]:checked`);
  return checked ? COLOR_PALETTE[checked.value] : null;
}

function openDeleteModal(id) {
  const modal = document.getElementById('settings-delete-modal');
  const message = document.getElementById('settings-delete-modal-message');
  const item = settingsData[activeKey].find((i) => i.id === id);
  if (!modal || !item) return;
  if (message) message.textContent = `Er du sikker på, at du vil slette "${item.name}"? Denne handling kan ikke fortrydes.`;
  modal.setAttribute('data-delete-id', String(id));
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeDeleteModal() {
  const modal = document.getElementById('settings-delete-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-delete-id');
  document.body.classList.remove('modal-open');
}

function confirmDelete() {
  const modal = document.getElementById('settings-delete-modal');
  if (!modal) return;
  const id = Number(modal.getAttribute('data-delete-id'));
  const items = settingsData[activeKey];
  const idx = items.findIndex((i) => i.id === id);
  if (idx !== -1) items.splice(idx, 1);
  closeDeleteModal();
  renderTab();
}

function initDeleteModal() {
  const closeBtn = document.getElementById('settings-delete-modal-close');
  const cancelBtn = document.getElementById('settings-delete-modal-cancel');
  const confirmBtn = document.getElementById('settings-delete-modal-confirm');
  const modal = document.getElementById('settings-delete-modal');
  if (!modal) return;
  if (closeBtn) closeBtn.addEventListener('click', closeDeleteModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeDeleteModal);
  if (confirmBtn) confirmBtn.addEventListener('click', confirmDelete);
  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeDeleteModal();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) closeDeleteModal();
  });
}

function openEditModal(id) {
  const modal = document.getElementById('settings-edit-modal');
  const item = settingsData[activeKey].find((i) => i.id === id);
  if (!modal || !item) return;
  const meta = TAB_LABELS[activeKey];
  const title = document.getElementById('settings-edit-modal-title');
  const idInput = document.getElementById('edit-settings-id');
  const nameInput = document.getElementById('edit-settings-name');
  const codeGroup = document.getElementById('edit-settings-code-group');
  const codeInput = document.getElementById('edit-settings-code');
  if (title) title.textContent = `Redigér ${meta.singular}`;
  if (idInput) idInput.value = `#${item.id}`;
  if (nameInput) nameInput.value = item.name;
  if (codeGroup) codeGroup.style.display = meta.hasCode ? '' : 'none';
  if (codeInput) codeInput.value = item.code || '';
  modal.setAttribute('data-edit-id', String(id));
  renderColorPicker('edit-settings-color-picker', item.color);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeEditModal() {
  const modal = document.getElementById('settings-edit-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  modal.removeAttribute('data-edit-id');
  document.body.classList.remove('modal-open');
}

function handleEditSubmit() {
  const modal = document.getElementById('settings-edit-modal');
  if (!modal) return;
  const id = Number(modal.getAttribute('data-edit-id'));
  const item = settingsData[activeKey].find((i) => i.id === id);
  if (!item) return;
  const nameInput = document.getElementById('edit-settings-name');
  if (!nameInput || !nameInput.value.trim()) {
    alert('Navn er et påkrævet felt.');
    nameInput.focus();
    return;
  }
  item.name = nameInput.value.trim();
  if (TAB_LABELS[activeKey].hasCode) {
    const codeInput = document.getElementById('edit-settings-code');
    item.code = codeInput ? codeInput.value.trim() : '';
  }
  item.color = getSelectedColor('edit-settings-color-picker') || COLOR_PALETTE.red;
  closeEditModal();
  renderTab();
  alert(`${TAB_LABELS[activeKey].singular} opdateret:\nNavn: ${item.name}`);
}

function initEditModal() {
  const closeBtn = document.getElementById('settings-edit-modal-close');
  const cancelBtn = document.getElementById('settings-edit-modal-cancel');
  const modal = document.getElementById('settings-edit-modal');
  if (!modal) return;
  if (closeBtn) closeBtn.addEventListener('click', closeEditModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeEditModal);
  const saveBtn = modal.querySelector('.modal__footer .btn--primary');
  if (saveBtn) saveBtn.addEventListener('click', handleEditSubmit);
  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeEditModal();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) closeEditModal();
  });
}

function openCreateModal() {
  const modal = document.getElementById('settings-create-modal');
  if (!modal) return;
  const meta = TAB_LABELS[activeKey];
  const title = document.getElementById('settings-create-modal-title');
  const nameInput = document.getElementById('create-settings-name');
  const codeGroup = document.getElementById('create-settings-code-group');
  const codeInput = document.getElementById('create-settings-code');
  if (title) title.textContent = `Tilføj ${meta.singular}`;
  if (nameInput) nameInput.value = '';
  if (codeGroup) codeGroup.style.display = meta.hasCode ? '' : 'none';
  if (codeInput) codeInput.value = '';
  renderColorPicker('create-settings-color-picker', COLOR_PALETTE.red);
  modal.classList.add('modal--open');
  document.body.classList.add('modal-open');
}

function closeCreateModal() {
  const modal = document.getElementById('settings-create-modal');
  if (!modal) return;
  modal.classList.remove('modal--open');
  document.body.classList.remove('modal-open');
}

function handleCreateSubmit() {
  const nameInput = document.getElementById('create-settings-name');
  if (!nameInput || !nameInput.value.trim()) {
    alert('Navn er et påkrævet felt.');
    nameInput.focus();
    return;
  }
  const meta = TAB_LABELS[activeKey];
  const items = settingsData[activeKey];
  const newId = items.length ? Math.max(...items.map((i) => i.id)) + 1 : 1;
  const entry = { id: newId, name: nameInput.value.trim(), color: getSelectedColor('create-settings-color-picker') || COLOR_PALETTE.red };
  if (meta.hasCode) {
    const codeInput = document.getElementById('create-settings-code');
    entry.code = codeInput ? codeInput.value.trim() : '';
  }
  items.push(entry);
  closeCreateModal();
  renderTab();
  alert(`${meta.singular} oprettet:\nNavn: ${entry.name}`);
}

function initCreateModal() {
  const closeBtn = document.getElementById('settings-create-modal-close');
  const cancelBtn = document.getElementById('settings-create-modal-cancel');
  const modal = document.getElementById('settings-create-modal');
  if (!modal) return;
  if (closeBtn) closeBtn.addEventListener('click', closeCreateModal);
  if (cancelBtn) cancelBtn.addEventListener('click', closeCreateModal);
  const saveBtn = modal.querySelector('.modal__footer .btn--primary');
  if (saveBtn) saveBtn.addEventListener('click', handleCreateSubmit);
  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeCreateModal();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('modal--open')) closeCreateModal();
  });
}

function initAddButton() {
  const btn = document.getElementById('add-settings-btn');
  if (!btn) return;
  btn.addEventListener('click', openCreateModal);
}

(function init() {
  updateAddButtonLabel();
  renderTab();
  initTabs();
  initSettingsFilters();
  initSettingsActions();
  initCreateModal();
  initEditModal();
  initDeleteModal();
  initAddButton();
})();
