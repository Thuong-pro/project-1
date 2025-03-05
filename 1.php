<?php
session_start();
$conn = new mysqli("localhost", "root", "your_password", "user_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Xử lý đăng ký
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    echo "Đăng ký thành công!";
}

// Xử lý đăng nhập
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            echo "Đăng nhập thành công!";
        } else {
            echo "Sai mật khẩu.";
        }
    } else {
        echo "Tài khoản không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký & Đăng nhập</title>
</head>
<body>
    <h2>Đăng ký</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit" name="register">Đăng ký</button>
    </form>

    <h2>Đăng nhập</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit" name="login">Đăng nhập</button>
    </form>
</body>
</html>