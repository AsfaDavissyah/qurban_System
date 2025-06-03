<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Proses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $is_panitia = isset($_POST['is_panitia']) ? 1 : 0;
    $is_berqurban = isset($_POST['is_berqurban']) ? 1 : 0;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (nik, nama, alamat, telepon, is_panitia, is_berqurban, role, password) VALUES (?, ?, ?, ?, ?, ?, 'warga', ?)");
    $stmt->bind_param("ssssiss", $nik, $nama, $alamat, $telepon, $is_panitia, $is_berqurban, $password);

    if ($stmt->execute()) {
        $success = "Data warga berhasil ditambahkan.";
    } else {
        $error = "Gagal menambahkan warga. Mungkin NIK sudah terdaftar.";
    }
}
?>

<?php include '../template/header.php'; ?>
<style>
    .form-container {
            background: #1e1e1e;
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid #2a2a2a;
            margin-bottom: 2rem;
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

        /* Page Header - konsisten dengan style existing */
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

        /* Alert Messages */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(225, 242, 31, 0.1);
            border-color: #e1f21f;
            color: #e1f21f;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
            color: #f87171;
        }

        /* Form Styles */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }

        .col-12 {
            flex: 0 0 100%;
            padding: 0.5rem;
        }

        .col-md-6 {
            flex: 0 0 50%;
            padding: 0.5rem;
        }

        .col-md-4 {
            flex: 0 0 33.333333%;
            padding: 0.5rem;
        }

        /* Form Labels */
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #b3b3b3;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Form Controls - styling yang konsisten */
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #121212;
            border: 2px solid #2a2a2a;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #e1f21f;
            box-shadow: 0 0 0 3px rgba(225, 242, 31, 0.1);
        }

        .form-control::placeholder {
            color: #666666;
        }

        /* Textarea */
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        /* Checkbox Styles */
        .form-check {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
        }
        

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-right: 0.75rem;
            background-color: #121212;
            border: 2px solid #2a2a2a;
            border-radius: 4px;
            cursor: pointer;
            position: relative;
            appearance: none;
            -webkit-appearance: none;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #e1f21f;
            border-color: #e1f21f;
        }

        .form-check-input:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #121212;
            font-size: 14px;
            font-weight: bold;
        }

        .form-check-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(225, 242, 31, 0.1);
        }

        .form-check-label {
            color: #b3b3b3;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Button Styles - konsisten dengan style existing */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            margin-right: 1rem;
            margin-bottom: 0.5rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background: #e1f21f;
            color: #121212;
        }

        .btn-primary:hover {
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
        }

        .btn-secondary:hover {
            background: #404040;
            color: #ffffff;
            text-decoration: none;
            border-color: #555555;
        }

        /* Base Styles */
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

        /* Responsive Design - konsisten dengan breakpoint existing */
        @media (max-width: 768px) {
            .col-md-6,
            .col-md-4 {
                flex: 0 0 100%;
            }
            
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header h4 {
                font-size: 1.5rem;
            }
            
            .btn {
                width: 100%;
                margin: 0.25rem 0;
                justify-content: center;
            }
            
            .form-label {
                font-size: 0.85rem;
            }
            
            .form-control {
                font-size: 0.85rem;
                padding: 0.65rem 0.85rem;
            }
            
            .container {
                padding: 1rem;
            }
        }

        /* Focus States untuk Accessibility */
        *:focus {
            outline: 2px solid #e1f21f;
            outline-offset: 2px;
        }
</style>

<div class="container mt-4">
    <h4>Form Tambah Warga</h4>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"> <?= $success ?> </div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"> <?= $error ?> </div>
    <?php endif; ?>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" name="nik" class="form-control" required maxlength="16">
        </div>
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="col-12">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
        </div>
        <div class="col-md-6">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" name="telepon" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_panitia" id="panitia">
                <label class="form-check-label" for="panitia">Panitia</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_berqurban" id="berqurban">
                <label class="form-check-label" for="berqurban">Berqurban</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="users.php" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<?php include '../template/footer.php'; ?>
