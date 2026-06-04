<?php
session_start();

// Jika admin sudah login sebelumnya, langsung lempar ke halaman admin
if (isset($_SESSION['is_admin'])) {
    header("Location: admin_secure.php");
    exit();
}

// Proses verifikasi form login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hardcoded akun admin
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Username atau Password salah, partner!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - BantuSesama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex align-items-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow">
                    <h3 class="text-center mb-4">🔐 Login Admin</h3>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger small py-2"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label small">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100 fw-bold">Masuk Panel</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-muted small">← Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>