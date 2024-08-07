<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/products.php");
        } elseif ($user['role'] == 'sales') {
            header("Location: sales/index.php");
        } else {
            header("Location: customer/index.php");
        }
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Jika Anda memiliki style kustom juga -->
</head>
<body>
    <div class="container">
        <div class="row row-cols-1 align-items-center mt-5">
            <div class="col text-center mt-5">
                <h1>Login</h1>
            </div>
            <?php if (isset($login_error)) : ?>
                <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <div class="col mt-5">
                        <label for="username">Username:</label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="username" required>   
                    </div>
                </div>
                <div class="form-group">
                    <div class="col">
                        <label for="password">Password:</label>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                    <div class="d-flex justify-content-center col">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
            </form> 
            <div class="col text-center">
                <p class="mt-3">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional, if your script requires it) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
