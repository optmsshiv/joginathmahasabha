<?php
// gallery-api.php — place this in your website root (same level as gallery.html)
// Returns active gallery images as JSON for the public gallery page

require_once __DIR__ . '/admin/includes/config.php';  // adjust path if needed

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$cat = $_GET['cat'] ?? '';
$db  = getDB();

$sql    = 'SELECT id, title, title_hindi, category, filename, description FROM gallery_images WHERE is_active=1';
$params = [];

if ($cat && in_array($cat, ['events','religious','meeting','community','awards'], true)) {
    $sql .= ' AND category = ?';
    $params[] = $cat;
}

$sql .= ' ORDER BY sort_order ASC, uploaded_at DESC';
$stmt = $db->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build response
$images = array_map(function($row) {
    return [
        'id'          => (int) $row['id'],
        'title'       => $row['title'],
        'title_hindi' => $row['title_hindi'],
        'category'    => $row['category'],
        'src'         => 'uploads/gallery/' . $row['filename'],
        'description' => $row['description'],
    ];
}, $rows);

echo json_encode(['success' => true, 'images' => $images]);