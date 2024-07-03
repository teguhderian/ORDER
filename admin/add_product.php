<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $description);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        $add_error = "Failed to add product.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <h1>Add New Product</h1>
    <?php if (isset($add_error)) : ?>
        <p><?php echo $add_error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        <label for="price">Price:</label>
        <input type="number" name="price" min="0" step="0.01" required><br>
        <label for="description">Description:</label><br>
        <textarea name="description" rows="4" cols="50"></textarea><br>
        <input type="submit" value="Add Product">
    </form>
</body>
</html>
