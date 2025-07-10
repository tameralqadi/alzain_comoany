<?php
session_start();
require_once '../db.php';

// تحقق من تسجيل الدخول
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// جلب عدد المنتجات
$productCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();

// جلب عدد الأقسام
$categoryCount = $db->query("SELECT COUNT(*) FROM categories")->fetchColumn();

ob_start();
?>

<h2 class="text-center mb-4">لوحة تحكم المسؤول</h2>
<div class="row text-center">
  <div class="col-md-6 mb-3">
    <div class="p-4 bg-white shadow-sm rounded">
      <h4>عدد المنتجات</h4>
      <p class="display-5"><?= $productCount ?></p>
      <a href="add_product.php" class="btn btn-primary">➕ إضافة منتج جديد</a>
      <a href="manage_products.php" class="btn btn-secondary mt-2">📦 إدارة المنتجات</a>
    </div>
  </div>
  <div class="col-md-6 mb-3">
    <div class="p-4 bg-white shadow-sm rounded">
      <h4>عدد الأقسام</h4>
      <p class="display-5"><?= $categoryCount ?></p>
      <a href="add_category.php" class="btn btn-primary">➕ إضافة قسم جديد</a>
      <a href="manage_categories.php" class="btn btn-secondary mt-2">📁 إدارة الأقسام</a>
    </div>
  </div>
</div>

<div class="text-center mt-4">
  <a href="../logout.php" class="btn btn-danger">تسجيل خروج</a>
</div>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
