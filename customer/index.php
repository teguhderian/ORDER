<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
    header("Location: ../login.php");
    exit();
}

include '../config.php';

$user_id = $_SESSION['user_id'];

// Fetch orders for the customer
$stmt = $conn->prepare("SELECT orders.id, products.name AS product_name, orders.quantity, orders.customer_address, orders.customer_phone, orders.order_date
                        FROM orders
                        JOIN products ON orders.product_id = products.id
                        WHERE orders.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch products for ordering
$product_stmt = $conn->prepare("SELECT * FROM products");
$product_stmt->execute();
$product_result = $product_stmt->get_result();

// Handle new order submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['customer_address']) && isset($_POST['customer_phone'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $customer_address = $_POST['customer_address'];
    $customer_phone = $_POST['customer_phone'];

    // Lakukan validasi apakah customer_address tidak kosong
    if (empty($customer_address)) {
        $order_error = "Customer address cannot be empty.";
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, customer_address, customer_phone) VALUES (?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("iiiss", $user_id, $product_id, $quantity, $customer_address, $customer_phone);

        if ($insert_stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $order_error = "Failed to place order.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Orders</title>
    <!--<link rel="stylesheet" href="../assets/css/style.css">  Jika Anda memiliki style kustom juga -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include '../navbar.php'; ?>

    <h1 class="title text-center">Selamat Datang, <?php echo $_SESSION['username']; ?></h1>

    <?php if (isset($order_error)) : ?>
        <p><?php echo $order_error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="container my-5">
            <div class="row">
                <div class="col">
                    <h2 class="subtitle text-center">Silakan Memesan</h2>
                </div>
            </div>
            <div class="d-flex row py-2 justify-content-center">
                <div class="col-sm-2">
                    <label for="product_id">Pilih Produk</label>  
                </div>
                <div class="col-sm-2">
                    <select name="product_id" id="product_id" required>
                        <?php while ($row = $product_result->fetch_assoc()) : ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="d-flex row py-2 justify-content-center">
                <div class="col-sm-2">
                    <label for="quantity">Jumlah</label>
                </div>
                <div class="col-sm-2">
                    <input type="number" name="quantity" id="quantity" min="1" required>
                </div>
            </div>
            <div class="d-flex row py-2 justify-content-center">
                <div class="col-sm-2">
                    <label for="name">Nama Toko & Alamat</label>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="customer_address" id="customer_address"required>
                </div>
            </div>
            <div class="d-flex row py-2 justify-content-center">
                <div class="col-sm-2">
                    <label for="name">No HP</label>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="customer_phone" id="customer_phone"required>
                </div>
            </div>
            <div class="row py-2">
                <div class="d-flex col justify-content-center">
                    <input class="btn btn-primary mx-auto" type="submit" value="Place Order">
                </div>
            </div>
        </div>
    </form>

    <h2 class="text-center">Pesanan</h2>
    <table class="table table-striped mt-4">
    <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Nama Toko & Alamat</th>
                <th>No HP</th>
                <th>Tanggal Pesan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 1;
                while ($row = $result->fetch_assoc()) : 
            ?>
                <tr>
                    <td><?php echo $count; ?></td>
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
