<?php
require_once '../config/db.php';

if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}

$nik = $_SESSION['nik'];
$user_check = $conn->query("SELECT role, is_panitia FROM users WHERE nik = '$nik'");
$user_data = $user_check->fetch_assoc();

if ($user_data['role'] !== 'admin' && $user_data['is_panitia'] != 1) {
    header("Location: ../dashboard/index.php");
    exit;
}

function hitungJumlahDaging($is_panitia, $is_berqurban)
{
    $jumlah = 1; 

    if ($is_panitia == 1) {
        $jumlah += 1; 
    }

    if ($is_berqurban == 1) {
        $jumlah += 2; 
    }

    return $jumlah;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn->begin_transaction();

        if (isset($_POST['reset_pembagian'])) {
            $conn->query("DELETE FROM pembagian_daging");
            $message = "Data pembagian daging berhasil direset!";
        }

        if (isset($_POST['bagi_otomatis'])) {
            // Ambil semua warga
            $query = "SELECT nik, nama, is_panitia, is_berqurban FROM users WHERE role = 'warga'";
            $result = $conn->query($query);

            $total_distributed = 0;
            $count_warga = 0;

            while ($warga = $result->fetch_assoc()) {
                $nik_warga = $warga['nik'];
                $nama_warga = $warga['nama'];
                $is_panitia = $warga['is_panitia'];
                $is_berqurban = $warga['is_berqurban'];

                $jumlah_kg = hitungJumlahDaging($is_panitia, $is_berqurban);

                $qr_path = null;

                $check_existing = $conn->query("SELECT id FROM pembagian_daging WHERE nik = '$nik_warga'");

                if ($check_existing->num_rows > 0) {
                    $update_query = "UPDATE pembagian_daging SET 
                                   jumlah_kg = $jumlah_kg, 
                                   status = 'belum_ambil'
                                   WHERE nik = '$nik_warga'";
                    $conn->query($update_query);
                } else {
                    $insert_query = "INSERT INTO pembagian_daging (nik, jumlah_kg, status, qrcode_path) 
                                   VALUES ('$nik_warga', $jumlah_kg, 'belum_ambil', NULL)";
                    $conn->query($insert_query);
                }

                $total_distributed += $jumlah_kg;
                $count_warga++;
            }

            $conn->commit();
            $message = "Pembagian daging berhasil! Total $count_warga warga mendapat $total_distributed kg daging.";
        }

        if (isset($_POST['bagi_manual'])) {
            $nik_manual = $_POST['nik_manual'];
            $jumlah_manual = floatval($_POST['jumlah_manual']);

            $check_user = $conn->query("SELECT nama FROM users WHERE nik = '$nik_manual'");
            if ($check_user->num_rows == 0) {
                throw new Exception("NIK tidak ditemukan!");
            }

            $user_info = $check_user->fetch_assoc();
            $nama_manual = $user_info['nama'];

            $qr_path = null;

            $check_existing = $conn->query("SELECT id FROM pembagian_daging WHERE nik = '$nik_manual'");

            if ($check_existing->num_rows > 0) {
                $update_query = "UPDATE pembagian_daging SET 
                               jumlah_kg = $jumlah_manual, 
                               status = 'belum_ambil'
                               WHERE nik = '$nik_manual'";
                $conn->query($update_query);
            } else {
                $insert_query = "INSERT INTO pembagian_daging (nik, jumlah_kg, status, qrcode_path) 
                               VALUES ('$nik_manual', $jumlah_manual, 'belum_ambil', NULL)";
                $conn->query($insert_query);
            }

            $conn->commit();
            $message = "Pembagian daging untuk $nama_manual ($nik_manual) berhasil ditambahkan: {$jumlah_manual}kg";
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error: " . $e->getMessage();
    }
}

$stats_query = "SELECT 
    COUNT(*) as total_penerima,
    SUM(jumlah_kg) as total_kg,
    SUM(CASE WHEN status = 'belum_ambil' THEN 1 ELSE 0 END) as belum_ambil,
    SUM(CASE WHEN status = 'sudah_ambil' THEN 1 ELSE 0 END) as sudah_ambil
    FROM pembagian_daging";
$stats = $conn->query($stats_query)->fetch_assoc();

$pembagian_query = "SELECT pd.*, u.nama, u.is_panitia, u.is_berqurban 
                   FROM pembagian_daging pd 
                   JOIN users u ON pd.nik = u.nik 
                   ORDER BY u.nama ASC";
$pembagian_result = $conn->query($pembagian_query);
?>

<?php include '../template/header.php'; ?>

