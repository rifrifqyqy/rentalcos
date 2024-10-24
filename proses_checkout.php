<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php'; // Koneksi ke database

// Ambil data dari formulir
$costume_id = isset($_POST['costume_id']) ? intval($_POST['costume_id']) : 0;
$size = isset($_POST['size']) ? $_POST['size'] : '';
$duration = isset($_POST['duration']) ? $_POST['duration'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';

// Validasi input
if ($costume_id > 0 && !empty($size) && !empty($duration) && !empty($name) && !empty($phone) && !empty($email)) {
    // Persiapkan query untuk menambahkan data ke tabel pemesanan
    $stmt = $conn->prepare("INSERT INTO tb_pemesanan (costume_id, size, duration, name, phone, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $costume_id, $size, $duration, $name, $phone, $email);

    // Eksekusi query dan periksa apakah berhasil
    if ($stmt->execute()) {
        header("location:home");
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Semua input harus diisi.";
}

$conn->close();
