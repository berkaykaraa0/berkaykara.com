// ============================================================
// Admin Dashboard — JavaScript
// ============================================================

/* ── Modal helpers ─────────────────────────────────────────── */
function openModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
}
document.querySelectorAll('[data-close]').forEach(btn => {
  btn.addEventListener('click', () => closeModal(btn.dataset.close));
});
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
});

/* ── AJAX helper ───────────────────────────────────────────── */
async function apiRequest(url, method = 'GET', data = null) {
  const opts = { method };
  if (data) opts.body = data instanceof FormData ? data : JSON.stringify(data);
  const res  = await fetch(url, opts);
  const json = await res.json();
  return json;
}

/* ── Projects CRUD ─────────────────────────────────────────── */
const projectModal = document.getElementById('projectModal');

async function openProjectModal(id = null) {
  const form  = document.getElementById('projectForm');
  const title = document.getElementById('modalTitle');
  form.reset();
  document.getElementById('projectId').value = '';

  if (id) {
    title.textContent = 'Edit Project';
    const json = await apiRequest(`../includes/api_projects.php?action=get&id=${id}`);
    if (!json.success) { showToast(json.message, 'error'); return; }
    const p = json.data;
    document.getElementById('projectId').value         = p.id;
    document.getElementById('projTitle').value         = p.title;
    document.getElementById('projDesc').value          = p.description;
    document.getElementById('projShort').value         = p.short_description || '';
    document.getElementById('projTech').value          = p.technologies || '';
    document.getElementById('projCategory').value      = p.category;
    document.getElementById('projGithub').value        = p.github_url || '';
    document.getElementById('projLive').value          = p.live_url || '';
    document.getElementById('projOrder').value         = p.display_order || 0;
    document.getElementById('projFeatured').checked   = p.is_featured == 1;
  } else {
    title.textContent = 'Add Project';
  }
  openModal('projectModal');
}

document.getElementById('projectForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const id  = document.getElementById('projectId').value;
  const fd  = new FormData(e.target);
  const url = `../includes/api_projects.php?action=${id ? 'update' : 'create'}`;
  const json = await apiRequest(url, 'POST', fd);
  if (json.success) {
    showToast(json.message, 'success');
    closeModal('projectModal');
    setTimeout(() => location.reload(), 800);
  } else {
    showToast(json.message, 'error');
  }
});

async function deleteProject(id, name) {
  if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
  const fd = new FormData();
  fd.append('id', id);
  const json = await apiRequest('../includes/api_projects.php?action=delete', 'POST', fd);
  if (json.success) {
    showToast(json.message, 'success');
    document.getElementById(`proj-row-${id}`)?.remove();
  } else {
    showToast(json.message, 'error');
  }
}

/* ── Messages ──────────────────────────────────────────────── */
async function markRead(id) {
  const fd = new FormData();
  fd.append('id', id); fd.append('action', 'mark_read');
  const json = await apiRequest('../includes/api_messages.php', 'POST', fd);
  if (json.success) {
    const badge = document.querySelector(`#msg-row-${id} .badge`);
    if (badge) { badge.className = 'badge badge-read'; badge.textContent = 'Read'; }
  }
}

async function deleteMessage(id) {
  if (!confirm('Delete this message?')) return;
  const fd = new FormData();
  fd.append('id', id); fd.append('action', 'delete');
  const json = await apiRequest('../includes/api_messages.php', 'POST', fd);
  if (json.success) { document.getElementById(`msg-row-${id}`)?.remove(); showToast('Message deleted.', 'success'); }
}

/* ── Toast ─────────────────────────────────────────────────── */
function showToast(msg, type = 'info') {
  let c = document.querySelector('.toast-container');
  if (!c) { c = document.createElement('div'); c.className = 'toast-container'; document.body.appendChild(c); }
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = `<span>${{success:'✅',error:'❌',info:'ℹ️'}[type]||''}</span><span>${msg}</span>`;
  c.appendChild(t);
  setTimeout(() => { t.style.opacity='0'; t.style.transform='translateX(30px)'; t.style.transition='all .3s'; setTimeout(()=>t.remove(),300); }, 4000);
}

/* ── Sidebar mobile toggle ─────────────────────────────────── */
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
  document.querySelector('.sidebar')?.classList.toggle('open');
});
