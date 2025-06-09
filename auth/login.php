<?php
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['nik'] = $user['nik'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_panitia'] = $user['is_panitia'];
        $_SESSION['is_berqurban'] = $user['is_berqurban'];

        header('Location: ../dashboard/index.php');
        exit;
    } else {
        $_SESSION['error'] = "NIK atau password salah!";
        header('Location: ../index.php');
        exit;
    }
}
?>