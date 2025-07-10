<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// جلب كل المنتجات مع بياناتها
$stmt = $db->query("SELECT id, name, price FROM products ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2 class="text-center mb-4">إدارة المنتجات</h2>

<table class="table table-bordered table-striped table-hover">
    <thead class="table-dark text-center">
        <tr>
            <th>صورة المنتج</th>
            <th>اسم المنتج</th>
            <th>السعر (شيكل)</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr class="text-center align-middle">
                <td style="width: 120px;">
                    <img src="../product_image.php?id=<?= $product['id'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 100px; max-height: 80px; object-fit: contain;">
                </td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= number_format($product['price'], 2) ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">تعديل</a>
                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">حذف</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="text-center mt-3">
    <a href="add_product.php" class="btn btn-success">➕ إضافة منتج جديد</a>
    <a href="dashboard.php" class="btn btn-secondary">⬅ العودة للوحة التحكم</a>
</div>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
