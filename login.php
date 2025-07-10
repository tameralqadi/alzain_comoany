<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>تسجيل دخول المسؤول</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 400px;">
  <h2 class="mb-4 text-center">تسجيل دخول المسؤول</h2>
  
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label for="username" class="form-label">اسم المستخدم</label>
      <input type="text" id="username" name="username" class="form-control" required autofocus />
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">كلمة المرور</label>
      <div class="input-group">
        <input type="password" id="password" name="password" class="form-control" required />
        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
          <i class="bi bi-eye-fill" id="toggleIcon"></i>
        </button>
      </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">دخول</button>
  </form>
</div>

<script>
function togglePassword() {
  const input = document.getElementById("password");
  const icon = document.getElementById("toggleIcon");
  const isHidden = input.type === "password";
  input.type = isHidden ? "text" : "password";
  icon.classList.toggle("bi-eye-fill");
  icon.classList.toggle("bi-eye-slash-fill");
}
</script>
</body>
</html>
