// Universal App Logic for Premium Admin

// Toast Notification System
function showToast(msg, type='success') {
  const container = document.getElementById('toast-container');
  if(!container) return;
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = msg;
  container.appendChild(t);
  setTimeout(()=>t.remove(), 3000);
}

// Ensure theme matches standard
document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.querySelector('.theme-toggle');
    if(themeToggleBtn) {
        // Any specific UI effects on toggle
    }
});
