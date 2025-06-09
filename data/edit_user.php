<?php
require_once '../config/db.php';

if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$nik = $_GET['nik'] ?? '';
$user_data = null;

if (!empty($nik)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    } else {
        header("Location: users.php?error=user_not_found");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_nik = $_POST['old_nik'];
    $new_nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $is_panitia = isset($_POST['is_panitia']) ? 1 : 0;
    $is_berqurban = isset($_POST['is_berqurban']) ? 1 : 0;
    $password = $_POST['password'];

    try {
        $conn->begin_transaction();

        if ($old_nik !== $new_nik) {
            $check_stmt = $conn->prepare("SELECT nik FROM users WHERE nik = ? AND nik != ?");
            $check_stmt->bind_param("ss", $new_nik, $old_nik);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception("NIK baru sudah digunakan!");
            }

            $related_updates = [
                "UPDATE qurban_peserta SET nik = ? WHERE nik = ?",
                "UPDATE qurban_hewan SET penanggung_jawab = ? WHERE penanggung_jawab = ?",
                "UPDATE qurban_hewan SET pembeli = ? WHERE pembeli = ?"
            ];

            foreach ($related_updates as $sql) {
                $stmt = $conn->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ss", $new_nik, $old_nik);
                    $stmt->execute();
                }
            }
        }

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET nik = ?, nama = ?, telepon = ?, is_panitia = ?, is_berqurban = ?, password = ? WHERE nik = ?");
            $update_stmt->bind_param("sssiiss", $new_nik, $nama, $telepon, $is_panitia, $is_berqurban, $hashed_password, $old_nik);
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET nik = ?, nama = ?, telepon = ?, is_panitia = ?, is_berqurban = ? WHERE nik = ?");
            $update_stmt->bind_param("sssiis", $new_nik, $nama, $telepon, $is_panitia, $is_berqurban, $old_nik);
        }

        if ($update_stmt->execute()) {
            $conn->commit();
            header("Location: users.php?success=updated&nama=" . urlencode($nama));
            exit;
        } else {
            throw new Exception("Gagal update data user");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = $e->getMessage();
    }
}

include '../template/header.php';
?>

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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h4 {
        color: #e1f21f;
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #ffffff;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        background: #2a2a2a;
        border: 1px solid #404040;
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #e1f21f;
        box-shadow: 0 0 0 3px rgba(225, 242, 31, 0.1);
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        accent-color: #e1f21f;
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
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-primary {
        background: #e1f21f;
        color: #121212;
    }

    .btn-primary:hover {
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
        color: #ffffff;
        text-decoration: none;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        border: 1px solid;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border-color: rgba(239, 68, 68, 0.3);
    }

    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    .password-note {
        font-size: 0.875rem;
        color: #b3b3b3;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .form-container {
            padding: 1.5rem;
        }

        .button-group {
            flex-direction: column;
        }
    }
</style>

<div class="container">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <div class="form-header">
            <h4><i class="fas fa-user-edit me-2"></i>Edit Data Warga</h4>
        </div>

        <?php if ($user_data): ?>
            <form method="POST">
                <input type="hidden" name="old_nik" value="<?= htmlspecialchars($user_data['nik']) ?>">

                <div class="form-group">
                    <label class="form-label" for="nik">
                        <i class="fas fa-id-card me-1"></i>NIK
                    </label>
                    <input type="text" class="form-control" id="nik" name="nik"
                        value="<?= htmlspecialchars($user_data['nik']) ?>"
                        required maxlength="16" pattern="[0-9]{16}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama">
                        <i class="fas fa-user me-1"></i>Nama Lengkap
                    </label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="<?= htmlspecialchars($user_data['nama']) ?>"
                        required maxlength="100">
                </div>

                <div class="form-group">
                    <label class="form-label" for="telepon">
                        <i class="fas fa-phone me-1"></i>Telepon
                    </label>
                    <input type="text" class="form-control" id="telepon" name="telepon"
                        value="<?= htmlspecialchars($user_data['telepon']) ?>"
                        required maxlength="15">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock me-1"></i>Password Baru (Opsional)
                    </label>
                    <input type="password" class="form-control" id="password" name="password"
                        minlength="6" maxlength="255">
                    <div class="password-note">
                        Kosongkan jika tidak ingin mengubah password
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_panitia" name="is_panitia"
                            <?= $user_data['is_panitia'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_panitia">
                            <i class="fas fa-users-cog me-1"></i>Panitia
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_berqurban" name="is_berqurban"
                            <?= $user_data['is_berqurban'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_berqurban">
                            <i class="fas fa-cow me-1"></i>Berqurban
                        </label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                Data user tidak ditemukan!
            </div>
            <div class="button-group">
                <a href="users.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../template/footer.php'; ?>

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




