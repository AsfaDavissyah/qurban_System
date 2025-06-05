
<?php
// ===========================================
// FILE: delete_user_improved.php
// ===========================================
session_start();
require_once '../config/db.php';

// Check if user is admin
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Check if NIK parameter is provided
if (!isset($_GET['nik']) || empty($_GET['nik'])) {
    header("Location: users.php?error=invalid_request");
    exit;
}

$nik = $_GET['nik'];

try {
    // Disable foreign key checks temporarily
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    
    // Start transaction
    $conn->begin_transaction();

    // First, check if user exists
    $check_stmt = $conn->prepare("SELECT nama FROM users WHERE nik = ?");
    if (!$check_stmt) {
        throw new Exception("Prepare statement failed: " . $conn->error);
    }
    
    $check_stmt->bind_param("s", $nik);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $conn->rollback();
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        header("Location: users.php?error=user_not_found");
        exit;
    }

    $user_data = $result->fetch_assoc();
    $nama = $user_data['nama'];

    // Get all tables that might reference this user
    $tables_to_check = [
        'keuangan' => 'nik',
        'hewan_qurban' => 'nik' ,
        'pembagian_daging' => 'nik', // jika ada tabel pembayaran
        'transaksi' => 'nik',  // jika ada tabel transaksi
        'qurban_peserta' => 'nik' // jika ada tabel kegiatan
    ];

    // Delete/Update from all related tables
    foreach ($tables_to_check as $table => $columns) {
        // Check if table exists first
        $table_check = $conn->query("SHOW TABLES LIKE '$table'");
        if ($table_check->num_rows == 0) {
            continue; // Skip if table doesn't exist
        }

        if (is_array($columns)) {
            // Handle multiple columns
            foreach ($columns as $column) {
                // Check if column exists
                $column_check = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
                if ($column_check->num_rows > 0) {
                    if ($column == 'pembeli') {
                        // Set to NULL instead of delete for pembeli
                        $stmt = $conn->prepare("UPDATE $table SET $column = NULL WHERE $column = ?");
                    } else {
                        // Delete for other columns
                        $stmt = $conn->prepare("DELETE FROM $table WHERE $column = ?");
                    }
                    
                    if ($stmt) {
                        $stmt->bind_param("s", $nik);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }
        } else {
            // Handle single column
            $column_check = $conn->query("SHOW COLUMNS FROM $table LIKE '$columns'");
            if ($column_check->num_rows > 0) {
                $stmt = $conn->prepare("DELETE FROM $table WHERE $columns = ?");
                if ($stmt) {
                    $stmt->bind_param("s", $nik);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
    }

    // Finally, delete from users table
    $delete_user = $conn->prepare("DELETE FROM users WHERE nik = ?");
    if (!$delete_user) {
        throw new Exception("Prepare delete_user failed: " . $conn->error);
    }
    $delete_user->bind_param("s", $nik);

    if (!$delete_user->execute()) {
        throw new Exception("Execute delete_user failed: " . $delete_user->error);
    }

    // Commit transaction
    $conn->commit();
    
    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    // Redirect with success message
    header("Location: users.php?success=deleted&nama=" . urlencode($nama));
    exit;

} catch (Exception $e) {
    // Rollback transaction on any error
    $conn->rollback();
    
    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    // Log error for debugging
    error_log("Delete user error: " . $e->getMessage());

    // Redirect with error
    header("Location: users.php?error=delete_failed&debug=" . urlencode($e->getMessage()));
    exit;
}
?>