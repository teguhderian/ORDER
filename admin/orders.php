<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
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
</head>
<body>
    <?php include '../navbar.php'; ?>

    <h1 class="text-center">Orders Management</h1>

    <h2 class="text-center mt-5">Orders List</h2>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Order Date</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 1;
                while ($row = $result->fetch_assoc()) : 
            ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['customer_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['customer_address']; ?></td>
                    <td><?php echo $row['customer_phone']; ?></td>
                    <td><?php echo $row['order_date']; ?></td>
                </tr>
            <?php 
                $count++;
                endwhile; 
            ?>
        </tbody>
    </table>
</body>
</html>
