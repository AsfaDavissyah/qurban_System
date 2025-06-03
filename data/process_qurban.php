<?php
require_once '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = $_POST['nik'];
    $hewan_id = !empty($_POST['hewan_id']) ? $_POST['hewan_id'] : null;
    $jumlah_iuran = $_POST['jumlah_iuran'];
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Validate user
        $check_user_stmt = $conn->prepare("SELECT nik, nama, is_berqurban FROM users WHERE nik = ?");
        $check_user_stmt->bind_param("s", $nik);
        $check_user_stmt->execute();
        $user_result = $check_user_stmt->get_result();
        
        if ($user_result->num_rows === 0) {
            throw new Exception("User not found");
        }
        
        $user = $user_result->fetch_assoc();
        
        if (!$user['is_berqurban']) {
            throw new Exception("User is not marked as berqurban");
        }
        
        // Check if already processed
        $check_processed_stmt = $conn->prepare("SELECT id FROM qurban_peserta WHERE nik = ?");
        $check_processed_stmt->bind_param("s", $nik);
        $check_processed_stmt->execute();
        $processed_result = $check_processed_stmt->get_result();
        
        if ($processed_result->num_rows > 0) {
            throw new Exception("User already processed");
        }
        
        // Insert into qurban_peserta
        $insert_stmt = $conn->prepare("INSERT INTO qurban_peserta (nik, hewan_id, jumlah_iuran) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sii", $nik, $hewan_id, $jumlah_iuran);
        
        if (!$insert_stmt->execute()) {
            throw new Exception("Failed to insert");
        }
        
        // Commit transaction
        $conn->commit();
        
        header("Location: users.php?success=processed");
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = $e->getMessage();
    }
}

// Get user data if NIK is provided
$user_data = null;
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];
    
    // Get user data
    $user_stmt = $conn->prepare("SELECT * FROM users WHERE nik = ? AND is_berqurban = 1");
    $user_stmt->bind_param("s", $nik);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        
        // Check if already processed
        $check_stmt = $conn->prepare("SELECT id FROM qurban_peserta WHERE nik = ?");
        $check_stmt->bind_param("s", $nik);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            header("Location: users.php?error=already_processed");
            exit;
        }
    } else {
        header("Location: users.php?error=not_berqurban");
        exit;
    }
}

// Get available hewan qurban
$hewan_qurban = $conn->query("
    SELECT 
    hq.id,
    hq.jenis,
    hq.jumlah,
    hq.harga_total,
    hq.tanggal,
    COUNT(qp.id) AS current_participants,
    CASE 
        WHEN hq.jenis = 'sapi' THEN (7 * hq.jumlah - COUNT(qp.id))
        WHEN hq.jenis = 'kambing' THEN (1 * hq.jumlah - COUNT(qp.id))
    END AS available_slots,
    CASE 
        WHEN hq.jenis = 'sapi' THEN ROUND(hq.harga_total / (7 * hq.jumlah))
        WHEN hq.jenis = 'kambing' THEN ROUND(hq.harga_total / hq.jumlah)
    END AS suggested_iuran
FROM 
    hewan_qurban hq
LEFT JOIN 
    qurban_peserta qp ON hq.id = qp.hewan_id
GROUP BY 
    hq.id, hq.jenis, hq.jumlah, hq.harga_total, hq.tanggal
HAVING 
    available_slots > 0
ORDER BY 
    hq.id;

");
?>

<?php include '../template/header.php'; ?>
<style>
    body {
        background: #121212;
        color: #ffffff;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .form-container {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #2a2a2a;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #e1f21f;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        background: #2a2a2a;
        border: 1px solid #404040;
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #e1f21f;
        outline: none;
        box-shadow: 0 0 0 2px rgba(225, 242, 31, 0.2);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: #e1f21f;
        color: #121212;
    }

    .btn-success:hover {
        background: #c8d91a;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: #2a2a2a;
        color: #ffffff;
        border: 1px solid #404040;
    }

    .btn-secondary:hover {
        background: #404040;
        text-decoration: none;
        color: #ffffff;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .hewan-option {
        background: #2a2a2a;
        border: 1px solid #404040;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .hewan-option:hover {
        border-color: #e1f21f;
    }

    .hewan-option.selected {
        border-color: #e1f21f;
        background: rgba(225, 242, 31, 0.1);
    }

    .hewan-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hewan-details {
        font-size: 0.9rem;
        color: #b3b3b3;
        margin-top: 0.5rem;
    }
</style>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-plus-circle me-2"></i>Proses Peserta Qurban</h4>
            <a href="users.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($user_data): ?>
            <div class="form-container">
                <form method="POST" id="processForm">
                    <input type="hidden" name="nik" value="<?= $user_data['nik'] ?>">
                    
                    <div class="form-group">
                        <label class="form-label">Data Warga</label>
                        <div class="form-control" style="background: #333;">
                            <strong><?= htmlspecialchars($user_data['nama']) ?></strong><br>
                            <small>NIK: <?= $user_data['nik'] ?></small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pilih Hewan Qurban (Opsional)</label>
                        <div id="hewanOptions">
                            <div class="hewan-option" data-id="" data-iuran="150000">
                                <div class="hewan-info">
                                    <strong>Tidak Dipilih Sekarang</strong>
                                    <span class="badge bg-secondary">Assign Nanti</span>
                                </div>
                                <div class="hewan-details">
                                    Admin dapat mengassign hewan nanti
                                </div>
                            </div>
                            
                            <?php while ($hewan = $hewan_qurban->fetch_assoc()): ?>
                                <div class="hewan-option" data-id="<?= $hewan['id'] ?>" data-iuran="<?= $hewan['suggested_iuran'] ?>">
                                    <div class="hewan-info">
                                        <strong><?= ucfirst($hewan['jenis']) ?> #<?= $hewan['id'] ?></strong>
                                        <span class="badge bg-success"><?= $hewan['available_slots'] ?> slot tersisa</span>
                                    </div>
                                    <div class="hewan-details">
                                        Jumlah: <?= $hewan['jumlah'] ?> | 
                                        Total: Rp <?= number_format($hewan['harga_total']) ?> | 
                                        Iuran: Rp <?= number_format($hewan['suggested_iuran']) ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                        <input type="hidden" name="hewan_id" id="selectedHewan">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jumlah_iuran">Jumlah Iuran</label>
                        <input type="number" class="form-control" name="jumlah_iuran" id="jumlah_iuran" 
                               value="150000" required min="0" step="1000">
                        <small style="color: #b3b3b3;">Akan otomatis terisi berdasarkan hewan yang dipilih</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Proses
                        </button>
                        <a href="users.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Data warga tidak ditemukan atau warga tidak terdaftar sebagai peserta qurban.
            </div>
            <a href="users.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hewanOptions = document.querySelectorAll('.hewan-option');
            const selectedHewanInput = document.getElementById('selectedHewan');
            const jumlahIuranInput = document.getElementById('jumlah_iuran');

            // Select first option by default
            hewanOptions[0].classList.add('selected');

            hewanOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    hewanOptions.forEach(opt => opt.classList.remove('selected'));
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Set hidden input values
                    const hewanId = this.dataset.id;
                    const iuran = this.dataset.iuran;
                    
                    selectedHewanInput.value = hewanId;
                    jumlahIuranInput.value = iuran;
                });
            });
        });
    </script>
</body>

<?php include '../template/footer.php'; ?>