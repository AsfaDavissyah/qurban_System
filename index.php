<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login Qurban System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Lato:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #e1f21f;
      --primary-dark: #c5d61a;
      --bg-dark: #121212;
      --card-dark: #1e1e1e;
      --border-dark: #2a2a2a;
      --text-primary: #ffffff;
      --text-secondary: rgb(252, 252, 252);
      --text-muted: #808080;
      --success: #10b981;
      --danger: #ef4444;
      --shadow: rgba(225, 242, 31, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Lato', sans-serif;
      background-color: var(--bg-dark);
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(225, 242, 31, 0.05) 0%, transparent 70%);
      animation: float 20s ease-in-out infinite;
      z-index: -1;
    }

    @keyframes float {

      0%,
      100% {
        transform: translate(0, 0) rotate(0deg);
      }

      50% {
        transform: translate(-20px, -20px) rotate(180deg);
      }
    }

    .container {
      position: relative;
      z-index: 1;
    }

    .login-card {
      background: var(--card-dark);
      border: 1px solid var(--border-dark);
      border-radius: 12px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      padding: 2.5rem;
      margin-top: 3rem;
      position: relative;
      overflow: hidden;
      transform: translateY(20px);
      animation: slideUp 0.8s ease-out forwards;
    }

    @keyframes slideUp {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--primary-color);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .login-card:hover::before {
      opacity: 1;
    }

    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .login-icon {
      width: 80px;
      height: 80px;
      background: rgba(225, 242, 31, 0.1);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      box-shadow: 0 10px 30px rgba(225, 242, 31, 0.1);
      animation: pulse 2s infinite;
      transition: all 0.3s ease;
    }

    .login-card:hover .login-icon {
      background: var(--primary-color);
      transform: scale(1.05);
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
      }

      50% {
        transform: scale(1.05);
      }

      100% {
        transform: scale(1);
      }
    }

    .login-icon i {
      font-size: 2rem;
      color: var(--primary-color);
      transition: color 0.3s ease;
    }

    .login-card:hover .login-icon i {
      color: var(--bg-dark);
    }

    .login-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      color: var(--text-primary);
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      letter-spacing: -0.02em;
    }

    .login-subtitle {
      font-family: 'Lato', sans-serif;
      color: var(--text-secondary);
      font-size: 1rem;
      font-weight: 400;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-label i {
      color: var(--primary-color);
      font-size: 0.9rem;
    }

    .form-control {
      border: 1px solid var(--border-dark);
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-family: 'Lato', sans-serif;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: var(--bg-dark);
      color: var(--text-primary);
    }

    .form-control::placeholder {
      color: var(--text-muted);
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(225, 242, 31, 0.25);
      background: var(--card-dark);
      transform: translateY(-2px);
      outline: none;
    }

    .form-control:hover {
      border-color: var(--primary-dark);
      transform: translateY(-1px);
    }

    .btn-login {
      background: var(--primary-color);
      border: none;
      border-radius: 8px;
      padding: 0.875rem 2rem;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--bg-dark);
      width: 100%;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-login::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn-login:hover::before {
      left: 100%;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(225, 242, 31, 0.3);
      background: var(--primary-dark);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .alert {
      border-radius: 8px;
      border: none;
      font-family: 'Lato', sans-serif;
      animation: shake 0.5s ease-in-out;
      margin-bottom: 1.5rem;
    }

    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      75% {
        transform: translateX(5px);
      }
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      color: var(--danger);
      border-left: 4px solid var(--danger);
    }

    .floating-elements {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      overflow: hidden;
    }

    .floating-element {
      position: absolute;
      background: var(--primary-color);
      border-radius: 50%;
      opacity: 0.05;
      animation: floatAround 15s linear infinite;
    }

    .floating-element:nth-child(1) {
      width: 20px;
      height: 20px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      width: 15px;
      height: 15px;
      top: 60%;
      right: 15%;
      animation-delay: 5s;
    }

    .floating-element:nth-child(3) {
      width: 25px;
      height: 25px;
      bottom: 30%;
      left: 20%;
      animation-delay: 10s;
    }

    @keyframes floatAround {
      0% {
        transform: translateY(0px) rotate(0deg);
      }

      50% {
        transform: translateY(-20px) rotate(180deg);
      }

      100% {
        transform: translateY(0px) rotate(360deg);
      }
    }

    /* Animation */
    .login-card {
      animation: fadeInUp 0.8s ease-out;
      animation-fill-mode: both;
      opacity: 0;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .login-card {
        margin: 1rem;
        padding: 2rem 1.5rem;
      }

      .login-title {
        font-size: 1.5rem;
      }

      .login-icon {
        width: 70px;
        height: 70px;
      }

      .login-icon i {
        font-size: 1.8rem;
      }
    }

    @media (max-width: 480px) {
      .login-card {
        margin: 0.5rem;
        padding: 1.5rem 1rem;
      }

      .login-title {
        font-size: 1.3rem;
      }
    }

    /* Loading state for button */
    .btn-login.loading {
      pointer-events: none;
      position: relative;
    }

    .btn-login.loading::after {
      content: '';
      position: absolute;
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid var(--bg-dark);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    @keyframes spin {
      0% {
        transform: translate(-50%, -50%) rotate(0deg);
      }

      100% {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }

    .form-text {
      font-size: 0.85rem;
      color: var(--text-primary);
      font-family: 'Lato', sans-serif;
      line-height: 1.4;
    }

    .form-text i {
      color: var(--primary-color);
      font-size: 0.8rem;
    }

    /* Focus animations for form groups */
    .form-group {
      transition: transform 0.3s ease;
    }

    .form-group:focus-within {
      transform: scale(1.02);
    }

    input.form-control {
      background-color: var(--card-dark);
      color: var(--text-primary) !important;
      /* putih atau kuning terang */
      border: 2px solid var(--primary-color);
      box-shadow: 0 0 5px var(--primary-color);

    }

    input.form-control::placeholder {
      color: var(--text-muted);
      /* warna kuning cerah */
    }


    .toggle-password {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      cursor: pointer;
      color: var(--text-secondary);
    }
  </style>
</head>

<body>
  <div class="floating-elements">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="login-card">
          <div class="login-header">
            <div class="login-icon">
              <i class="fas fa-mosque"></i>
            </div>
            <h3 class="login-title">Sistem Qurban</h3>
            <p class="login-subtitle">Masuk ke akun Anda</p>
          </div>

          <form action="auth/login.php" method="POST" id="loginForm">
            <div class="form-group">
              <label for="nik" class="form-label">
                <i class="fas fa-id-card"></i>
                NIK
              </label>
              <input type="text" class="form-control" id="nik" name="nik" required placeholder="Masukkan NIK Anda">
            </div>

            <div class="form-group">
              <label for="password" class="form-label">
                <i class="fas fa-lock "></i>
                Kata Sandi
              </label>
              <input type="text" class="form-control" id="password" name="password" required placeholder="Masukkan kata sandi">
              <small class="form-text text-light mt-1 fs-12">
                <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                Password default: NIK + 123
              </small>
            </div>

            <button type="submit" class="btn btn-login">
              <i class="fas fa-sign-in-alt me-2"></i>
              Masuk
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add loading state to button on form submit
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.querySelector('.btn-login');
      btn.classList.add('loading');
      btn.innerHTML = '<span style="opacity: 0;">Memproses...</span>';
    });

    // Add focus animations
    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
      });

      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
      });
    });

    // Add hover effect to login card
    const loginCard = document.querySelector('.login-card');
    loginCard.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-4px)';
      this.style.boxShadow = '0 25px 50px rgba(225, 242, 31, 0.15)';
    });

    loginCard.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
      this.style.boxShadow = '0 20px 40px rgba(0, 0, 0, 0.3)';
    });
  </script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>

</body>

</html>