<?php
require_once 'db.php';
require_once 'functions.php';
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$categoryStmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
$categoryStmt->execute([$category_id]);
$category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("القسم غير موجود.");
}

$productsStmt = $db->prepare("SELECT * FROM products WHERE category_id = ?");
$productsStmt->execute([$category_id]);
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>
<h2 class="text-center text-primary fw-bold mb-5 display-6">
    منتجات قسم: <?= htmlspecialchars($category['name']) ?>
</h2>

<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-6 col-md-4">
            <div class="category-box p-3 h-100 text-center" >
                <a href="product.php?id=<?= $product['id'] ?>">
                    <img src="product_image.php?id=<?= $product['id'] ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($product['name']) ?>" />
                </a>
                <h5 class="fw-bold text-dark-emphasis"><?= htmlspecialchars($product['name']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($product['description']) ?></p>
                <p class="text-primary fw-bold"><?= number_format($product['price'], 2) ?> شيكل</p>
                
                <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                    <label class="form-label mb-0">الكمية:</label>
                    <input type="number" min="1" value="1" 
                           class="form-control form-control-sm text-center quantity-input" 
                           data-id="<?= $product['id'] ?>" style="width: 70px;">
                </div>
                
                <button class="btn btn-warning add-to-cart" data-id="<?= $product['id'] ?>">➕ أضف إلى السلة</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- ✅ إشعار -->
<div id="toast" class="position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded shadow" style="display:none; z-index:9999;">
  ✅ تم إضافة المنتج إلى السلة!
</div>

<!-- ✅ سكريبت AJAX -->
<script>
document.querySelectorAll('.add-to-cart').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;
    const qtyInput = document.querySelector('.quantity-input[data-id="' + id + '"]');
    const quantity = parseInt(qtyInput.value);

    if (quantity < 1) return;

    fetch('cart_action.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + encodeURIComponent(id) + '&qty=' + encodeURIComponent(quantity)
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const toast = document.getElementById('toast');
        toast.style.display = 'block';
        setTimeout(() => toast.style.display = 'none', 2000);
      }
    });
  });
});
</script>

<?php
$content = ob_get_clean();
include 'templates/layout.php';
?>
