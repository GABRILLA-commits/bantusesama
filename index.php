<?php
$conn = new mysqli("sql200.infinityfree.com", "if0_42095823", "BA9bFtvD5z2jh7l", "if0_42095823_bantusesama");

// Proses upload donasi jika form disubmit
if (isset($_POST['kirim_donasi'])) {
    $campaign_id = $_POST['campaign_id'];
    $nama = $_POST['nama_donatur'];
    $nominal = $_POST['nominal'];
    $is_anonim = isset($_POST['is_anonim']) ? 1 : 0;
    
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    $file_name = time() . "_" . basename($_FILES["bukti_transfer"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
        $conn->query("INSERT INTO donations (campaign_id, nama_donatur, nominal, bukti_transfer, is_anonim) 
                      VALUES ('$campaign_id', '$nama', '$nominal', '$file_name', '$is_anonim')");
        echo "<script>alert('Donasi berhasil dikirim! Menunggu verifikasi admin.'); window.location='index.php';</script>";
    }
}

// Ambil data campaign
$campaign = $conn->query("SELECT * FROM campaigns LIMIT 1")->fetch_assoc();
$id_campaign = $campaign['id'];

// Hitung Total Donatur yang di-approve
$query_donatur = $conn->query("SELECT COUNT(*) as total_orang FROM donations WHERE status='Approved' AND campaign_id=$id_campaign");
$data_donatur = $query_donatur->fetch_assoc();
$total_donatur = $data_donatur['total_orang'];

// Hitung Sisa Target Dana
$sisa_target = $campaign['target_dana'] - $campaign['dana_terkumpul'];
if($sisa_target < 0) $sisa_target = 0;

$persen = ($campaign['dana_terkumpul'] / $campaign['target_dana']) * 100;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BantuSesama - Platform Donasi Transparan</title>
    <!-- Bootstrap 5 & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fe; }
        .navbar { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .hero-section {
            background: linear-gradient(rgba(10, 37, 83, 0.85), rgba(10, 37, 83, 0.85)), url('https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=1200') no-repeat center center;
            background-size: cover; color: white; padding: 60px 0; border-radius: 0 0 40px 40px;
        }
        .progress-card { background: white; border-radius: 16px; padding: 20px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.03); margin-top: -50px; }
        .stat-card { background: white; border-radius: 16px; padding: 20px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.02); display: flex; align-items: center; }
        .stat-icon { width: 50px; height: 50px; border-radius: 50px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 15px; }
        .form-donasi { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(10,37,83,0.05); border: none; }
        .btn-primary-custom { background: linear-gradient(135deg, #4f46e5, #3b82f6); border: none; padding: 12px; font-weight: 600; border-radius: 10px; }
        .btn-primary-custom:hover { background: linear-gradient(135deg, #4338ca, #2563eb); }
        .upload-area { border: 2px dashed #cbd5e1; border-radius: 10px; padding: 20px; text-center; cursor: pointer; background: #f8fafc; }
        .footer { background-color: #2b44d2; color: #e0e7ff; padding: 40px 0 20px 0; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

    <!-- 1. NAVBAR -->
    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#"><i class="bi bi-heart-fill me-2"></i>BantuSesama</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link text-primary fw-bold border-bottom border-primary border-2" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link text-muted" href="#campaign-section">Campaign</a></li>
                    <li class="nav-item"><a class="nav-link text-muted" href="#donatur-section">Donatur</a></li>
                    <li class="nav-item"><a class="nav-link text-muted" href="#tentang-kami-section">Tentang Kami</a></li>
                </ul>
            </div>
            <a href="#form-donasi-section" class="btn btn-primary px-4 py-2 rounded-pill fw-bold"><i class="bi bi-heart me-2"></i>Donasi Sekarang</a>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <div class="hero-section" id="campaign-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-3"><?php echo $campaign['judul']; ?></h1>
                    <p class="fs-5 opacity-75"><?php echo $campaign['deskripsi']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="container mb-5">
        <div class="row">
            <!-- SISI KIRI: PROGRES, STATISTIK, & RIWAYAT -->
            <div class="col-lg-7">
                
                <!-- CARD TARGET & PROGRESS -->
                <div class="card progress-card mb-4">
                    <div class="row text-center text-md-start">
                        <div class="col-md-6 border-end border-light">
                            <span class="text-muted small">Dana Terkumpul</span>
                            <h2 class="text-primary fw-bold mt-1">Rp <?php echo number_format($campaign['dana_terkumpul'], 0, ',', '.'); ?></h2>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <span class="text-muted small">Target</span>
                            <h3 class="text-secondary fw-semibold mt-1">Rp <?php echo number_format($campaign['target_dana'], 0, ',', '.'); ?></h3>
                        </div>
                    </div>
                    <div class="progress mt-4 shadow-sm" style="height: 20px; border-radius: 10px; background-color: #f1f5f9;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary text-white fw-bold" role="progressbar" style="width: <?php echo $persen; ?>%; border-radius: 10px;">
                            <?php echo round($persen, 0); ?>%
                        </div>
                    </div>
                </div>

                <!-- 3 CARDS STATISTIK BARU -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <span class="text-muted small d-block">Total Donatur</span>
                                <strong class="fs-5 text-dark"><?php echo $total_donatur; ?></strong> <span class="text-muted small">Orang</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-wallet2"></i></div>
                            <div>
                                <span class="text-muted small d-block">Dana Terkumpul</span>
                                <strong class="fs-6 text-success">Rp <?php echo number_format($campaign['dana_terkumpul'], 0, ',', '.'); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card">
                            <div class="stat-icon bg-purple bg-opacity-10 text-purple" style="color: #8b5cf6; background: #f3e8ff;"><i class="bi bi-target"></i></div>
                            <div>
                                <span class="text-muted small d-block">Sisa Target</span>
                                <strong class="fs-6 text-dark">Rp <?php echo number_format($sisa_target, 0, ',', '.'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIWAYAT DONATUR -->
                <div class="card p-4 border-0 shadow-sm mb-4" id="donatur-section" style="border-radius: 16px;">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-journal-text text-primary me-2"></i>Riwayat Donatur Kebaikan</h5>
                    <div class="d-flex flex-column gap-3">
                        <?php
                        $donatur_list = $conn->query("SELECT * FROM donations WHERE status='Approved' AND campaign_id=$id_campaign ORDER BY id DESC");
                        if($donatur_list->num_rows == 0) {
                            echo "<div class='text-center text-muted p-3'>Belum ada donasi terverifikasi</div>";
                        }
                        while($d = $donatur_list->fetch_assoc()) {
                            $nama_tampil = ($d['is_anonim'] == 1) ? "Hamba Allah (Anonim)" : $d['nama_donatur']; // Menjaga Etika Privasi Bab 14
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
                    <a href="donatur.php" class="btn btn-outline-primary btn-sm mt-3 w-100 rounded-3"><i class="bi bi-people me-2"></i>Lihat Semua Donatur</a>
                </div>

            </div>

            <!-- SISI KANAN: FORM DONASI MANDIRI -->
            <div class="col-lg-5" id="form-donasi-section" style="margin-top: -120px;">
                <div class="card form-donasi sticky-lg-top" style="top: 100px; z-index: 10;">
                    <div class="d-flex align-items-center mb-4 text-primary">
                        <i class="bi bi-heart-pulse-fill fs-3 me-2"></i>
                        <h4 class="fw-bold text-dark mb-0">Formulir Donasi Mandiri</h4>
                    </div>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="campaign_id" value="<?php echo $id_campaign; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="nama_donatur" class="form-control border-start-0 ps-0" required placeholder="Contoh: Budi Susanto">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Nominal Donasi (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-wallet2"></i></span>
                                <input type="number" name="nominal" class="form-control border-start-0 ps-0" required placeholder="Minimal Rp 10.000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Bukti Transfer (.jpg/.png)</label>
                            <div class="upload-area text-center" onclick="document.getElementById('file-input').click()">
                                <i class="bi bi-cloud-arrow-up text-primary display-6 mb-2 d-block"></i>
                                <span class="fw-bold text-primary d-block">Pilih file</span>
                                <small class="text-muted">JPG, PNG maksimal 5MB</small>
                                <input type="file" id="file-input" name="bukti_transfer" class="d-none" accept="image/*" required onchange="updateFileName(this)">
                                <p id="file-chosen" class="text-success small mt-2 fw-semibold"></p>
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="is_anonim" class="form-check-input" id="anonim">
                            <label class="form-check-label text-muted small" for="anonim">Sembunyikan nama saya dari publik (Anonim)</label>
                        </div>

                        <button type="submit" name="kirim_donasi" class="btn btn-primary-custom text-white w-100 fs-5 mb-3">
                            <i class="bi bi-send-fill me-2"></i>Kirim Donasi Sekarang
                        </button>
                        
                        <div class="text-center text-muted small">
                            <i class="bi bi-lock-fill me-1"></i> Donasi Anda aman dan terpercaya
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FOOTER -->
    <footer class="footer" id="tentang-kami-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h4 class="fw-bold text-white mb-3"><i class="bi bi-heart-fill me-2"></i>BantuSesama</h4>
                    <p class="small opacity-75">Bersama kita bisa membantu lebih banyak mereka yang membutuhkan.</p>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold text-white mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled small opacity-75 d-flex flex-column gap-2">
                        <li>Beranda</li>
                        <li>Campaign</li>
                        <li>Tentang Kami</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-white mb-3">Hubungi Kami</h6>
                    <p class="small mb-1 opacity-75"><i class="bi bi-envelope me-2"></i> bantusesama@gmail.com</p>
                    <p class="small opacity-75"><i class="bi bi-telephone me-2"></i> +62 812-3456-7890</p>
                </div>
                <div class="col-md-3 text-md-end">
                    <h6 class="fw-bold text-white mb-3 text-md-end">Ikuti Kami</h6>
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="#" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-light opacity-25 my-4">
            <div class="text-center small opacity-50">
                &copy; 2026 BantuSesama. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Script sederhana untuk memunculkan nama file saat donatur milih foto bukti transfer
        function updateFileName(input) {
            const fileName = input.files[0].name;
            document.getElementById('file-chosen').innerText = "File terpilih: " + fileName;
        }
    </script>
</body>
</html>