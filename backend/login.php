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
  $password = trim($data['password'] ?? '');

  if (empty($username) || empty($password)) {
      echo json_encode(['success' => false, 'message' => 'لطفاً همه فیلدها را پر کنید']);
      exit;
  }

  $stmt = $conn->prepare("SELECT id, username, password, is_verified FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows !== 1) {
      echo json_encode(['success' => false, 'message' => 'نام کاربری یافت نشد']);
      $stmt->close();
      exit;
  }

  $stmt->bind_result($id, $username, $hashed_password, $is_verified);
  $stmt->fetch();
  $stmt->close();

  if (!$is_verified) {
      echo json_encode(['success' => false, 'message' => 'حساب شما تأیید نشده است']);
      exit;
  }

  if (password_verify($password, $hashed_password)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['username'] = $username;
      echo json_encode(['success' => true, 'username' => $username]);
  } else {
      echo json_encode(['success' => false, 'message' => 'رمز عبور اشتباه است']);
  }

  $conn->close();
  ?>
