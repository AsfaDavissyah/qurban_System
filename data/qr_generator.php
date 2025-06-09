<?php
require_once '../phpqrcode/qrlib.php';
require_once '../config/db.php';

if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}

$current_nik = $_SESSION['nik'];

$user_check = $conn->query("SELECT role, is_panitia FROM users WHERE nik = '$current_nik'");
$user_data = $user_check->fetch_assoc();

$is_admin_or_panitia = ($user_data['role'] === 'admin' || $user_data['is_panitia'] == 1);

$target_nik = isset($_GET['nik']) ? $_GET['nik'] : $current_nik;

if (!$is_admin_or_panitia && $target_nik !== $current_nik) {
    echo "<script>alert('Anda tidak memiliki akses untuk generate QR Code warga lain!'); window.close();</script>";
    exit;
}

$is_download = isset($_GET['download']) && $_GET['download'] == '1';

$stmt = $conn->prepare("
    SELECT u.nik, u.nama, u.alamat, u.telepon, u.is_panitia, u.is_berqurban, u.role,
           pd.jumlah_kg, pd.status, pd.id as pembagian_id
    FROM users u 
    LEFT JOIN pembagian_daging pd ON u.nik = pd.nik 
    WHERE u.nik = ?
");
$stmt->bind_param("s", $target_nik);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Data warga tidak ditemukan!'); window.close();</script>";
    exit;
}

$data = $result->fetch_assoc();

if (!$data['pembagian_id']) {
    echo "<script>alert('Data pembagian daging untuk warga ini belum ada!'); window.close();</script>";
    exit;
}

$nik = $data['nik'];
$nama = $data['nama'];
$alamat = $data['alamat'];
$telepon = $data['telepon'];
$jumlah_kg = $data['jumlah_kg'];
$status_pengambilan = $data['status'];

$status_badges = [];
if ($data['is_panitia'] == 1) $status_badges[] = 'Panitia';
if ($data['is_berqurban'] == 1) $status_badges[] = 'Berqurban';
if (empty($status_badges)) $status_badges[] = 'Warga';
$status_role = implode(' + ', $status_badges);

$jam_pengambilan = "08:00 - 12:00 WIB";

$qr_content = "=== PEMBAGIAN DAGING QURBAN ===\n";
$qr_content .= "NIK: $nik\n";
$qr_content .= "Nama: $nama\n";
$qr_content .= "Status: $status_role\n";
$qr_content .= "Jumlah Daging: $jumlah_kg kg\n";
$qr_content .= "Jam Pengambilan: $jam_pengambilan\n";
$qr_content .= "Status: " . ucwords(str_replace('_', ' ', $status_pengambilan)) . "\n";
$qr_content .= "Generated: " . date('d/m/Y H:i:s');

$qr_dir = "../qrcode/";
if (!is_dir($qr_dir)) {
    mkdir($qr_dir, 0755, true);
}

$filename = $qr_dir . $nik . ".png";

QRcode::png($qr_content, $filename, QR_ECLEVEL_H, 6, 2);

if (empty($data['qrcode_path']) || $data['qrcode_path'] != $nik . ".png") {
    $update_qr = $conn->prepare("UPDATE pembagian_daging SET qrcode_path = ? WHERE nik = ?");
    $qr_path = $nik . ".png";
    $update_qr->bind_param("ss", $qr_path, $nik);
    $update_qr->execute();
}

