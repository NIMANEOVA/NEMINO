<?php
  session_start();
  header('Content-Type: application/json');
  require '../config/config.php';

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'روش درخواست نامعتبر']);
      exit;
  }

  $data = json_decode(file_get_contents('php://input'), true);
  $code = trim($data['code'] ?? '');

  if (empty($code)) {
      echo json_encode(['success' => false, 'message' => 'کد تأیید را وارد کنید']);
      exit;
  }

  if (!isset($_SESSION['pending_user_id'])) {
      echo json_encode(['success' => false, 'message' => 'جلسه نامعتبر است']);
      exit;
  }

  $user_id = $_SESSION['pending_user_id'];
  $current_time = date('Y-m-d H:i:s');

  $stmt = $conn->prepare("SELECT code FROM verification_codes WHERE user_id = ? AND code = ? AND expires_at > ?");
  $stmt->bind_param("iss", $user_id, $code, $current_time);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
      // تأیید کاربر
      $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $stmt->close();

      // حذف کدهای تأیید
      $stmt = $conn->prepare("DELETE FROM verification_codes WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $stmt->close();

      // ایجاد سشن کاربر
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $_SESSION['pending_username'];
      unset($_SESSION['pending_user_id'], $_SESSION['pending_username']);

      echo json_encode(['success' => true]);
  } else {
      echo json_encode(['success' => false, 'message' => 'کد تأیید اشتباه یا منقضی شده است']);
  }

  $conn->close();
  ?>
