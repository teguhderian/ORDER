<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Ordering System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['role'])) : ?>
                <?php if ($_SESSION['role'] == 'admin') : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/products.php">Manage Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../admin/orders.php">View Orders</a>
                    </li>
                <?php elseif ($_SESSION['role'] == 'sales') : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../sales/index.php">Dashboard</a>
                    </li>
                <?php elseif ($_SESSION['role'] == 'customer') : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../customer/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../customer/info_produk.php">Info Produk</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
