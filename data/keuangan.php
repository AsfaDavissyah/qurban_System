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

        /* Status Badges */
        .badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.2);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-secondary {
            background: rgba(107, 114, 128, 0.2);
            color: #9ca3af;
            border: 1px solid rgba(107, 114, 128, 0.3);
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
        .table-container {
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
        
        <div class="table-container">
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
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= ucfirst($row['jenis']) ?></td>
                            <td><?= ucfirst($row['kategori']) ?></td>
                            <td>Rp <?= number_format($row['jumlah']) ?></td>
                            <td><?= $row['keterangan'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <a href="../dashboard/<?= $_SESSION['role'] ?>.php" class="btn btn-secondary">Kembali</a>
    </div>
<?php include '../template/footer.php'; ?>