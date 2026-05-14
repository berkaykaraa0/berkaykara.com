<?php
// ============================================================
// Database Configuration — InfinityFree Production
// ============================================================
// Dynamic Site URL Detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', $protocol . $host);

if (strpos($host, 'localhost') !== false || $host === '127.0.0.1') {
    define('DB_HOST', '127.0.0.1;port=8889');
    define('DB_NAME', 'portfolio_db');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
} else {
    // InfinityFree Production Settings
    define('DB_HOST', 'sql211.infinityfree.com');
    define('DB_NAME', 'if0_41916564_portfolio');
    define('DB_USER', 'if0_41916564');
    define('DB_PASS', 'saniUboEfxt');
}

define('DB_CHARSET', 'utf8mb4');
define('SITE_NAME', 'Berkay Kara');
define('SITE_TITLE', 'Berkay Kara — Full-Stack Developer');
define('SITE_EMAIL', 'berkaykr611@gmail.com');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

class Database {
    private static ?PDO $instance = null;

    public static function connect(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
            }
        }
        return self::$instance;
    }
}
