<?php
// ============================================
// Database Configuration
// Edit these values to match your server
// ============================================

define('DB_HOST',     'localhost');
define('DB_NAME',     'joginath_db');
define('DB_USER',     'root');        // ← change to your MySQL username
define('DB_PASS',     '');            // ← change to your MySQL password
define('DB_CHARSET',  'utf8mb4');

// Upload settings
define('UPLOAD_DIR',     __DIR__ . '/../uploads/gallery/');
define('UPLOAD_URL',     '../uploads/gallery/');
define('MAX_FILE_SIZE',  5 * 1024 * 1024);  // 5 MB
define('ALLOWED_TYPES',  ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
define('ALLOWED_EXTS',   ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Session name
define('SESSION_NAME', 'joginath_admin');

// ─────────────────────────────────────────
// PDO Connection
// ─────────────────────────────────────────
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('<div style="font-family:sans-serif;padding:30px;color:#c0392b;background:#fdecea;border-radius:8px;margin:40px auto;max-width:600px;">
                <strong>Database connection failed.</strong><br><br>
                Please check your <code>includes/config.php</code> settings.<br>
                Error: ' . htmlspecialchars($e->getMessage()) . '
            </div>');
        }
    }
    return $pdo;
}