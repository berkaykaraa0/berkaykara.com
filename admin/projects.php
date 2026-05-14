<?php
$adminTitle = 'Projects';
require_once __DIR__ . '/includes/admin_header.php';
$projects = getProjects(null, false);
?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
  <p style="color:var(--muted);font-size:.88rem"><?= count($projects) ?> projects total</p>
  <button class="btn-primary btn-sm" onclick="openModal('create')">+ Add Project</button>
</div>

<div class="glass-card" style="padding:1.5rem">
  <div class="table-wrap">
    <table id="projectsTable">
      <thead>
        <tr>
          <th>Title</th><th>Category</th><th>Technologies</th><th>Featured</th><th>Status</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $p): ?>
        <tr data-id="<?= $p['id'] ?>">
          <td style="font-weight:600"><?= htmlspecialchars($p['title']) ?></td>
          <td><span class="badge"><?= $p['category'] ?></span></td>
          <td style="font-size:.8rem;color:var(--muted)"><?= htmlspecialchars(substr($p['technologies'],0,40)) ?>…</td>
          <td><?= $p['featured'] ? '<span class="badge success">Yes</span>' : '<span class="badge">No</span>' ?></td>
          <td><span class="badge <?= $p['status']==='active'?'success':'danger' ?>"><?= $p['status'] ?></span></td>
          <td>
            <div style="display:flex;gap:.5rem">
              <button class="btn-outline btn-sm" onclick="editProject(<?= $p['id'] ?>)">Edit</button>
              <button class="btn-outline btn-sm" style="color:#f87171;border-color:rgba(248,113,113,.3)" onclick="deleteProject(<?= $p['id'] ?>, '<?= htmlspecialchars(addslashes($p['title'])) ?>')">Delete</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- PROJECT MODAL -->
<div class="modal-overlay" id="projectModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="modalTitle">Add Project</h3>
      <button class="modal-close" onclick="closeModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <form id="projectForm" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:1rem">
      <input type="hidden" name="id" id="projectId">
      <input type="hidden" name="action" id="projectAction" value="create">
      <div class="form-row">
        <div class="form-group">
          <label>Title *</label>
          <input type="text" name="title" id="pTitle" required placeholder="Project title">
        </div>
        <div class="form-group">
          <label>Category</label>
          <select name="category" id="pCategory">
            <option value="web">Web</option>
            <option value="backend">Backend</option>
            <option value="fullstack">Full Stack</option>
            <option value="mobile">Mobile</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Short Description *</label>
        <textarea name="description" id="pDesc" required placeholder="Brief description…" style="min-height:80px"></textarea>
      </div>
      <div class="form-group">
        <label>Technologies (comma-separated)</label>
        <input type="text" name="technologies" id="pTech" placeholder="PHP, MySQL, JavaScript">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>GitHub URL</label>
          <input type="url" name="github_url" id="pGithub" placeholder="https://github.com/…">
        </div>
        <div class="form-group">
          <label>Live Demo URL</label>
          <input type="url" name="live_url" id="pLive" placeholder="https://…">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Sort Order</label>
          <input type="number" name="sort_order" id="pOrder" value="0" min="0">
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" id="pStatus">
            <option value="active">Active</option>
            <option value="hidden">Hidden</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label>Project Image</label>
        <input type="file" name="image" id="pImage" accept="image/*">
      </div>
      <div class="form-group" style="flex-direction:row;align-items:center;gap:.75rem">
        <input type="checkbox" name="featured" id="pFeatured" style="width:auto;min-height:auto;padding:0">
        <label for="pFeatured" style="text-transform:none;letter-spacing:0;font-size:.9rem;cursor:pointer">Mark as Featured</label>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-outline" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-primary" id="modalSubmit">Save Project</button>
      </div>
    </form>
  </div>
</div>

<!-- DELETE CONFIRM MODAL -->
<div class="modal-overlay" id="deleteModal">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <h3>Delete Project</h3>
      <button class="modal-close" onclick="document.getElementById('deleteModal').classList.remove('open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.5rem">Are you sure you want to delete <strong id="deleteProjectName"></strong>? This cannot be undone.</p>
    <div class="modal-actions">
      <button class="btn-outline" onclick="document.getElementById('deleteModal').classList.remove('open')">Cancel</button>
      <button class="btn-primary" style="background:linear-gradient(135deg,#f87171,#ef4444)" id="confirmDelete">Delete</button>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
