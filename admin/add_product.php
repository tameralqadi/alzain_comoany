<?php
require_once '../db.php';
require_once '../functions.php';

$categories = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $cat_id = $_POST['category_id'];

    $imageData = file_get_contents($_FILES['image']['tmp_name']);
    $mime = $_FILES['image']['type'];

    $stmt = $db->prepare("INSERT INTO products (name, description, price, category_id, image_data, mime_type)
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $price, $cat_id, $imageData, $mime]);

    header("Location: dashboard.php"); // عدل حسب مكان لوحة تحكم المسؤول
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>إضافة منتج</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="text-center mb-4">إضافة منتج جديد</h2>
  <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
    <div class="mb-3">
      <label class="form-label">اسم المنتج</label>
      <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">الوصف</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">السعر</label>
      <input type="number" name="price" class="form-control" required step="0.01" />
    </div>
    <div class="mb-3">
      <label class="form-label">القسم</label>
      <select name="category_id" class="form-select" required>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">الصورة</label>
      <input type="file" name="image" accept="image/*" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">إضافة المنتج</button>
  </form>
</div>
</body>
</html>
