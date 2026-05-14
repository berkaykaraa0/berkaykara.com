<?php
$adminTitle = 'Messages';
require_once __DIR__ . '/includes/admin_header.php';
$messages = getContacts();
$unread   = array_filter($messages, fn($m) => !$m['is_read']);
?>

<div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap">
  <div class="stat-card" style="padding:1rem 1.5rem;flex:1;min-width:160px">
    <div class="stat-card-label">Total</div>
    <div class="stat-card-value" style="font-size:1.5rem"><?= count($messages) ?></div>
  </div>
  <div class="stat-card" style="padding:1rem 1.5rem;flex:1;min-width:160px">
    <div class="stat-card-label">Unread</div>
    <div class="stat-card-value" style="font-size:1.5rem;<?= count($unread)>0?'background:linear-gradient(135deg,#f87171,#fb923c);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text':'' ?>"><?= count($unread) ?></div>
  </div>
</div>

<div class="glass-card" style="padding:1.5rem">
  <div class="table-wrap">
    <table>
      <thead><tr><th>From</th><th>Email</th><th>Subject</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody id="messagesTable">
        <?php foreach ($messages as $m): ?>
        <tr id="msg-<?= $m['id'] ?>">
          <td style="font-weight:<?= $m['is_read']?'400':'600' ?>">
            <?php if (!$m['is_read']): ?><span class="unread-dot"></span><?php endif; ?>
            <?= htmlspecialchars($m['name']) ?>
          </td>
          <td style="font-size:.82rem;color:var(--muted)"><?= htmlspecialchars($m['email']) ?></td>
          <td style="font-size:.82rem"><?= htmlspecialchars(substr($m['subject']??'—',0,35)) ?></td>
          <td style="font-size:.75rem;color:var(--muted)"><?= date('M d, Y', strtotime($m['created_at'])) ?></td>
          <td><span class="badge <?= $m['is_read']?'':'success' ?>"><?= $m['is_read']?'Read':'New' ?></span></td>
          <td>
            <div style="display:flex;gap:.5rem">
              <button class="btn-outline btn-sm" onclick="viewMessage(<?= $m['id'] ?>, <?= json_encode($m) ?>)">View</button>
              <button class="btn-outline btn-sm" style="color:#f87171;border-color:rgba(248,113,113,.3)" onclick="deleteMessage(<?= $m['id'] ?>)">Delete</button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- VIEW MESSAGE MODAL -->
<div class="modal-overlay" id="msgModal">
  <div class="modal">
    <div class="modal-header">
      <h3>Message from <span id="msgFrom"></span></h3>
      <button class="modal-close" onclick="document.getElementById('msgModal').classList.remove('open')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div style="display:flex;flex-direction:column;gap:1rem">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
        <div class="info-card"><div class="info-card-label">Email</div><div class="info-card-value" id="msgEmail"></div></div>
        <div class="info-card"><div class="info-card-label">Subject</div><div class="info-card-value" id="msgSubject"></div></div>
      </div>
      <div class="glass-card" style="padding:1.25rem">
        <div class="info-card-label" style="margin-bottom:.75rem">Message</div>
        <p id="msgBody" style="color:var(--text);line-height:1.8;font-size:.9rem;white-space:pre-wrap"></p>
      </div>
      <div style="display:flex;justify-content:flex-end">
        <a id="msgReply" href="#" class="btn-primary btn-sm">Reply via Email ↗</a>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
