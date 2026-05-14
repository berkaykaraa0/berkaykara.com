<?php
require_once __DIR__ . '/includes/functions.php';
startSession();
$skills = getSkills();
include __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<div style="padding:0 2rem">
<section id="home" class="fade-up">
  <div class="hero-badge">Istanbul, Turkey &nbsp;|&nbsp; Full-Stack Developer</div>
  <h1>
    BERKAY<br>
    <span class="gradient-text">KARA</span><br>
  </h1>
  <p class="hero-sub">
    I am a <span id="typingText" class="typing-cursor"
      data-words="Full-Stack Developer|Software Engineer|PHP Developer|Problem Solver"></span>
    — building clean, performant apps from backend APIs to polished interfaces.
  </p>
  <div class="hero-actions">
    <a href="#projects" class="btn-primary">View Projects →</a>
    <a href="#contact" class="btn-outline">Get in Touch</a>
  </div>
  <div class="social-row">
    <a href="https://github.com/berkaykaraa0" target="_blank" rel="noopener" class="social-pill">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
      GitHub
    </a>
    <a href="mailto:berkaykr611@gmail.com" class="social-pill">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Email
    </a>
  </div>
  <div class="stats-row">
    <div class="stat-item">
      <div class="stat-num" data-target="5" data-suffix="+">0+</div>
      <div class="stat-label">Projects Completed</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" data-target="2" data-suffix="+ yrs">0</div>
      <div class="stat-label">Years Experience</div>
    </div>
    <div class="stat-item">
      <div class="stat-num" data-target="14" data-suffix="+">0+</div>
      <div class="stat-label">Technologies</div>
    </div>
  </div>
</section>
</div>

<!-- TICKER -->
<div class="ticker-wrap">
  <div class="ticker">
    <?php $techs = ['HTML5','CSS3','JavaScript','PHP 8','MySQL','React','Node.js','Docker','Git','Linux','Bootstrap','Python','REST API','GitHub']; ?>
    <?php foreach(array_merge($techs,$techs) as $t): ?>
      <span class="ticker-item"><?= $t ?></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ABOUT -->
<section id="about" class="fade-up">
  <div class="section-label">About Me</div>
  <div class="about-grid">
    <div>
      <h2>Someone who codes<br><span class="gradient-text">and ships.</span></h2>
      <p class="about-text">
        As a junior Software Engineering student at Haliç University, I develop solutions across a wide spectrum,
        ranging from low-level systems to modern web technologies. I am a software enthusiast dedicated not just
        to utilizing technology, but to developing algorithms that make it more efficient.
      </p>
      <div class="about-info-grid">
        <div class="info-card"><div class="info-card-label">Name</div><div class="info-card-value">Berkay Kara</div></div>
        <div class="info-card"><div class="info-card-label">Role</div><div class="info-card-value">Full-Stack Dev</div></div>
        <div class="info-card"><div class="info-card-label">University</div><div class="info-card-value">Haliç University</div></div>
        <div class="info-card"><div class="info-card-label">Degree</div><div class="info-card-value">Software Engineering</div></div>
        <div class="info-card"><div class="info-card-label">Location</div><div class="info-card-value">Istanbul, Turkey</div></div>
        <div class="info-card"><div class="info-card-label">Email</div><div class="info-card-value" style="font-size:.75rem">berkaykr611@gmail.com</div></div>
      </div>
      <div style="margin-top:1.5rem;display:flex;gap:.75rem;flex-wrap:wrap">
        <a href="#contact" class="btn-primary btn-sm">Get in Touch →</a>
        <a href="https://github.com/berkaykaraa0" target="_blank" class="btn-outline btn-sm">GitHub ↗</a>
      </div>
    </div>
    <div class="profile-box">
      <div class="profile-img-wrap">
        <div class="profile-initials">BK</div>
      </div>
      <div class="timeline" style="margin-top:2rem">
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <h4>Software Engineering</h4>
            <p>Haliç University — Bachelor's Degree</p>
            <div class="timeline-year">2022 – Present</div>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-dot"></div>
          <div class="timeline-content">
            <h4>Full-Stack Development</h4>
            <p>Self-taught &amp; personal projects</p>
            <div class="timeline-year">2021 – Present</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SKILLS -->
