/* ============================================================
   Admin Panel JavaScript
   ============================================================ */
'use strict';

const API_PROJECTS = window.location.origin + window.location.pathname.replace(/admin\/.*/, '') + 'api/admin_projects.php';
const API_MESSAGES = window.location.origin + window.location.pathname.replace(/admin\/.*/, '') + 'api/admin_messages.php';

// ── Project modal ──────────────────────────────────────────
function openModal(mode, data = null) {
  const modal  = document.getElementById('projectModal');
  const form   = document.getElementById('projectForm');
  const title  = document.getElementById('modalTitle');
  const action = document.getElementById('projectAction');

  form.reset();
  if (mode === 'create') {
    title.textContent  = 'Add Project';
    action.value       = 'create';
    document.getElementById('projectId').value = '';
  } else if (data) {
    title.textContent  = 'Edit Project';
    action.value       = 'update';
    document.getElementById('projectId').value = data.id;
    document.getElementById('pTitle').value    = data.title;
    document.getElementById('pDesc').value     = data.description;
    document.getElementById('pTech').value     = data.technologies;
    document.getElementById('pCategory').value = data.category;
    document.getElementById('pGithub').value   = data.github_url || '';
    document.getElementById('pLive').value     = data.live_url || '';
    document.getElementById('pOrder').value    = data.sort_order;
    document.getElementById('pStatus').value   = data.status;
    document.getElementById('pFeatured').checked = data.featured == 1;
  }
  modal.classList.add('open');
}

function closeModal() { document.getElementById('projectModal').classList.remove('open'); }

async function editProject(id) {
  try {
    const res  = await fetch(`${API_PROJECTS}?action=get&id=${id}`);
    const data = await res.json();
    if (data.success) openModal('edit', data.project);
    else showToast('Failed to load project.', 'error');
  } catch { showToast('Network error.', 'error'); }
}

function deleteProject(id, name) {
  document.getElementById('deleteProjectName').textContent = name;
  document.getElementById('deleteModal').classList.add('open');
  document.getElementById('confirmDelete').onclick = async () => {
    const fd = new FormData();
    fd.append('action', 'delete'); fd.append('id', id);
    const res  = await fetch(API_PROJECTS, { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      document.querySelector(`tr[data-id="${id}"]`)?.remove();
      showToast('Project deleted.', 'success');
    } else showToast(data.message, 'error');
    document.getElementById('deleteModal').classList.remove('open');
  };
}

document.getElementById('projectForm')?.addEventListener('submit', async e => {
  e.preventDefault();
  const btn  = document.getElementById('modalSubmit');
  btn.textContent = 'Saving…'; btn.disabled = true;
  try {
    const res  = await fetch(API_PROJECTS, { method: 'POST', body: new FormData(e.target) });
    const data = await res.json();
    if (data.success) { showToast(data.message, 'success'); closeModal(); setTimeout(() => location.reload(), 800); }
    else showToast(data.message, 'error');
  } catch { showToast('Network error.', 'error'); }
  btn.textContent = 'Save Project'; btn.disabled = false;
});

// ── Messages ───────────────────────────────────────────────
function viewMessage(id, msg) {
  document.getElementById('msgFrom').textContent    = msg.name;
  document.getElementById('msgEmail').textContent   = msg.email;
  document.getElementById('msgSubject').textContent = msg.subject || '—';
  document.getElementById('msgBody').textContent    = msg.message;
  document.getElementById('msgReply').href          = `mailto:${msg.email}?subject=Re: ${encodeURIComponent(msg.subject || '')}`;
  document.getElementById('msgModal').classList.add('open');

  if (!msg.is_read) {
    const fd = new FormData(); fd.append('action', 'read'); fd.append('id', id);
    fetch(API_MESSAGES, { method: 'POST', body: fd });
    const row = document.getElementById(`msg-${id}`);
    if (row) {
      row.querySelector('.unread-dot')?.remove();
      row.querySelector('td')?.style?.setProperty('font-weight', '400');
      const badge = row.querySelector('.badge.success');
      if (badge) { badge.textContent = 'Read'; badge.classList.remove('success'); }
    }
  }
}

async function deleteMessage(id) {
  if (!confirm('Delete this message?')) return;
  const fd = new FormData(); fd.append('action','delete'); fd.append('id', id);
  const res  = await fetch(API_MESSAGES, { method:'POST', body:fd });
  const data = await res.json();
  if (data.success) { document.getElementById(`msg-${id}`)?.remove(); showToast('Message deleted.','success'); }
  else showToast(data.message, 'error');
}

// Close modals on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('open'); });
});
