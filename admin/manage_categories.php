<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// جلب كل الأقسام مع بياناتها
$stmt = $db->query("SELECT id, name FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2 class="text-center mb-4">إدارة الأقسام</h2>

<table class="table table-bordered table-striped table-hover">
    <thead class="table-dark text-center">
        <tr>
            <th>صورة القسم</th>
            <th>اسم القسم</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $category): ?>
            <tr class="text-center align-middle">
                <td style="width: 120px;">
                    <img src="../category_image.php?id=<?= $category['id'] ?>" alt="<?= htmlspecialchars($category['name']) ?>" style="max-width: 100px; max-height: 80px; object-fit: contain;">
                </td>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td>
                    <a href="edit_category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-primary">تعديل</a>
                    <a href="delete_category.php?id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا القسم؟');">حذف</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="text-center mt-3">
    <a href="add_category.php" class="btn btn-success">➕ إضافة قسم جديد</a>
    <a href="dashboard.php" class="btn btn-secondary">⬅ العودة للوحة التحكم</a>
</div>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
