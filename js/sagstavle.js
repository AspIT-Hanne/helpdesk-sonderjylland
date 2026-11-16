
function updateColumnCounts() {
  // 1. Find alle unikke kolonnetyper (f.eks. "Ikke-startet", "I gang", etc.)
  const columnTypes = new Set();
  document.querySelectorAll('.kanban-column[data-column]').forEach((column) => {
    columnTypes.add(column.getAttribute('data-column'));
  });

  // 2. Gå igennem hver unik type ÉN gang
  columnTypes.forEach((columnType) => {
    // Find alle kolonner af denne type
    const columnsOfType = document.querySelectorAll(`.kanban-column[data-column="${columnType}"]`);
    
    let totalCards = 0;
    
    // Tæl kortene sammen på tværs af alle kolonner i denne kategori
    columnsOfType.forEach((column) => {
      const cards = column.querySelectorAll('.kanban-column__cards .kanban-card');
      totalCards += cards.length;
    });

    // Opdater tælleren i ALLE kolonner af denne type, så de viser det samlede rigtige tal
    columnsOfType.forEach((column) => {
      const countEl = column.querySelector('.kanban-column__count');
      if (countEl) {
        countEl.textContent = totalCards;
      }
    });
  });
}

function getCardsInColumns(columnSelectors) {
  const cards = [];
  columnSelectors.forEach((selector) => {
      const columns = document.querySelectorAll(`.kanban-column[data-column="${selector}"]`);
      
      columns.forEach((column) => {
        const columnCards = column.querySelectorAll('.kanban-card');
        columnCards.forEach((card) => {
          // Tilføj kun kortet, hvis det ikke allerede er i arrayet
          if (!cards.includes(card)) {
            cards.push(card);
          }
        });
      });
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
  const openCards = getCardsInColumns(['Ikke-startet', 'Åben']); 
  const pendingCards = getCardsInColumns(['Afventer']);
  const resolvedCards = getCardsInColumns(['Løst']);
  
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
