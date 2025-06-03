<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}

$nik = $_SESSION['nik'];
$result = $conn->query("SELECT * FROM pembagian_daging WHERE nik = '$nik'");
$data = $result->fetch_assoc();
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
        content: '\f2e7';
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

    /* Data Display Styles */
    .data-item {
        background-color: var(--border-dark);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #333;
        transition: all 0.3s ease;
    }

    .data-item:hover {
        background-color: #333;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(225, 242, 31, 0.1);
    }

    .data-item p {
        color: var(--text-light) !important;
        font-weight: 400;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        line-height: 1.6;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .data-item p strong {
        color: var(--text-white) !important;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 140px;
    }

    .data-value {
        color: var(--accent-green) !important;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Status Styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-tersedia {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .status-diambil {
        background-color: rgba(225, 242, 31, 0.2);
        color: var(--accent-green);
        border: 1px solid rgba(225, 242, 31, 0.3);
    }

    /* QR Code Container */
    .qr-container {
        background-color: var(--border-dark);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        margin: 2rem 0;
        border: 1px solid #333;
        transition: all 0.3s ease;
    }

    .qr-container:hover {
        background-color: #333;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(225, 242, 31, 0.15);
    }

    .qr-container img {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        margin-bottom: 1rem;
        border: 3px solid var(--accent-green);
    }

    .qr-title {
        color: var(--text-white);
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Alert Styles */
    .alert {
        border: none;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .alert-warning {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .alert-warning::before {
        content: '\f071';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 1.2rem;
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
        margin: 0.5rem;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
        color: white !important;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #218838, #1fa085);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.3);
        color: white !important;
    }

    .btn-success::before {
        content: '\f019';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
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

    /* Responsive Styles */
    @media (max-width: 768px) {
        .container.mt-4 {
            padding: 1.5rem;
            margin: 1rem !important;
        }

        h4 {
            font-size: 1.5rem;
        }

        .data-item p {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .data-item p strong {
            min-width: auto;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .qr-container {
            padding: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .qr-container img {
            max-width: 150px;
        }
    }
</style>

<div class="container mt-4">
    <h4>Distribusi Daging Saya</h4>

    <?php if ($data): ?>
        <div class="data-item">
            <p>
                <strong>
                    <i class="fas fa-weight-hanging"></i>
                    Jumlah Daging:
                </strong>
                <span class="data-value"><?= $data['jumlah_kg'] ?> kg</span>
            </p>
        </div>

        <div class="data-item">
            <p>
                <strong>
                    <i class="fas fa-info-circle"></i>
                    Status:
                </strong>
                <span class="status-badge status-<?= strtolower($data['status']) ?>">
                    <i class="fas fa-<?= $data['status'] == 'tersedia' ? 'check-circle' : 'clock' ?>"></i>
                    <?= ucfirst($data['status']) ?>
                </span>
            </p>
        </div>

        <?php if ($data['qrcode_path']): ?>
            <div class="qr-container">
                <div class="qr-title">
                    <i class="fas fa-qrcode"></i>
                    QR Code Pengambilan Daging
                </div>
                <img src="../qrcode/<?= $data['qrcode_path'] ?>" alt="QR Code" width="200">
                <br>
                <a href="../qrcode/<?= $data['qrcode_path'] ?>" download class="btn btn-success mt-2">
                    Download QR Code
                </a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">
            Data pembagian daging belum tersedia untuk NIK Anda.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="../dashboard/index.php" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php include '../template/footer.php'; ?>