<section id="skills" class="fade-up">
  <div class="section-label">Skills</div>
  <h2>Technologies I <span class="gradient-text">work with</span></h2>
  <?php
  $categories = ['frontend'=>'Frontend','backend'=>'Backend','database'=>'Database','tools'=>'Tools'];
  foreach ($categories as $cat => $label):
    $catSkills = array_filter($skills, fn($s) => $s['category'] === $cat);
    if (empty($catSkills)) continue;
  ?>
  <h3 style="font-size:.8rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em;margin:2rem 0 1rem"><?= $label ?></h3>
  <div class="skills-grid">
    <?php foreach ($catSkills as $skill): ?>
    <div class="skill-card">
      <div class="skill-top">
        <div><div class="skill-name"><?= htmlspecialchars($skill['name']) ?></div></div>
        <div class="skill-pct"><?= $skill['proficiency'] ?>%</div>
      </div>
      <div class="skill-bar"><div class="skill-fill" data-pct="<?= $skill['proficiency'] ?>"></div></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endforeach; ?>
  <div class="tech-cloud" style="margin-top:3rem">
    <?php foreach ($skills as $s): ?>
      <span class="tech-pill"><?= htmlspecialchars($s['name']) ?></span>
    <?php endforeach; ?>
  </div>
</section>

<!-- PROJECTS -->
<section id="projects" class="fade-up">
  <div class="section-label">Projects</div>
  <h2>Selected <span class="gradient-text">work</span></h2>
  <div class="filter-bar">
    <button class="filter-btn active" data-filter="all">All</button>
    <button class="filter-btn" data-filter="web">Web</button>
    <button class="filter-btn" data-filter="backend">Backend</button>
    <button class="filter-btn" data-filter="fullstack">Full Stack</button>
  </div>
  <div class="projects-grid" id="projectsGrid">
    <div class="projects-loading"><div class="loader-ring"></div><p>Loading projects…</p></div>
  </div>
</section>

<!-- CONTACT -->
<section id="contact" class="fade-up">
  <div class="section-label">Contact</div>
  <h2>Let's build something<br><span class="gradient-text">together.</span></h2>
  <div class="contact-grid">
    <div>
      <div class="status-badge"><div class="status-dot"></div>Open to opportunities</div>
      <div class="contact-info">
        <div class="contact-item">
          <div class="contact-icon">✉️</div>
          <div><div class="contact-label">Email</div><div class="contact-value">berkaykr611@gmail.com</div></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">📍</div>
          <div><div class="contact-label">Location</div><div class="contact-value">Istanbul, Turkey</div></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">💻</div>
          <div><div class="contact-label">GitHub</div><div class="contact-value">github.com/berkaykaraa0</div></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">🎓</div>
          <div><div class="contact-label">University</div><div class="contact-value">Haliç University</div></div>
        </div>
      </div>
    </div>
    <form id="contactForm" class="contact-form" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label>Name *</label>
          <input type="text" name="name" placeholder="Your Full Name" required>
          <span class="field-error" data-for="name">Name is required.</span>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" name="email" placeholder="email@example.com" required>
          <span class="field-error" data-for="email">Valid email required.</span>
        </div>
      </div>
      <div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject" placeholder="Let's work together">
      </div>
      <div class="form-group">
        <label>Message *</label>
        <textarea name="message" placeholder="Tell me about your project…" required></textarea>
        <span class="field-error" data-for="message">Message is required.</span>
      </div>
      <button type="submit" class="btn-primary" style="align-self:flex-start">Send Message ↗</button>
    </form>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
