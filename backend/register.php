<?php
  session_start();
  header('Content-Type: application/json');
  require '../config/config.php';

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      echo json_encode(['success' => false, 'message' => 'روش درخواست نامعتبر']);
      exit;
  }

  $data = json_decode(file_get_contents('php://input'), true);
