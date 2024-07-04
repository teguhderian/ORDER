<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config.php';

// Fetch all products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <h1 class="text-center mt-2">Products Management</h1>

    <h2 class="text-center mt-5">Tambah Produk Baru</h2>

    <div class="container d-flex justify-content-center">
        <form method="POST" action="add_product.php">
            <div class="row py-1">
                <div class="col-sm-2">
                    <label for="name">Nama</label>
                </div>
                <div class="col-sm-2">
                    <input type="text" name="name" required>
                </div>
            </div>
            <div class="row py-1">
                <div class="col-sm-2">
                    <label for="price">Harga</label>
                </div>
                <div class="col-sm-2">
                    <input type="number" name="price" min="0" step="0.01" required>
                </div>
            </div>
            <div class="row py-1">
                <div class="col-sm-2">
                    <label for="description">Keterangan</label>
                </div>
                <div class="col-sm-2">
                    <textarea name="description" rows="4" cols="50"></textarea>
                </div>
            </div>
            <div class="row py-1">
                <div class="col d-flex justify-content-center">
                    <input type="submit" class="btn btn-primary" value="Add Product">
                </div>
            </div>
        </form>
    </div>


    <h2 class="text-center mt-4">Daftar Produk</h2>
    <table class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $count = 1;
                while ($row = $result->fetch_assoc()) : 
            ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <a class="btn btn-secondary" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                        <a class="btn btn-danger" href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php 
                $count++;
                endwhile; ?>
        </tbody>
    </table>
</body>
</html>
