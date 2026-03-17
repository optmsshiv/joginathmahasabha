<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cur  = $_POST['current']  ?? '';
    $new  = $_POST['new']      ?? '';
    $conf = $_POST['confirm']  ?? '';

    $db   = getDB();
    $stmt = $db->prepare('SELECT password FROM admin_users WHERE id=?');
    $stmt->execute([$_SESSION['admin_id']]);
    $row  = $stmt->fetch();

    if (!$row || !password_verify($cur, $row['password'])) {
        $errors[] = 'Current password is incorrect.';
    } elseif (strlen($new) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    } elseif ($new !== $conf) {
        $errors[] = 'New passwords do not match.';
    } else {
        $db->prepare('UPDATE admin_users SET password=? WHERE id=?')
           ->execute([password_hash($new, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        setFlash('success', 'Password changed successfully.');
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Change Password — Gallery Admin</title>
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
      <div><h2>Change Password</h2><p class="page-sub">Update your admin password</p></div>
    </div>

    <?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <div class="form-card" style="max-width:500px;">
      <h3>Update Password</h3>
      <form method="POST">
        <div class="form-group" style="margin-bottom:16px">
          <label>Current Password</label>
          <input type="password" name="current" required>
        </div>
        <div class="form-group" style="margin-bottom:16px">
          <label>New Password <small style="color:var(--muted)">(min. 8 characters)</small></label>
          <input type="password" name="new" minlength="8" required>
        </div>
        <div class="form-group" style="margin-bottom:20px">
          <label>Confirm New Password</label>
          <input type="password" name="confirm" required>
        </div>
        <button type="submit" class="btn-primary"><i class="fas fa-key"></i> Change Password</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>