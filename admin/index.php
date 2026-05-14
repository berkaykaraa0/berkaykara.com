<?php
require_once __DIR__ . '/includes/admin_header.php';
$stats = getDashboardStats();
$recentProjects = array_slice(getProjects(), 0, 5);
$recentMessages = array_slice(getContacts(), 0, 5);
?>

<div class="stats-grid" id="statsGrid">
  <div class="stat-card-premium">
    <div class="stat-icon-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
    </div>
    <div class="stat-card-label">Total Projects</div>
    <div class="stat-card-value"><?= $stats['projects'] ?></div>
    <div class="stat-card-sub"><?= $stats['featured'] ?> featured items</div>
  </div>
  <div class="stat-card-premium">
    <div class="stat-icon-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    </div>
    <div class="stat-card-label">Total Messages</div>
    <div class="stat-card-value"><?= $stats['messages'] ?></div>
    <div class="stat-card-sub">In your inbox</div>
  </div>
  <div class="stat-card-premium" style="<?= $stats['unread'] > 0 ? 'border-color:rgba(239,68,68,0.4); box-shadow:0 0 30px rgba(239,68,68,0.1)' : '' ?>">
    <div class="stat-icon-wrap" style="<?= $stats['unread'] > 0 ? 'color:#f87171; background:rgba(239,68,68,0.1); border-color:rgba(239,68,68,0.3)' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
    </div>
    <div class="stat-card-label">Unread Alerts</div>
    <div class="stat-card-value" style="<?= $stats['unread'] > 0 ? 'color:#f87171' : '' ?>"><?= $stats['unread'] ?></div>
    <div class="stat-card-sub" style="<?= $stats['unread'] > 0 ? 'color:#f87171' : '' ?>">Requires attention</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;flex-wrap:wrap">
  <div class="glass-panel">
    <div class="panel-header">
      <h3 class="panel-title">Recent Projects</h3>
      <a href="projects.php" class="btn-neon">Manage</a>
    </div>
    <div class="premium-table-wrap">
      <table class="premium-table">
        <thead><tr><th>Title</th><th>Category</th><th>Status</th></tr></thead>
        <tbody>
          <?php foreach($recentProjects as $p): ?>
          <tr>
            <td style="font-weight:600"><?= htmlspecialchars($p['title']) ?></td>
            <td><span style="color:var(--muted); font-size:0.85rem"><?= htmlspecialchars($p['category']) ?></span></td>
            <td><span class="badge-neon <?= $p['status']==='active'?'badge-success':'badge-danger' ?>"><?= $p['status'] ?></span></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentProjects)): ?>
          <tr><td colspan="3" style="text-align:center;color:var(--muted)">No projects found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <div class="glass-panel">
    <div class="panel-header">
      <h3 class="panel-title">Recent Messages</h3>
      <a href="messages.php" class="btn-neon">View All</a>
    </div>
    <div class="premium-table-wrap">
      <table class="premium-table">
        <thead><tr><th>From</th><th>Subject</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach($recentMessages as $m): ?>
          <tr>
            <td style="font-weight:600; display:flex; align-items:center; gap:0.5rem">
              <?php if(!$m['is_read']): ?>
              <span style="width:8px;height:8px;border-radius:50%;background:#f87171;box-shadow:0 0 8px #f87171"></span>
              <?php endif; ?>
              <?= htmlspecialchars($m['name']) ?>
            </td>
            <td style="color:var(--muted)"><?= htmlspecialchars($m['subject']) ?></td>
            <td style="color:var(--muted); font-size:0.85rem"><?= date('M d', strtotime($m['created_at'])) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($recentMessages)): ?>
          <tr><td colspan="3" style="text-align:center;color:var(--muted)">No messages found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>document.getElementById('pageTitle').innerText = 'Dashboard';</script>
<?php include __DIR__ . '/includes/admin_footer.php'; ?>
