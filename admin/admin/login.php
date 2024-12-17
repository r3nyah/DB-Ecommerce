<?php
// admin/login.php
session_start();
include '../config.php';

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = md5($_POST['password']); // MD5 encryption

    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['admin'] = $username; // Menyimpan username admin
        $_SESSION['admin_id'] = $row['admin_id']; // Menyimpan admin_id dalam sesi
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}

?>