<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Menghapus relasi antara orders dan products yang masih terhubung sebelum menghapus product itu sendiri
include '../config.php';

$product_id = $_GET['id']; // misalnya, id produk yang ingin dihapus dari URL

// Hapus semua pesanan yang terkait dengan produk ini terlebih dahulu
$stmt = $conn->prepare("DELETE FROM orders WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();

// Setelah menghapus semua pesanan yang terkait, barulah hapus produknya
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // Produk berhasil dihapus
    echo "Produk berhasil dihapus.";
    header("Location: products.php");
    exit();
} else {
    // Error saat menghapus produk
    echo "Gagal menghapus produk: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

