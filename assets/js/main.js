/* ============================================================
   Portfolio Main JavaScript
   ============================================================ */

'use strict';

// ── Theme ──────────────────────────────────────────────────
const Theme = {
  init() {
    const saved = localStorage.getItem('theme') || 'dark';
    this.apply(saved);
    document.getElementById('themeToggle')?.addEventListener('click', () => {
      const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
      this.apply(next);
    });
  },
  apply(theme) {
    document.documentElement.dataset.theme = theme;
    localStorage.setItem('theme', theme);
  }
};

// ── Loader ─────────────────────────────────────────────────
function hideLoader() {
  const el = document.getElementById('loader');
  if (el) { el.classList.add('hidden'); setTimeout(() => el.remove(), 600); }
}

// ── Toast notifications ────────────────────────────────────
function showToast(msg, type = 'success', duration = 3500) {
  const container = document.getElementById('toast-container');
  if (!container) return;
  const icon = type === 'success' ? '✓' : '✕';
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${icon}</span>${msg}`;
  container.appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(100%)'; setTimeout(() => toast.remove(), 400); }, duration);
}

// ── Navbar ─────────────────────────────────────────────────
function initNavbar() {
  const navbar   = document.getElementById('navbar');
  const ham      = document.getElementById('hamburger');
  const navLinks = document.getElementById('navLinks');

  window.addEventListener('scroll', () => {
    if (navbar) navbar.style.background = window.scrollY > 60 ? 'rgba(15,10,30,.95)' : '';
  });

  ham?.addEventListener('click', () => {
    navLinks?.classList.toggle('open');
    ham.classList.toggle('open');
  });

  // Active link on scroll
  const sections = document.querySelectorAll('section[id]');
  const links    = document.querySelectorAll('.nav-links a');
  window.addEventListener('scroll', () => {
    let cur = '';
    sections.forEach(s => { if (window.scrollY >= s.offsetTop - 220) cur = s.id; });
    links.forEach(l => {
      l.classList.toggle('active', l.getAttribute('href') === `#${cur}` || l.getAttribute('href')?.endsWith(`#${cur}`));
    });
  }, { passive: true });
}

// ── Scroll-to-top ──────────────────────────────────────────
function initScrollTop() {
  const btn = document.getElementById('scrollTop');
  if (!btn) return;
  window.addEventListener('scroll', () => btn.classList.toggle('visible', window.scrollY > 400), { passive: true });
  btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// ── Fade-up on scroll ──────────────────────────────────────
function initFadeUp() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade-up').forEach(el => obs.observe(el));
}

// ── Skill bars ─────────────────────────────────────────────
function initSkillBars() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const fill = e.target.querySelector('.skill-fill');
        if (fill) fill.style.width = fill.dataset.pct + '%';
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.3 });
  document.querySelectorAll('.skill-card').forEach(el => obs.observe(el));
}

// ── Animated counters ──────────────────────────────────────
function animateCounter(el) {
  const target = parseInt(el.dataset.target, 10);
  const dur    = 2000;
  const step   = 16;
  const inc    = target / (dur / step);
  let cur      = 0;
  const timer  = setInterval(() => {
    cur += inc;
    if (cur >= target) { cur = target; clearInterval(timer); }
    el.textContent = Math.floor(cur) + (el.dataset.suffix || '');
  }, step);
}
function initCounters() {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { animateCounter(e.target); obs.unobserve(e.target); } });
  }, { threshold: 0.5 });
  document.querySelectorAll('[data-target]').forEach(el => obs.observe(el));
}

// ── Typing effect ──────────────────────────────────────────
function initTyping() {
  const el = document.getElementById('typingText');
  if (!el) return;
  const words  = el.dataset.words?.split('|') || [];
  let wi = 0, ci = 0, deleting = false;
  function tick() {
    const word = words[wi % words.length];
    if (!deleting) {
      el.textContent = word.slice(0, ++ci);
      if (ci === word.length) { deleting = true; setTimeout(tick, 1800); return; }
    } else {
      el.textContent = word.slice(0, --ci);
      if (ci === 0) { deleting = false; wi++; }
    }
    setTimeout(tick, deleting ? 60 : 100);
  }
  tick();
}

// ── Projects AJAX ──────────────────────────────────────────
async function loadProjects(category = 'all') {
  const grid = document.getElementById('projectsGrid');
  if (!grid) return;

  grid.innerHTML = `<div class="projects-loading"><div class="loader-ring"></div><p>Loading projects…</p></div>`;

  try {
    const res  = await fetch(`api/projects.php?category=${category}`);
    const data = await res.json();

    if (!data.success || !data.projects.length) {
      grid.innerHTML = '<p style="color:var(--muted);padding:2rem">No projects found.</p>';
      return;
    }

    grid.innerHTML = data.projects.map(p => `
      <div class="project-card fade-up">
        <div class="project-thumb">
          ${p.image && p.image !== 'default.jpg'
            ? `<img src="uploads/${p.image}" alt="${p.title}" loading="lazy">`
            : `<div class="project-thumb-placeholder">💻</div>`}
          ${p.featured == 1 ? '<span class="featured-badge">Featured</span>' : ''}
        </div>
        <div class="project-body">
          <div class="project-tags">
            ${p.technologies.split(',').slice(0,3).map(t => `<span class="project-tag">${t.trim()}</span>`).join('')}
          </div>
          <div class="project-title">${p.title}</div>
          <div class="project-desc">${p.description}</div>
          <div class="project-links">
            ${p.github_url ? `<a href="${p.github_url}" target="_blank" rel="noopener" class="project-link">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77"/></svg> GitHub</a>` : ''}
            ${p.live_url ? `<a href="${p.live_url}" target="_blank" rel="noopener" class="project-link">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg> Live Demo</a>` : ''}
          </div>
        </div>
      </div>`).join('');

    initFadeUp();
  } catch (err) {
    grid.innerHTML = '<p style="color:var(--muted);padding:2rem">Failed to load projects.</p>';
  }
}

function initProjectFilter() {
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      loadProjects(btn.dataset.filter);
    });
  });
}

// ── Contact form ───────────────────────────────────────────
function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (!validateForm(form)) return;

    const btn = form.querySelector('[type="submit"]');
    const orig = btn.innerHTML;
    btn.innerHTML = 'Sending…'; btn.disabled = true;

    try {
      const res  = await fetch('api/contact.php', { method: 'POST', body: new FormData(form) });
      const data = await res.json();
      if (data.success) { showToast('Message sent! I\'ll get back to you soon.', 'success'); form.reset(); }
      else showToast(data.message || 'Something went wrong.', 'error');
    } catch { showToast('Network error. Please try again.', 'error'); }

    btn.innerHTML = orig; btn.disabled = false;
  });
}

function validateForm(form) {
  let valid = true;
  form.querySelectorAll('[required]').forEach(field => {
    const err = form.querySelector(`[data-for="${field.name}"]`);
    const empty = !field.value.trim();
    if (err) err.classList.toggle('visible', empty);
    if (empty) valid = false;
  });
  const email = form.querySelector('[type="email"]');
  if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    const err = form.querySelector('[data-for="email"]');
    if (err) { err.textContent = 'Please enter a valid email.'; err.classList.add('visible'); }
    valid = false;
  }
  return valid;
}

// ── Boot ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  Theme.init();
  initNavbar();
  initScrollTop();
  initFadeUp();
  initSkillBars();
  initCounters();
  initTyping();
  initProjectFilter();
  loadProjects();
  initContactForm();
  hideLoader();
});
