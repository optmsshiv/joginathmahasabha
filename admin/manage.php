<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/gallery.php';
requireLogin();

// Handle quick actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        if ($action === 'delete') {
            deleteImage($id);
            setFlash('success', 'Photo deleted successfully.');
        } elseif ($action === 'toggle') {
            toggleActive($id);
            setFlash('success', 'Visibility updated.');
        }
    }
    header('Location: manage.php?' . http_build_query(['cat'=>$_POST['cat']??'','q'=>$_POST['q']??'','page'=>$_POST['page']??1]));
    exit;
}

// Filters
$cat    = $_GET['cat']  ?? '';
$search = $_GET['q']    ?? '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$perPage = 12;

$all    = getAllImages($cat, $search);
$total  = count($all);
$pages  = (int) ceil($total / $perPage);
$items  = array_slice($all, ($page - 1) * $perPage, $perPage);

$flash  = getFlash();
$cats   = [''=>'All Categories','events'=>'Events','religious'=>'Religious',
           'meeting'=>'Meetings','community'=>'Community','awards'=>'Awards'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Manage Photos — Gallery Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style><?php include __DIR__ . '/admin/admin.css.php'; ?></style>
</head>
<body>
<?php include __DIR__ . '/admin/sidebar.php'; ?>
<div class="main">
  <?php include __DIR__ . '/admin/topbar.php'; ?>
  <div class="content">

    <div class="page-header">
      <div>
        <h2>Manage Photos</h2>
        <p class="page-sub"><?= $total ?> photo<?= $total !== 1 ? 's' : '' ?> found</p>
      </div>
      <a href="upload.php" class="btn-primary"><i class="fas fa-plus"></i> Upload Photo</a>
    </div>

    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <div class="section-card">

      <!-- Filters -->
      <form method="GET" class="table-filters">
        <select name="cat" onchange="this.form.submit()">
          <?php foreach ($cats as $v => $l): ?>
          <option value="<?= $v ?>" <?= $cat===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search by title...">
        <button type="submit" class="btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
        <?php if ($cat || $search): ?>
        <a href="manage.php" class="btn-secondary btn-sm"><i class="fas fa-times"></i> Clear</a>
        <?php endif; ?>
      </form>

      <!-- Table -->
      <?php if ($items): ?>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Title</th>
              <th>Category</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $img): ?>
            <tr>
              <td>
                <img src="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>"
                     alt="" class="table-img"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="table-img-fallback" style="display:none"><i class="fas fa-image"></i></div>
              </td>
              <td>
                <strong><?= htmlspecialchars($img['title']) ?></strong>
                <?php if ($img['title_hindi']): ?>
                <div style="font-size:12px;color:var(--muted);font-family:'Noto Sans Devanagari',sans-serif">
                  <?= htmlspecialchars($img['title_hindi']) ?>
                </div>
                <?php endif; ?>
              </td>
              <td><?= categoryLabel($img['category']) ?></td>
              <td>
                <span class="badge <?= $img['is_active'] ? 'badge-active' : 'badge-inactive' ?>">
                  <?= $img['is_active'] ? 'Visible' : 'Hidden' ?>
                </span>
              </td>
              <td style="white-space:nowrap;color:var(--muted);font-size:12.5px">
                <?= date('d M Y', strtotime($img['uploaded_at'])) ?>
              </td>
              <td>
                <div class="actions">
                  <a href="edit.php?id=<?= $img['id'] ?>" class="btn-secondary btn-sm" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>

                  <!-- Toggle visibility -->
                  <form method="POST" style="display:inline">
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id"     value="<?= $img['id'] ?>">
                    <input type="hidden" name="cat"    value="<?= htmlspecialchars($cat) ?>">
                    <input type="hidden" name="q"      value="<?= htmlspecialchars($search) ?>">
                    <input type="hidden" name="page"   value="<?= $page ?>">
                    <button type="submit" class="btn-secondary btn-sm"
                      title="<?= $img['is_active']?'Hide':'Show' ?>">
                      <i class="fas fa-<?= $img['is_active']?'eye-slash':'eye' ?>"></i>
                    </button>
                  </form>

                  <!-- Delete -->
                  <form method="POST" style="display:inline"
                    onsubmit="return confirm('Delete this photo permanently? This cannot be undone.')">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id"     value="<?= $img['id'] ?>">
                    <input type="hidden" name="cat"    value="<?= htmlspecialchars($cat) ?>">
                    <input type="hidden" name="q"      value="<?= htmlspecialchars($search) ?>">
                    <input type="hidden" name="page"   value="<?= $page ?>">
                    <button type="submit" class="btn-danger btn-sm" title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($pages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
        <a href="?cat=<?= urlencode($cat) ?>&q=<?= urlencode($search) ?>&page=<?= $page-1 ?>" class="page-link">‹ Prev</a>
        <?php endif; ?>
        <?php for ($p = 1; $p <= $pages; $p++): ?>
        <a href="?cat=<?= urlencode($cat) ?>&q=<?= urlencode($search) ?>&page=<?= $p ?>"
           class="page-link <?= $p===$page?'active':'' ?>"><?= $p ?></a>
        <?php endfor; ?>
        <?php if ($page < $pages): ?>
        <a href="?cat=<?= urlencode($cat) ?>&q=<?= urlencode($search) ?>&page=<?= $page+1 ?>" class="page-link">Next ›</a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-images"></i>
        <p>No photos found<?= ($cat||$search) ? ' for this filter' : '' ?>.</p>
        <a href="upload.php" class="btn-primary">Upload First Photo</a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>
</body>
</html>