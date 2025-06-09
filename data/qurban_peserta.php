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

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #121212 !important;
        color: #ffffff !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        line-height: 1.6;
        min-height: 100vh;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    h4 {
        font-size: 2rem;
        font-weight: 600;
        color: #ffffff !important;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    h4 i {
        color: #ffffff;
        font-size: 1.5rem;
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #2a2a2a;
        transition: all 0.3s ease;
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card:hover {
        background: #2a2a2a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.1);
    }

    .stat-icon {
        font-size: 2rem;
        color: #e1f21f;
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #b3b3b3;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-container {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #2a2a2a;
        overflow-x: auto;
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    .table {
        color: #ffffff;
        margin: 0;
        width: 100%;
        border-collapse: collapse;
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
        text-align: left;
    }

    .table thead th:nth-child(3),
    .table thead th:nth-child(4) {
        text-align: center;
    }

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
        background: transparent;
        border-bottom: 1px solid #2a2a2a;
        padding: 1rem 0.75rem;
        vertical-align: middle;
        color: #ffffff;
        text-align: left;
    }

    .table tbody td:nth-child(3),
    .table tbody td:nth-child(4) {
        text-align: center;
    }

    .table tbody tr:hover {
        background: rgba(225, 242, 31, 0.05);
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .hewan-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .hewan-sapi {
        background: rgba(139, 69, 19, 0.2);
        color: #cd853f;
        border: 1px solid rgba(139, 69, 19, 0.3);
    }

    .hewan-kambing {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .currency {
        font-weight: 600;
        color: #e1f21f !important;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }

    .btn-secondary {
        background: #2a2a2a;
        color: #ffffff;
        border: 1px solid #404040;
    }

    .btn-secondary:hover {
        background: #404040;
        color: #ffffff;
        text-decoration: none;
        border-color: #555555;
    }

    .btn-secondary::before {
        content: '\f060';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #b3b3b3;
    }

    .empty-state i {
        font-size: 4rem;
        color: #404040;
        margin-bottom: 1rem;
    }

    .empty-state h5 {
        color: #ffffff;
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .empty-state p {
        color: #b3b3b3;
        font-size: 1rem;
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

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        h4 {
            font-size: 1.5rem;
        }

        .stats-container {
            grid-template-columns: 1fr;
        }

        .table-container {
            padding: 1rem;
        }

        .table thead th,
        .table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }

        .table thead th::before {
            display: none;
        }

        .hewan-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
        }

        .btn {
            width: 100%;
            margin: 0.25rem 0;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .stat-card {
            margin: 0 0.5rem;
        }

        .table-container {
            margin: 0 0.5rem 2rem 0.5rem;
        }
    }

    *:focus {
        outline: 2px solid #e1f21f;
        outline-offset: 2px;
    }

    .text-center {
        text-align: center;
    }

    .text-center .btn {
        margin: 0.25rem;
    }
</style>

<div class="container mt-4">
    <div class="page-header">
        <h4><i class="fas fa-person"></i> Data Peserta Qurban</h4>
        <div class="header-actions">
        </div>
    </div>
    
    <?php
    $total_peserta = $result->num_rows;
    $total_iuran = 0;
    $jenis_count = ['sapi' => 0, 'kambing' => 0];

    $result->data_seek(0);
    while ($row = $result->fetch_assoc()) {
        $total_iuran += $row['jumlah_iuran'];
        $jenis_count[strtolower($row['jenis'])]++;
    }
    $result->data_seek(0); 
    ?>

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
                <i class="fas fa-fish"></i>
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
                                        <i class="fas fa-<?= $row['jenis'] == 'sapi' ? 'cow' : 'fish' ?>"></i>
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