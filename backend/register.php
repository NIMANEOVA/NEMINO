<?php
  session_start();
  header('Content-Type: application/json');
  require '../config/config.php';

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'روش درخواست نامعتبر']);
      exit;
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $username = trim($data['username'] ?? '');
  $email = trim($data['email'] ?? '');
  $phone = trim($data['phone'] ?? '');
  $password = trim($data['password'] ?? '');

  // اعتبارسنجی ورودی‌ها
  if (empty($username) || empty($email) || empty($phone) || empty($password)) {
      echo json_encode(['success' => false, 'message' => 'لطفاً همه فیلدها را پر کنید']);
      exit;
  }

  if (strlen($username) <= 4) {
      echo json_encode(['success' => false, 'message' => 'نام کاربری باید بیشتر از ۴ حرف باشد']);
      exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo json_encode(['success' => false, 'message' => 'ایمیل معتبر نیست']);
      exit;
  }

  if (!preg_match('/^9\d{9}$/', $phone)) {
      echo json_encode(['success' => false, 'message' => 'شماره تلفن معتبر نیست']);
      exit;
  }

  if (!preg_match('/^[A-Z].*\d.*$/', $password) || strlen($password) < 8) {
      echo json_encode(['success' => false, 'message' => 'رمز عبور باید با حرف بزرگ شروع شود، حداقل یک عدد داشته باشد و ۸+ حرف باشد']);
      exit;
  }

  // بررسی وجود کاربر
  $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? OR phone = ?");
  $stmt->bind_param("sss", $username, $email, $phone);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
      echo json_encode(['success' => false, 'message' => 'نام کاربری، ایمیل یا شماره تلفن قبلاً ثبت شده است']);
      $stmt->close();
      exit;
  }
  $stmt->close();

  // هش کردن رمز عبور
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // ثبت کاربر
  $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $username, $email, $phone, $hashed_password);
  if ($stmt->execute()) {
      $user_id = $stmt->insert_id;

      // تولید کد تأیید
      $code = sprintf("%06d", mt_rand(100000, 999999));
      $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));

      $stmt = $conn->prepare("INSERT INTO verification_codes (user_id, code, expires_at) VALUES (?, ?, ?)");
      $stmt->bind_param("iss", $user_id, $code, $expires_at);
      $stmt->execute();
      $stmt->close();

      // ذخیره موقت اطلاعات کاربر در سشن
      $_SESSION['pending_user_id'] = $user_id;
      $_SESSION['pending_username'] = $username;

      // شبیه‌سازی ارسال کد (در عمل باید از ایمیل یا SMS استفاده شود)
      error_log("کد تأیید برای $username: $code");

      echo json_encode(['success' => true, 'code' => $code]);
  } else {
      echo json_encode(['success' => false, 'message' => 'خطا در ثبت‌نام']);
  }

  $conn->close();
  ?>
