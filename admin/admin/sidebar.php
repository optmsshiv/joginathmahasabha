<?php
$current = basename($_SERVER['PHP_SELF']);
function isActive(string $page, string $current): string {
    return $page === $current ? 'active' : '';
}
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">🪔</div>
    <h2>Gallery Admin</h2>
    <p>Jogi/Nath MahaSabha</p>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a href="dashboard.php" class="<?= isActive('dashboard.php',$current) ?>">
      <i class="fas fa-th-large"></i> Dashboard
    </a>
    <a href="upload.php" class="<?= isActive('upload.php',$current) ?>">
      <i class="fas fa-cloud-upload-alt"></i> Upload Photo
    </a>
    <a href="manage.php" class="<?= isActive('manage.php',$current) ?>">
      <i class="fas fa-images"></i> Manage Photos
    </a>

    <div class="nav-section-label">Account</div>
    <a href="change_password.php" class="<?= isActive('change_password.php',$current) ?>">
      <i class="fas fa-key"></i> Change Password
    </a>
    <a href="https://joginathmahasabha.in/gallery.html" target="_blank">
      <i class="fas fa-external-link-alt"></i> View Gallery
    </a>
  </nav>

  <div class="sidebar-footer">
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</aside>