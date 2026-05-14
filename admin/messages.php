<?php
require_once __DIR__ . '/includes/admin_header.php';
$messages = getContacts(); 
?>

<div class="glass-panel">
  <div class="panel-header">
    <h3 class="panel-title">Inbox</h3>
  </div>
  <div class="premium-table-wrap">
    <table class="premium-table">
      <thead><tr><th>Sender</th><th>Subject</th><th>Date</th><th style="text-align:right">Actions</th></tr></thead>
      <tbody>
        <?php foreach($messages as $m): ?>
        <tr>
          <td style="font-weight:600; display:flex; align-items:center; gap:0.5rem">
            <?php if(!$m['is_read']): ?>
            <span id="dot_<?= $m['id'] ?>" style="width:8px;height:8px;border-radius:50%;background:#f87171;box-shadow:0 0 8px #f87171"></span>
            <?php endif; ?>
            <?= htmlspecialchars($m['name']) ?>
            <div style="font-size:0.75rem;color:var(--muted);font-weight:400;margin-left:0.5rem"><?= htmlspecialchars($m['email']) ?></div>
          </td>
          <td style="color:var(--muted)"><?= htmlspecialchars($m['subject'] ?: 'No Subject') ?></td>
          <td style="color:var(--muted)"><?= date('M d, Y', strtotime($m['created_at'])) ?></td>
          <td style="text-align:right">
            <?php if(!$m['is_read']): ?>
              <button id="btn_<?= $m['id'] ?>" class="btn-neon" onclick="markAsRead(<?= $m['id'] ?>)">Mark Read</button>
            <?php else: ?>
              <button class="btn-neon" style="opacity:0.5;cursor:default">Read</button>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
            <td colspan="4" style="padding: 1rem; background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05); font-size: 0.9rem; color: #cbd5e1;">
                <?= nl2br(htmlspecialchars($m['message'])) ?>
            </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.getElementById('pageTitle').innerText = 'Messages';
function markAsRead(id) {
    fetch('<?= SITE_URL ?>/api/admin_messages.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'mark_read', id: id })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const dot = document.getElementById('dot_'+id);
            if(dot) dot.remove();
            
            const btn = document.getElementById('btn_'+id);
            if(btn) {
                btn.innerText = 'Read';
                btn.onclick = null;
                btn.style.opacity = '0.5';
                btn.style.cursor = 'default';
            }
            showToast('Message marked as read.');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast('Network error', 'error'));
}
</script>

<?php include __DIR__ . '/includes/admin_footer.php'; ?>
