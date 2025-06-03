<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Sistem Qurban - Simple & Elegant</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-black: #121212;
      --accent-green: #e1f21f;
      --text-white: #ffffff;
      --text-light: #b3b3b3;
      --border-dark: #2a2a2a;
      --bg-light: #f8f9fa;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-light);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Header Styles */
    .main-header {
      background-color: var(--primary-black);
      padding: 1.5rem 0;
      border-bottom: 1px solid var(--border-dark);
    }

    .header-brand {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .header-icon {
      width: 50px;
      height: 50px;
      background-color: var(--accent-green);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .header-icon i {
      font-size: 1.5rem;
      color: var(--primary-black);
    }

    .header-text h1 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-white);
      margin: 0;
    }

    .header-text p {
      font-size: 0.9rem;
      color: var(--text-light);
      margin: 0;
    }

    /* Content Area */
    .content-area {
      flex: 1;
      padding: 4rem 0;
    }

    .content-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      padding: 3rem;
      text-align: center;
    }

    .content-card h2 {
      font-size: 2rem;
      font-weight: 600;
      color: var(--primary-black);
      margin-bottom: 1rem;
    }

    .content-card p {
      font-size: 1.1rem;
      color: #6c757d;
      margin: 0;
    }

    /* Footer Styles */
    .main-footer {
      background-color: var(--primary-black);
      border-top: 1px solid var(--border-dark);
      margin-top: auto;
    }

    .footer-content {
      padding: 3rem 0 2rem;
    }

    .footer-brand {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .footer-icon {
      width: 45px;
      height: 45px;
      background-color: var(--accent-green);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .footer-icon i {
      font-size: 1.3rem;
      color: var(--primary-black);
    }

    .footer-brand-text h4 {
      font-size: 1.3rem;
      font-weight: 600;
      color: var(--text-white);
      margin: 0;
    }

    .footer-brand-text p {
      font-size: 0.85rem;
      color: var(--text-light);
      margin: 0;
    }

    .footer-description {
      color: var(--text-light);
      line-height: 1.6;
      margin-bottom: 2rem;
      max-width: 500px;
    }

    /* Contact Info */
    .contact-info {
      margin-bottom: 2rem;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      color: var(--text-light);
    }

    .contact-item-icon {
      width: 35px;
      height: 35px;
      background-color: var(--border-dark);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--accent-green);
      flex-shrink: 0;
    }

    .contact-item-icon i {
      font-size: 0.9rem;
    }

    .contact-text strong {
      display: block;
      color: var(--text-white);
      font-weight: 500;
      margin-bottom: 0.2rem;
      font-size: 0.9rem;
    }

    .contact-text span {
      font-size: 0.85rem;
    }

    /* Social Media */
    .social-links {
      display: flex;
      gap: 0.75rem;
      margin-bottom: 2rem;
    }

    .social-link {
      width: 40px;
      height: 40px;
      background-color: var(--border-dark);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-light);
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .social-link:hover {
      background-color: var(--accent-green);
      color: var(--primary-black);
    }

    .social-link i {
      font-size: 1rem;
    }

    /* Footer Bottom */
    .footer-bottom {
      padding: 1.5rem 0;
      border-top: 1px solid var(--border-dark);
      text-align: center;
    }

    .copyright {
      color: var(--text-light);
      font-size: 0.85rem;
      margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .header-brand,
      .footer-brand {
        justify-content: center;
        text-align: center;
      }

      .footer-content {
        padding: 2rem 0 1.5rem;
        text-align: center;
      }

      .footer-description {
        max-width: none;
      }

      .social-links {
        justify-content: center;
      }

      .contact-item {
        justify-content: center;
      }
    }

    @media (max-width: 576px) {
      .content-card {
        padding: 2rem;
      }

      .content-card h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="main-header">
    <div class="container">
      <div class="header-brand">
        <div class="header-icon">
          <i class="fas fa-mosque"></i>
        </div>
        <div class="header-text">
          <h1>Qurban System</h1>
          <p>Sistem Manajemen Qurban Digital</p>
        </div>
      </div>
    </div>
  </header>