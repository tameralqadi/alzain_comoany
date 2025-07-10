<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// جلب معرف القسم من الرابط
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("معرف القسم غير صالح.");
}

// جلب بيانات القسم الحالية
$stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("القسم غير موجود.");
}

// معالجة نموذج التعديل عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "الرجاء تعبئة اسم القسم.";
    } else {
        // إذا تم رفع صورة جديدة
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageType = mime_content_type($_FILES['image']['tmp_name']);

            $updateStmt = $db->prepare("UPDATE categories SET name = ?, image_data = ?, mime_type = ? WHERE id = ?");
            $updated = $updateStmt->execute([$name, $imageData, $imageType, $id]);
        } else {
            // بدون تحديث الصورة
            $updateStmt = $db->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $updated = $updateStmt->execute([$name, $id]);
        }

        if ($updated) {
            header("Location: manage_categories.php?msg=تم تعديل القسم بنجاح");
            exit;
        } else {
            $error = "حدث خطأ أثناء تحديث القسم.";
        }
    }
}

ob_start();
?>

<h2 class="text-center mb-4">تعديل القسم</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="mx-auto" style="max-width: 600px;">
    <div class="mb-3">
        <label for="name" class="form-label">اسم القسم</label>
        <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($category['name']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">الصورة الحالية</label><br>
        <img src="../uploads/<?= htmlspecialchars($cat['image']) ?>" alt="صورة القسم" style="max-width: 200px; max-height: 150px; object-fit: contain;">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">رفع صورة جديدة (اختياري)</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">تحديث القسم</button>
        <a href="manage_categories.php" class="btn btn-secondary">إلغاء</a>
    </div>
</form>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
