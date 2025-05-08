<?php
  // تنظیمات اتصال به پایگاه داده
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'nemino');

  // اتصال به پایگاه داده
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  // بررسی خطای اتصال
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // تنظیم کدگذاری به UTF-8
  $conn->set_charset("utf8mb4");
  ?>
