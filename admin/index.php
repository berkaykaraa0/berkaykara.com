<?php
$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';
$stats = getDashboardStats();
$projects = getProjects(null, false);
$messages = getContacts();
?>

<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card-label">Total Projects</div>
    <div class="stat-card-value"><?= $stats['projects'] ?></div>
    <div class="stat-card-sub"><?= $stats['featured'] ?> featured</div>
  </div>
  <div class="stat-card">
    <div class="stat-card-label">Messages</div>
    <div class="stat-card-value"><?= $stats['messages'] ?></div>
    <div class="stat-card-sub"><?= $stats['unread'] ?> unread</div>
  </div>
  <div class="stat-card">
    <div class="stat-card-label">Unread</div>
    <div class="stat-card-value" style="<?= $stats['unread']>0?'background:linear-gradient(135deg,#f87171,#fb923c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text':'' ?>"><?= $stats['unread'] ?></div>
    <div class="stat-card-sub">new messages</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;flex-wrap:wrap">
  <div class="glass-card" style="padding:1.5rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
      <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700">Recent Projects</h3>
      <a href="projects.php" class="btn-outline btn-sm">Manage</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Title</th><th>Category</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach (array_slice($projects, 0, 5) as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td><span class="badge"><?= $p['category'] ?></span></td>
            <td><span class="badge <?= $p['status']==='active'?'success':'danger' ?>"><?= $p['status'] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="glass-card" style="padding:1.5rem">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem">
      <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700">Recent Messages</h3>
      <a href="messages.php" class="btn-outline btn-sm">View All</a>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>From</th><th>Subject</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach (array_slice($messages, 0, 5) as $m): ?>
          <tr>
            <td>
              <?php if (!$m['is_read']): ?><span class="unread-dot"></span><?php endif; ?>
              <?= htmlspecialchars($m['name']) ?>
            </td>
            <td style="font-size:.82rem;color:var(--muted)"><?= htmlspecialchars(substr($m['subject']??'—',0,30)) ?></td>
            <td style="font-size:.75rem;color:var(--muted)"><?= date('M d', strtotime($m['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
