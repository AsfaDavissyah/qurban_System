<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik'])) {
    header("Location: ../index.php");
    exit;
}

$nik = $_SESSION['nik'];
$result = $conn->query("SELECT * FROM pembagian_daging WHERE nik = '$nik'");
$data = $result->fetch_assoc();
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h4>Distribusi Daging Saya</h4>

    <?php if ($data): ?>
        <p><strong>Jumlah Daging:</strong> <?= $data['jumlah_kg'] ?> kg</p>
        <p><strong>Status:</strong> <?= ucfirst($data['status']) ?></p>
        <?php if ($data['qrcode_path']): ?>
            <img src="../qrcode/<?= $data['qrcode_path'] ?>" alt="QR Code" width="200">
            <br>
            <a href="../qrcode/<?= $data['qrcode_path'] ?>" download class="btn btn-success mt-2">Download QR</a>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">Data pembagian daging belum tersedia.</div>
    <?php endif; ?>

    <a href="../dashboard/index.php" class="btn btn-secondary mt-4">Kembali</a>
</div>
<?php include '../template/footer.php'; ?>