if ($is_download) {
    if ($status_pengambilan == 'belum_ambil') {
        $update_status = $conn->prepare("UPDATE pembagian_daging SET status = 'sudah_ambil' WHERE nik = ?");
        $update_status->bind_param("s", $nik);
        $update_status->execute();

        $status_pengambilan = 'sudah_ambil';
    }

    if (file_exists($filename)) {
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="QR_' . $nama . '_' . $nik . '.png"');
        header('Content-Length: ' . filesize($filename));

        readfile($filename);
        exit;
    } else {
        echo "<script>alert('File QR Code tidak ditemukan!'); window.history.back();</script>";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - <?= htmlspecialchars($nama) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-black: #1a1a1a;
            --border-dark: #2d2d2d;
            --text-white: #ffffff;
            --text-light: #b3b3b3;
            --accent-green: #e1f21f;
        }

        body {
            background-color: var(--primary-black);
            color: var(--text-white);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }

        .qr-container {
            background-color: var(--border-dark);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid #333;
            max-width: 500px;
            margin: 0 auto;
        }

        .qr-header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--accent-green);
            padding-bottom: 1rem;
        }

        .qr-header h2 {
            color: var(--accent-green);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .info-grid {
            display: grid;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background-color: #333;
            border-radius: 8px;
            border-left: 4px solid var(--accent-green);
        }

        .info-label {
            font-weight: 600;
            color: var(--text-light);
        }

        .info-value {
            font-weight: 700;
            color: var(--text-white);
        }

        .qr-image-container {
            text-align: center;
            background-color: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-panitia {
            background-color: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-berqurban {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .status-warga {
            background-color: rgba(108, 117, 125, 0.2);
            color: #6c757d;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-green), #b8cc00);
            color: #000;
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #b8cc00, var(--accent-green));
            transform: translateY(-2px);
            color: #000;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #20c997, #28a745);
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        .print-note {
            text-align: center;
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: rgba(225, 242, 31, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(225, 242, 31, 0.3);
        }

        .download-notice {
            text-align: center;
            margin-top: 1rem;
            padding: 1rem;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 8px;
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #28a745;
        }

        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }

            .qr-container {
                box-shadow: none;
                border: 2px solid #000;
            }

            .btn-group {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .btn-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="qr-container">
            <div class="qr-header">
                <h2><i class="fas fa-qrcode"></i> QR Code Pembagian Daging</h2>
                <p class="mb-0">Idul Adha <?= date('Y') ?></p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-id-card"></i> NIK:</span>
                    <span class="info-value"><?= htmlspecialchars($nik) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user"></i> Nama:</span>
                    <span class="info-value"><?= htmlspecialchars($nama) ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label"><i class="fas fa-tags"></i> Status:</span>
                    <span class="info-value">
                        <?php
                        $badge_classes = [
                            'Panitia' => 'status-panitia',
                            'Berqurban' => 'status-berqurban',
                            'Warga' => 'status-warga'
                        ];

                        $badges = explode(' + ', $status_role);
                        foreach ($badges as $badge):
                            $class = $badge_classes[$badge] ?? 'status-warga';
                        ?>
                            <span class="status-badge <?= $class ?>"><?= $badge ?></span>
                        <?php endforeach; ?>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label"><i class="fas fa-weight"></i> Jumlah Daging:</span>
                    <span class="info-value"><?= $jumlah_kg ?> kg</span>
                </div>

                <div class="info-item">
                    <span class="info-label"><i class="fas fa-clock"></i> Jam Pengambilan:</span>
                    <span class="info-value"><?= $jam_pengambilan ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label"><i class="fas fa-info-circle"></i> Status Pengambilan:</span>
                    <span class="info-value">
                        <span class="status-badge <?= $status_pengambilan == 'sudah_ambil' ? 'status-panitia' : 'status-berqurban' ?>">
                            <?= ucwords(str_replace('_', ' ', $status_pengambilan)) ?>
                        </span>
                    </span>
                </div>
            </div>

            <div class="qr-image-container">
                <img src="<?= $filename ?>" alt="QR Code <?= htmlspecialchars($nama) ?>" class="qr-image">
            </div>

            <div class="print-note">
                <i class="fas fa-info-circle"></i>
                <strong>Petunjuk:</strong> Tunjukkan QR Code ini kepada panitia saat pengambilan daging qurban
            </div>

            <?php if ($status_pengambilan == 'sudah_ambil'): ?>
                <div class="download-notice">
                    <i class="fas fa-check-circle"></i>
                    <strong>Status:</strong> QR Code sudah pernah didownload dan status telah diperbarui menjadi "Sudah Ambil"
                </div>
            <?php endif; ?>

            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print QR Code
                </button>

                <a href="?<?= http_build_query(array_merge($_GET, ['download' => '1'])) ?>" class="btn btn-success">
                    <i class="fas fa-download"></i> Download QR Code
                    <?php if ($status_pengambilan == 'belum_ambil'): ?>
                    <?php endif; ?>
                </a>

                <?php
                $is_panitia = $_SESSION['is_panitia'] ?? false;
                $role = $_SESSION['role'] ?? '';

                if ($is_panitia) {
                    $back_link = '../dashboard/panitia.php';
                } elseif ($role === 'warga') {
                    $back_link = '../dashboard/warga.php';
                } else {
                    $back_link = 'pembagian_daging.php'; 
                }
                ?>

                <a href="<?= $back_link ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Auto print jika parameter print=1
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                window.print();
            }

            const downloadBtn = document.querySelector('a[href*="download=1"]');
            if (downloadBtn && <?= $status_pengambilan == 'belum_ambil' ? 'true' : 'false' ?>) {
                downloadBtn.addEventListener('click', function(e) {
                    if (!confirm('Download QR Code akan mengubah status menjadi "Sudah Ambil". Lanjutkan?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>

</html>