<?php
// ── Auth helpers ──────────────────────────
require_once __DIR__ . '/config.php';

function startAdminSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_start();
    }
}

function isLoggedIn(): bool {
    startAdminSession();
    return !empty($_SESSION['admin_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function loginAdmin(string $username, string $password): bool {
    $db   = getDB();
    $stmt = $db->prepare('SELECT id, password FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $row  = $stmt->fetch();
    if ($row && password_verify($password, $row['password'])) {
        startAdminSession();
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $row['id'];
        $_SESSION['admin_name'] = $username;
        return true;
    }
    return false;
}

function logoutAdmin(): void {
    startAdminSession();
    session_unset();
    session_destroy();
}

// ── Flash messages ────────────────────────
function setFlash(string $type, string $msg): void {
    startAdminSession();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
    startAdminSession();
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}