<?php
require_once __DIR__ . '/../../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($adminTitle) ? $adminTitle . ' — ' : '' ?>Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="admin-body">
<script>const t=localStorage.getItem('theme')||'dark';document.documentElement.dataset.theme=t;</script>
<div id="toast-container"></div>
<div class="admin-wrap">
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <a href="<?= SITE_URL ?>" class="logo">BK.</a>
      <p style="font-size:.72rem;color:var(--muted);margin-top:.3rem">Admin Panel</p>
    </div>
    <nav class="sidebar-nav">
      <a href="<?= SITE_URL ?>/admin/index.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])==='index.php'?'active':'' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="<?= SITE_URL ?>/admin/projects.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])==='projects.php'?'active':'' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        Projects
      </a>
      <a href="<?= SITE_URL ?>/admin/messages.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])==='messages.php'?'active':'' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        Messages
      </a>
      <div class="sidebar-divider"></div>
      <a href="<?= SITE_URL ?>/index.php" class="sidebar-link" target="_blank">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        View Site
      </a>
      <a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="color:#f87171">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </a>
    </nav>
  </aside>
  <main class="admin-content">
    <div class="admin-header">
      <h1><?= $adminTitle ?? 'Dashboard' ?></h1>
      <div style="display:flex;align-items:center;gap:1rem">
        <button class="theme-toggle" onclick="const t=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=t;localStorage.setItem('theme',t)">
          <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/></svg>
          <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>
        <span style="font-size:.85rem;color:var(--muted)">👋 <?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></span>
      </div>
    </div>
