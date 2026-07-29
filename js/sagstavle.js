function updateColumnCounts() {
  document.querySelectorAll('.kanban-column').forEach((column) => {
    const cards = column.querySelectorAll('.kanban-column__cards .kanban-card');
    const countEl = column.querySelector('.kanban-column__count');
    if (countEl) {
      countEl.textContent = cards.length;
    }
  });
}

function getCardsInColumns(columnSelectors) {
  const cards = [];
  columnSelectors.forEach((selector) => {
    const column = document.querySelector(`.kanban-column[data-column="${selector}"]`);
    if (column) {
      cards.push(...column.querySelectorAll('.kanban-card'));
    }
  });
  return cards;
}

function countHighPriority(cards) {
  return cards.filter((card) => {
    return card.querySelector('[data-badge="priority:Høj"]') !== null;
  }).length;
}

function countRecentCards(cards, days) {
  const cutoff = new Date();
  cutoff.setDate(cutoff.getDate() - days);
  cutoff.setHours(0, 0, 0, 0);

  return cards.filter((card) => {
    const dateStr = card.getAttribute('data-created-date');
    if (!dateStr) return false;
    const cardDate = new Date(dateStr);
    return cardDate >= cutoff;
  }).length;
}

function updateStatCards() {
  const columns = document.querySelectorAll('[data-column]');
  const columnNames = Array.from(columns, col => col.dataset.column);
  // ColumnNames indeholder navnene på de oprettede kolonner, hvor navnet står i attributten data-column
  const openCards = getCardsInColumns([columnNames[0], columnNames[1], columnNames[2]]);
  const pendingCards = getCardsInColumns([columnNames[1]]);
  const resolvedCards = getCardsInColumns([columnNames[3]]);

  const openCount = openCards.length;
  const urgentCount = countHighPriority(openCards);
  const pendingCount = pendingCards.length;
  const recentResolvedCount = countRecentCards(resolvedCards, 30);

  const statOpen = document.getElementById('stat-open');
  if (statOpen) {
    statOpen.querySelector('.stat-card__value').textContent = openCount;
    statOpen.querySelector('.stat-card__urgent-count').textContent = urgentCount;
    statOpen.classList.toggle('stat-card--focus', urgentCount > 0);
  }

  const statPending = document.getElementById('stat-pending');
  if (statPending) {
    statPending.querySelector('.stat-card__value').textContent = pendingCount;
    statPending.classList.toggle('stat-card--focus', pendingCount > 0);
  }

  const statResolved = document.getElementById('stat-resolved');
  if (statResolved) {
    statResolved.querySelector('.stat-card__value').textContent = resolvedCards.length;
    statResolved.querySelector('.stat-card__sublabel').textContent =
      recentResolvedCount + ' seneste 30 dage';
  }
}

(function init() {
  updateColumnCounts();
  updateStatCards();
})();
