<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h3>Halo, <?= $_SESSION['nama'] ?> (Warga)</h3>
    <p>Selamat datang di sistem informasi qurban.</p>

    <div class="row">
        <div class="col-md-6">
            <a href="../data/pembagian_daging.php" class="btn btn-outline-success w-100 mb-2">QR & Daging Saya</a>
        </div>
        <?php if ($_SESSION['is_berqurban']) : ?>
        <div class="col-md-6">
            <a href="pengqurban.php" class="btn btn-outline-primary w-100 mb-2">Qurban Saya</a>
        </div>
        <?php endif; ?>
    </div>

    <a href="../auth/logout.php" class="btn btn-danger mt-4">Logout</a>
</div>
<?php include '../template/footer.php'; ?>
