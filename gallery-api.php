<?php
// ── Path to your admin config ──────────────
require_once __DIR__ . '/admin/includes/config.php';

// ── CORS + JSON headers ────────────────────
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// ── Only GET requests allowed ──────────────
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// ── Sanitize category filter ───────────────
$allowed = ['events', 'religious', 'meeting', 'community', 'awards'];
$cat     = isset($_GET['cat']) && in_array($_GET['cat'], $allowed, true)
           ? $_GET['cat']
           : '';

try {
    $db  = getDB();
    $sql = 'SELECT id, title, title_hindi, category, filename, description
            FROM gallery_images
            WHERE is_active = 1';
    $params = [];

    if ($cat) {
        $sql     .= ' AND category = ?';
        $params[] = $cat;
    }

    $sql .= ' ORDER BY sort_order ASC, uploaded_at DESC';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build image list with correct public URL path
    $images = array_map(function ($row) {
        return [
            'id'          => (int) $row['id'],
            'title'       => $row['title'],
            'title_hindi' => $row['title_hindi'],
            'category'    => $row['category'],
            'description' => $row['description'],
            // Public URL path — adjust if your admin folder is named differently
            'src'         => 'admin/uploads/gallery/' . $row['filename'],
        ];
    }, $rows);

    echo json_encode([
        'success' => true,
        'total'   => count($images),
        'images'  => $images,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
    ]);
}