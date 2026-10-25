<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$fullname = $_SESSION['fullname'];
$firstDisplayName = explode(' ', $fullname)[0];
$fullDisplayName = htmlspecialchars($fullname);
$isLoggedIn = true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RuangRasa</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
</head>
<body class="app-container">

    <!-- GLOBAL APP HEADER -->
    <header class="app-header">
        <a href="dashboard.php" class="app-brand" title="RuangRasa - Ruang Pulih & Kesehatan Mental">
            <img src="image/logo-horizontal.png" alt="RuangRasa - Kesehatan Mental" class="brand-logo-img logo-desktop" style="height:40px;width:auto;max-width:200px;object-fit:contain;">
            <img src="image/logo-icon.png" alt="RuangRasa Logo" class="brand-logo-icon logo-mobile" style="height:38px;width:auto;object-fit:contain;">
        </a>

        <!-- Global Search Bar -->
        <div class="app-search-wrapper">
            <svg class="app-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="app-search-input" placeholder="Cari meditasi, nama psikolog, artikel...">
        </div>

        <!-- Header Actions -->
        <div class="app-header-actions">
            <a href="emergency.php" class="btn-crisis-header">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Bantuan Krisis 119</span>
            </a>

            <button type="button" class="header-icon-btn" title="Notifikasi">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <span class="badge-dot"></span>
            </button>

            <!-- User Pill -->
            <a href="<?= $isLoggedIn ? 'profile.php' : 'login.php' ?>" class="header-user-pill">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%23ea580c'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%230f766e'/></svg>" alt="<?= $fullDisplayName ?>">
                <div>
                    <div class="header-user-name"><?= $fullDisplayName ?></div>
                    <div class="header-user-role"><?= $isLoggedIn ? 'Member Aktif' : 'Masuk Akun' ?></div>
                </div>
            </a>
        </div>
    </header>

    <!-- APP BODY (SIDEBAR + MAIN CONTENT) -->
    <div class="app-body-layout">
        
        <!-- LEFT SIDEBAR -->
        <aside class="app-sidebar">
            <div>
                <div class="sidebar-status-box">
                    <span>Status Kehadiran</span>
                    <span class="status-dot-active">
                        <span class="dot"></span>
                        <span>Online</span>
                    </span>
                </div>

                <ul class="sidebar-nav-list">
                    <li class="sidebar-nav-item">
                        <a href="dashboard.php" class="active">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="self-check.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                            <span>Self Check</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="mood-tracker.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                            <span>Mood Tracker</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="meditasi.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                            <span>Meditasi</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="chatbot.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            <span>AI Chatbot</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="artikel.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                            <span>Artikel Edukasi</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="forum-komunitas.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            <span>Forum Komunitas</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="konsultasi.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            <span>Konsultasi Psikolog</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="event-wellness.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Event Wellness</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="emergency.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span>Bantuan Darurat</span>
                            <span class="sidebar-badge-red">119</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="<?= $isLoggedIn ? 'profile.php' : 'login.php' ?>">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Profil & Pengaturan</span>
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li class="sidebar-nav-item">
                        <a href="logout.php" style="color:#ef4444;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                            <span>Keluar</span>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="sidebar-nav-item">
                        <a href="login.php" style="color:var(--teal-700);font-weight:700;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                            <span>Masuk Akun</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Bottom user summary card -->
            <div class="sidebar-user-card">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%23ea580c'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%230f766e'/></svg>" alt="<?= $fullDisplayName ?>">
                <div class="sidebar-user-details">
                    <span class="sidebar-user-name"><?= $fullDisplayName ?></span>
                    <span class="sidebar-user-sub">Mahasiswi & Anggota RuangRasa</span>
                </div>
            </div>
        </aside>

        <!-- MAIN SCROLLABLE DASHBOARD -->
        <main class="dashboard-content-scroll">
            
            <!-- HERO GREETING BANNER -->
            <div class="dash-hero-container">
                <div class="dash-hero-text-block">
                    <div class="dash-date-pill">
                        <span class="dot">●</span>
                        <span>Kamis, 24 Oktober 2026</span>
                    </div>
                    <h1 class="dash-hero-title">Halo, selamat datang kembali, <?= $firstDisplayName ?>.</h1>
                    <p class="dash-hero-desc">
                        Ambil waktu sejenak. Kamu sudah berjuang dengan baik.
                    </p>
                </div>

                <!-- Weather widget -->
                <div class="dash-weather-widget">
                    <div class="weather-icon-circle">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 0 0-5 5v1a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5z"></path><path d="M5 13a7 7 0 0 0 14 0"></path><path d="M12 20v2"></path></svg>
                    </div>
                    <div>
                        <div class="weather-title-sub">CUACA BATIN HARI INI</div>
                        <div class="weather-state-bold">Teduh & Mengalir</div>
                        <div class="weather-index-muted">Indeks Ketenangan 78%</div>
                    </div>
                </div>
            </div>

            <!-- ROW 1: MOOD CHECK-IN & WEEKLY MENTAL SCREENING -->
            <div class="dash-two-cols-grid">
                
                <!-- Card 1: Bagaimana Perasaanmu Saat Ini? -->
                <section class="mood-checkin-card">
                    <div class="mood-card-head">
                        <h2 class="mood-card-title">Bagaimana perasaanmu saat ini?</h2>
                        <span class="badge-tag-pill" style="background:#f1f5f9;color:var(--slate-600);border:none;">Refleksi Harian</span>
                    </div>
                    <p style="font-size:12px;color:var(--slate-500);margin-top:-8px;">
                        Setiap emosi berharga dan layak diakui.
                    </p>

                    <!-- 5 Mood Emojis -->
                    <div class="mood-emojis-row">
                        <div class="mood-emoji-btn" data-mood="sangat-baik">
                            <span class="mood-emoji-icon">✨</span>
                            <span class="mood-emoji-title">Sangat Baik</span>
                            <span class="mood-emoji-desc">Senyum berseri</span>
                        </div>
                        <div class="mood-emoji-btn active" data-mood="baik-tenang">
                            <span class="mood-emoji-icon">🍃</span>
                            <span class="mood-emoji-title">Baik/Tenang</span>
                            <span class="mood-emoji-desc">Tersenyum lembut</span>
                        </div>
                        <div class="mood-emoji-btn" data-mood="biasa-saja">
                            <span class="mood-emoji-icon">😐</span>
                            <span class="mood-emoji-title">Biasa Saja</span>
                            <span class="mood-emoji-desc">Cukup netral</span>
                        </div>
                        <div class="mood-emoji-btn" data-mood="cemas-lelah">
                            <span class="mood-emoji-icon">😮‍💨</span>
                            <span class="mood-emoji-title">Cemas/Lelah</span>
                            <span class="mood-emoji-desc">Menghela nafas</span>
                        </div>
                        <div class="mood-emoji-btn" data-mood="kewalahan">
                            <span class="mood-emoji-icon">🫂</span>
                            <span class="mood-emoji-title">Kewalahan</span>
                            <span class="mood-emoji-desc">Butuh pelukan</span>
                        </div>
                    </div>

                    <!-- Heart note input -->
                    <div class="mood-note-container">
                        <div class="mood-note-head-row">Catatan Hati Singkat</div>
                        <div class="mood-note-input-row">
                            <input type="text" id="moodNoteInput" class="mood-note-input" placeholder="Tuliskan 1 hal yang sedang ada di pikiranmu...">
                            <button type="button" class="btn-mood-save" onclick="alert('Catatan emosi harian tersimpan terenkripsi.'); document.getElementById('moodNoteInput').value='';">
                                ✓ Simpan Catatan
                            </button>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mood-footer-meta">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <span>Privasi terenkripsi</span>
                        </div>
                        <div style="color:#d97706;font-weight:700;">
                            Check-in: 12 Hari 🔥
                        </div>
                    </div>
                </section>

                <!-- Card 2: Cek Kondisi Mental Mingguanmu -->
                <section class="mental-screening-card">
                    <div class="screening-top-meta">
                        <span class="badge-tag-pill" style="background:rgba(255,255,255,0.15);color:#5eead4;border:none;">● Skrining Rutin</span>
                        <span>Hanya butuh ~3 menit</span>
                    </div>

                    <h2 class="screening-main-heading">Cek Kondisi Mental Mingguanmu</h2>
                    
                    <p class="screening-desc-text">
                        Sudah 7 hari sejak skrining terakhir. Cek kondisimu lewat DASS-21.
                    </p>

                    <div class="screening-stats-duo">
                        <div class="screening-stat-box">
                            <div class="lbl">Terakhir dicek</div>
                            <div class="val">17 Okt 2026</div>
                        </div>
                        <div class="screening-stat-box">
                            <div class="lbl">Hasil lalu</div>
                            <div class="val">Stres Ringan (Aman)</div>
                        </div>
                    </div>

                    <a href="self-check.php" class="btn-screening-white">
                        <span>Mulai Skrining Sekarang</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                </section>

            </div>

            <!-- ROW 2: MOOD TREND 7 DAYS & UPCOMING CONSULTATION -->
            <div class="dash-two-cols-grid">
                
                <!-- Card 3: Tren Suasana Hati 7 Hari -->
                <section class="mood-trend-card">
                    <div class="trend-card-head">
                        <h2 class="trend-card-title">Tren Suasana Hati (7 Hari Terakhir)</h2>
                        <span class="badge-tag-pill" style="background:#f1f5f9;color:var(--slate-600);border:none;">18 - 24 Okt</span>
                    </div>
                    <div class="trend-card-sub">
                        Kondisi dominan minggu ini: <strong>Tenang & Reflektif</strong> (4 dari 7 hari)
                    </div>

                    <!-- SVG 7-Day Trend Chart -->
                    <div class="trend-svg-container">
                        <svg viewBox="0 0 540 120" style="width:100%;height:100%;overflow:visible;">
                            <!-- Grid lines -->
                            <line x1="20" y1="20" x2="520" y2="20" stroke="#f1f5f9" stroke-width="1"></line>
                            <line x1="20" y1="55" x2="520" y2="55" stroke="#f1f5f9" stroke-width="1"></line>
                            <line x1="20" y1="90" x2="520" y2="90" stroke="#f1f5f9" stroke-width="1"></line>

                            <!-- Smooth Curve line -->
                            <path d="M 40 45 C 90 35, 120 20, 160 25 C 200 30, 220 80, 260 75 C 300 70, 320 40, 360 42 C 400 44, 420 18, 460 22 C 480 24, 500 45, 510 40" 
                                  fill="none" stroke="#0f766e" stroke-width="3" stroke-linecap="round"></path>

                            <!-- Nodes & labels -->
                            <!-- Sen -->
                            <circle cx="60" cy="38" r="4.5" fill="#0f766e" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="60" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Sen</text>
                            <text x="60" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Tenang</text>

                            <!-- Sel -->
                            <circle cx="135" cy="22" r="4.5" fill="#10b981" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="135" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Sel</text>
                            <text x="135" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Senang</text>

                            <!-- Rab -->
                            <circle cx="210" cy="78" r="4.5" fill="#f59e0b" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="210" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Rab</text>
                            <text x="210" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Lelah</text>

                            <!-- Kam -->
                            <circle cx="285" cy="46" r="4.5" fill="#0f766e" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="285" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Kam</text>
                            <text x="285" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Tenang</text>

                            <!-- Jum -->
                            <circle cx="360" cy="24" r="4.5" fill="#10b981" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="360" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Jum</text>
                            <text x="360" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Berseri</text>

                            <!-- Sab -->
                            <circle cx="435" cy="36" r="4.5" fill="#0f766e" stroke="#ffffff" stroke-width="2"></circle>
                            <text x="435" y="108" text-anchor="middle" font-size="11" fill="#64748b" font-weight="700">Sab</text>
                            <text x="435" y="118" text-anchor="middle" font-size="9" fill="#94a3b8">Tenang</text>

                            <!-- Min (Hari Ini) -->
                            <circle cx="505" cy="40" r="5.5" fill="#0f766e" stroke="#2dd4bf" stroke-width="3"></circle>
                            <text x="505" y="108" text-anchor="middle" font-size="11" fill="#0f766e" font-weight="800">Min</text>
                            <text x="505" y="118" text-anchor="middle" font-size="9" fill="#0f766e" font-weight="700">Hari Ini</text>
                        </svg>
                    </div>

                    <div class="trend-footer-row">
                        <div style="display:flex;gap:14px;align-items:center;">
                            <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:7px;height:7px;border-radius:50%;background:#0f766e;"></span> Positif / Tenang</span>
                            <span style="display:inline-flex;align-items:center;gap:4px;"><span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;"></span> Fluktuasi Stres</span>
                        </div>
                        <a href="#riwayat-suasana">Lihat Riwayat & Pola Lengkap &gt;</a>
                    </div>
                </section>

                <!-- Card 4: Jadwal Konsultasi Terdekat -->
                <section class="upcoming-consultation-panel">
                    <div class="consult-panel-head">
                        <h2 class="mood-card-title">Jadwal Konsultasi Terdekat</h2>
                        <span class="badge-tag-pill" style="background:#f0fdf4;color:#15803d;border:none;">Terkonfirmasi</span>
                    </div>

                    <div class="consult-doctor-header">
                        <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%230f766e'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%23134e4a'/></svg>" alt="Sarah Amelia" class="consult-doctor-avatar">
                        <div class="consult-doctor-info">
                            <div class="tag">KONSELING PSIKOLOGI KLINIS</div>
                            <div class="name">Sarah Amelia, M.Psi., Psikolog</div>
                            <div class="spec">Spesialisasi: Burnout Akademik & Quarter-life Crisis</div>
                        </div>
                    </div>

                    <div class="consult-time-details">
                        <div class="consult-detail-row">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Besok, 25 Okt</span>
                        </div>
                        <div class="consult-detail-row">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            <span>19:30 - 20:30 WIB</span>
                        </div>
                        <div class="consult-detail-row">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>Sesi Privat 1-on-1</span>
                        </div>
                    </div>

                    <div class="consult-notice-muted">
                        Tautan sesi video dibuka 10 menit sebelum jadwal.
                    </div>

                    <div class="consult-action-buttons">
                        <a href="#video-room" class="btn-consult-enter" onclick="alert('Ruang konsultasi video HD akan aktif sesuai jadwal.'); return false;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                            <span>Masuk Ruang Sesi</span>
                        </a>
                        <button type="button" class="btn-sm-outline" style="padding:9px 16px;" onclick="alert('Detail reservasi konsultasi psikolog.');">
                            Lihat Detail
                        </button>
                    </div>
                </section>

            </div>

            <!-- ROW 3: PUSAT AKTIVITAS CEPAT -->
            <section class="quick-activities-section">
                <div class="quick-activities-head">
                    <div>
                        <h2 class="quick-activities-title">Pusat Aktivitas Cepat</h2>
                        <div class="quick-activities-sub">Langkah kecil harian untuk ketenangan pikiranmu</div>
                    </div>
                    <span style="font-size:11.5px;color:var(--slate-500);font-weight:600;">Akses Terarah</span>
                </div>

                <div class="quick-activities-grid">
                    
                    <!-- Act 1: Meditasi 5 Menit -->
                    <a href="meditasi.php" class="quick-activity-card">
                        <span class="act-badge-tag">AUDIO DIPANDU</span>
                        <div class="act-main-title">Meditasi 5 Menit</div>
                        <p class="act-desc-snippet">Relaksasi singkat sebelum belajar…</p>
                        <div class="act-action-link">
                            <span>Mulai Dengarkan</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>

                    <!-- Act 2: RuangRasa AI Chat -->
                    <a href="chatbot.php" class="quick-activity-card">
                        <span class="act-badge-tag">SIAGA 24/7</span>
                        <div class="act-main-title">RuangRasa AI Chat</div>
                        <p class="act-desc-snippet">Obrolan curhat hangat tanpa takut…</p>
                        <div class="act-action-link">
                            <span>Buka Percakapan</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>

                    <!-- Act 3: Imposter Syndrome -->
                    <a href="artikel.php" class="quick-activity-card">
                        <span class="act-badge-tag">EDUKASI POPULER</span>
                        <div class="act-main-title">Imposter Syndrome</div>
                        <p class="act-desc-snippet">Trik praktis mengatasi rasa ragu…</p>
                        <div class="act-action-link">
                            <span>Baca Selengkapnya</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>

                    <!-- Act 4: Forum Anonim -->
                    <a href="forum-komunitas.php" class="quick-activity-card">
                        <span class="act-badge-tag">RUANG CERITA</span>
                        <div class="act-main-title">Forum Anonim</div>
                        <p class="act-desc-snippet">Thread baru: "Cara kamu redakan…</p>
                        <div class="act-action-link">
                            <span>Gabung Diskusi</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>

                    <!-- Act 5: Temukan Psikolog -->
                    <a href="#konseling" class="quick-activity-card" onclick="alert('Mencari jadwal psikolog mitra terverifikasi.'); return false;">
                        <span class="act-badge-tag">KONSELING AHLI</span>
                        <div class="act-main-title">Temukan Psikolog</div>
                        <p class="act-desc-snippet">Pilih psikolog berlisensi sesuai…</p>
                        <div class="act-action-link">
                            <span>Cari Jadwal</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </a>

                </div>
            </section>

            <!-- ROW 4: ARTIKEL EDUKASI & CRISIS HOTLINE -->
            <div class="curated-and-emergency-row">
                
                <!-- Left: Artikel Edukasi Terkurasi Buatmu -->
                <section class="curated-articles-box">
                    <div class="curated-head-row">
                        <h2 class="curated-title">Artikel Edukasi Terkurasi Buatmu</h2>
                        <a href="#semua-artikel" style="font-size:12px;color:var(--teal-700);text-decoration:none;font-weight:700;">Lihat Semua</a>
                    </div>

                    <div class="articles-duo-grid">
                        
                        <!-- Article 1 -->
                        <div class="article-card-item">
                            <div class="article-thumb-banner" style="background:linear-gradient(135deg, #134e4a, #0f766e);">
                                <span class="article-tag-pill">#ManajemenStres</span>
                            </div>
                            <div class="article-body-content">
                                <div class="article-meta-info">⏱ 4 mnt baca • Ditinjau Psikolog</div>
                                <h3 class="article-title-bold">Seni Melambat: Mengatasi Sindrom Kelelahan Batin Mahasiswa</h3>
                                <p class="article-snippet-text">
                                    Memahami batas kapasitas mental dan cara memulihkan ritme belajar…
                                </p>
                                <div class="article-author-row">
                                    <span>Oleh Tim Klinis RuangRasa</span>
                                    <span class="read-btn">Baca &gt;</span>
                                </div>
                            </div>
                        </div>

                        <!-- Article 2 -->
                        <div class="article-card-item">
                            <div class="article-thumb-banner" style="background:linear-gradient(135deg, #1e293b, #334155);">
                                <span class="article-tag-pill">#WorkLifeBalance</span>
                            </div>
                            <div class="article-body-content">
                                <div class="article-meta-info">⏱ 4 mnt baca • Tips Praktis</div>
                                <h3 class="article-title-bold">Menjaga Batas Digital: Berhenti Mengorbankan Kualitas Tidurmu</h3>
                                <p class="article-snippet-text">
                                    Langkah konkret menciptakan batasan sehat antara notifikasi kuliah,…
                                </p>
                                <div class="article-author-row">
                                    <span>Oleh Sarah Amelia, M.Psi.</span>
                                    <span class="read-btn">Baca &gt;</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- Right: Pusat Bantuan Krisis Darurat -->
                <section class="crisis-support-box">
                    <div class="crisis-head-title">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <span>Pusat Bantuan Krisis Darurat</span>
                    </div>

                    <h2 class="crisis-bold-heading">Kamu Tidak Sendirian</h2>
                    
                    <p class="crisis-desc-para">
                        Jika kamu mengalami tekanan berat atau kondisi darurat:
                    </p>

                    <div class="crisis-hotline-item">
                        <div>
                            <div class="lbl">Layanan Sejiwa Kemenkes</div>
                            <div class="val">119 Ext 8</div>
                        </div>
                        <a href="tel:119" class="btn-phone-call" title="Hubungi 119 Ext 8">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </a>
                    </div>

                    <div class="crisis-hotline-item">
                        <div>
                            <div class="lbl">Hotline LISA Cinta Pulih</div>
                            <div class="val">110 / 24 Jam</div>
                        </div>
                        <a href="tel:110" class="btn-phone-call" title="Hubungi 110">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </a>
                    </div>

                    <a href="emergency.php" class="crisis-bottom-guide-link">
                        <span>Buka Panduan Penanganan Krisis</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    </a>
                </section>

            </div>

        </main>
    </div>

    <script src="script.js"></script>
    <script>
        // Mood button selection interaction
        document.querySelectorAll('.mood-emoji-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.mood-emoji-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    </script>
</body>
</html>
