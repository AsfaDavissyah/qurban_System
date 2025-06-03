<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || !$_SESSION['is_berqurban']) {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../template/header.php'; ?>
<div class="container mt-4">
    <h3>Halo, <?= $_SESSION['nama'] ?> (Peserta Qurban)</h3>
    <p>Berikut informasi qurban Anda:</p>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Informasi Iuran</h5>
            <ul>
                <?php
                $nik = $_SESSION['nik'];
                $sql = "SELECT q.jumlah_iuran, h.jenis 
                        FROM qurban_peserta q
                        JOIN hewan_qurban h ON q.hewan_id = h.id
                        WHERE q.nik = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $nik);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>Hewan: <strong>{$row['jenis']}</strong>, Iuran: Rp " . number_format($row['jumlah_iuran']) . "</li>";
                    }
                } else {
                    echo "<li>Belum terdaftar sebagai peserta qurban.</li>";
                }
                ?>
            </ul>

            <h5 class="mt-3">Hak Daging</h5>
            <p>Sebagai peserta qurban, Anda berhak mendapatkan <strong>2kg</strong> daging.</p>
        </div>
    </div>

    <a href="warga.php" class="btn btn-secondary mt-3">Kembali</a>
</div>
<?php include '../template/footer.php'; ?>
