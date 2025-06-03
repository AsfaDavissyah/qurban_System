<?php
require_once '../config/db.php';
if ($_SESSION['role'] !== 'admin') exit;

$sql = "SELECT u.nama, u.nik, h.jenis, q.jumlah_iuran
        FROM qurban_peserta q
        JOIN users u ON q.nik = u.nik
        JOIN hewan_qurban h ON q.hewan_id = h.id
        ORDER BY u.nama";
$result = $conn->query($sql);
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h4>Data Peserta Qurban</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Jenis Hewan</th>
                <th>Iuran</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nik'] ?></td>
                <td><?= ucfirst($row['jenis']) ?></td>
                <td>Rp <?= number_format($row['jumlah_iuran']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="../dashboard/admin.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
<?php include '../template/footer.php'; ?>
