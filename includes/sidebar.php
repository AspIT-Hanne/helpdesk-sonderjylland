<?php

  $username = $hidemenu = '';
  $role = 4; // Default role
  
  if(!isset($_SESSION['logged_in']))
  {
    $hidemenu = 'hidden';
  }
  else
  {
    $username = $_SESSION['username'];
    if($_SESSION['userRole_id'] > 0 && $_SESSION['userRole_id'] < 4)
    {
      $role = $_SESSION['userRole_id'];
    }
    else
    {
      $role = 4;
    }
  }
  
  // Initialize $permissions if not set (from phpheader.php)
  if (!isset($permissions)) {
    $permissions = [];
  }
?>

<nav class="sidebar" id="sidebar">
    <div class="sidebar__header">
      <a href="index.php"><img src="assets/logo.svg" alt="AspIT" class="sidebar__logo"></a>
      <button class="sidebar__close" id="sidebar-close" aria-label="Luk menu">&times;</button>
    </div>
    <ul class="sidebar__nav <?= $hidemenu; ?>">
      <li>
        <a href="index.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="index.php") {echo "sidebar__item--active ";}?>">
          <img src="assets/house.svg" alt="" class="sidebar__icon">
          Dashboard
        </a>
      </li>
      <!-- Filen genkender ikke $permissions fordi den genereres i phpheader.php, som inkluderes i alle de filer, der inkluderer sidebar.php -->
      <li class='<?= $permissions[$role]['show'] ?? ''; ?>'>
        <a href="sager.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="sager.php") {echo "sidebar__item--active ";}?>">
          <img src="assets/file.svg" alt="" class="sidebar__icon">
          Sager
        </a>
      </li>
      <li class='<?= $permissions[$role]['show'] ?? ''; ?>'>
        <a href="sagstavle.php" class="sidebar__item">
          <img src="assets/board.svg" alt="" class="sidebar__icon">
          Sagstavle
        </a>
      </li>
      <li class='<?= $permissions[$role]['show'] ?? ''; ?>'>
        <a href="brugere.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="brugere.php") {echo "sidebar__item--active ";}?>"
          <img src="assets/users.svg" alt="" class="sidebar__icon">
          Brugere
        </a>
      </li>
      <li>
        <a href="opret-sag.php" class="sidebar__item sidebar__item--cta">
          <img src="assets/plus.svg" alt="" class="sidebar__icon">
          Opret Sag
        </a>
      </li>
      <li class='<?= $permissions[$role]['show'] ?? ''; ?>'>
        <a href="indstillinger.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="indstillinger.php") {echo "sidebar__item--active ";}?>"
          <img src="assets/settings.svg" alt="" class="sidebar__icon">
          Indstillinger
        </a>
      </li>
  </ul>
  <div class="sidebar__footer <?= $hidemenu; ?>">
      <button class="sidebar__dark-mode-toggle" id="dark-mode-toggle" aria-label="Slå mørk tilstand til/fra">
        <svg class="sidebar__dark-mode-icon sidebar__dark-mode-icon--sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="5"></circle>
          <line x1="12" y1="1" x2="12" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="23"></line>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
          <line x1="1" y1="12" x2="3" y2="12"></line>
          <line x1="21" y1="12" x2="23" y2="12"></line>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <svg class="sidebar__dark-mode-icon sidebar__dark-mode-icon--moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
        <span>Theme Toggle</span>
      </button>
      <div class="sidebar__user-info">
        <div class="sidebar__user-text">Logget ind som <?= $username; ?>.</div>
        <a href="logout.php" class="sidebar__item"><img src="assets/logout.svg" alt="" class="sidebar__icon">Log ud</a>
      </div>
  </div>
  </nav>