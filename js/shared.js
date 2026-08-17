function initMobileNav() {
  const hamburger = document.getElementById('hamburger');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const sidebarClose = document.getElementById('sidebar-close');

  if (!hamburger || !sidebar || !overlay) return;

  function openSidebar() {
    sidebar.classList.add('sidebar--open');
    overlay.classList.add('overlay--visible');
  }

  function closeSidebar() {
    sidebar.classList.remove('sidebar--open');
    overlay.classList.remove('overlay--visible');
  }

  hamburger.addEventListener('click', openSidebar);
  if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
  overlay.addEventListener('click', closeSidebar);
}

function showBottomMessage(message, type = 'error') {
    let messageBar = document.getElementById('bottom-message-bar');
    
    if (!messageBar) {
        messageBar = document.createElement('div');
        messageBar.id = 'bottom-message-bar';

        messageBar.innerHTML = `
            <span id="bottom-message-text"></span>
            <button id="bottom-message-close">&times;</button>
        `;

        document.body.appendChild(messageBar);

        document.getElementById('bottom-message-close').addEventListener('click', () => {
            hideBottomMessage(messageBar);
        });
    }

    // Fjern tidligere type-klasser og sæt den nye på
    messageBar.classList.remove('error', 'warning', 'success');
    messageBar.classList.add(type);

    // Sæt teksten ind
    document.getElementById('bottom-message-text').textContent = message;

    // Vis messagebaren
    setTimeout(() => {
        messageBar.classList.add('show');
    }, 10);

    // Nulstil timer
    if (messageBar.dataset.timeout) {
        clearTimeout(Number(messageBar.dataset.timeout));
    }

    // Skjul automatisk efter 5 sekunder
    const timer = setTimeout(() => {
        hideBottomMessage(messageBar);
    }, 5000);

    messageBar.dataset.timeout = timer;
}

function hideBottomMessage(messageBar) {
    messageBar.classList.remove('show');
}

// Objekt til at holde styr på de aktiverede filtre
const currentFilters = {
    status: '',
    tildelt: '',
    rolle: ''
};

function filterDataTable(columnIdentifier, data) {
    const table = document.querySelector('.data-table');
    console.log("Tabel: " + table);
    if (!table) return;

    

    // Gem den nye filterværdi i filter-objektete
    currentFilters[columnIdentifier] = data;

    const headers = Array.from(table.querySelectorAll('.data-table__header'));
    const rows = table.querySelectorAll('tbody tr');
    
    const filterIndices = {};
    Object.keys(currentFilters).forEach(key => {
        filterIndices[key] = headers.findIndex(th => th.textContent.trim().toLowerCase() === key.toLowerCase());
    });

    rows.forEach(tr => {
        let showRow = true;

        // Tjek alle aktive filtre automatisk
        for (const [key, filterValue] of Object.entries(currentFilters)) {
            if (filterValue !== "") {
                const columnIndex = filterIndices[key];
                if (columnIndex !== -1) {
                    const cell = tr.querySelectorAll('td')[columnIndex];
                    if (cell && cell.textContent.trim() !== filterValue) {
                        showRow = false;
                        break; // Stop med at tjekke de andre filtre, hvis rækken allerede er afvist
                    }
                }
            }
        }

        // Vis eller skjul rækken baseret på om den bestod alle aktive filtre
        tr.style.display = showRow ? '' : 'none';
    });
}


/* ============================
   Dark Mode
   ============================ */

function applyDarkModePreference() {
    const savedMode = localStorage.getItem('darkMode');

    if (savedMode === null) {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.body.classList.toggle('dark-mode', prefersDark);
    } else {
        document.body.classList.toggle('dark-mode', savedMode === 'true');
    }
}

function initDarkModeToggle() {
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (!darkModeToggle) return;

    darkModeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        const isDarkMode = document.body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);
    });
}
function initSystemThemeListener() {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (localStorage.getItem('darkMode') === null) {
            document.body.classList.toggle('dark-mode', e.matches);
        }
    });
}

(function init() {
  applyDarkModePreference();
  initMobileNav();
  initDarkModeToggle();
  initSystemThemeListener();
})();