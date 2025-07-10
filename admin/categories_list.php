<?php
session_start();
require_once '../db.php';

$stmt = $db->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2 class="mb-4">📂 قائمة الأقسام</h2>
<table class="table table-bordered text-center align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>اسم القسم</th>
      <th>صورة</th>
      <th>إجراءات</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($categories as $cat): ?>
      <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= htmlspecialchars($cat['name']) ?></td>
        <td><img src="../uploads/<?= htmlspecialchars($cat['image']) ?>" width="60"></td>
        <td>
          <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-primary">✏️ تعديل</a>
          <a href="delete_category.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️ حذف</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$content = ob_get_clean();
include '../templates/layout.php';
?>
