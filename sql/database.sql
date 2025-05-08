-- ایجاد پایگاه داده
  CREATE DATABASE IF NOT EXISTS nemino;
  USE nemino;

  -- جدول کاربران
  CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      username VARCHAR(50) NOT NULL UNIQUE,
      email VARCHAR(100) NOT NULL UNIQUE,
      phone VARCHAR(15) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      is_verified TINYINT(1) DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

  -- جدول کدهای تأیید
  CREATE TABLE verification_codes (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
      code VARCHAR(6) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      expires_at TIMESTAMP NOT NULL,
      FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
  );
