<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("UPDATE products SET name=?, price=?, description=? WHERE id=?");
    $stmt->bind_param("sdsi", $name, $price, $description, $product_id);

    if ($stmt->execute()) {
        header("Location: products.php");
        exit();
    } else {
        $edit_error = "Failed to edit product.";
    }

    $stmt->close();
} else {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include '../navbar.php'; ?>

    <h1>Edit Product</h1>
    <?php if (isset($edit_error)) : ?>
        <p><?php echo $edit_error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label for="price">Price:</label>
        <input type="number" name="price" value="<?php echo $product['price']; ?>" min="0" step="0.01" required><br>
        <label for="description">Description:</label><br>
        <textarea name="description" rows="4" cols="50"><?php echo $product['description']; ?></textarea><br>
        <input type="submit" class="btn btn-primary" value="Edit Product">
    </form>
</body>
</html>