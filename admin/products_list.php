<?php
session_start();
require_once '../db.php';
require_once '../functions.php';

$stmt = $db->query("SELECT products.id, products.name, price, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2 class="mb-4">📦 قائمة المنتجات</h2>
<table class="table table-bordered text-center align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>اسم المنتج</th>
      <th>القسم</th>
      <th>السعر</th>
      <th>إجراءات</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $product): ?>
      <tr>
        <td><?= $product['id'] ?></td>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><?= htmlspecialchars($product['category_name']) ?></td>
        <td><?= number_format($product['price'], 2) ?> شيكل</td>
        <td>
          <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">✏️ تعديل</a>
          <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️ حذف</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
