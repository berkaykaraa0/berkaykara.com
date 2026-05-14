<?php
require_once __DIR__ . '/includes/functions.php';
startSession();
if (isLoggedIn()) { header('Location: ' . SITE_URL . '/admin/index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $db   = Database::connect();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :u OR email = :e LIMIT 1");
        $stmt->execute([':u' => $username, ':e' => $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: ' . SITE_URL . '/admin/index.php');
            exit;
        }
        $error = 'Invalid username or password.';
    } else { $error = 'Please fill in all fields.'; }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
<script>
  const t = localStorage.getItem('theme') || 'dark';
  document.documentElement.dataset.theme = t;
</script>
<div id="toast-container"></div>
<div class="login-wrap">
  <div class="login-card">
    <a href="<?= SITE_URL ?>" class="logo" style="display:inline-block;margin-bottom:1.5rem"><?= substr(SITE_NAME,0,2) ?>.</a>
    <h1>Admin Login</h1>
    <p>Access the dashboard to manage your portfolio.</p>
    <div class="login-divider"></div>
    <?php if ($error): ?>
      <div class="toast error" style="margin-bottom:1rem;animation:none"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST" style="display:flex;flex-direction:column;gap:1rem">
      <div class="form-group">
        <label>Username or Email</label>
        <input type="text" name="username" placeholder="admin" required autocomplete="username">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn-primary" style="margin-top:.5rem">Sign In →</button>
    </form>
    <p style="margin-top:1.5rem;font-size:.78rem;color:var(--muted);text-align:center">
      Default: <strong>admin</strong> / <strong>Admin@123</strong> — change after first login.
    </p>
  </div>
</div>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body>
</html>
