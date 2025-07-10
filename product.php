<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    $error_message = "المنتج غير موجود.";
}

ob_start();
?>

<div class="container my-5">
  <?php if (isset($error_message)): ?>
    <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
  <?php else: ?>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <h2 class="text-center text-primary fw-bold mb-4"><?= htmlspecialchars($product['name']) ?></h2>
        <img src="product_image.php?id=<?= $product['id'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded mb-4 mx-auto d-block" style="max-height: 400px; object-fit: contain;">
        <p class="text-muted mb-4" style="white-space: pre-wrap;"><?= htmlspecialchars($product['description']) ?></p>
        <h4 class="text-success mb-4 text-center"><?= number_format($product['price'], 2) ?> شيكل</h4>

        <form method="POST" action="cart.php?action=add&id=<?= $product['id'] ?>" class="d-flex justify-content-center align-items-center gap-2">
  <input type="number" name="quantity" value="1" min="1" class="form-control w-25" />
  <button type="submit" class="btn btn-warning">أضف إلى السلة</button>
</form>

      </div>
    </div>
  <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'templates/layout.php';
?>
