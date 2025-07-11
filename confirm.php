<?php
session_start();
require_once 'db.php';
require_once 'functions.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// جمع تفاصيل الطلب لإرسالها
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$orderDetails = "طلب جديد من موقع شركة الزين للتجارة:\n";
foreach ($products as $product) {
    $qty = $_SESSION['cart'][$product['id']];
    $subtotal = $product['price'] * $qty;
    $orderDetails .= "- {$product['name']} × {$qty} = " . number_format($subtotal, 2) . " شيكل\n";
}

$total = 0;
foreach ($products as $product) {
    $total += $product['price'] * $_SESSION['cart'][$product['id']];
}
$orderDetails .= "المجموع الكلي: " . number_format($total, 2) . " شيكل";

$encodedMessage = urlencode($orderDetails);
$adminWhatsAppNumber = '972598057107' /*'972598740722'*/; // عدل الرقم بدون فراغات
$whatsAppLink = "https://api.whatsapp.com/send?phone={$adminWhatsAppNumber}&text={$encodedMessage}";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart'] = [];
    header("Location: thankyou.php");
    exit;
}

ob_start();
?>

<h2 class="text-center text-primary fw-bold mb-5 display-6">تأكيد الطلب</h2>

<div class="card p-4 shadow-sm">
    <h4>تفاصيل طلبك:</h4>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?= htmlspecialchars($product['name']) ?> × <?= $_SESSION['cart'][$product['id']] ?> = <?= number_format($product['price'] * $_SESSION['cart'][$product['id']], 2) ?> شيكل</li>
        <?php endforeach; ?>
    </ul>
    <h5>المجموع الكلي: <?= number_format($total, 2) ?> شيكل</h5>

<div class="mt-4 text-center">
    <a href="<?= $whatsAppLink ?>" 
       target="_blank" 
       class="btn btn-success btn-lg mb-3" 
       onclick="setTimeout(() => { window.location.href='thankyou.php'; }, 3000);">
       ✅ إرسال الطلب عبر واتساب
    </a><br>
    <a href="cart.php" class="btn btn-secondary me-2">↩️ العودة إلى السلة</a>
    <a href="index.php" class="btn btn-outline-primary">🏠 العودة إلى الصفحة الرئيسية</a>
</div>

</div>

<?php
$content = ob_get_clean();
include 'templates/layout.php';
?>
