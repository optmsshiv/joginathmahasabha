<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/gallery.php';
requireLogin();

$id  = (int)($_GET['id'] ?? 0);
$img = $id ? getImageById($id) : null;

if (!$img) {
    setFlash('error', 'Photo not found.');
    header('Location: manage.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $title_hindi = trim($_POST['title_hindi'] ?? '');
    $category    = $_POST['category']         ?? '';
    $description = trim($_POST['description'] ?? '');
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order'] ?? 0);

    if (!$title)    $errors[] = 'Title is required.';
    if (!$category) $errors[] = 'Category is required.';

    // Optional new image
    if (!empty($_FILES['image']['name'])) {
        try {
            $newFilename = uploadImage($_FILES['image']);
            deleteImageFile($img['filename']);
            $img['filename'] = $newFilename;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }

    if (!$errors) {
        updateImage($id, [
            'title'       => $title,
            'title_hindi' => $title_hindi,
            'category'    => $category,
            'filename'    => $img['filename'],
            'description' => $description,
            'is_active'   => $is_active,
            'sort_order'  => $sort_order,
        ]);
        setFlash('success', 'Photo updated successfully!');
        header('Location: manage.php');
        exit;
    }
}

$cats = ['events'=>'Events / कार्यक्रम','religious'=>'Religious / धार्मिक',
         'meeting'=>'Meetings / बैठक','community'=>'Community / समाज','awards'=>'Awards / सम्मान'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Edit Photo — Gallery Admin</title>
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
        <h2>Edit Photo</h2>
        <p class="page-sub">Update details or replace image</p>
      </div>
      <a href="manage.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">

      <!-- Current image -->
      <div class="form-card">
        <h3>Current Image</h3>
        <div style="display:flex;gap:20px;align-items:flex-start;flex-wrap:wrap;">
          <img src="uploads/gallery/<?= htmlspecialchars($img['filename']) ?>"
               alt="" style="max-width:220px;border-radius:12px;border:1px solid var(--border);"
               onerror="this.style.display='none'">
          <div style="flex:1;min-width:220px;">
            <p style="font-size:13px;color:var(--muted);margin-bottom:12px;">
              To replace this image, select a new file below. Leave blank to keep the current image.
            </p>
            <div class="form-group">
              <label>Replace Image (optional)</label>
              <input type="file" name="image" accept="image/*"
                     style="padding:9px 14px;background:var(--warm);">
            </div>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h3>Photo Details</h3>
        <div class="form-grid">

          <div class="form-group">
            <label>Title (English) *</label>
            <input type="text" name="title" required
                   value="<?= htmlspecialchars($_POST['title'] ?? $img['title']) ?>">
          </div>

          <div class="form-group">
            <label>Title (Hindi)</label>
            <input type="text" name="title_hindi"
                   value="<?= htmlspecialchars($_POST['title_hindi'] ?? $img['title_hindi']) ?>"
                   style="font-family:'Noto Sans Devanagari',sans-serif">
          </div>

          <div class="form-group">
            <label>Category *</label>
            <select name="category" required>
              <?php foreach ($cats as $val => $label):
                $cur = $_POST['category'] ?? $img['category'];
                $sel = $cur === $val ? 'selected' : '';
              ?>
              <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Sort Order</label>
            <input type="number" name="sort_order" min="0"
                   value="<?= (int)($_POST['sort_order'] ?? $img['sort_order']) ?>">
          </div>

          <div class="form-group full">
            <label>Description</label>
            <textarea name="description"><?= htmlspecialchars($_POST['description'] ?? $img['description']) ?></textarea>
          </div>

          <div class="form-group full">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;text-transform:none;letter-spacing:0;font-size:14px;color:var(--text)">
              <input type="checkbox" name="is_active" value="1"
                     <?= (isset($_POST['is_active']) ? $_POST['is_active'] : $img['is_active']) ? 'checked' : '' ?>
                     style="width:16px;height:16px;accent-color:var(--saffron);margin:0;padding:0;background:none;border:none;box-shadow:none;">
              Show this photo on the public gallery
            </label>
          </div>

          <div class="form-row-actions">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            <a href="manage.php" class="btn-secondary">Cancel</a>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>
</body>
</html>