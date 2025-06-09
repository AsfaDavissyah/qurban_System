<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$result = $conn->query("SELECT * FROM users ORDER BY nama");
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

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
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

    .btn-qurban {
        background: rgba(34, 197, 94, 0.2);
        color: #4ade80;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .btn-qurban:hover {
        background: #22c55e;
        color: white;
        text-decoration: none;
        transform: scale(1.05);
    }

    .btn-qurban:disabled {
        background: rgba(107, 114, 128, 0.2);
        color: #6b7280;
        border: 1px solid rgba(107, 114, 128, 0.3);
        cursor: not-allowed;
        transform: none;
    }

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

    .table tbody tr.loading {
        opacity: 0.6;
        pointer-events: none;
    }

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

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border: 1px solid;
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
</style>
</head>

<body>
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php
                switch ($_GET['success']) {
                    case 'processed':
                        echo 'Data warga berhasil diproses dan ditambahkan ke daftar peserta qurban!';
                        break;
                    case 'deleted':
                        $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : 'Warga';
                        echo "Data {$nama} berhasil dihapus dari sistem!";
                        break;
                    case 'updated':
                        $nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : 'Warga';
                        echo "Data {$nama} berhasil diperbarui!";
                        break;
                    default:
                        echo 'Operasi berhasil dilakukan!';
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php
                switch ($_GET['error']) {
                    case 'already_processed':
                        echo 'Warga ini sudah terdaftar sebagai peserta qurban!';
                        break;
                    case 'not_berqurban':
                        echo 'Warga ini tidak terdaftar sebagai penyembelih qurban!';
                        break;
                    case 'failed':
                        echo 'Gagal memproses data. Silakan coba lagi!';
                        break;
                    case 'delete_failed':
                        echo 'Gagal menghapus data warga. Silakan coba lagi!';
                        break;
                    case 'user_not_found':
                        echo 'Data warga tidak ditemukan!';
                        break;
                    case 'constraint_error':
                        echo 'Tidak dapat menghapus warga karena masih terkait dengan data lain. Hapus data terkait terlebih dahulu!';
                        break;
                    case 'invalid_request':
                        echo 'Permintaan tidak valid!';
                        break;
                    default:
                        echo 'Terjadi kesalahan. Silakan coba lagi!';
                }
                ?>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h4><i class="fas fa-users me-2"></i>Data Warga</h4>
            <div class="header-actions">
                <a href="register_user.php" class="btn-success">
                    <i class="fas fa-plus"></i>
                    Tambah Warga
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-id-card me-1"></i>NIK</th>
                            <th><i class="fas fa-user me-1"></i>Nama</th>
                            <th><i class="fas fa-users-cog me-1"></i>Panitia</th>
                            <th><i class="fas fa-cow me-1"></i>Qurban</th>
                            <th><i class="fas fa-phone me-1"></i>Telepon</th>
                            <th><i class="fas fa-cogs me-1"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $processed_users = [];
                        $processed_result = $conn->query("SELECT nik FROM qurban_peserta");
                        while ($processed_row = $processed_result->fetch_assoc()) {
                            $processed_users[] = $processed_row['nik'];
                        }

                        while ($row = $result->fetch_assoc()):
                            $is_processed = in_array($row['nik'], $processed_users);
                        ?>
                            <tr>
                                <td><?= $row['nik'] ?></td>
                                <td><?= $row['nama'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $row['is_panitia'] ? 'success' : 'secondary' ?>">
                                        <?= $row['is_panitia'] ? 'Ya' : 'Tidak' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $row['is_berqurban'] ? 'success' : 'secondary' ?>">
                                        <?= $row['is_berqurban'] ? 'Ya' : 'Tidak' ?>
                                    </span>
                                </td>
                                <td><?= $row['telepon'] ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_user.php?nik=<?= urlencode($row['nik']) ?>" class="btn-action btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn-action btn-delete" title="Hapus" onclick="confirmDelete('<?= htmlspecialchars($row['nik'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['nama'], ENT_QUOTES) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php if ($row['is_berqurban']): ?>
                                            <?php if ($is_processed): ?>
                                                <button class="btn-action btn-qurban" disabled title="Sudah Diproses">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php else: ?>
                                                <a href="process_qurban.php?nik=<?= urlencode($row['nik']) ?>" class="btn-action btn-qurban" title="Proses ke Peserta Qurban">
                                                    <i class="fas fa-plus-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-start">
            <a href="../dashboard/admin.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete(nik, nama) {
            if (confirm(`Apakah Anda yakin ingin menghapus data warga "${nama}" dengan NIK ${nik}?\n\nData yang dihapus tidak dapat dikembalikan dan akan menghapus semua data terkait (qurban, hewan, dll).`)) {
                const button = event.target.closest('.btn-delete');
                const originalContent = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.style.pointerEvents = 'none';

                window.location.href = `delete_user.php?nik=${encodeURIComponent(nik)}`;
            }
        }

        function confirmProcess(nik, nama) {
            if (confirm(`Apakah Anda yakin ingin memproses data warga "${nama}" ke daftar peserta qurban?\n\nData akan ditambahkan ke tabel qurban_peserta.`)) {
                const row = event.target.closest('tr');
                addLoadingState(row);

                window.location.href = `process_qurban.php?nik=${encodeURIComponent(nik)}`;
            }
        }

        function addLoadingState(row) {
            row.classList.add('loading');
        }

        function removeLoadingState(row) {
            row.classList.remove('loading');
        }

        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            });
        }, 5000);

        document.querySelectorAll('.btn-action').forEach(function(btn) {
            btn.addEventListener('click', function() {
                setTimeout(() => {
                    this.style.pointerEvents = 'auto';
                }, 1000);
            });
        });
    </script>
    <?php include '../template/footer.php'; ?>