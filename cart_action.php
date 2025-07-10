<?php
session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

if ($id > 0 && $qty > 0) {
    $_SESSION['cart'][$id] = ($qty);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>
