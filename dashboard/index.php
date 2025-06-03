<?php
require_once '../config/db.php';

if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header("Location: admin.php");
} elseif ($_SESSION['is_panitia']) {
    header("Location: panitia.php");
} elseif ($_SESSION['is_berqurban']) {  
    header("Location: pengqurban.php");
}else {
    header("Location: warga.php");
}
exit;
?>
