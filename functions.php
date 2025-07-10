<?php
function getLogo($db) {
  $stmt = $db->query("SELECT logo, mime_type FROM settings ORDER BY id DESC LIMIT 1");
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    header("Content-Type: {$row['mime_type']}");
    echo $row['logo'];
    exit;
  }
}

function generateWhatsAppLink($products, $cart, $adminNumber) {
    $orderDetails = "طلب جديد من شركة الزين للتجارة:%0A";
    $total = 0;
    foreach ($products as $product) {
        $qty = $cart[$product['id']];
        $subtotal = $product['price'] * $qty;
        $orderDetails .= "- {$product['name']} × {$qty} = " . number_format($subtotal, 2) . " شيكل%0A";
        $total += $subtotal;
    }
    $orderDetails .= "المجموع الكلي: " . number_format($total, 2) . " شيكل";
    return "https://api.whatsapp.com/send?phone={$adminNumber}&text={$orderDetails}";
}

?>
