<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || !$_SESSION['is_panitia']) {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h3>Halo, <?= $_SESSION['nama'] ?> (Panitia)</h3>
    <p>Kelola kegiatan qurban sebagai panitia.</p>

    <div class="row">
        <div class="col-md-6">
            <a href="../data/keuangan.php" class="btn btn-outline-primary w-100 mb-2">Input Pengeluaran</a>
            <a href="../data/pembagian_daging.php" class="btn btn-outline-primary w-100 mb-2">Distribusi Daging</a>
        </div>
    </div>

    <a href="../auth/logout.php" class="btn btn-danger mt-4">Logout</a>
</div>
<?php include '../template/footer.php'; ?>
