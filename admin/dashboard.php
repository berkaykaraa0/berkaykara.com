<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$db = getDB();

// Stats
$totalProjects  = $db->query("SELECT COUNT(*) FROM projects WHERE is_active=1")->fetchColumn();
$totalMessages  = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$unreadMessages = $db->query("SELECT COUNT(*) FROM contacts WHERE is_read=0")->fetchColumn();
$totalSkills    = $db->query("SELECT COUNT(*) FROM skills WHERE is_active=1")->fetchColumn();

// Recent projects
$projects = $db->query("SELECT * FROM projects WHERE is_active=1 ORDER BY created_at DESC LIMIT 10")->fetchAll();

// Recent messages
$messages = $db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      Admin Panel
      <span>Portfolio Dashboard</span>
    </div>
    <nav class="sidebar-nav">
      <a href="dashboard.php" class="active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
        Dashboard
      </a>
      <a href="projects.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        Projects
      </a>
      <a href="messages.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Messages
        <?php if ($unreadMessages > 0): ?>
        <span style="margin-left:auto;background:rgba(167,139,250,0.2);color:var(--purple);font-size:0.68rem;font-weight:700;padding:0.1rem 0.5rem;border-radius:100px;"><?= $unreadMessages ?></span>
        <?php endif; ?>
      </a>
      <a href="skills.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Skills
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="../logout.php" style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-radius:12px;color:var(--muted);font-size:.88rem;transition:all .3s;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </a>
      <a href="../index.php" style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-radius:12px;color:var(--muted);font-size:.88rem;transition:all .3s;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        View Site
      </a>
    </div>
  </aside>

  <!-- Main -->
  <main class="admin-main">
    <header class="admin-header">
      <div style="display:flex;align-items:center;gap:1rem;">
        <button id="sidebarToggle" style="display:none;background:none;border:none;color:var(--text);font-size:1.3rem;cursor:pointer;">☰</button>
        <h1>Dashboard</h1>
      </div>
      <div style="display:flex;align-items:center;gap:1rem;">
        <span style="font-size:.85rem;color:var(--muted);">Welcome, <strong><?= htmlspecialchars($_SESSION['full_name']) ?></strong></span>
        <button class="theme-toggle" onclick="toggleTheme()">☀️</button>
      </div>
    </header>

    <div class="admin-content">

      <!-- Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-card-icon">📁</div>
          <div class="stat-card-num"><?= $totalProjects ?></div>
          <div class="stat-card-label">Total Projects</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon">✉️</div>
          <div class="stat-card-num"><?= $totalMessages ?></div>
          <div class="stat-card-label">Total Messages</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon">🔔</div>
          <div class="stat-card-num"><?= $unreadMessages ?></div>
          <div class="stat-card-label">Unread Messages</div>
        </div>
        <div class="stat-card">
          <div class="stat-card-icon">⚡</div>
          <div class="stat-card-num"><?= $totalSkills ?></div>
          <div class="stat-card-label">Skills Listed</div>
        </div>
      </div>

      <!-- Quick actions -->
      <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
        <a href="projects.php" class="btn-primary btn-sm">+ Add Project</a>
        <a href="messages.php" class="btn-outline btn-sm">View Messages</a>
        <a href="skills.php" class="btn-outline btn-sm">Manage Skills</a>
      </div>

      <!-- Recent Projects -->
      <h3 style="font-family:'Syne',sans-serif;margin-bottom:1rem;font-size:1rem;font-weight:700;">Recent Projects</h3>
      <div class="table-wrap" style="margin-bottom:2rem;">
        <table>
          <thead>
            <tr>
              <th>#</th><th>Title</th><th>Category</th><th>Featured</th><th>Created</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projects as $p): ?>
            <tr id="proj-row-<?= $p['id'] ?>">
              <td><?= $p['id'] ?></td>
              <td><?= htmlspecialchars($p['title']) ?></td>
              <td><span style="text-transform:capitalize;"><?= htmlspecialchars($p['category']) ?></span></td>
              <td><?= $p['is_featured'] ? '<span class="badge badge-featured">✓</span>' : '—' ?></td>
              <td style="color:var(--muted);font-size:.82rem;"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
              <td>
                <a href="projects.php?edit=<?= $p['id'] ?>" class="btn-outline btn-sm" style="margin-right:.5rem;">Edit</a>
                <button onclick="deleteProject(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['title'])) ?>')" class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Recent Messages -->
      <h3 style="font-family:'Syne',sans-serif;margin-bottom:1rem;font-size:1rem;font-weight:700;">Recent Messages</h3>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($messages as $m): ?>
            <tr id="msg-row-<?= $m['id'] ?>">
              <td><?= $m['id'] ?></td>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td style="color:var(--muted);font-size:.82rem;"><?= htmlspecialchars($m['email']) ?></td>
              <td><?= htmlspecialchars($m['subject'] ?: '—') ?></td>
              <td style="color:var(--muted);font-size:.82rem;"><?= date('d M Y', strtotime($m['created_at'])) ?></td>
              <td>
                <span class="badge <?= $m['is_read'] ? 'badge-read' : 'badge-unread' ?>">
                  <?= $m['is_read'] ? 'Read' : 'Unread' ?>
                </span>
              </td>
              <td>
                <?php if (!$m['is_read']): ?>
                <button onclick="markRead(<?= $m['id'] ?>)" class="btn-outline btn-sm" style="margin-right:.5rem;">Mark Read</button>
                <?php endif; ?>
                <button onclick="deleteMessage(<?= $m['id'] ?>)" class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

    </div><!-- /admin-content -->
  </main>
</div><!-- /admin-layout -->

<div class="toast-container"></div>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