<style>
    body {
        background-color: var(--primary-black) !important;
        color: var(--text-white) !important;
    }

    .content-area {
        background-color: var(--primary-black);
        padding: 2rem 0;
    }

    .container.mt-4 {
        background-color: var(--primary-black);
        border: 1px solid var(--border-dark);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        margin-top: 2rem !important;
        margin-bottom: 2rem !important;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--border-dark);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid #333;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        background-color: #333;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.1);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-green);
        display: block;
    }

    .stat-label {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    .form-section {
        background-color: var(--border-dark);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid #333;
    }

    .form-section h5 {
        color: var(--text-white);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section .form-control {
        background-color: #333;
        border: 1px solid #555;
        color: var(--text-white);
        border-radius: 8px;
        padding: 0.75rem;
    }

    .form-control:focus {
        background-color: #404040;
        border-color: var(--accent-green);
        color: var(--text-white);
        box-shadow: 0 0 0 0.2rem rgba(225, 242, 31, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--accent-green), #b8cc00);
        border: none;
        color: #000;
        font-weight: 600;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #b8cc00, var(--accent-green));
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border: none;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #c82333, #a71e2a);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.3);
    }

    .table-dark {
        background-color: var(--border-dark);
        border-radius: 12px;
        overflow: hidden;
    }

    .table-dark th {
        background-color: #333;
        border-color: #555;
        color: var(--text-white);
        font-weight: 600;
    }

    .table-dark td {
        border-color: #555;
        color: var(--text-light);
    }

    .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-success {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .badge-warning {
        background-color: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .alert {
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.15);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .text-muted {
        color: #ffffff !important;
    }

    input.form-control::placeholder {
        color: #b3b3b3;
    }
</style>

<div class="container mt-4">
    <h4><i class="fas fa-cut"></i> Admin Pembagian Daging Qurban</h4>

    <?php if ($message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= $message ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_penerima'] ?? 0 ?></span>
            <div class="stat-label">Total Penerima</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['total_kg'] ?? 0 ?> kg</span>
            <div class="stat-label">Total Daging</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['belum_ambil'] ?? 0 ?></span>
            <div class="stat-label">Belum Diambil</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['sudah_ambil'] ?? 0 ?></span>
            <div class="stat-label">Sudah Diambil</div>
        </div>
    </div>

    <div class="form-section">
        <h5><i class="fas fa-magic"></i> Pembagian Otomatis</h5>
        <p class="text-muted mb-3">
            Sistem akan membagi daging otomatis berdasarkan kriteria:
            <br>• Warga biasa: 1kg
            <br>• Panitia: +1kg tambahan (total 2kg)
            <br>• Berqurban: +2kg tambahan (total 3kg)
            <br>• Panitia + Berqurban: +3kg tambahan (total 4kg)
        </p>
        <form method="POST">
            <button type="submit" name="bagi_otomatis" class="btn btn-primary"
                onclick="return confirm('Yakin ingin membagi daging otomatis? Data pembagian yang sudah ada akan diperbarui.')">
                <i class="fas fa-magic"></i> Bagi Daging Otomatis
            </button>
            <button type="submit" name="reset_pembagian" class="btn btn-danger ms-2"
                onclick="return confirm('Yakin ingin mereset semua data pembagian? Tindakan ini tidak dapat dibatalkan!')">
                <i class="fas fa-trash"></i> Reset Pembagian
            </button>
        </form>
    </div>

    <div class="form-section">
        <h5><i class="fas fa-user-edit"></i> Pembagian Manual</h5>
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <label for="nik_manual" class="form-label">Masukkan NIK Warga</label>
                <input type="text" class="form-control" id="nik_manual" name="nik_manual"
                    placeholder="Masukkan NIK" required maxlength="16">
            </div>
            <div class="col-md-4">
                <label for="jumlah_manual" class="form-label">Masukkan Jumlah (kg)</label>
                <input type="number" class="form-control" id="jumlah_manual" name="jumlah_manual"
                    placeholder="0.00" step="0.01" min="0" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="bagi_manual" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Jumlah (kg)</th>
                    <th>Status Pengambilan</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($pembagian_result && $pembagian_result->num_rows > 0):
                    while ($row = $pembagian_result->fetch_assoc()):
                        $status_badges = [];
                        if ($row['is_panitia']) $status_badges[] = '<span class="badge badge-success">Panitia</span>';
                        if ($row['is_berqurban']) $status_badges[] = '<span class="badge badge-warning">Berqurban</span>';
                        if (empty($status_badges)) $status_badges[] = '<span class="badge badge-secondary">Warga</span>';
                ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nik']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= implode(' ', $status_badges) ?></td>
                            <td><strong><?= $row['jumlah_kg'] ?> kg</strong></td>
                            <td>
                                <span class="badge badge-<?= $row['status'] == 'sudah_ambil' ? 'success' : 'warning' ?>">
                                    <?= ucwords(str_replace('_', ' ', $row['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['qrcode_path']): ?>
                                    <a href="../qrcode/<?= $row['qrcode_path'] ?>" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-qrcode"></i> Lihat QR
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Belum dibuat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data pembagian</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="../dashboard/index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<?php include '../template/footer.php'; ?>