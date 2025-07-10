<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// جلب معرف المنتج من الرابط
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("معرف المنتج غير صالح.");
}

// جلب بيانات المنتج الحالية
$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("المنتج غير موجود.");
}

// جلب الأقسام لاستخدامها في اختيار القسم
$categoriesStmt = $db->query("SELECT id, name FROM categories ORDER BY name");
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// معالجة نموذج التعديل عند الإرسال
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);

    if (empty($name) || $price <= 0 || $category_id <= 0) {
        $error = "الرجاء تعبئة الحقول بشكل صحيح.";
    } else {
        // إذا تم رفع صورة جديدة
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageType = mime_content_type($_FILES['image']['tmp_name']);

            $updateStmt = $db->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image_data = ?, mime_type = ? WHERE id = ?");
            $updated = $updateStmt->execute([$name, $description, $price, $category_id, $imageData, $imageType, $id]);
        } else {
            // بدون تحديث الصورة
            $updateStmt = $db->prepare("UPDATE products SET name = ?, description = ?, price = ?, category_id = ? WHERE id = ?");
            $updated = $updateStmt->execute([$name, $description, $price, $category_id, $id]);
        }

        if ($updated) {
            header("Location: manage_products.php?msg=تم تعديل المنتج بنجاح");
            exit;
        } else {
            $error = "حدث خطأ أثناء تحديث المنتج.";
        }
    }
}

ob_start();
?>

<h2 class="text-center mb-4">تعديل المنتج</h2>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="mx-auto" style="max-width: 600px;">
    <div class="mb-3">
        <label for="name" class="form-label">اسم المنتج</label>
        <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">الوصف</label>
        <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">السعر (شيكل)</label>
        <input type="number" step="0.01" id="price" name="price" class="form-control" required value="<?= htmlspecialchars($product['price']) ?>">
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">القسم</label>
        <select id="category_id" name="category_id" class="form-select" required>
            <option value="">-- اختر القسم --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">الصورة الحالية</label><br>
        <img src="../product_image.php?id=<?= $product['id'] ?>" alt="صورة المنتج" style="max-width: 200px; max-height: 150px; object-fit: contain;">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">رفع صورة جديدة (اختياري)</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">تحديث المنتج</button>
        <a href="manage_products.php" class="btn btn-secondary">إلغاء</a>
    </div>
</form>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
