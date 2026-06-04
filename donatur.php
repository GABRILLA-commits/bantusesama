<?php
$conn = new mysqli("sql200.infinityfree.com", "if0_42095823", "BA9bFtvD5z2jh7l", "if0_42095823_bantusesama");

// Ambil data campaign untuk ditampilkan di judul/header
$campaign = $conn->query("SELECT * FROM campaigns LIMIT 1")->fetch_assoc();
$id_campaign = $campaign['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Donatur - BantuSesama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fe; }
        .navbar { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .footer { background-color: #2b44d2; color: #e0e7ff; padding: 40px 0 20px 0; }
        .card-custom { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="index.php"><i class="bi bi-heart-fill me-2"></i>BantuSesama</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link text-muted" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-muted" href="index.php#campaign-section">Campaign</a></li>
                    <li class="nav-item"><a class="nav-link text-primary fw-bold border-bottom border-primary border-2" href="#">Donatur</a></li>
                    <li class="nav-item"><a class="nav-link text-muted" href="index.php#tentang-kami-section">Tentang Kami</a></li>
                </ul>
            </div>
            <a href="index.php#form-donasi-section" class="btn btn-primary px-4 py-2 rounded-pill fw-bold"><i class="bi bi-heart me-2"></i>Donasi Sekarang</a>
        </div>
    </nav>

    <!-- KONTEN DAFTAR DONATUR -->
    <div class="container my-5" style="min-height: 60vh;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card p-4 card-custom">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="fw-bold text-dark mb-1">Seluruh Donatur Kebaikan</h3>
                            <p class="text-muted small mb-0">Campaign: <?php echo $campaign['judul']; ?></p>
                        </div>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-3"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <?php
                        // Hanya menampilkan donasi yang berstatus 'Approved' ke publik
                        $donatur_list = $conn->query("SELECT * FROM donations WHERE status='Approved' AND campaign_id=$id_campaign ORDER BY waktu_donasi DESC");
                        if($donatur_list->num_rows == 0) {
                            echo "<div class='text-center text-muted p-5'>Belum ada donasi terverifikasi.</div>";
                        }
                        while($d = $donatur_list->fetch_assoc()) {
                            // Implementasi Keamanan & Etika Privasi (Bab 14 Pressman)
                            $nama_tampil = ($d['is_anonim'] == 1) ? "Hamba Allah (Anonim)" : $d['nama_donatur']; 
                            $waktu = date('j F Y • H:i', strtotime($d['waktu_donasi'])) . " WIB";
                            ?>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark"><?php echo $nama_tampil; ?></h6>
                                        <small class="text-muted text-xs"><?php echo $waktu; ?></small>
                                    </div>
                                </div>
                                <span class="badge bg-success px-3 py-2 rounded-2 fs-6">Rp <?php echo number_format($d['nominal'], 0, ',', '.'); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container text-center small opacity-50">
            &copy; 2026 BantuSesama. All rights reserved.
        </div>
    </footer>

</body>
</html>