<?php
require_once 'db.php';

$categories = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<h2 class="text-center text-primary fw-bold mb-5 display-6">استعرض أقسامنا المميزة</h2>
<div class="row g-4">
  <?php foreach ($categories as $cat): ?>
     <div class="col-6 col-md-4">
      <a href="category.php?id=<?= $cat['id'] ?>" class="text-decoration-none">
        <div class="category-box p-3 h-100 text-center">
          <img src="uploads/<?= htmlspecialchars($cat['image']) ?>" class="img-fluid rounded mb-3" alt="<?= $cat['name'] ?>">
          <h5 class="fw-bold text-dark-emphasis"><?= $cat['name'] ?></h5>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();

include 'templates/layout.php';
?>
