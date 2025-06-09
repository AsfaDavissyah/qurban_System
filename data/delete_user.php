
<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['nik']) || empty($_GET['nik'])) {
    header("Location: users.php?error=invalid_request");
    exit;
}

$nik = $_GET['nik'];

try {
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    
    $conn->begin_transaction();

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

    $tables_to_check = [
        'keuangan' => 'nik',
        'hewan_qurban' => 'nik' ,
        'pembagian_daging' => 'nik', 
        'transaksi' => 'nik',  
        'qurban_peserta' => 'nik' 
    ];

    foreach ($tables_to_check as $table => $columns) {
    
        $table_check = $conn->query("SHOW TABLES LIKE '$table'");
        if ($table_check->num_rows == 0) {
            continue; 
        }

        if (is_array($columns)) {
            foreach ($columns as $column) {
                $column_check = $conn->query("SHOW COLUMNS FROM $table LIKE '$column'");
                if ($column_check->num_rows > 0) {
                    if ($column == 'pembeli') {
                        $stmt = $conn->prepare("UPDATE $table SET $column = NULL WHERE $column = ?");
                    } else {
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

    $delete_user = $conn->prepare("DELETE FROM users WHERE nik = ?");
    if (!$delete_user) {
        throw new Exception("Prepare delete_user failed: " . $conn->error);
    }
    $delete_user->bind_param("s", $nik);

    if (!$delete_user->execute()) {
        throw new Exception("Execute delete_user failed: " . $delete_user->error);
    }

    $conn->commit();
    
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    header("Location: users.php?success=deleted&nama=" . urlencode($nama));
    exit;

} catch (Exception $e) {
    $conn->rollback();
    
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");

    error_log("Delete user error: " . $e->getMessage());

    header("Location: users.php?error=delete_failed&debug=" . urlencode($e->getMessage()));
    exit;
}
?>