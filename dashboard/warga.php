<?php
require_once '../config/db.php';
if (!isset($_SESSION['nik'])) {
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
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1rem;
    }

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

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
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
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(225, 242, 31, 0.1);
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
        width: 50px;
        height: 50px;
        background: rgba(225, 242, 31, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .menu-card:hover .menu-icon {
        background: #e1f21f;
        transform: scale(1.05);
    }

    .menu-icon i {
        font-size: 1.3rem;
        color: #e1f21f;
        transition: color 0.3s ease;
    }

    .menu-card:hover .menu-icon i {
        color: #121212;
    }

    .menu-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #ffffff;
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

    .welcome-section {
        animation: fadeIn 0.6s ease-out;
    }

    .menu-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }

    .menu-card:nth-child(1) { animation-delay: 0.1s; }
    .menu-card:nth-child(2) { animation-delay: 0.2s; }

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

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 0 1rem;
        }

        .welcome-section {
            padding: 2rem;
        }

        .welcome-title {
            font-size: 1.3rem;
        }

        .menu-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .menu-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="content-area">
    <div class="dashboard-container">
        <div class="welcome-section">
            <div class="welcome-icon">
                <i class="fas fa-user"></i>
            </div>
            <h3 class="welcome-title">Halo, <?= $_SESSION['nama'] ?></h3>
            <p class="welcome-subtitle">Selamat datang di sistem informasi qurban</p>
        </div>

        <div class="menu-grid">
            <a href="../data/qr_generator.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h4 class="menu-title">QR & Daging Saya</h4>
            </a>

            <?php if ($_SESSION['is_berqurban']) : ?>
            <a href="pengqurban.php" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h4 class="menu-title">Qurban Saya</h4>
            </a>
            <?php endif; ?>
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