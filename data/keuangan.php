<?php

require_once '../config/db.php';
if (!isset($_SESSION['nik']) || (!$_SESSION['is_panitia'] && $_SESSION['role'] !== 'admin')) {
    header("Location: ../index.php");
    exit;
}

$result = $conn->query("SELECT * FROM keuangan ORDER BY tanggal DESC");
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
        --stat-color: #28a745;
        --card-accent: #28a745;
    }

    .stat-card:nth-child(1) .stat-number {
        color: #28a745;
    }

    .stat-card:nth-child(2) {
        --stat-color: #dc3545;
        --card-accent: #dc3545;
    }

    .stat-card:nth-child(2) .stat-number {
        color: #dc3545;
    }

    .stat-card:nth-child(3) {
        --stat-color: #e1f21f;
        --card-accent: #e1f21f;
    }

    .stat-card:nth-child(3) .stat-number {
        color: #e1f21f;
    }

    /* Positive/Negative Saldo Styling */
    .stat-card.positive .stat-number {
        color: #28a745;
    }

    .stat-card.negative .stat-number {
        color: #dc3545;
    }

    .stat-card.zero .stat-number {
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

    /* Transaction Type Badges */
    .transaction-badge {
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

    .badge-pemasukan {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .badge-pengeluaran {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .amount-positive {
        color: #28a745;
        font-weight: 600;
    }

    .amount-negative {
        color: #dc3545;
        font-weight: 600;
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

    .btn-edit {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .btn-edit:hover {
        background: #3b82f6;
        color: white;
        text-decoration: none;
        transform: scale(1.05);
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

    /* Loading States */
    .table tbody tr.loading {
        opacity: 0.6;
        pointer-events: none;
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

    /* Animation */
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
        <h4>Data Keuangan Qurban</h4>
        <div class="header-actions">
            <a href="input_keuangan.php" class="btn-success">+ Tambah Transaksi</a>
        </div>
    </div>

    <?php
    // Get total from hewan_qurban (harga_total + biaya_admin)
    $result_hewan = $conn->query("SELECT 
            SUM(harga_total + biaya_admin) as total_pemasukan_hewan
            FROM hewan_qurban");
    $hewan_stats = $result_hewan->fetch_assoc();
    $total_pemasukan_hewan = $hewan_stats['total_pemasukan_hewan'] ?: 0;

    // Get total pengeluaran from keuangan table
    $result_keuangan = $conn->query("SELECT 
            SUM(CASE WHEN jenis = 'pemasukan' THEN jumlah ELSE 0 END) as total_pemasukan_keuangan
            FROM keuangan");
    $keuangan_stats = $result_keuangan->fetch_assoc();
    $total_pemasukan_keuangan = $keuangan_stats['total_pemasukan_keuangan'] ?: 0;

    // Calculate saldo
    $saldo_total = $total_pemasukan_hewan - $total_pemasukan_keuangan;

    // Determine saldo class for styling
    $saldo_class = '';
    if ($saldo_total > 0) {
        $saldo_class = 'positive';
    } elseif ($saldo_total < 0) {
        $saldo_class = 'negative';
    } else {
        $saldo_class = 'zero';
    }
    ?>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number">
                <i class="fas fa-arrow-up stat-icon"></i>
                Rp <?= number_format($total_pemasukan_hewan) ?>
            </div>
            <div class="stat-label">Total Pemasukan</div>
            <div class="stat-description">Dari Hewan Qurban (Harga + Admin)</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <i class="fas fa-arrow-down stat-icon"></i>
                Rp <?= number_format($total_pemasukan_keuangan) ?>
            </div>
            <div class="stat-label">Total Pengeluaran</div>
            <div class="stat-description">Dari Transaksi Keuangan</div>
        </div>
        <div class="stat-card <?= $saldo_class ?>">
            <div class="stat-number">
                <i class="fas fa-wallet stat-icon"></i>
                Rp <?= number_format($saldo_total) ?>
            </div>
            <div class="stat-label">Saldo Total</div>
            <div class="stat-description">Pemasukan - Pengeluaran</div>
        </div>
    </div>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <span class="transaction-badge badge-<?= $row['jenis'] ?>">
                                    <i class="fas fa-<?= $row['jenis'] == 'pemasukan' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                    <?= ucfirst($row['jenis']) ?>
                                </span>
                            </td>
                            <td><?= ucwords(str_replace('_', ' ', $row['kategori'])) ?></td>
                            <td>
                                <span class="amount-<?= $row['jenis'] == 'pemasukan' ? 'positive' : 'negative' ?>">
                                    <?= $row['jenis'] == 'pemasukan' ? '+' : '-' ?> Rp <?= number_format($row['jumlah']) ?>
                                </span>
                            </td>
                            <td><?= $row['keterangan'] ?: '-' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <h3>Belum Ada Transaksi</h3>
                <p>Silakan tambahkan transaksi keuangan menggunakan tombol di atas</p>
            </div>
        <?php endif; ?>
    </div>

    <a href="../dashboard/<?= $_SESSION['role'] ?>.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>
<?php include '../template/footer.php'; ?>