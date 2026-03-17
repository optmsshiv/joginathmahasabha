<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/gallery.php';
requireLogin();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $title_hindi = trim($_POST['title_hindi'] ?? '');
    $category    = $_POST['category']         ?? '';
    $description = trim($_POST['description'] ?? '');
    $is_active   = isset($_POST['is_active']) ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order'] ?? 0);

    // Validation
    if (!$title)    $errors[] = 'Title (English) is required.';
    if (!$category) $errors[] = 'Category is required.';
    if (empty($_FILES['image']['name'])) $errors[] = 'Please select an image to upload.';

    if (!$errors) {
        try {
            $filename = uploadImage($_FILES['image']);
            insertImage([
                'title'       => $title,
                'title_hindi' => $title_hindi,
                'category'    => $category,
                'filename'    => $filename,
                'description' => $description,
                'is_active'   => $is_active,
                'sort_order'  => $sort_order,
            ]);
            setFlash('success', 'Photo uploaded successfully!');
            header('Location: manage.php');
            exit;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Upload Photo — Gallery Admin</title>
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
        <h2>Upload Photo</h2>
        <p class="page-sub">Add a new image to the gallery</p>
      </div>
      <a href="manage.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Manage Photos</a>
    </div>

    <?php foreach ($errors as $err): ?>
    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data" id="uploadForm">

      <div class="form-card">
        <h3>Image File</h3>
        <div class="form-grid">
          <!-- Drop Zone -->
          <div class="drop-zone full" id="dropZone">
            <input type="file" name="image" id="imageInput" accept="image/*" required>
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Drag &amp; drop an image here, or <strong style="color:var(--saffron)">click to browse</strong></p>
            <span>JPG, PNG, WEBP, GIF — Max 5 MB</span>
          </div>
          <!-- Preview -->
          <div id="previewWrap">
            <img id="previewImg" src="" alt="Preview">
            <button type="button" id="removePreview" title="Remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
      </div>

      <div class="form-card">
        <h3>Photo Details</h3>
        <div class="form-grid">

          <div class="form-group">
            <label>Title (English) <span style="color:#B91C1C">*</span></label>
            <input type="text" name="title" placeholder="e.g. Annual Mahasabha 2024"
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
          </div>

          <div class="form-group">
            <label>Title (Hindi)</label>
            <input type="text" name="title_hindi" placeholder="e.g. वार्षिक महासभा 2024"
                   value="<?= htmlspecialchars($_POST['title_hindi'] ?? '') ?>"
                   style="font-family:'Noto Sans Devanagari',sans-serif">
          </div>

          <div class="form-group">
            <label>Category <span style="color:#B91C1C">*</span></label>
            <select name="category" required>
              <option value="">— Select Category —</option>
              <?php
              $cats = ['events'=>'Events / कार्यक्रम','religious'=>'Religious / धार्मिक',
                       'meeting'=>'Meetings / बैठक','community'=>'Community / समाज','awards'=>'Awards / सम्मान'];
              foreach ($cats as $val => $label):
                $sel = (($_POST['category'] ?? '') === $val) ? 'selected' : '';
              ?>
              <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label>Sort Order <small style="color:var(--muted)">(lower = first)</small></label>
            <input type="number" name="sort_order" min="0" value="<?= (int)($_POST['sort_order'] ?? 0) ?>">
          </div>

          <div class="form-group full">
            <label>Description (optional)</label>
            <textarea name="description" placeholder="Brief description of the photo..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
          </div>

          <div class="form-group full">
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;text-transform:none;letter-spacing:0;font-size:14px;color:var(--text)">
              <input type="checkbox" name="is_active" value="1" <?= !isset($_POST['is_active']) || $_POST['is_active'] ? 'checked' : '' ?>
                     style="width:16px;height:16px;accent-color:var(--saffron);margin:0;padding:0;background:none;border:none;box-shadow:none;">
              Show this photo on the public gallery
            </label>
          </div>

          <div class="form-row-actions">
            <button type="submit" class="btn-primary"><i class="fas fa-upload"></i> Upload Photo</button>
            <a href="manage.php" class="btn-secondary">Cancel</a>
          </div>

        </div>
      </div>

    </form>
  </div>
</div>

<script>
const dropZone    = document.getElementById('dropZone');
const imageInput  = document.getElementById('imageInput');
const previewWrap = document.getElementById('previewWrap');
const previewImg  = document.getElementById('previewImg');
const removeBtn   = document.getElementById('removePreview');

dropZone.addEventListener('click', () => imageInput.click());

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => {
  e.preventDefault(); dropZone.classList.remove('dragover');
  const f = e.dataTransfer.files[0];
  if (f) setPreview(f);
});

imageInput.addEventListener('change', () => {
  if (imageInput.files[0]) setPreview(imageInput.files[0]);
});

function setPreview(file) {
  if (!file.type.startsWith('image/')) return;
  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    dropZone.style.display  = 'none';
    previewWrap.style.display = 'block';
  };
  reader.readAsDataURL(file);
  // Transfer to actual input
  const dt = new DataTransfer();
  dt.items.add(file);
  imageInput.files = dt.files;
}

removeBtn.addEventListener('click', () => {
  previewImg.src = '';
  imageInput.value = '';
  dropZone.style.display  = 'block';
  previewWrap.style.display = 'none';
});
</script>
</body>
</html>