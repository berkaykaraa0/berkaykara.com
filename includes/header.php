<?php require_once __DIR__ . '/../config/database.php'; ?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? sanitize($pageTitle) . ' — ' : '' ?><?= SITE_TITLE ?></title>
<meta name="description" content="Full-Stack Developer Portfolio — PHP, MySQL, JavaScript, React">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<div id="loader" class="loader"><div class="loader-ring"></div></div>
<div id="toast-container"></div>

<!-- NAVIGATION -->
<nav class="navbar" id="navbar">
  <div class="nav-inner">
    <a href="<?= SITE_URL ?>" class="logo"><?= substr(SITE_NAME,0,2) ?>.</a>
    <ul class="nav-links" id="navLinks">
      <li><a href="<?= SITE_URL ?>/#home">Home</a></li>
      <li><a href="<?= SITE_URL ?>/#about">About</a></li>
      <li><a href="<?= SITE_URL ?>/#skills">Skills</a></li>
      <li><a href="<?= SITE_URL ?>/#projects">Projects</a></li>
      <li><a href="<?= SITE_URL ?>/#contact" class="nav-cta">Contact</a></li>
    </ul>
    <div class="nav-actions">
      <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>
      <button class="hamburger" id="hamburger" aria-label="Menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</nav>
