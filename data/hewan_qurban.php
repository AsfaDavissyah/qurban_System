<?php
require_once '../config/db.php';
if ($_SESSION['role'] !== 'admin') exit;

// Handle form submission


// Handle delete action


$result = $conn->query("SELECT * FROM hewan_qurban ORDER BY tanggal DESC");
?>

<?php include '../template/header.php'; ?>

<style>
    /* Dark Theme CSS for Hewan Qurban Page */
    .qurban-container {
        background-color: #121212;
        min-height: 100vh;
        padding: 20px 0;
    }

    .content-wrapper {
        background-color: #121212;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
        margin: 20px auto;
        max-width: 1200px;
    }

    .page-header {
        background-color: #121212;
        color: #ffffff;
        padding: 30px;
        text-align: center;
        border-bottom: 1px solid #2a2a2a;
    }

    .page-header h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        color: #ffffff;
    }

    .page-header .subtitle {
        margin-top: 10px;
        color: #b3b3b3;
        font-size: 1.2rem;
    }

    .form-section {
        background-color: #121212;
        padding: 30px;
        border-bottom: 1px solid #2a2a2a;
    }

    .form-card {
        background-color: #121212;
        border-radius: 8px;
        padding: 25px;
        border: 1px solid #2a2a2a;
    }

    .form-title {
        color: #e1f21f;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #b3b3b3;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border-radius: 4px;
        border: 1px solid #2a2a2a;
        padding: 12px 15px;
        font-size: 1rem;
        background-color: #121212;
        color: #ffffff;
    }

    .form-control:focus {
        border-color: #e1f21f;
        box-shadow: 0 0 0 0.2rem rgba(225, 242, 31, 0.25);
        background-color: #121212;
        color: #ffffff;
    }

    .form-control option {
        background-color: #121212;
        color: #ffffff;
    }

    .btn-submit {
        background-color: #e1f21f;
        border: none;
        color: #121212;
        padding: 12px 30px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .btn-submit:hover {
        background-color: #c8d91b;
        color: #121212;
    }

    .data-section {
        padding: 30px;
        background-color: #121212;
    }

    .data-table {
        background-color: #121212;
        border-radius: 8px;
        border: 1px solid #2a2a2a;
    }

    .table {
        margin: 0;
        color: #ffffff;
    }

    .table thead th {
        background-color: #2a2a2a;
        color: #ffffff;
        font-weight: 600;
        border: 1px solid #2a2a2a;
        padding: 15px 12px;
        font-size: 0.95rem;
    }

    .table tbody td {
        padding: 15px 12px;
        vertical-align: middle;
        border-color: #2a2a2a;
        background-color: #121212;
        color: #ffffff;
    }

    .table tbody tr:hover {
        background-color: #1a1a1a;
    }

    .price-tag {
        background-color: #e1f21f;
        color: #121212;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .admin-fee {
        background-color: #2a2a2a;
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .date-badge {
        background-color: #2a2a2a;
        color: #b3b3b3;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
    }

    .btn-delete {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .btn-delete:hover {
        background-color: #c82333;
        color: white;
    }

    .btn-back {
        background-color: #2a2a2a;
        border: none;
        color: #ffffff;
        padding: 12px 25px;
        border-radius: 4px;
        font-weight: 600;
        margin-top: 20px;
    }

    .btn-back:hover {
        background-color: #3a3a3a;
        color: #ffffff;
    }

    .alert {
        border-radius: 4px;
        padding: 15px 20px;
        margin-bottom: 20px;
        border: 1px solid #2a2a2a;
    }

    .alert-success {
        background-color: #1a2f1a;
        color: #e1f21f;
        border-color: #2a2a2a;
    }

    .alert-danger {
        background-color: #2f1a1a;
        color: #ff6b6b;
        border-color: #2a2a2a;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #b3b3b3;
    }

    .empty-state .icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        flex: 1;
        background-color: #121212;
        border: 1px solid #2a2a2a;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #e1f21f;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #b3b3b3;
        font-size: 0.9rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 2rem;
        }

        .stats-row {
            flex-direction: column;
        }

        .form-section,
        .data-section {
            padding: 20px;
        }

        .table-responsive {
            font-size: 0.9rem;
        }
    }
</style>

<div class="qurban-container">
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-cow"></i> Manajemen Hewan Qurban</h1>
            <p class="subtitle">Kelola data hewan qurban dengan mudah dan efisien</p>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <strong><i class="fas fa-check-circle"></i> Berhasil!</strong> <?= $success_message ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-circle"></i> Error!</strong> <?= $error_message ?>
                </div>
            <?php endif; ?>

            <div class="form-card">
                <h3 class="form-title"><i class="fas fa-plus-circle"></i> Tambah Hewan Qurban Baru</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="tambah">
                    <?php
                    if (
                        isset($_POST['action']) &&
                        $_POST['action'] === 'tambah' &&
                        $_SERVER['REQUEST_METHOD'] === 'POST'
                    ) {
                        $jenis = $_POST['jenis'];
                        $jumlah = (int)$_POST['jumlah'];
                        $harga_total = (int)$_POST['harga_total'];
                        $biaya_admin = (int)$_POST['biaya_admin'];
                        $tanggal = date('Y-m-d');

                        $stmt = $conn->prepare("INSERT INTO hewan_qurban (jenis, jumlah, harga_total, biaya_admin, tanggal) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("siiis", $jenis, $jumlah, $harga_total, $biaya_admin, $tanggal);

                        if ($stmt->execute()) {
                            $success_message = "Hewan qurban berhasil ditambahkan!";
                        } else {
                            $error_message = "Gagal menambahkan hewan qurban!";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jenis Hewan</label>
                                <select name="jenis" class="form-control" required>
                                    <option value="">Pilih Jenis Hewan</option>
                                    <option value="sapi"><i class="fas fa-cow"></i> Sapi</option>
                                    <option value="kambing"><i class="fas fa-goat"></i> Kambing</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Jumlah Hewan</label>
                                <input type="number" name="jumlah" class="form-control" min="1" required placeholder="Masukkan jumlah hewan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Harga Total (Rp)</label>
                                <input type="number" name="harga_total" class="form-control" min="0" required placeholder="Masukkan harga total">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Biaya Admin (Rp)</label>
                                <input type="number" name="biaya_admin" class="form-control" min="0" required placeholder="Masukkan biaya admin">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-plus"></i> Tambah Hewan Qurban
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Section -->
        <div class="data-section">
            <?php
            // Calculate statistics
            $result_stats = $conn->query("SELECT 
                COUNT(*) as total_entries,
                SUM(jumlah) as total_animals,
                SUM(harga_total) as total_value,
                SUM(biaya_admin) as total_admin_fee
                FROM hewan_qurban");
            $stats = $result_stats->fetch_assoc();
            ?>

            <!-- Statistics -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total_entries'] ?: 0 ?></div>
                    <div class="stat-label">Total Entri</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $stats['total_animals'] ?: 0 ?></div>
                    <div class="stat-label">Total Hewan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">Rp <?= number_format($stats['total_value'] ?: 0) ?></div>
                    <div class="stat-label">Total Nilai</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">Rp <?= number_format($stats['total_admin_fee'] ?: 0) ?></div>
                    <div class="stat-label">Total Biaya Admin</div>
                </div>
            </div>

            <div class="data-table">
                <?php
                $result = $conn->query("SELECT * FROM hewan_qurban ORDER BY tanggal DESC");
                if ($result->num_rows > 0):
                ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Jenis Hewan</th>
                                    <th>Jumlah</th>
                                    <th>Harga Total</th>
                                    <th>Biaya Admin</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <i class="fas <?= $row['jenis'] == 'sapi' ? 'fa-cow' : 'fa-fish' ?>"></i>
                                            <?= ucfirst($row['jenis']) ?>
                                        </td>
                                        <td><strong><?= $row['jumlah'] ?> ekor</strong></td>
                                        <td><span class="price-tag">Rp <?= number_format($row['harga_total']) ?></span></td>
                                        <td><span class="admin-fee">Rp <?= number_format($row['biaya_admin']) ?></span></td>
                                        <td><span class="date-badge"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span></td>
                                        <td>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                <input type="hidden" name="action" value="hapus">
                                                <?php
                                                        if (
                                                            isset($_POST['action']) == 'hapus' && $_SERVER['REQUEST_METHOD'] == 'POST') {
                                                            $id = (int)$_POST['id'];
                                                            $stmt = $conn->prepare("DELETE FROM hewan_qurban WHERE id = ?");
                                                            $stmt->bind_param("i", $id);

                                                            if ($stmt->execute()) {
                                                                $success_message = "Hewan qurban berhasil dihapus!";
                                                            } else {
                                                                $error_message = "Gagal menghapus hewan qurban!";
                                                            }
                                                        }
                                                        ?>
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-delete">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-cow"></i></div>
                        <h3>Belum Ada Data Hewan Qurban</h3>
                        <p>Silakan tambahkan hewan qurban menggunakan form di atas</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-center">
                <a href="../dashboard/admin.php" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>