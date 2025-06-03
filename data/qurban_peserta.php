<?php
require_once '../config/db.php';
if ($_SESSION['role'] !== 'admin') exit;

$sql = "SELECT u.nama, u.nik, h.jenis, q.jumlah_iuran
        FROM qurban_peserta q
        JOIN users u ON q.nik = u.nik
        JOIN hewan_qurban h ON q.hewan_id = h.id
        ORDER BY u.nama";
$result = $conn->query($sql);
?>

<?php include '../template/header.php'; ?>

<!-- Custom CSS untuk halaman ini -->
<style>
    /* Override Body Background untuk halaman dark */
    body {
        background-color: var(--primary-black) !important;
        color: var(--text-white) !important;
    }

    /* Content Area Override untuk Dark Theme */
    .content-area {
        background-color: var(--primary-black);
        padding: 2rem 0;
    }

    /* Container Styles untuk halaman dark */
    .container.mt-4 {
        background-color: var(--primary-black);
        border: 1px solid var(--border-dark);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        margin-top: 2rem !important;
    }

    /* Typography Styles */
    h4 {
        color: var(--text-white) !important;
        font-weight: 700;
        font-size: 1.875rem;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    h4::before {
        content: '\f500';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--accent-green);
        font-size: 1.5rem;
    }

    h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 70px;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-green), #b8cc00);
        border-radius: 2px;
    }

    /* Table Container */
    .table-container {
        background-color: var(--primary-black);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-dark);
        margin-bottom: 2rem;
    }

    /* Table Styles */
    .table {
        background-color: var(--primary-black) !important;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead {
        background: linear-gradient(135deg, var(--accent-green), #b8cc00);
    }

    .table thead th {
        color: var(--primary-black) !important;
        font-weight: 700;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1.25rem 1rem;
        border: none;
        position: relative;
        text-align: center;
    }

    .table thead th:first-child {
        text-align: left;
    }

    .table thead th::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        width: 1px;
        height: 60%;
        background-color: rgba(18, 18, 18, 0.2);
    }

    .table thead th:last-child::after {
        display: none;
    }

    /* Add icons to table headers */
    .table thead th:nth-child(1)::before {
        content: '\f007';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
    }

    .table thead th:nth-child(2)::before {
        content: '\f2c2';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
    }

    .table thead th:nth-child(3)::before {
        content: '\f6d7';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
    }

    .table thead th:nth-child(4)::before {
        content: '\f4c0';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
    }

    .table tbody td {
        color: var(--text-light) !important;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid var(--border-dark);
        border-left: none;
        border-right: none;
        font-weight: 400;
        vertical-align: middle;
        text-align: center;
    }

    .table tbody td:first-child {
        text-align: left;
        font-weight: 500;
        color: var(--text-white) !important;
    }

    .table tbody tr {
        background-color: var(--primary-black);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #1a1a1a !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(225, 242, 31, 0.1);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Jenis Hewan Badges */
    .hewan-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .hewan-sapi {
        background-color: rgba(139, 69, 19, 0.2);
        color: #8B4513;
        border: 1px solid rgba(139, 69, 19, 0.3);
    }

    .hewan-kambing {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Currency Formatting */
    .currency {
        font-weight: 600;
        color: var(--accent-green) !important;
        font-family: 'Courier New', monospace;
    }

    /* Statistics Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background-color: var(--border-dark);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #333;
        transition: all 0.3s ease;
        text-align: center;
    }

    .stat-card:hover {
        background-color: #333;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.1);
    }

    .stat-icon {
        font-size: 2rem;
        color: var(--accent-green);
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-white);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Button Styles */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 2px solid transparent;
    }

    .btn-secondary {
        background-color: var(--border-dark);
        border-color: var(--border-dark);
        color: var(--text-light) !important;
    }

    .btn-secondary:hover {
        background-color: #404040;
        border-color: #404040;
        color: var(--text-white) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(42, 42, 42, 0.3);
    }

    .btn-secondary::before {
        content: '\f060';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: var(--text-light);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--border-dark);
        margin-bottom: 1rem;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .container.mt-4 {
            padding: 1.5rem;
            margin: 1rem !important;
        }

        h4 {
            font-size: 1.5rem;
        }

        .table-responsive {
            border-radius: 12px;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .table thead th::before {
            display: none;
        }

        .hewan-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }
    }
</style>

<div class="container mt-4">
    <h4>Data Peserta Qurban</h4>

    <?php
    // Hitung statistik
    $total_peserta = $result->num_rows;
    $total_iuran = 0;
    $jenis_count = ['sapi' => 0, 'kambing' => 0];

    // Reset pointer dan hitung statistik
    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $total_iuran += $row['jumlah_iuran'];
        $jenis_count[strtolower($row['jenis'])]++;
    }
    $result->data_seek(0); // Reset lagi untuk tampilan tabel
    ?>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number"><?= $total_peserta ?></div>
            <div class="stat-label">Total Peserta</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number">Rp <?= number_format($total_iuran / 1, 1) ?></div>
            <div class="stat-label">Total Iuran</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-cow"></i>
            </div>
            <div class="stat-number"><?= $jenis_count['sapi'] ?></div>
            <div class="stat-label">Peserta Sapi</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-sheep"></i>
            </div>
            <div class="stat-number"><?= $jenis_count['kambing'] ?></div>
            <div class="stat-label">Peserta Kambing</div>
        </div>
    </div>

    <?php if ($total_peserta > 0): ?>
        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Jenis Hewan</th>
                            <th>Iuran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['nik']) ?></td>
                                <td>
                                    <span class="hewan-badge hewan-<?= strtolower($row['jenis']) ?>">
                                        <i class="fas fa-<?= $row['jenis'] == 'sapi' ? 'cow' : 'sheep' ?>"></i>
                                        <?= ucfirst($row['jenis']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="currency">Rp <?= number_format($row['jumlah_iuran']) ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h5>Belum Ada Data Peserta</h5>
            <p>Data peserta qurban belum tersedia.</p>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../dashboard/admin.php" class="btn btn-secondary">
            Kembali ke Dashboard Admin
        </a>
    </div>
</div>

<?php include '../template/footer.php'; ?>