<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || (!$_SESSION['is_panitia'] && $_SESSION['role'] !== 'admin')) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $jenis = $_POST['jenis'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $nik = $_SESSION['nik'];

    $stmt = $conn->prepare("INSERT INTO keuangan (tanggal, jenis, kategori, jumlah, keterangan, nik) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $tanggal, $jenis, $kategori, $jumlah, $keterangan, $nik);

    if ($stmt->execute()) {
        $success = "Transaksi berhasil ditambahkan.";
    } else {
        $error = "Gagal menambahkan transaksi.";
    }
}
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

        .form-container {
            background: #1e1e1e;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #2a2a2a;
            margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .alert {
            border: none;
            border-radius: 8px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .form-label {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            background: #2a2a2a;
            border: 1px solid #404040;
            color: #ffffff;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: #333333;
            border-color: #e1f21f;
            box-shadow: 0 0 0 0.2rem rgba(225, 242, 31, 0.25);
            color: #ffffff;
        }

        .form-control::placeholder {
            color: #b3b3b3;
        }

        .form-select option {
            background: #2a2a2a;
            color: #ffffff;
        }

        .row.g-3 > * {
            margin-bottom: 1.5rem;
        }

        .btn {
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: #e1f21f;
            color: #121212;
        }

        .btn-primary:hover {
            background: #c8d91a;
            color: #121212;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225, 242, 31, 0.3);
        }

        .btn-secondary {
            background: #2a2a2a;
            color: #ffffff;
            border: 1px solid #404040;
        }

        .btn-secondary:hover {
            background: #404040;
            color: #ffffff;
            border-color: #555555;
        }

        .btn-primary::before {
            content: "\f0c7";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
        }

        .btn-secondary::before {
            content: "\f053";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #2a2a2a;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #e1f21f;
            z-index: 2;
        }

        .form-control.with-icon {
            padding-left: 2.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .page-header h4 {
                font-size: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
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

        .form-control:invalid {
            border-color: #ef4444;
        }

        .form-control:valid {
            border-color: #22c55e;
        }

        .btn:disabled {
            opacity: 0.6;
            pointer-events: none;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23e1f21f' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h4>Input Transaksi Keuangan</h4>
        </div>

        <div class="form-container">
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $success ?>
                </div>
            <?php elseif (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>


            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">
                        <i class="fas fa-calendar-alt me-1" style="color: #e1f21f;"></i>
                        Tanggal
                    </label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                </div>
                
                <div class="col-md-4">
                    <label for="jenis" class="form-label">
                        <i class="fas fa-exchange-alt me-1" style="color: #e1f21f;"></i>
                        Jenis
                    </label>
                    <select class="form-select" name="jenis" id="jenis" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="kategori" class="form-label">
                        <i class="fas fa-tags me-1" style="color: #e1f21f;"></i>
                        Kategori
                    </label>
                    <select class="form-select" name="kategori" id="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="iuran_qurban">Iuran Qurban</option>
                        <option value="admin_qurban">Admin Qurban</option>
                        <option value="pembelian_hewan">Pembelian Hewan</option>
                        <option value="perlengkapan">Perlengkapan</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="jumlah" class="form-label">
                        <i class="fas fa-money-bill-wave me-1" style="color: #e1f21f;"></i>
                        Jumlah (Rp)
                    </label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" placeholder="0" required>
                </div>
                
                <div class="col-md-6">
                    <label for="keterangan" class="form-label">
                        <i class="fas fa-comment me-1" style="color: #e1f21f;"></i>
                        Keterangan
                    </label>
                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Masukkan keterangan...">
                </div>
                
                <div class="col-12">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        <a href="keuangan.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('tanggal').valueAsDate = new Date();
        
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
        
        const jumlahInput = document.getElementById('jumlah');
        jumlahInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    </script>

<?php include '../template/footer.php'; ?>