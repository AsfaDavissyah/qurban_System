<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || !$_SESSION['is_berqurban']) {
    header("Location: ../index.php");
    exit;
}
?>

<?php include '../template/header.php'; ?>

<style>
    .content-area {
        background-color: #121212;
        min-height: calc(100vh - 200px);
        padding: 4rem 0;
    }

    .dashboard-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    /* Welcome Section */
    .welcome-section {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 2.5rem;
        text-align: center;
        margin-bottom: 3rem;
        border: 1px solid #2a2a2a;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #e1f21f;
    }

    .welcome-icon {
        width: 60px;
        height: 60px;
        background: rgba(225, 242, 31, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .welcome-icon i {
        font-size: 1.8rem;
        color: #e1f21f;
    }

    .welcome-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1rem;
        color: #b3b3b3;
        margin: 0;
    }

    .info-card {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #2a2a2a;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #e1f21f;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-section:last-child {
        margin-bottom: 0;
    }

    .info-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.2rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 1rem;
    }

    .info-title-icon {
        width: 35px;
        height: 35px;
        background: rgba(225, 242, 31, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-title-icon i {
        font-size: 1rem;
        color: #e1f21f;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        background: #2a2a2a;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        color: #b3b3b3;
        font-size: 0.95rem;
        line-height: 1.5;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-list li:last-child {
        margin-bottom: 0;
    }

    .info-list li strong {
        color: #ffffff;
    }

    .info-list li .list-icon {
        width: 24px;
        height: 24px;
        background: rgba(225, 242, 31, 0.1);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-list li .list-icon i {
        font-size: 0.8rem;
        color: #e1f21f;
    }

    .highlight-text {
        background: rgba(225, 242, 31, 0.1);
        color: #e1f21f;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .no-data {
        text-align: center;
        color: #b3b3b3;
        font-style: italic;
        padding: 2rem;
        background: #2a2a2a;
        border-radius: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #2a2a2a;
    }

    .btn-secondary {
        background: #2a2a2a;
        color: #ffffff;
        border: 1px solid #2a2a2a;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #3a3a3a;
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(42, 42, 42, 0.3);
    }

    .welcome-section,
    .info-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .info-card {
        animation-delay: 0.2s;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 0 1rem;
        }

        .welcome-section,
        .info-card {
            padding: 1.5rem;
        }

        .welcome-title {
            font-size: 1.3rem;
        }

        .info-title {
            font-size: 1.1rem;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-secondary {
            width: 100%;
            max-width: 200px;
            justify-content: center;
        }
    }
</style>

<div class="content-area">
    <div class="dashboard-container">
        <div class="welcome-section">
            <div class="welcome-icon">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3 class="welcome-title">Halo, <?= $_SESSION['nama'] ?></h3>
            <p class="welcome-subtitle">Berikut informasi qurban Anda</p>
        </div>

        <div class="info-card">
            <div class="info-section">
                <div class="info-title">
                    <div class="info-title-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    Informasi Iuran
                </div>
                <ul class="info-list">
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
                            echo "<li>
                                    <div class='list-icon'><i class='fas fa-cow'></i></div>
                                    <div>
                                        Hewan: <strong>{$row['jenis']}</strong><br>
                                        Iuran: <span class='highlight-text'>Rp " . number_format($row['jumlah_iuran']) . "</span>
                                    </div>
                                  </li>";
                        }
                    } else {
                        echo "<li class='no-data'>Belum terdaftar sebagai peserta qurban.</li>";
                    }
                    ?>
                </ul>
            </div>

            <div class="info-section">
                <div class="info-title">
                    <div class="info-title-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    Hak Daging
                </div>
                <ul class="info-list">
                    <li>
                        <div class="list-icon"><i class="fas fa-weight"></i></div>
                        <div>
                            Sebagai peserta qurban, Anda berhak mendapatkan <span class="highlight-text">2kg</span> daging qurban
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="action-buttons">
            <a href="warga.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>