<?php
// Menghubungkan dengan file config untuk koneksi database
include 'config.php';

// Memeriksa jika form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $customer_address = mysqli_real_escape_string($conn, $_POST['customer_address']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Enkripsi password

    // Masukkan data ke tabel customer
    $query = "INSERT INTO customer (first_name, last_name, email, phone_number, customer_address) 
          VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$customer_address' )";

    if (mysqli_query($conn, $query)) {
        // Ambil customer_id yang baru saja dimasukkan
        $customer_id = mysqli_insert_id($conn);

        // Masukkan data login ke tabel login
        // Default role adalah pelanggan
        $query_login = "INSERT INTO login (customer_id, username, password) 
                        VALUES ('$customer_id', '$username', '$hashed_password')";
        if (mysqli_query($conn, $query_login)) {
            // Jika berhasil, redirect ke halaman login
            header("Location: loginform.php");
            exit();
        } else {
            echo "Error: " . $query_login . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>
