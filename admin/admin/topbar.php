<div class="topbar">
  <div class="topbar-left">
    <a href="../index.html">🏠 Website</a>
    &nbsp;/&nbsp;
    <?= ucfirst(str_replace(['.php','_'],['',' '], basename($_SERVER['PHP_SELF']))) ?>
  </div>
  <div class="topbar-right">
    <span class="topbar-user"><i class="fas fa-user-circle"></i><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
    <a href="logout.php" class="btn-secondary btn-sm"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</div>