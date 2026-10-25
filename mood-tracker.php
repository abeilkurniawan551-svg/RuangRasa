<?php
session_start();
if (!isset($_SESSION['fullname'])) {
    $_SESSION['fullname'] = 'Sobat RuangRasa';
    $_SESSION['role'] = 'Mahasiswa & Anggota RuangRasa';
}
$fullname = $_SESSION['fullname'];
$firstDisplayName = explode(' ', $fullname)[0];
$fullDisplayName = htmlspecialchars($fullname);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood Tracker: Rekam Jejak Emosi - RuangRasa</title>
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
            <input type="text" class="app-search-input" placeholder="Cari materi, nama psikolog, artikel...">
        </div>

        <!-- Header Actions -->
        <div class="app-header-actions">
            <a href="emergency.php" class="btn-crisis-header">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Bantuan Krisis 119</span>
            </a>

            <button type="button" class="header-icon-btn" title="Notifikasi" onclick="alert('Belum ada notifikasi baru.');">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <span class="badge-dot"></span>
            </button>

            <!-- User Pill -->
            <a href="profile.php" class="header-user-pill">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%23ea580c'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%230f766e'/></svg>" alt="<?= $fullDisplayName ?>">
                <div>
                    <div class="header-user-name"><?= $fullDisplayName ?></div>
                    <div class="header-user-role">Member Aktif</div>
                </div>
            </a>
        </div>
    </header>

    <!-- APP BODY -->
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
                        <a href="dashboard.php">
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
                        <a href="mood-tracker.php" class="active">
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
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            <span style="color:#ef4444;font-weight:600;">Bantuan Darurat</span>
                            <span class="badge-count-red">119</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="profile.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span>Profil & Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- User profile summary card at bottom -->
            <div class="sidebar-user-card">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%23ea580c'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%230f766e'/></svg>" alt="<?= $fullDisplayName ?>">
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?= $fullDisplayName ?></div>
                    <div class="sidebar-user-sub">Mahasiswa & Anggota</div>
                </div>
            </div>
        </aside>

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="app-main-content dashboard-content-scroll">
            
            <!-- Page Header Row -->
            <div class="mood-header-row">
                <div>
                    <h1 style="font-size:1.55rem;font-weight:800;color:var(--slate-900);margin:0 0 4px 0;letter-spacing:-0.02em;">
                        Mood Tracker
                    </h1>
                    <p style="color:var(--slate-500);font-size:13px;margin:0;">
                        Rekam suasana hati harianmu untuk mengenali pola emosi secara bertahap.
                    </p>
                </div>

                <!-- Consistency Streak Badge -->
                <div class="mood-streak-badge">
                    <span style="font-size:16px;">🔥</span>
                    <span style="font-size:13px;color:var(--teal-900);">14 Hari Teratur</span>
                </div>
            </div>

            <!-- Main Layout Grid: Journal Form (Left) + Analytics (Right) -->
            <div class="mood-main-layout">
                
                <!-- Left Card: Jurnal Harian -->
                <div class="mood-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                        <h2 style="font-size:1.15rem;font-weight:700;color:var(--slate-900);margin:0;">
                            Perasaanmu hari ini, <?= $firstDisplayName ?>?
                        </h2>
                        <span style="font-size:11px;font-weight:600;color:var(--teal-800);background:var(--teal-50);padding:3px 10px;border-radius:var(--radius-full);border:1px solid var(--teal-200);">
                            24 Oktober
                        </span>
                    </div>

                    <!-- 1. Emotion Spectrum Row -->
                    <div class="mood-section-label" style="font-size:12px;margin-bottom:8px;">
                        <span>Pilih Emosi Utama</span>
                    </div>
                    <div class="mood-spectrum-grid">
                        
                        <!-- 1. Sangat Bahagia -->
                        <div class="mood-spectrum-btn" data-mood="bahagia">
                            <div class="mood-spectrum-icon" style="background:#ecfdf5;color:#10b981;">😄</div>
                            <div class="mood-spectrum-name">Bahagia</div>
                        </div>

                        <!-- 2. Tenang -->
                        <div class="mood-spectrum-btn" data-mood="tenang">
                            <div class="mood-spectrum-icon" style="background:#f0fdf9;color:#0f766e;">😌</div>
                            <div class="mood-spectrum-name">Tenang</div>
                        </div>

                        <!-- 3. Biasa Saja -->
                        <div class="mood-spectrum-btn" data-mood="netral">
                            <div class="mood-spectrum-icon" style="background:#f1f5f9;color:#64748b;">😐</div>
                            <div class="mood-spectrum-name">Netral</div>
                        </div>

                        <!-- 4. Lelah / Cemas (Selected by default) -->
                        <div class="mood-spectrum-btn selected" data-mood="lelah">
                            <div class="mood-spectrum-icon" style="background:#f5f3ff;color:#8b5cf6;">😰</div>
                            <div class="mood-spectrum-name">Lelah</div>
                        </div>

                        <!-- 5. Sedih -->
                        <div class="mood-spectrum-btn" data-mood="sedih">
                            <div class="mood-spectrum-icon" style="background:#fff1f2;color:#f43f5e;">😢</div>
                            <div class="mood-spectrum-name">Sedih</div>
                        </div>

                    </div>

                    <!-- 2. Emotional Tags -->
                    <div class="mood-section-label" style="font-size:12px;margin-bottom:8px;">
                        <span>Tag Emosi</span>
                    </div>
                    <div class="mood-tags-row" id="moodTagsContainer">
                        <span class="mood-tag-pill">#Tenang</span>
                        <span class="mood-tag-pill">#Fokus</span>
                        <span class="mood-tag-pill">#Lega</span>
                        <span class="mood-tag-pill active">#Cemas</span>
                        <span class="mood-tag-pill active">#LelahMental</span>
                        <span class="mood-tag-pill active">#DeadlineDekat</span>
                        <span class="mood-tag-pill">#Bersyukur</span>
                    </div>

                    <!-- 3. Triggers -->
                    <div class="mood-section-label" style="font-size:12px;margin-bottom:8px;">
                        <span>Pemicu</span>
                    </div>
                    <div class="mood-triggers-grid" id="moodTriggersContainer">
                        <div class="mood-trigger-box active">🎓 Kuliah/Tugas</div>
                        <div class="mood-trigger-box">💼 Pekerjaan</div>
                        <div class="mood-trigger-box active">💤 Kurang Tidur</div>
                        <div class="mood-trigger-box">👥 Hubungan</div>
                        <div class="mood-trigger-box">🏃 Fisik/Tubuh</div>
                        <div class="mood-trigger-box">🌦️ Lingkungan</div>
                    </div>

                    <!-- 4. Note Textarea -->
                    <div class="mood-section-label" style="font-size:12px;margin-bottom:8px;">
                        <span>Catatan Pribadi</span>
                    </div>
                    <div class="mood-textarea-wrap">
                        <textarea class="mood-textarea" id="moodNoteInput" placeholder="Tuliskan apa yang ada di pikiranmu hari ini...">Presentasi tugas berjalan lancar meski sempat gugup. Bangga sudah bisa melewatinya dengan baik.</textarea>
                    </div>

                    <!-- Footer -->
                    <div class="mood-journal-footer">
                        <span style="font-size:11.5px;color:var(--slate-400);">🔒 Tersimpan secara privat</span>
                        <button type="button" class="btn-submit-teal" style="padding:9px 18px;font-size:12.5px;" onclick="alert('Jurnal emosi berhasil disimpan!');">
                            Simpan Jurnal
                        </button>
                    </div>
                </div>

                <!-- Right Column: Analytics & Monthly Distribution -->
                <div class="mood-right-col">
                    
                    <!-- Weekly Chart Card -->
                    <div class="mood-chart-card">
                        <div class="mood-chart-header">
                            <div>
                                <h3 style="font-size:1.05rem;font-weight:700;color:var(--slate-900);margin:0;">
                                    Fluktuasi Mingguan
                                </h3>
                            </div>
                            <span style="font-size:11px;font-weight:600;color:var(--slate-500);background:var(--slate-100);padding:3px 9px;border-radius:var(--radius-full);">
                                Pekan Ini
                            </span>
                        </div>

                        <div class="mood-chart-stats">
                            <span style="color:var(--teal-700);font-weight:600;">● Rata-rata Ketenangan</span>
                            <span><strong>7.2 / 10</strong></span>
                        </div>

                        <!-- SVG Curve Graph -->
                        <div style="background:#f8fafc;border-radius:var(--radius-md);padding:10px 8px;border:1px solid var(--slate-100);">
                            <svg width="100%" height="90" viewBox="0 0 350 90" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="curveGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#2dd4bf" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#2dd4bf" stop-opacity="0.0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M 15,35 C 50,60 75,55 115,45 C 160,25 180,15 220,12 C 265,10 285,35 335,20 L 335,90 L 15,90 Z" fill="url(#curveGradient)" />
                                <path d="M 15,35 C 50,60 75,55 115,45 C 160,25 180,15 220,12 C 265,10 285,35 335,20" fill="none" stroke="#0f766e" stroke-width="2.5" stroke-linecap="round" />
                                
                                <circle cx="15" cy="35" r="3.5" fill="#0f766e" />
                                <circle cx="68" cy="58" r="3.5" fill="#ef4444" />
                                <circle cx="120" cy="45" r="3.5" fill="#0f766e" />
                                <circle cx="175" cy="25" r="3.5" fill="#0f766e" />
                                <circle cx="220" cy="12" r="4.5" fill="#10b981" />
                                <circle cx="275" cy="28" r="3.5" fill="#0f766e" />
                                <circle cx="335" cy="20" r="3.5" fill="#0f766e" />
                            </svg>
                        </div>

                        <!-- Days row -->
                        <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--slate-400);font-weight:600;padding:6px 10px 0;">
                            <span>Sen</span>
                            <span style="color:#ef4444;">Sel</span>
                            <span>Rab</span>
                            <span style="color:var(--teal-800);font-weight:700;">Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                            <span>Min</span>
                        </div>

                        <!-- Concise Insights -->
                        <div class="mood-insights-wrap" style="margin-top:12px;padding-top:10px;">
                            <div class="mood-insight-item positive" style="padding:8px 10px;font-size:11.5px;">
                                <span>💡</span>
                                <div>Mood terbaik terjadi di hari <strong>Kamis & Jumat</strong> setelah istirahat cukup.</div>
                            </div>
                            <div class="mood-insight-item warning" style="padding:8px 10px;font-size:11.5px;">
                                <span>⚠️</span>
                                <div>Pemicu stres terbanyak: <strong>Tugas / Kuliah</strong> (3x).</div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Calendar Card -->
                    <div class="mood-calendar-card">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                            <h3 style="font-size:1.05rem;font-weight:700;color:var(--slate-900);margin:0;">
                                Oktober 2026
                            </h3>
                            <div style="font-size:11px;color:var(--slate-400);">
                                24 Terisi
                            </div>
                        </div>

                        <!-- Calendar Grid -->
                        <table class="mood-cal-table">
                            <thead>
                                <tr>
                                    <th>S</th><th>S</th><th>R</th><th>K</th><th>J</th><th>S</th><th>M</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="mood-cal-day"><div class="mood-cal-num" style="color:var(--slate-300);">29</div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num" style="color:var(--slate-300);">30</div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">1<span class="mood-cal-dot green"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">2<span class="mood-cal-dot green"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">3<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">4<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">5<span class="mood-cal-dot green"></span></div></td>
                                </tr>
                                <tr>
                                    <td class="mood-cal-day"><div class="mood-cal-num">6<span class="mood-cal-dot green"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">7<span class="mood-cal-dot red"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">8<span class="mood-cal-dot purple"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">9<span class="mood-cal-dot slate"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">10<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">11<span class="mood-cal-dot green"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">12<span class="mood-cal-dot green"></span></div></td>
                                </tr>
                                <tr>
                                    <td class="mood-cal-day"><div class="mood-cal-num">13<span class="mood-cal-dot purple"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">14<span class="mood-cal-dot purple"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">15<span class="mood-cal-dot slate"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">16<span class="mood-cal-dot slate"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">17<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">18<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">19<span class="mood-cal-dot green"></span></div></td>
                                </tr>
                                <tr>
                                    <td class="mood-cal-day"><div class="mood-cal-num">20<span class="mood-cal-dot green"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">21<span class="mood-cal-dot purple"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">22<span class="mood-cal-dot slate"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">23<span class="mood-cal-dot slate"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num today">24<span class="mood-cal-dot" style="background:#ffffff;"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">25<span class="mood-cal-dot teal"></span></div></td>
                                    <td class="mood-cal-day"><div class="mood-cal-num">26<span class="mood-cal-dot green"></span></div></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Legend -->
                        <div class="mood-cal-legend" style="margin-top:10px;padding-top:8px;">
                            <span class="mood-legend-item"><span class="mood-cal-dot green" style="position:static;"></span> Bahagia</span>
                            <span class="mood-legend-item"><span class="mood-cal-dot teal" style="position:static;"></span> Tenang</span>
                            <span class="mood-legend-item"><span class="mood-cal-dot slate" style="position:static;"></span> Netral</span>
                            <span class="mood-legend-item"><span class="mood-cal-dot purple" style="position:static;"></span> Lelah</span>
                            <span class="mood-legend-item"><span class="mood-cal-dot red" style="position:static;"></span> Sedih</span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Breathing Quick Banner -->
            <div class="mood-breathe-banner" style="padding:14px 20px;">
                <div class="mood-breathe-left">
                    <div class="mood-breathe-icon" style="width:38px;height:38px;">
                        🧘
                    </div>
                    <div>
                        <strong style="font-size:13.5px;color:#0f766e;display:block;">
                            Butuh jeda sejenak?
                        </strong>
                        <span style="font-size:12px;color:#134e4a;">
                            Latihan napas 3 menit 'Grounding Box' untuk meredakan ketegangan.
                        </span>
                    </div>
                </div>
                <button type="button" class="btn-submit-teal" style="padding:8px 16px;font-size:12px;" onclick="alert('Latihan napas Grounding Box 3 menit dimulai...');">
                    Mulai Napas
                </button>
            </div>

            <!-- Bottom Section: Riwayat Entri Jurnal -->
            <div class="mood-history-card">
                <div class="mood-history-header" style="margin-bottom:14px;">
                    <div>
                        <h2 style="font-size:1.15rem;font-weight:700;color:var(--slate-900);margin:0;">
                            Riwayat Jurnal
                        </h2>
                    </div>

                    <!-- Filter buttons -->
                    <div class="mood-history-filters">
                        <button type="button" class="mood-filter-btn active">7 Hari</button>
                        <button type="button" class="mood-filter-btn">30 Hari</button>
                        <button type="button" class="mood-filter-btn">Bulan Ini</button>
                    </div>
                </div>

                <!-- 3 Clean Entries List -->
                <div class="mood-entries-list">
                    
                    <!-- Entry 1 -->
                    <div class="mood-entry-item" style="padding:14px 16px;">
                        <div class="mood-entry-head" style="margin-bottom:6px;">
                            <div class="mood-entry-emotion" style="font-size:13px;">
                                <span>😐</span>
                                <span>Netral</span>
                                <span style="font-size:10.5px;color:var(--slate-500);background:var(--slate-100);padding:1px 6px;border-radius:var(--radius-full);">6.5/10</span>
                            </div>
                            <div style="font-size:11.5px;color:var(--slate-400);">
                                23 Okt • 21:15 WIB
                            </div>
                        </div>
                        <div class="mood-entry-quote" style="font-size:12px;padding:8px 12px;margin-bottom:8px;">
                            "Hujan deras seharian. Energi terasa datar tapi pikiran tetap tenang setelah minum teh hangat."
                        </div>
                        <div class="mood-entry-tags-row">
                            <span class="selfcheck-mini-badge">#Lega</span>
                            <span class="selfcheck-mini-badge">#Fokus</span>
                            <span style="font-size:11px;color:var(--slate-400);margin-left:auto;">🎓 Kuliah</span>
                        </div>
                    </div>

                    <!-- Entry 2 -->
                    <div class="mood-entry-item" style="padding:14px 16px;">
                        <div class="mood-entry-head" style="margin-bottom:6px;">
                            <div class="mood-entry-emotion" style="font-size:13px;">
                                <span>🟣</span>
                                <span>Lelah / Cemas</span>
                                <span style="font-size:10.5px;color:#7c3aed;background:#f5f3ff;padding:1px 6px;border-radius:var(--radius-full);">4.8/10</span>
                            </div>
                            <div style="font-size:11.5px;color:var(--slate-400);">
                                22 Okt • 23:40 WIB
                            </div>
                        </div>
                        <div class="mood-entry-quote" style="font-size:12px;padding:8px 12px;margin-bottom:8px;">
                            "Tugas menumpuk dan kurang tidur. Perlu atur waktu istirahat lebih baik esok hari."
                        </div>
                        <div class="mood-entry-tags-row">
                            <span class="selfcheck-mini-badge">#Cemas</span>
                            <span class="selfcheck-mini-badge">#LelahMental</span>
                            <span style="font-size:11px;color:var(--slate-400);margin-left:auto;">🎓 Kuliah • 💤 Kurang Tidur</span>
                        </div>
                    </div>

                    <!-- Entry 3 -->
                    <div class="mood-entry-item" style="padding:14px 16px;">
                        <div class="mood-entry-head" style="margin-bottom:6px;">
                            <div class="mood-entry-emotion" style="font-size:13px;">
                                <span>🟢</span>
                                <span>Sangat Bahagia</span>
                                <span style="font-size:10.5px;color:#059669;background:#ecfdf5;padding:1px 6px;border-radius:var(--radius-full);">9.2/10</span>
                            </div>
                            <div style="font-size:11.5px;color:var(--slate-400);">
                                21 Okt • 19:30 WIB
                            </div>
                        </div>
                        <div class="mood-entry-quote" style="font-size:12px;padding:8px 12px;margin-bottom:8px;">
                            "Jogging pagi di taman dan dapat kabar baik presentasi diterima. Energi hari ini sangat positif."
                        </div>
                        <div class="mood-entry-tags-row">
                            <span class="selfcheck-mini-badge">#Bersyukur</span>
                            <span class="selfcheck-mini-badge">#Tenang</span>
                            <span style="font-size:11px;color:var(--slate-400);margin-left:auto;">👥 Teman • 🏃 Olahraga</span>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>

    <!-- Script for interactions -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mood buttons
        const moodBtns = document.querySelectorAll('.mood-spectrum-btn');
        moodBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                moodBtns.forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
            });
        });

        // Tags toggle
        const tagPills = document.querySelectorAll('#moodTagsContainer .mood-tag-pill');
        tagPills.forEach(tag => {
            tag.addEventListener('click', () => {
                tag.classList.toggle('active');
            });
        });

        // Trigger boxes toggle
        const triggerBoxes = document.querySelectorAll('#moodTriggersContainer .mood-trigger-box');
        triggerBoxes.forEach(trig => {
            trig.addEventListener('click', () => {
                trig.classList.toggle('active');
            });
        });

        // History filter buttons
        const filterBtns = document.querySelectorAll('.mood-filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            });
        });
    });
    </script>
</body>
</html>
