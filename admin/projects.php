<?php
require_once __DIR__ . '/includes/admin_header.php';
$projects = getProjects('all', false); // Get all, even inactive
?>

<div class="glass-panel">
  <div class="panel-header">
    <h3 class="panel-title">All Projects</h3>
    <button class="btn-neon btn-neon-primary" onclick="showToast('Add project functionality is available via API integration but UI is simplified for demo.', 'info')">+ New Project</button>
  </div>
  <div class="premium-table-wrap">
    <table class="premium-table">
      <thead><tr><th>Title</th><th>Category</th><th>Status</th><th style="text-align:right">Actions</th></tr></thead>
      <tbody id="projectsTableBody">
        <?php foreach($projects as $p): ?>
        <tr id="proj_<?= $p['id'] ?>">
          <td style="font-weight:600"><?= htmlspecialchars($p['title']) ?></td>
          <td style="color:var(--muted)"><?= htmlspecialchars($p['category']) ?></td>
          <td>
            <span class="badge-neon <?= $p['status']==='active'?'badge-success':'badge-danger' ?>"><?= $p['status'] ?></span>
            <?php if($p['featured']): ?>
              <span class="badge-neon badge-warning" style="margin-left:8px">Featured</span>
            <?php endif; ?>
          </td>
          <td style="text-align:right">
            <button class="btn-neon" onclick="showToast('Edit mode opened for <?= addslashes($p['title']) ?>', 'info')">Edit</button>
            <button class="btn-neon" style="color:#f87171; border-color:rgba(248,113,113,0.3)" onclick="deleteProject(<?= $p['id'] ?>)">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('pageTitle').innerText = 'Projects Management';
function deleteProject(id) {
    if(!confirm('Are you sure you want to delete this project?')) return;
    
    fetch('<?= SITE_URL ?>/api/admin_projects.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('proj_'+id).remove();
            showToast('Project deleted successfully.');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast('Network error', 'error'));
}
</script>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
