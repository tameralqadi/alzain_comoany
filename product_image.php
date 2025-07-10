<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $db->prepare("SELECT image_data, mime_type FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product && $product['image_data']) {
    header("Content-Type: " . $product['mime_type']);
    echo $product['image_data'];
    exit;
} else {
    http_response_code(404);
    echo "Image not found.";
}
?>
