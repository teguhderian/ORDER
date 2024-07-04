<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'sales') {
    header("Location: ../login.php");
    exit();
}

include '../config.php';

// Fetch all orders
$stmt = $conn->prepare("SELECT orders.id, users.username AS customer_name, products.name AS product_name, orders.quantity, orders.customer_address, orders.customer_phone, orders.order_date
                        FROM orders
                        JOIN users ON orders.user_id = users.id
                        JOIN products ON orders.product_id = products.id");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Jika Anda memiliki style kustom juga -->
</head>

<body>
    <?php include '../navbar.php'; ?>

    <h1>Selamat Datang, <?php echo $_SESSION['username']; ?></h1>

    <h2>Pesanan</h2>
    <table class="table table-bordered mt-4">
    <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Nama Toko & Alamat </th>
                <th>No HP</th>
                <th>Tanggal Pesan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 1;
                while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['customer_address']; ?></td>
                    <td><?php echo $row['customer_phone']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                </tr>
            <?php 
                $count++;
                endwhile; ?>
        </tbody>
    </table>
</body>
</html>
