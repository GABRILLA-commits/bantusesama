<?php
session_start();
if (!isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("sql200.infinityfree.com", "if0_42095823", "BA9bFtvD5z2jh7l", "if0_42095823_bantusesama");

if (isset($_POST['update_campaign'])) {
    $judul_baru = $_POST['judul_campaign'];
    $target_baru = $_POST['target_dana'];
    $deskripsi_baru = $_POST['deskripsi_campaign'];

    $conn->query("UPDATE campaigns SET judul='$judul_baru', target_dana='$target_baru', deskripsi='$deskripsi_baru' WHERE id=1");
    echo "<script>alert('Data Campaign Berhasil Diperbarui!'); window.location='admin.php';</script>";
}

// Aksi Verifikasi Admin
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    if ($action == 'approve') {
        // Ambil info nominal donasi
        $donation = $conn->query("SELECT * FROM donations WHERE id=$id")->fetch_assoc();
        if ($donation['status'] == 'Pending') {
            $nominal = $donation['nominal'];
            $campaign_id = $donation['campaign_id'];
            
            // Jalankan transaksi: Ubah status & Update akumulasi dana campaign
            $conn->query("UPDATE donations SET status='Approved' WHERE id=$id");
            $conn->query("UPDATE campaigns SET dana_terkumpul = dana_terkumpul + $nominal WHERE id=$campaign_id");
        }
    } elseif ($action == 'reject') {
        $conn->query("UPDATE donations SET status='Rejected' WHERE id=$id");
    }
    header("Location: admin.php");
}
$campaign = $conn->query("SELECT * FROM campaigns LIMIT 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Kontrol Verifikasi Admin - BantuSesama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container py-5">
        <h2 class="mb-4">🛡️ Panel Verifikasi Keuangan Admin (Transparansi Real-Time)</h2>
<div class="card bg-secondary text-white p-4 mb-4" style="border-radius: 12px;">
    <h4 class="mb-3"><i class="bi bi-gear-fill me-2"></i> Pengaturan Target & Informasi Campaign</h4>
    <form action="" method="POST">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Judul Campaign</label>
                <input type="text" name="judul_campaign" class="form-control bg-dark text-white border-0" value="<?php echo $campaign['judul']; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Target Dana (Rp)</label>
                <input type="number" name="target_dana" class="form-control bg-dark text-white border-0" value="<?php echo $campaign['target_dana']; ?>" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" name="update_campaign" class="btn btn-info w-100 fw-bold"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
            </div>
            <div class="col-12">
                <label class="form-label small fw-bold">Deskripsi Singkat</label>
                <textarea name="deskripsi_campaign" class="form-control bg-dark text-white border-0" rows="2" required><?php echo $campaign['deskripsi']; ?></textarea>
            </div>
        </div>
    </form>
</div>
        <div class="card bg-secondary text-white p-4">
            <h4>Daftar Persetujuan Donasi Masuk</h4>
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mt-3">
                    <thead>
                        <tr>
                            <th>Nama Donatur</th>
                            <th>Nominal</th>
                            <th>Bukti Foto</th>
                            <th>Status</th>
                            <th>Aksi Validasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM donations ORDER BY id DESC");
                        if($res->num_rows == 0) echo "<tr><td colspan='5' class='text-center text-muted'>Belum ada transaksi masuk</td></tr>";
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td>".$row['nama_donatur']."</td>
                                    <td>Rp ".number_format($row['nominal'], 0, ',', '.')."</td>
                                    <td><a href='uploads/".$row['bukti_transfer']."' target='_blank' class='btn btn-sm btn-light'>Lihat Bukti</a></td>
                                    <td><span class='badge bg-warning'>".$row['status']."</span></td>
                                    <td>";
                            if ($row['status'] == 'Pending') {
                                echo "<a href='admin.php?action=approve&id=".$row['id']."' class='btn btn-sm btn-success me-2'>Approve</a>";
                                echo "<a href='admin.php?action=reject&id=".$row['id']."' class='btn btn-sm btn-danger'>Reject</a>";
                            } else {
                                echo "<span class='text-muted'>Selesai diarsip</span>";
                            }
                            echo "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <div class="text-center mt-4 mb-5">
            <a href="index.php" class="btn btn-outline-light">← Kembali ke Halaman Utama Publik</a>
            <a href="logout.php" class="btn btn-danger ms-2"><i class="bi bi-lock-fill me-1"></i> Logout</a>
        </div>
    </div>
</body>
</html>