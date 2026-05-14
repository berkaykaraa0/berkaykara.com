<?php
require_once __DIR__ . '/../config/database.php';

// ── Session ────────────────────────────────────────────────
function startSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
            session_start();
        } else {
            session_set_cookie_params([
                'lifetime' => 3600,
                'path'     => '/',
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }
    }
}

function isLoggedIn(): bool {
    startSession();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/login.php');
        exit;
    }
}

// ── Sanitisation ───────────────────────────────────────────
function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// ── JSON response helper ───────────────────────────────────
function jsonResponse(bool $success, string $message, array $data = []): never {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

// ── Projects ───────────────────────────────────────────────
function getProjects(?string $category = null, bool $activeOnly = true): array {
    $db  = Database::connect();
    $sql = "SELECT * FROM projects WHERE 1=1";
    $params = [];

    if ($activeOnly) { $sql .= " AND status = 'active'"; }
    if ($category && $category !== 'all') {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }
    $sql .= " ORDER BY featured DESC, sort_order ASC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getProjectById(int $id): array|false {
    $db   = Database::connect();
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function createProject(array $data): int {
    $db   = Database::connect();
    $stmt = $db->prepare("INSERT INTO projects (title, description, long_description, technologies, category, image, github_url, live_url, featured, sort_order, status)
                          VALUES (:title,:desc,:ldesc,:tech,:cat,:img,:gh,:live,:feat,:order,:status)");
    $stmt->execute([
        ':title'  => $data['title'],
        ':desc'   => $data['description'],
        ':ldesc'  => $data['long_description'] ?? '',
        ':tech'   => $data['technologies'],
        ':cat'    => $data['category'],
        ':img'    => $data['image'] ?? 'default.jpg',
        ':gh'     => $data['github_url'] ?? null,
        ':live'   => $data['live_url'] ?? null,
        ':feat'   => $data['featured'] ?? 0,
        ':order'  => $data['sort_order'] ?? 0,
        ':status' => $data['status'] ?? 'active',
    ]);
    return (int)$db->lastInsertId();
}

function updateProject(int $id, array $data): bool {
    $db   = Database::connect();
    $stmt = $db->prepare("UPDATE projects SET title=:title, description=:desc, long_description=:ldesc,
                          technologies=:tech, category=:cat, image=:img, github_url=:gh, live_url=:live,
                          featured=:feat, sort_order=:order, status=:status WHERE id=:id");
    return $stmt->execute([
        ':title'  => $data['title'],
        ':desc'   => $data['description'],
        ':ldesc'  => $data['long_description'] ?? '',
        ':tech'   => $data['technologies'],
        ':cat'    => $data['category'],
        ':img'    => $data['image'] ?? 'default.jpg',
        ':gh'     => $data['github_url'] ?? null,
        ':live'   => $data['live_url'] ?? null,
        ':feat'   => $data['featured'] ?? 0,
        ':order'  => $data['sort_order'] ?? 0,
        ':status' => $data['status'] ?? 'active',
        ':id'     => $id,
    ]);
}

function deleteProject(int $id): bool {
    $db   = Database::connect();
    $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
    return $stmt->execute([':id' => $id]);
}

// ── Contacts ───────────────────────────────────────────────
function saveContact(array $data): bool {
    $db   = Database::connect();
    $stmt = $db->prepare("INSERT INTO contacts (name, email, subject, message, ip_address)
                          VALUES (:name,:email,:subject,:message,:ip)");
    return $stmt->execute([
        ':name'    => $data['name'],
        ':email'   => $data['email'],
        ':subject' => $data['subject'] ?? '',
        ':message' => $data['message'],
        ':ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);
}

function getContacts(bool $unreadOnly = false): array {
    $db  = Database::connect();
    $sql = "SELECT * FROM contacts" . ($unreadOnly ? " WHERE is_read=0" : "") . " ORDER BY created_at DESC";
    return $db->query($sql)->fetchAll();
}

function markContactRead(int $id): bool {
    $db   = Database::connect();
    $stmt = $db->prepare("UPDATE contacts SET is_read=1 WHERE id=:id");
    return $stmt->execute([':id' => $id]);
}

function deleteContact(int $id): bool {
    $db   = Database::connect();
    $stmt = $db->prepare("DELETE FROM contacts WHERE id=:id");
    return $stmt->execute([':id' => $id]);
}

// ── Skills ─────────────────────────────────────────────────
function getSkills(?string $category = null): array {
    $db  = Database::connect();
    $sql = "SELECT * FROM skills WHERE status='active'";
    $params = [];
    if ($category) { $sql .= " AND category=:cat"; $params[':cat'] = $category; }
    $sql .= " ORDER BY sort_order ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// ── Stats ──────────────────────────────────────────────────
function getDashboardStats(): array {
    $db = Database::connect();
    return [
        'projects'       => (int)$db->query("SELECT COUNT(*) FROM projects WHERE status='active'")->fetchColumn(),
        'messages'       => (int)$db->query("SELECT COUNT(*) FROM contacts")->fetchColumn(),
        'unread'         => (int)$db->query("SELECT COUNT(*) FROM contacts WHERE is_read=0")->fetchColumn(),
        'featured'       => (int)$db->query("SELECT COUNT(*) FROM projects WHERE featured=1")->fetchColumn(),
    ];
}

// ── Image upload ───────────────────────────────────────────
function handleImageUpload(array $file): string {
    if ($file['error'] !== UPLOAD_ERR_OK) return 'default.jpg';
    if ($file['size'] > MAX_FILE_SIZE) return 'default.jpg';

    $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
    if (!in_array($file['type'], $allowed)) return 'default.jpg';

    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('proj_', true) . '.' . $ext;
    $dest     = UPLOAD_DIR . $filename;

    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    return move_uploaded_file($file['tmp_name'], $dest) ? $filename : 'default.jpg';
}
