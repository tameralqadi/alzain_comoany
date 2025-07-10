<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action === 'add' && $product_id) {
    $qty = 1; // افتراضي
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $qty = max(1, intval($_POST['quantity'] ?? 1));
    }
    // عيّن الكمية مباشرة (مش زيادة)
    $_SESSION['cart'][$product_id] = $qty;
    header("Location: cart.php");
    exit;
}


if ($action === 'remove' && $product_id) {
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty = max(1, intval($_POST['quantity'] ?? 1));
    $_SESSION['cart'][$product_id] = $qty;
    header("Location: cart.php");
    exit;
}

$products = [];
if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

ob_start();
?>

<h2 class="text-center text-primary fw-bold mb-5 display-6">سلة المشتريات</h2>

<?php if (empty($products)): ?>
    <p class="text-center fs-5">🛒 السلة فارغة.</p>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>صورة</th>
                    <th>اسم المنتج</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
            <?php $total = 0; ?>
            <?php foreach ($products as $product): ?>
                <?php
                $qty = $_SESSION['cart'][$product['id']];
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
                ?>
                <tr>
                    <td>
  <a href="product.php?id=<?= $product['id'] ?>">
    <img src="product_image.php?id=<?= $product['id'] ?>" width="60" alt="<?= htmlspecialchars($product['name']) ?>">
  </a>
</td>

                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>
                        <form method="POST" action="cart.php?action=update&id=<?= $product['id'] ?>" class="d-flex justify-content-center gap-2">
                            <input type="number" name="quantity" value="<?= $qty ?>" min="1" class="form-control form-control-sm w-50 text-center" />
                            <button type="submit" class="btn btn-sm btn-outline-primary">تحديث</button>
                        </form>
                    </td>
                    <td><?= number_format($product['price'], 2) ?> شيكل</td>
                    <td><?= number_format($subtotal, 2) ?> شيكل</td>
                    <td><a href="cart.php?action=remove&id=<?= $product['id'] ?>" class="btn btn-sm btn-danger">🗑️</a></td>
                </tr>
            <?php endforeach; ?>
                <tr class="table-info fw-bold">
                    <td colspan="4">المجموع الكلي</td>
                    <td colspan="2"><?= number_format($total, 2) ?> شيكل</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center d-flex justify-content-between flex-wrap mt-4 gap-2">
        <a href="index.php" class="btn btn-secondary">🔙 العودة إلى الصفحة الرئيسية</a>
        <a href="confirm.php" class="btn btn-success btn-lg">✅ تأكيد الطلب</a>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include 'templates/layout.php';
?>
