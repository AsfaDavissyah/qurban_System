<?php
require_once '../config/db.php';
if ($_SESSION['role'] !== 'admin') exit;

$result = $conn->query("SELECT * FROM hewan_qurban ORDER BY tanggal DESC");
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h4>Data Hewan Qurban</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Harga Total</th>
                <th>Admin</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['harga_total']) ?></td>
                <td>Rp <?= number_format($row['biaya_admin']) ?></td>
                <td><?= $row['tanggal'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../dashboard/admin.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
<?php include '../template/footer.php'; ?>
