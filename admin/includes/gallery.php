<?php
// ── Gallery helpers ───────────────────────
require_once __DIR__ . '/config.php';

// Category labels
function categoryLabel(string $cat): string {
    return [
        'events'    => 'Events / कार्यक्रम',
        'religious' => 'Religious / धार्मिक',
        'meeting'   => 'Meetings / बैठक',
        'community' => 'Community / समाज',
        'awards'    => 'Awards / सम्मान',
    ][$cat] ?? ucfirst($cat);
}

// ── Upload image ──────────────────────────
function uploadImage(array $file): string|false {
    // Validate size
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new RuntimeException('File too large. Max 5 MB allowed.');
    }

    // Validate MIME by reading file bytes
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_TYPES, true)) {
        throw new RuntimeException('Invalid file type. Only JPG, PNG, WEBP, GIF allowed.');
    }

    // Validate extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTS, true)) {
        throw new RuntimeException('Invalid file extension.');
    }

    // Generate unique filename
    $filename = uniqid('img_', true) . '.' . $ext;
    $dest     = UPLOAD_DIR . $filename;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Failed to save the uploaded file.');
    }

    return $filename;
}

// ── Delete image file ─────────────────────
function deleteImageFile(string $filename): void {
    $path = UPLOAD_DIR . $filename;
    if (file_exists($path)) {
        unlink($path);
    }
}

// ── DB queries ────────────────────────────
function getAllImages(string $category = '', string $search = ''): array {
    $db  = getDB();
    $sql = 'SELECT * FROM gallery_images WHERE 1=1';
    $params = [];
    if ($category) {
        $sql .= ' AND category = ?';
        $params[] = $category;
    }
    if ($search) {
        $sql .= ' AND (title LIKE ? OR title_hindi LIKE ?)';
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $sql .= ' ORDER BY sort_order ASC, uploaded_at DESC';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getImageById(int $id): ?array {
    $db   = getDB();
    $stmt = $db->prepare('SELECT * FROM gallery_images WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function insertImage(array $data): int {
    $db   = getDB();
    $stmt = $db->prepare('INSERT INTO gallery_images
        (title, title_hindi, category, filename, description, is_active, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['title'],
        $data['title_hindi'] ?? '',
        $data['category'],
        $data['filename'],
        $data['description'] ?? '',
        $data['is_active']   ?? 1,
        $data['sort_order']  ?? 0,
    ]);
    return (int) $db->lastInsertId();
}

function updateImage(int $id, array $data): void {
    $db   = getDB();
    $stmt = $db->prepare('UPDATE gallery_images
        SET title=?, title_hindi=?, category=?, description=?, is_active=?, sort_order=?
        WHERE id=?');
    $stmt->execute([
        $data['title'],
        $data['title_hindi'] ?? '',
        $data['category'],
        $data['description'] ?? '',
        $data['is_active']   ?? 1,
        $data['sort_order']  ?? 0,
        $id,
    ]);
}

function deleteImage(int $id): void {
    $db   = getDB();
    $row  = getImageById($id);
    if ($row) {
        deleteImageFile($row['filename']);
        $db->prepare('DELETE FROM gallery_images WHERE id = ?')->execute([$id]);
    }
}

function toggleActive(int $id): void {
    $db = getDB();
    $db->prepare('UPDATE gallery_images SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
}

function countByCategory(): array {
    $db   = getDB();
    $stmt = $db->query('SELECT category, COUNT(*) as total FROM gallery_images GROUP BY category');
    $out  = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[$row['category']] = $row['total'];
    }
    return $out;
}