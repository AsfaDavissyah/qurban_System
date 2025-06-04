<?php
require_once '../config/db.php';
if ($_SESSION['role'] !== 'admin') exit;

// Handle form submission
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

// Handle delete action
if (
    isset($_POST['action']) &&
    $_POST['action'] === 'hapus' &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("DELETE FROM hewan_qurban WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $success_message = "Hewan qurban berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus hewan qurban!";
    }
}

$result = $conn->query("SELECT * FROM hewan_qurban ORDER BY tanggal DESC");
?>

<?php include '../template/header.php'; ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #121212;
        color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        min-height: 100vh;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header h4 {
        font-size: 2rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    /* Buttons */
    .btn-success {
        background: #e1f21f;
        color: #121212;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: #c8d91a;
        color: #121212;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(225, 242, 31, 0.3);
    }

    .btn-secondary {
        background: #2a2a2a;
        color: #ffffff;
        border: 1px solid #404040;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #404040;
        color: #ffffff;
        text-decoration: none;
        border-color: #555555;
    }

    /* Form Section */
    .form-section {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #2a2a2a;
        margin-bottom: 2rem;
    }

    .form-title {
        color: #e1f21f;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #b3b3b3;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #2a2a2a;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        background-color: #121212;
        color: #ffffff;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #e1f21f;
        box-shadow: 0 0 0 0.2rem rgba(225, 242, 31, 0.25);
        background-color: #121212;
        color: #ffffff;
        outline: none;
    }

    .form-control option {
        background-color: #121212;
        color: #ffffff;
    }

    .row {
        display: flex;
        gap: 1rem;
        margin: 0 -0.5rem;
    }

    .col-md-6 {
        flex: 1;
        padding: 0 0.5rem;
    }

    .text-center {
        text-align: center;
    }

    /* Alert Messages */
    .alert {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid #2a2a2a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        color: #4ade80;
        border-color: rgba(34, 197, 94, 0.3);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border-color: rgba(239, 68, 68, 0.3);
    }

    /* Statistics Cards */
    .stats-row {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        flex: 1;
        background-color: #1e1e1e;
        border: 1px solid #2a2a2a;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--card-accent), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        background-color: #2a2a2a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.1);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--stat-color);
    }

    .stat-label {
        color: #b3b3b3;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-description {
        color: #808080;
        font-size: 0.75rem;
        font-style: italic;
        margin-top: 4px;
    }

    .stat-icon {
        font-size: 1.2rem;
        opacity: 0.8;
    }

    /* Different colors and accents for different stats */
    .stat-card:nth-child(1) {
        --stat-color: #e1f21f;
        --card-accent: #e1f21f;
    }

    .stat-card:nth-child(1) .stat-number {
        color: #e1f21f;
    }

    .stat-card:nth-child(2) {
        --stat-color: #28a745;
        --card-accent: #28a745;
    }

    .stat-card:nth-child(2) .stat-number {
        color: #28a745;
    }

    .stat-card:nth-child(3) {
        --stat-color: #17a2b8;
        --card-accent: #17a2b8;
    }

    .stat-card:nth-child(3) .stat-number {
        color: #17a2b8;
    }

    .stat-card:nth-child(4) {
        --stat-color: #ffc107;
        --card-accent: #ffc107;
    }

    .stat-card:nth-child(4) .stat-number {
        color: #ffc107;
    }

    /* Table Styling */
    .table-container {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #2a2a2a;
        overflow-x: auto;
        margin-bottom: 2rem;
    }

    .table {
        color: #ffffff;
        margin: 0;
        width: 100%;
    }

    .table thead th {
        background: #2a2a2a;
        color: #e1f21f;
        border: none;
        padding: 1rem 0.75rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody td {
        background: transparent;
        border-bottom: 1px solid #2a2a2a;
        padding: 1rem 0.75rem;
        vertical-align: middle;
        color: #ffffff;
    }

    .table tbody tr:hover {
        background: rgba(225, 242, 31, 0.05);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Animal Type Badges */
    .animal-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .badge-sapi {
        background: rgba(225, 242, 31, 0.2);
        color: #e1f21f;
        border: 1px solid rgba(225, 242, 31, 0.3);
    }

    .badge-kambing {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .price-tag {
        background: rgba(225, 242, 31, 0.2);
        color: #e1f21f;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid rgba(225, 242, 31, 0.3);
    }

    .admin-fee {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .date-badge {
        background: rgba(108, 117, 125, 0.2);
        color: #b3b3b3;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-size: 0.8rem;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: none;
        font-size: 0.85rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        min-width: 36px;
        height: 36px;
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        background: #ef4444;
        color: white;
        text-decoration: none;
        transform: scale(1.05);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #b3b3b3;
    }

    .empty-state i {
        font-size: 3rem;
        color: #404040;
        margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .page-header h4 {
            font-size: 1.5rem;
        }

        .header-actions {
            justify-content: stretch;
        }

        .stats-row {
            flex-direction: column;
            gap: 15px;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .row {
            flex-direction: column;
            gap: 0;
        }

        .col-md-6 {
            padding: 0;
        }

        .table-container {
            padding: 1rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-action {
            min-width: auto;
            width: 100%;
        }
    }

    /* Animation */
    .form-section,
    .table-container,
    .stats-row {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h4><i class="fas fa-cow"></i> Manajemen Hewan Qurban</h4>
        <div class="header-actions">
            <!-- Add button if needed -->
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <strong>Berhasil!</strong> <?= $success_message ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Error!</strong> <?= $error_message ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="form-section">
        <h3 class="form-title"><i class="fas fa-plus-circle"></i> Tambah Hewan Qurban Baru</h3>
        <form method="POST" action="">
            <input type="hidden" name="action" value="tambah">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Jenis Hewan</label>
                        <select name="jenis" class="form-control" required>
                            <option value="">Pilih Jenis Hewan</option>
                            <option value="sapi">🐄 Sapi</option>
                            <option value="kambing">🐐 Kambing</option>
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
                <button type="submit" class="btn-success">
                    <i class="fas fa-plus"></i> Tambah Hewan Qurban
                </button>
            </div>
        </form>
    </div>

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
            <div class="stat-number">
                <i class="fas fa-list stat-icon"></i>
                <?= $stats['total_entries'] ?: 0 ?>
            </div>
            <div class="stat-label">Total Entri</div>
            <div class="stat-description">Jumlah Data Hewan Qurban</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <i class="fas fa-paw stat-icon"></i>
                <?= $stats['total_animals'] ?: 0 ?>
            </div>
            <div class="stat-label">Total Hewan</div>
            <div class="stat-description">Jumlah Hewan Keseluruhan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <i class="fas fa-money-bill-wave stat-icon"></i>
                Rp <?= number_format($stats['total_value'] ?: 0) ?>
            </div>
            <div class="stat-label">Total Nilai</div>
            <div class="stat-description">Harga Total Semua Hewan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <i class="fas fa-percentage stat-icon"></i>
                Rp <?= number_format($stats['total_admin_fee'] ?: 0) ?>
            </div>
            <div class="stat-label">Total Biaya Admin</div>
            <div class="stat-description">Biaya Admin Keseluruhan</div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        <?php
        $result = $conn->query("SELECT * FROM hewan_qurban ORDER BY tanggal DESC");
        if ($result->num_rows > 0):
        ?>
            <table class="table">
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
                                <span class="animal-badge badge-<?= $row['jenis'] ?>">
                                    <i class="fas <?= $row['jenis'] == 'sapi' ? 'fa-cow' : 'fa-fish' ?>"></i>
                                    <?= ucfirst($row['jenis']) ?>
                                </span>
                            </td>
                            <td><strong><?= $row['jumlah'] ?> ekor</strong></td>
                            <td><span class="price-tag">Rp <?= number_format($row['harga_total']) ?></span></td>
                            <td><span class="admin-fee">Rp <?= number_format($row['biaya_admin']) ?></span></td>
                            <td><span class="date-badge"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></span></td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        <input type="hidden" name="action" value="hapus">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn-action btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-cow"></i>
                <h3>Belum Ada Data Hewan Qurban</h3>
                <p>Silakan tambahkan hewan qurban menggunakan form di atas</p>
            </div>
        <?php endif; ?>
    </div>

    <a href="../dashboard/admin.php" class="btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Dashboard
    </a>
</div>

<?php include '../template/footer.php'; ?>