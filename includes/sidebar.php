<?php

  $username = $hidemenu = '';

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
      <li class='<?= $permissions[$role]['show']; ?>'>
        <a href="sager.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="sager.php") {echo "sidebar__item--active ";}?>">
          <img src="assets/file.svg" alt="" class="sidebar__icon">
          Sager
        </a>
      </li>
      <li class='<?= $permissions[$role]['show']; ?>'>
        <a href="sagstavle.php" class="sidebar__item">
          <img src="assets/board.svg" alt="" class="sidebar__icon">
          Sagstavle
        </a>
      </li>
      <li class='<?= $permissions[$role]['show']; ?>'>
        <a href="brugere.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="brugere.php") {echo "sidebar__item--active ";}?>">
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
      <li class='<?= $permissions[$role]['show']; ?>'>
        <a href="indstillinger.php" class="sidebar__item <?php if(basename($_SERVER['PHP_SELF'])=="indstillinger.php") {echo "sidebar__item--active ";}?>">
          <img src="assets/settings.svg" alt="" class="sidebar__icon">
          Indstillinger
        </a>
      </li>
  </ul>
  <ul class="sidebar__nav <?= $hidemenu; ?>">
      <li>
        <div>Logget ind som <?= $username; ?>.</div>
      </li>
      <li>
          <a href="logout.php" class="sidebar__item"><img src="assets/logout.svg" alt="" class="sidebar__icon">Log ud</a>
      </li>
    </ul>
  </nav>