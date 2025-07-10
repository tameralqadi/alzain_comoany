<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>شركة الزين للتجارة</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="public/logo.png">
  <style>
    body { background-color:#f0f2f5; font-family: 'Segoe UI'; }
    .navbar { background-color: #004085; }
    .navbar-brand { color: #ffd700; font-weight: bold; display: flex; align-items: center; }
    .navbar-brand img { max-height: 60px; width: 60px; margin-left: 10px; border-radius: 50%; object-fit: cover; }
    .category-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: 0.3s;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 15px;
    }
    .category-box:hover { transform: scale(1.05); }
    footer { background: #004085; color: #fff; padding: 20px; margin-top: 40px; text-align: center; }
    img { max-width: 100%; height: auto; object-fit: contain; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
  <a class="navbar-brand" href="index.php">
  <img src="public/logo.png" alt="Logo" />
  شركة الزين للتجارة
</a>

    <div class="ms-auto d-flex gap-3">
      <div class="dropdown">
        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          تواصل معنا
        </button>
        <ul class="dropdown-menu dropdown-menu-end text-end" dir="rtl">
          <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="https://api.whatsapp.com/message/2YFFM6KWRRQ4P1?autoload=1&app_absent=0"><span>واتساب</span><i class="bi bi-whatsapp text-success"></i></a></li>
          <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="https://www.facebook.com/profile.php?id=100094363070676"><span>فيسبوك</span><i class="bi bi-facebook text-primary"></i></a></li>
          <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="#"><span>إنستغرام</span><i class="bi bi-instagram text-danger"></i></a></li>
        </ul>
      </div>
      <a href="cart.php" class="btn btn-warning">🛒 السلة</a>
    </div>
  </div>
</nav>
<div class="container my-5">
  <?= $content ?? '' ?>
</div>
<footer>© 2025 شركة الزين للتجارة - جميع الحقوق محفوظة</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
