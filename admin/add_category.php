<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    if (!$name) {
        $error = "الرجاء إدخال اسم القسم.";
    } else {
        // رفع صورة القسم
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $mime = $_FILES['image']['type'];

            $stmt = $db->prepare("INSERT INTO categories (name, image_data, mime_type) VALUES (?, ?, ?)");
            $stmt->execute([$name, $imageData, $mime]);
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "يرجى رفع صورة القسم.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>إضافة قسم جديد</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
  <h2 class="mb-4 text-center">إضافة قسم جديد</h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">اسم القسم</label>
      <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">صورة القسم</label>
      <input type="file" name="image" class="form-control" accept="image/*" required />
    </div>
    <button type="submit" class="btn btn-primary w-100">إضافة</button>
  </form>
</div>
</body>
</html>
