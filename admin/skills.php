<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();
$db     = getDB();
$skills = $db->query("SELECT * FROM skills ORDER BY category, display_order ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Skills — Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
  <aside class="sidebar">
    <div class="sidebar-logo">Admin Panel<span>Portfolio Dashboard</span></div>
    <nav class="sidebar-nav">
      <a href="dashboard.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>Dashboard</a>
      <a href="projects.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>Projects</a>
      <a href="messages.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Messages</a>
      <a href="skills.php" class="active"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>Skills</a>
    </nav>
    <div class="sidebar-footer">
      <a href="../logout.php" style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-radius:12px;color:var(--muted);font-size:.88rem;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Logout
      </a>
    </div>
  </aside>
  <main class="admin-main">
    <header class="admin-header">
      <h1>Skills</h1>
      <div style="display:flex;gap:.75rem;align-items:center;">
        <button class="btn-primary btn-sm" onclick="openModal('addSkillModal')">+ Add Skill</button>
        <button class="theme-toggle" onclick="toggleTheme()">☀️</button>
      </div>
    </header>
    <div class="admin-content">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Name</th><th>Category</th><th>Proficiency</th><th>Order</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($skills as $s): ?>
            <tr id="skill-row-<?= $s['id'] ?>">
              <td><?= $s['id'] ?></td>
              <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
              <td style="text-transform:capitalize;"><?= htmlspecialchars($s['category']) ?></td>
              <td>
                <div style="display:flex;align-items:center;gap:.75rem;">
                  <div style="flex:1;height:6px;background:rgba(255,255,255,0.08);border-radius:100px;max-width:100px;overflow:hidden;">
                    <div style="height:100%;width:<?= $s['proficiency'] ?>%;background:linear-gradient(90deg,var(--purple),var(--blue));border-radius:100px;"></div>
                  </div>
                  <span style="font-size:.82rem;color:var(--purple);font-weight:600;"><?= $s['proficiency'] ?>%</span>
                </div>
              </td>
              <td><?= $s['display_order'] ?></td>
              <td>
                <button onclick="deleteSkill(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>')" class="btn-sm btn-danger">Delete</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<!-- Add Skill Modal -->
<div class="modal-overlay" id="addSkillModal">
  <div class="modal">
    <div class="modal-header">
      <h2>Add Skill</h2>
      <button class="modal-close" data-close="addSkillModal">✕</button>
    </div>
    <form id="addSkillForm">
      <div class="form-group" style="margin-bottom:.75rem;">
        <label>Skill Name *</label>
        <input type="text" name="name" placeholder="e.g. TypeScript" required>
      </div>
      <div class="form-row" style="margin-bottom:.75rem;">
        <div class="form-group">
          <label>Category</label>
          <select name="category">
            <option value="frontend">Frontend</option>
            <option value="backend">Backend</option>
            <option value="database">Database</option>
            <option value="tools">Tools</option>
          </select>
        </div>
        <div class="form-group">
          <label>Proficiency (0–100)</label>
          <input type="number" name="proficiency" value="75" min="0" max="100">
        </div>
      </div>
      <div class="form-row" style="margin-bottom:1.25rem;">
        <div class="form-group">
          <label>Icon Key</label>
          <input type="text" name="icon" placeholder="e.g. typescript">
        </div>
        <div class="form-group">
          <label>Display Order</label>
          <input type="number" name="display_order" value="0" min="0">
        </div>
      </div>
      <button type="submit" class="btn-submit">Add Skill</button>
    </form>
  </div>
</div>

<div class="toast-container"></div>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/admin.js"></script>
<script>
document.getElementById('addSkillForm').addEventListener('submit', async e => {
  e.preventDefault();
  const fd   = new FormData(e.target);
  const json = await apiRequest('../includes/api_skills.php?action=create', 'POST', fd);
  if (json.success) {
    showToast(json.message, 'success');
    closeModal('addSkillModal');
    setTimeout(() => location.reload(), 800);
  } else {
    showToast(json.message, 'error');
  }
});

async function deleteSkill(id, name) {
  if (!confirm(`Delete skill "${name}"?`)) return;
  const fd = new FormData(); fd.append('id', id);
  const json = await apiRequest('../includes/api_skills.php?action=delete', 'POST', fd);
  if (json.success) {
    showToast('Skill deleted.', 'success');
    document.getElementById(`skill-row-${id}`)?.remove();
  } else {
    showToast(json.message, 'error');
  }
}
</script>
</body>
</html>
