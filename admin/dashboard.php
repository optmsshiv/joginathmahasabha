<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/gallery.php';
requireLogin();

$counts  = countByCategory();
$total   = array_sum($counts);
$flash   = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Dashboard — Gallery Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
<?php include __DIR__ . '/admin/admin.css.php'; ?>
</style>
</head>
<body>
<?php include __DIR__ . '/admin/sidebar.php'; ?>

<div class="main">
  <?php include __DIR__ . '/admin/topbar.php'; ?>

  <div class="content">
    <?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?>">
      <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>"></i>
      <?= htmlspecialchars($flash['msg']) ?>
    </div>
    <?php endif; ?>

    <div class="page-header">
      <div>
        <h2>Dashboard</h2>
        <p class="page-sub">Gallery overview &amp; quick actions</p>
      </div>
      <a href="upload.php" class="btn-primary"><i class="fas fa-plus"></i> Upload Photo</a>
    </div>

    <!-- Stat Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-images"></i></div>
        <div><strong><?= $total ?></strong><span>Total Photos</span></div>
      </div>
      <?php
      $cats = ['events','religious','meeting','community','awards'];
      $icons = ['calendar-alt','om','users','hands-helping','award'];
      foreach ($cats as $i => $cat): ?>
      <div class="stat-card">
        <div class="stat-icon brown"><i class="fas fa-<?= $icons[$i] ?>"></i></div>
        <div>
          <strong><?= $counts[$cat] ?? 0 ?></strong>
          <span><?= categoryLabel($cat) ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Recent uploads -->
    <div class="section-card">
      <div class="section-card-head">
        <h3>Recent Uploads</h3>
        <a href="manage.php">View All →</a>
      </div>
      <?php
      $recent = getAllImages();
      $recent = array_slice($recent, 0, 6);
      ?>
      <?php if ($recent): ?>
      <div class="thumb-grid">
        <?php foreach ($recent as $img): ?>
        <div class="thumb-item">
          <div class="thumb-wrap">
            <img src="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>"
                 alt="<?= htmlspecialchars($img['title']) ?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="thumb-fallback" style="display:none"><i class="fas fa-image"></i></div>
            <?php if (!$img['is_active']): ?>
            <span class="thumb-badge inactive">Hidden</span>
            <?php endif; ?>
          </div>
          <p class="thumb-title"><?= htmlspecialchars($img['title']) ?></p>
          <p class="thumb-cat"><?= categoryLabel($img['category']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-images"></i>
        <p>No images uploaded yet.</p>
        <a href="upload.php" class="btn-primary">Upload First Photo</a>
      </div>
      <?php endif; ?>
    </div>

  </div><!-- /content -->
</div><!-- /main -->
</body>
</html>