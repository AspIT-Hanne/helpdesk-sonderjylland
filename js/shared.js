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

(function init() {
  initMobileNav();
})();
