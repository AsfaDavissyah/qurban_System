<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik']) || $_SESSION['role'] !== 'admin') {
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
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .dashboard-header {
        text-align: center;
        margin-bottom: 4rem;
    }

    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .dashboard-header .subtitle {
        font-size: 1.1rem;
        color: #b3b3b3;
        font-weight: 400;
        margin-bottom: 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .menu-card {
        background: #1e1e1e;
        border-radius: 12px;
        padding: 2rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        border: 1px solid #2a2a2a;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        overflow: hidden;
    }

    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(225, 242, 31, 0.1);
        text-decoration: none;
        color: inherit;
        border-color: #e1f21f;
    }

    .menu-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #e1f21f;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .menu-card:hover::before {
        opacity: 1;
    }

    .menu-icon {
        width: 48px;
        height: 48px;
        background: rgba(225, 242, 31, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .menu-card:hover .menu-icon {
        background: #e1f21f;
        transform: scale(1.05);
    }

    .menu-icon i {
        font-size: 1.5rem;
        color: #e1f21f;
        transition: color 0.3s ease;
    }

    .menu-card:hover .menu-icon i {
        color: #121212;
    }

    .menu-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .menu-description {
        font-size: 0.95rem;
        color: #b3b3b3;
        line-height: 1.5;
        margin: 0;
    }

    .logout-section {
        text-align: center;
        padding-top: 2rem;
        border-top: 1px solid #2a2a2a;
    }

    .btn-logout {
        background: #ef4444;
        color: white;
        border: none;
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

    .btn-logout:hover {
        background: #dc2626;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .menu-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .menu-card:nth-child(1) { animation-delay: 0.1s; }
    .menu-card:nth-child(2) { animation-delay: 0.2s; }
    .menu-card:nth-child(3) { animation-delay: 0.3s; }
    .menu-card:nth-child(4) { animation-delay: 0.4s; }
    .menu-card:nth-child(5) { animation-delay: 0.5s; }

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

    .dashboard-header {
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 0 1rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .menu-card {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .dashboard-header h1 {
            font-size: 1.75rem;
        }

        .menu-card {
            padding: 1.25rem;
        }

        .menu-title {
            font-size: 1.1rem;
        }
    }
</style>

<div class="content-area">
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
            <p class="subtitle">Kelola seluruh sistem qurban dengan mudah</p>
        </div>

        <div class="dashboard-grid">
            <a href="../data/users.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="menu-title">Data Warga</h3>
                <p class="menu-description">Kelola data warga dan informasi personal peserta qurban</p>
            </a>

            <a href="../data/hewan_qurban.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-cow"></i>
                </div>
                <h3 class="menu-title">Data Hewan Qurban</h3>
                <p class="menu-description">Manajemen data hewan qurban, jenis, dan spesifikasi</p>
            </a>

            <a href="../data/qurban_peserta.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3 class="menu-title">Peserta Qurban</h3>
                <p class="menu-description">Daftar peserta qurban dan status pembayaran</p>
            </a>

            <a href="../data/keuangan.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3 class="menu-title">Keuangan</h3>
                <p class="menu-description">Laporan keuangan dan transaksi qurban</p>
            </a>

            <a href="../data/pembagian_daging.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3 class="menu-title">Distribusi Daging</h3>
                <p class="menu-description">Pengaturan pembagian dan distribusi daging qurban</p>
            </a>
        </div>

        <div class="logout-section">
            <a href="../auth/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>