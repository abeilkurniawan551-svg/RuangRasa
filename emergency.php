<?php
session_start();
require_once 'db_connect.php';

$isLoggedIn = isset($_SESSION['user_id']);
$fullname = $_SESSION['fullname'] ?? 'Sobat RuangRasa';
$firstDisplayName = explode(' ', $fullname)[0];
$fullDisplayName = htmlspecialchars($fullname);
$roleName = $_SESSION['role'] ?? 'Mahasiswi & Anggota RuangRasa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan Darurat &amp; Krisis 24 Jam - RuangRasa</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <!-- Cache-busting link ke style.css -->
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    
    <!-- EMBEDDED STYLES: Memastikan CSS selalu ter-load 100% tanpa terhalang cache browser -->
    <style>
        /* Base page layout */
        .emergency-page-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* 1. Hero Emergency Banner */
        .emergency-hero-banner {
            background: linear-gradient(135deg, #fff1f2 0%, #fff7f7 100%) !important;
            border: 1px solid #fecdd3 !important;
            border-radius: 18px !important;
            padding: 24px 30px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 24px !important;
            box-shadow: 0 4px 20px -2px rgba(225, 29, 72, 0.08) !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .emergency-hero-banner::after {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, rgba(244, 63, 94, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .emergency-hero-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 780px;
        }

        .emergency-tag-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #fee2e2;
            color: #be123c;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 9999px;
            align-self: flex-start;
        }

        .emergency-tag-pill .icon-asterisk {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            background-color: #e11d48;
            color: #ffffff;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 900;
        }

        .emergency-hero-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .emergency-hero-subtitle {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.5;
            margin: 0;
        }

        .emergency-btn-red {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 10px !important;
            background-color: #e11d48 !important;
            color: #ffffff !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            padding: 13px 24px !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            white-space: nowrap !important;
            box-shadow: 0 4px 14px rgba(225, 29, 72, 0.35) !important;
            transition: all 0.2s ease !important;
            flex-shrink: 0 !important;
        }

        .emergency-btn-red:hover {
            background-color: #be123c !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(225, 29, 72, 0.45) !important;
        }

        .emergency-btn-red svg {
            animation: pulse-phone 2s infinite ease-in-out;
        }

        @keyframes pulse-phone {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        /* 2. Three Hotlines Grid */
        .emergency-hotlines-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .hotline-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .hotline-card:hover {
            border-color: #5eead4;
            box-shadow: 0 6px 16px -2px rgba(13, 148, 136, 0.1);
            transform: translateY(-2px);
        }

        .hotline-badges-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .hotline-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .hotline-badge.teal {
            background-color: #f0fdfa;
            color: #115e59;
            border: 1px solid #99f6e4;
        }

        .hotline-badge.green {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .hotline-badge.rose {
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .hotline-badge.purple {
            background-color: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ddd6fe;
        }

        .hotline-badge.amber {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .hotline-badge.slate {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .hotline-card-head {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 14px;
        }

        .hotline-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hotline-icon-box.teal {
            background-color: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
        }

        .hotline-icon-box.rose {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px solid #fecdd3;
        }

        .hotline-icon-box.emerald {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .hotline-card-info {
            display: flex;
            flex-direction: column;
        }

        .hotline-card-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            margin: 0;
        }

        .hotline-card-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 3px;
        }

        /* Number Box */
        .hotline-number-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .hotline-number-meta {
            display: flex;
            flex-direction: column;
        }

        .hotline-number-lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
        }

        .hotline-number-val {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin-top: 2px;
        }

        .hotline-copy-btn {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .hotline-copy-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        .hotline-desc-text {
            font-size: 12.5px;
            color: #475569;
            line-height: 1.5;
            min-height: 38px;
            margin: 0;
        }

        .hotline-actions-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: auto;
        }

        .btn-hotline-primary {
            flex: 1;
            background-color: #0f766e;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
            border: none;
            cursor: pointer;
        }

        .btn-hotline-primary:hover {
            background-color: #115e59;
        }

        .btn-hotline-secondary {
            flex: 1;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #1e293b;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-hotline-secondary:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-hotline-wa {
            flex: 1;
            background-color: #22c55e;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-hotline-wa:hover {
            background-color: #16a34a;
        }

        .btn-hotline-map {
            flex: 1;
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #1e293b;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
        }

        .btn-hotline-map:hover {
            background-color: #f8fafc;
            border-color: #2dd4bf;
            color: #0f766e;
        }

        /* 3. Middle Section: Breathing & Panic Protocol */
        .emergency-two-cols {
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            gap: 20px;
        }

        .emergency-action-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .section-pretitle {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f766e;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-pretitle.rose {
            color: #e11d48;
        }

        .section-main-heading {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
            margin: 4px 0 0 0;
        }

        .section-desc-light {
            font-size: 12.5px;
            color: #475569;
            line-height: 1.5;
            margin: -6px 0 0 0;
        }

        /* Breathing Circle */
        .breathing-visual-box {
            background: linear-gradient(180deg, #f0fdfa 0%, #e6fffa 100%);
            border: 1px solid #ccfbf1;
            border-radius: 16px;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
            text-align: center;
            min-height: 270px;
            position: relative;
            overflow: hidden;
        }

        .breathing-circle-outer {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(45, 212, 191, 0.22);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform 0.6s ease-in-out;
        }

        .breathing-circle-inner {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(13, 148, 136, 0.35);
            transition: transform 1s ease-in-out;
        }

        .breathing-phase-text {
            font-size: 13.5px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .breathing-phase-count {
            font-size: 26px;
            font-weight: 800;
            line-height: 1;
            margin-top: 2px;
        }

        .breathing-status-instruction {
            font-size: 13px;
            font-weight: 600;
            color: #042f2e;
            min-height: 20px;
        }

        .btn-breathing-control {
            background-color: #0f766e;
            color: #ffffff;
            border: none;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        }

        .btn-breathing-control:hover {
            background-color: #115e59;
            transform: translateY(-1px);
        }

        /* Grounding Steps List */
        .grounding-steps-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .grounding-step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 12px 14px;
            border-radius: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease;
        }

        .grounding-step-item:hover {
            background-color: #ffffff;
            border-color: #5eead4;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .grounding-step-num {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #0f766e;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .grounding-step-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .grounding-step-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .grounding-step-desc {
            font-size: 12px;
            color: #475569;
            line-height: 1.45;
        }

        /* 4. Peer Guide Section */
        .peer-guide-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .peer-two-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .peer-box {
            border-radius: 12px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .peer-box.dos {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .peer-box.donts {
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
        }

        .peer-box-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13.5px;
            font-weight: 800;
            margin: 0;
        }

        .peer-box.dos .peer-box-title {
            color: #15803d;
        }

        .peer-box.donts .peer-box-title {
            color: #be123c;
        }

        .peer-items-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .peer-item-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12.5px;
            line-height: 1.45;
        }

        .peer-item-row.do-item {
            color: #14532d;
        }

        .peer-item-row.dont-item {
            color: #881337;
        }

        .peer-icon-mark {
            flex-shrink: 0;
            margin-top: 1px;
        }

        .peer-item-strong {
            font-weight: 700;
        }

        /* 5. Privacy Footer & Quick Exit */
        .emergency-privacy-footer {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .privacy-left-block {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .privacy-icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #f0fdfa;
            color: #0f766e;
            border: 1px solid #99f6e4;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .privacy-text-block {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .privacy-title-bold {
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
        }

        .privacy-desc-para {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }

        .btn-quick-exit {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #334155;
            font-size: 12.5px;
            font-weight: 700;
            padding: 9px 16px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .btn-quick-exit:hover {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #b91c1c;
        }

        .btn-quick-exit kbd {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 1px 5px;
            font-size: 10px;
            color: #475569;
        }

        /* Floating Copy Toast */
        .emergency-copy-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25);
            z-index: 9999;
            opacity: 0;
            transform: translateY(12px);
            pointer-events: none;
            transition: all 0.25s ease;
        }

        .emergency-copy-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .emergency-hotlines-grid,
            .emergency-two-cols,
            .peer-two-cols {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .emergency-hero-banner {
                flex-direction: column;
                align-items: flex-start;
            }
            .emergency-btn-red {
                width: 100%;
            }
            .emergency-privacy-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            .btn-quick-exit {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
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
            <input type="text" class="app-search-input" placeholder="Cari materi meditasi, nama psikolog, artikel...">
        </div>

        <!-- Header Actions -->
        <div class="app-header-actions">
            <a href="emergency.php" class="btn-crisis-header" style="background:#fee2e2;border-color:#fca5a5;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Pusat Bantuan Krisis (119 ext 8)</span>
            </a>

            <button type="button" class="header-icon-btn" title="Notifikasi" onclick="alert('Tidak ada notifikasi darurat baru.');">
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
                        <a href="emergency.php" class="active">
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
                        <a href="login.php" style="color:#0f766e;font-weight:700;">
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
                    <span class="sidebar-user-sub"><?= htmlspecialchars($roleName) ?></span>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="dashboard-content-scroll">
            <div class="emergency-page-container">

                <!-- 1. HERO EMERGENCY BANNER -->
                <section class="emergency-hero-banner">
                    <div class="emergency-hero-content">
                        <div class="emergency-tag-pill">
                            <span class="icon-asterisk">★</span>
                            <span>SIAGA DARURAT 24 JAM • RAHASIA &amp; TERLINDUNGI</span>
                        </div>
                        <h1 class="emergency-hero-title">
                            Kamu Tidak Sendirian. Bantuan Segera Selalu Ada Untukmu.
                        </h1>
                        <p class="emergency-hero-subtitle">
                            Jika kamu atau orang terdekat sedang mengalami krisis emosional, tekanan hebat, atau kondisi darurat—segera hubungi layanan bebas pulsa di bawah. Siaga 24 jam.
                        </p>
                    </div>

                    <a href="tel:119,8" class="emergency-btn-red" id="heroCall119Btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                        <span>Panggil 119 Ext 8</span>
                    </a>
                </section>

                <!-- 2. THREE HOTLINES ROW -->
                <section class="emergency-hotlines-grid">
                    
                    <!-- Card 1: Layanan SEJIWA -->
                    <div class="hotline-card">
                        <div>
                            <div class="hotline-badges-row">
                                <span class="hotline-badge teal">Kementerian Kesehatan RI</span>
                                <span class="hotline-badge green">24 Jam Bebas Pulsa</span>
                            </div>

                            <div class="hotline-card-head">
                                <div class="hotline-icon-box teal">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                </div>
                                <div class="hotline-card-info">
                                    <h2 class="hotline-card-title">Layanan SEJIWA</h2>
                                    <span class="hotline-card-subtitle">Kemenkes Republik Indonesia</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline-number-box">
                            <div class="hotline-number-meta">
                                <span class="hotline-number-lbl">Hotline Resmi Darurat</span>
                                <span class="hotline-number-val">119 Ext 8</span>
                            </div>
                            <button type="button" class="hotline-copy-btn" data-copy="119" title="Salin nomor hotline">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>

                        <p class="hotline-desc-text">
                            Layanan konseling darurat psikologis resmi Kemenkes RI terhubung langsung dengan psikolog klinis siaga tanggap.
                        </p>

                        <div class="hotline-actions-row">
                            <a href="tel:119,8" class="btn-hotline-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>Hubungi 119 Ext 8 Sekarang</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Hotline LISA -->
                    <div class="hotline-card">
                        <div>
                            <div class="hotline-badges-row">
                                <span class="hotline-badge rose">Pencegahan Krisis Bunuh Diri</span>
                                <span class="hotline-badge purple">ID &amp; EN Support</span>
                            </div>

                            <div class="hotline-card-head">
                                <div class="hotline-icon-box rose">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                                </div>
                                <div class="hotline-card-info">
                                    <h2 class="hotline-card-title">Hotline LISA</h2>
                                    <span class="hotline-card-subtitle">Love Inside Suicide Awareness</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline-number-box">
                            <div class="hotline-number-meta">
                                <span class="hotline-number-lbl">Saluran Panggilan &amp; WA</span>
                                <span class="hotline-number-val">0811-3815-472</span>
                            </div>
                            <button type="button" class="hotline-copy-btn" data-copy="08113815472" title="Salin nomor Hotline LISA">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>

                        <p class="hotline-desc-text">
                            Dukungan pencegahan krisis dan intervensi darurat dalam Bahasa Indonesia &amp; Inggris, bebas stigma dan penuh empati.
                        </p>

                        <div class="hotline-actions-row">
                            <a href="tel:08113815472" class="btn-hotline-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>Telepon</span>
                            </a>
                            <a href="https://wa.me/628113815472?text=Halo%20Hotline%20LISA,%20saya%20sedang%20mengalami%20krisis%20dan%20membutuhkan%20bantuan%20segera" target="_blank" rel="noopener" class="btn-hotline-wa">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>

                    <!-- Card 3: IGD Psikiatri Terdekat -->
                    <div class="hotline-card">
                        <div>
                            <div class="hotline-badges-row">
                                <span class="hotline-badge amber">Tindakan Medis Darurat</span>
                                <span class="hotline-badge slate">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    Radius 5 km
                                </span>
                            </div>

                            <div class="hotline-card-head">
                                <div class="hotline-icon-box emerald">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"></path><path d="M2 12h20"></path><rect x="3" y="3" width="18" height="18" rx="2"></rect></svg>
                                </div>
                                <div class="hotline-card-info">
                                    <h2 class="hotline-card-title">IGD Psikiatri Terdekat</h2>
                                    <span class="hotline-card-subtitle">RSJ &amp; RSUD Rujukan 24 Jam</span>
                                </div>
                            </div>
                        </div>

                        <div class="hotline-number-box">
                            <div class="hotline-number-meta" style="overflow:hidden;">
                                <span class="hotline-number-lbl">RS Jiwa Dr. Soeharto Heerdjan</span>
                                <span style="font-size:12px;font-weight:700;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    Jl. Prof. Dr. Latumeten No. 1, Grogol (2.4 km)
                                </span>
                            </div>
                            <a href="https://maps.google.com/?q=RS+Jiwa+Dr.+Soeharto+Heerdjan" target="_blank" rel="noopener" class="hotline-copy-btn" title="Buka peta">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                            </a>
                        </div>

                        <p class="hotline-desc-text">
                            Pusat gawat darurat psikiatri dengan Dokter Jiwa (Sp.KJ) dan perawat krisis siaga non-stop 24 jam.
                        </p>

                        <div class="hotline-actions-row">
                            <a href="https://maps.google.com/?q=RS+Jiwa+Dr.+Soeharto+Heerdjan" target="_blank" rel="noopener" class="btn-hotline-map">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 11 22 2 13 21 11 13 3 11"></polygon></svg>
                                <span>Buka Petunjuk Arah IGD</span>
                            </a>
                        </div>
                    </div>

                </section>

                <!-- 3. MIDDLE SECTION: RITME NAPAS & PROTOKOL PANIK -->
                <div class="emergency-two-cols">
                    
                    <!-- LEFT: Guided 4-7-8 Breathing Technique -->
                    <section class="emergency-action-card">
                        <div>
                            <span class="section-pretitle">Latihan Mandiri Cepat</span>
                            <h2 class="section-main-heading">Ritme Napas 4-7-8</h2>
                        </div>
                        <p class="section-desc-light">
                            Teknik napas darurat untuk mengaktifkan sistem saraf parasimpatis dan menurunkan lonjakan detak jantung seketika.
                        </p>

                        <!-- Breathing Visualizer -->
                        <div class="breathing-visual-box">
                            <div class="breathing-circle-outer" id="breathingCircleOuter">
                                <div class="breathing-circle-inner" id="breathingCircleInner">
                                    <span class="breathing-phase-text" id="breathingPhaseText">Siap</span>
                                    <span class="breathing-phase-count" id="breathingCount">4</span>
                                </div>
                            </div>

                            <div class="breathing-status-instruction" id="breathingInstruction">
                                Tekan tombol di bawah untuk memulai panduan relaksasi.
                            </div>

                            <button type="button" class="btn-breathing-control" id="toggleBreathingBtn">
                                <svg id="breathingPlayIcon" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg>
                                <span id="breathingBtnText">Mulai Panduan Napas</span>
                            </button>
                        </div>
                    </section>

                    <!-- RIGHT: Panic Attack Protocol (4 Grounding Steps) -->
                    <section class="emergency-action-card">
                        <div>
                            <span class="section-pretitle rose">PROTOKOL STABILISASI EMOSI</span>
                            <h2 class="section-main-heading">Pertolongan Pertama Saat Serangan Panik</h2>
                        </div>
                        <p class="section-desc-light">
                            Saat cemas berlebih atau tubuh gemetar hebat, lakukan 4 langkah grounding fisik ini secara berurutan:
                        </p>

                        <div class="grounding-steps-list">
                            <!-- Step 1 -->
                            <div class="grounding-step-item">
                                <div class="grounding-step-num">1</div>
                                <div class="grounding-step-content">
                                    <div class="grounding-step-title">Pijakkan Kaki Kuat-Kuat ke Lantai</div>
                                    <div class="grounding-step-desc">
                                        Lepaskan alas kaki jika memungkinkan. Rasakan gravitasi menopang tubuhmu. Kamu ada di tempat yang aman saat ini.
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="grounding-step-item">
                                <div class="grounding-step-num">2</div>
                                <div class="grounding-step-content">
                                    <div class="grounding-step-title">Sentuh 3 Benda Bertekstur Berbeda</div>
                                    <div class="grounding-step-desc">
                                        Pegang ujung pakaian, meja dingin, atau kunci logam. Alihkan fokus pikiran pada suhu dan tekstur untuk meredakan disosiasi.
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="grounding-step-item">
                                <div class="grounding-step-num">3</div>
                                <div class="grounding-step-content">
                                    <div class="grounding-step-title">Teguk Air Dingin Pelan-Pelan</div>
                                    <div class="grounding-step-desc">
                                        Perhatikan sensasi dingin di tenggorokan saat menelan. Refleks menelan secara biologis menstabilkan laju napas yang terengah.
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="grounding-step-item">
                                <div class="grounding-step-num">4</div>
                                <div class="grounding-step-content">
                                    <div class="grounding-step-title">Hubungi Hotline / Satu Orang Tepercaya</div>
                                    <div class="grounding-step-desc">
                                        Kirim pesan sederhana: <em>"Aku sedang panik, bisakah temani aku sebentar?"</em> atau langsung tekan tombol panggilan 119 di atas.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>

                <!-- 4. PANDUAN REKAN & PENDAMPING (DO'S & DON'TS) -->
                <section class="peer-guide-card">
                    <div class="peer-guide-header">
                        <span class="section-pretitle">PANDUAN REKAN &amp; PENDAMPING</span>
                        <h2 class="section-main-heading">Menghadapi Teman yang Sedang Mengalami Krisis</h2>
                        <p class="section-desc-light">
                            Ketika seseorang mempercayaimu dalam titik terendahnya, kehadiran yang tenang jauh lebih berharga daripada solusi yang terburu-buru.
                        </p>
                    </div>

                    <div class="peer-two-cols">
                        <!-- DO'S -->
                        <div class="peer-box dos">
                            <div class="peer-box-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Hal yang Sebaiknya Dilakukan (Do's)</span>
                            </div>

                            <ul class="peer-items-list">
                                <li class="peer-item-row do-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div>
                                        <span class="peer-item-strong">Dengarkan tanpa menghakimi:</span> Biarkan mereka meluapkan rasa sakit tanpa memotong atau berusaha menceramahi.
                                    </div>
                                </li>
                                <li class="peer-item-row do-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div>
                                        <span class="peer-item-strong">Validasi perasaan mereka:</span> Ucapkan kalimat seperti <em>"Aku paham ini sangat berat untukmu, terima kasih sudah mau bertahan."</em>
                                    </div>
                                </li>
                                <li class="peer-item-row do-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div>
                                        <span class="peer-item-strong">Amankan lingkungan sekitar:</span> Jauhkan benda berbahaya, obat-obatan berlebih, atau situasi berisiko secara halus dan tenang.
                                    </div>
                                </li>
                                <li class="peer-item-row do-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div>
                                        <span class="peer-item-strong">Temani saat menelepon bantuan:</span> Tawarkan diri untuk mendampingi ketika mereka menelepon 119 Ext 8 atau LISA hotline.
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- DON'TS -->
                        <div class="peer-box donts">
                            <div class="peer-box-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                <span>Hal yang Harus Dihindari (Don'ts)</span>
                            </div>

                            <ul class="peer-items-list">
                                <li class="peer-item-row dont-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <div>
                                        <span class="peer-item-strong">Jangan membandingkan penderitaan:</span> Hindari kalimat <em>"Masih banyak orang yang lebih susah dari kamu"</em> yang memperparah rasa bersalah.
                                    </div>
                                </li>
                                <li class="peer-item-row dont-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <div>
                                        <span class="peer-item-strong">Jangan menganggap sepele atau drama:</span> Tanggapi setiap ungkapan ingin menyerah sebagai panggilan bantuan yang nyata.
                                    </div>
                                </li>
                                <li class="peer-item-row dont-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <div>
                                        <span class="peer-item-strong">Jangan tinggalkan sendirian:</span> Jika dalam kondisi agitasi tinggi atau ada ancaman menyakiti diri, tetaplah bersama mereka.
                                    </div>
                                </li>
                                <li class="peer-item-row dont-item">
                                    <svg class="peer-icon-mark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    <div>
                                        <span class="peer-item-strong">Jangan berjanji merahasiakan bahaya:</span> Keselamatan nyawa berada di atas janji menjaga rahasia. Segera libatkan bantuan medis.
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- 5. PRIVACY GUARANTEE & QUICK EXIT -->
                <footer class="emergency-privacy-footer">
                    <div class="privacy-left-block">
                        <div class="privacy-icon-circle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <div class="privacy-text-block">
                            <span class="privacy-title-bold">Jaminan Kerahasiaan &amp; Keamanan Privasi</span>
                            <span class="privacy-desc-para">
                                Panggilan atau akses hotline krisis pada halaman ini terenkripsi dan tidak disimpan dalam riwayat aktivitas aplikasi RuangRasa demi privasi penuh tanpa jejak.
                            </span>
                        </div>
                    </div>

                    <button type="button" class="btn-quick-exit" id="quickExitBtn" title="Tutup segera halaman ini dan alihkan ke pencarian Google">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Keluar Cepat (Quick Exit)</span>
                        <kbd>Esc</kbd>
                    </button>
                </footer>

            </div>
        </main>
    </div>

    <!-- FLOATING TOAST FEEDBACK -->
    <div class="emergency-copy-toast" id="copyToast">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
        <span id="copyToastMsg">Nomor hotline berhasil disalin!</span>
    </div>

    <!-- INTERACTIVE JAVASCRIPT LOGIC -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. Copy Hotline Buttons
        const copyBtns = document.querySelectorAll('.hotline-copy-btn');
        const toast = document.getElementById('copyToast');
        const toastMsg = document.getElementById('copyToastMsg');
        let toastTimeout;

        function showToast(msg) {
            if (toast) {
                toastMsg.textContent = msg;
                toast.classList.add('show');
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.remove('show');
                }, 2400);
            }
        }

        copyBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const textToCopy = this.getAttribute('data-copy');
                if (textToCopy) {
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        showToast(`Nomor ${textToCopy} berhasil disalin ke papan klip!`);
                    }).catch(() => {
                        showToast(`Nomor disalin: ${textToCopy}`);
                    });
                }
            });
        });

        // 2. Quick Exit (Safety Escape Feature)
        const quickExitBtn = document.getElementById('quickExitBtn');
        function executeQuickExit() {
            window.location.replace('https://www.google.com');
        }

        if (quickExitBtn) {
            quickExitBtn.addEventListener('click', executeQuickExit);
        }

        // Listen for ESC key for emergency exit
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                executeQuickExit();
            }
        });

        // 3. Functional 4-7-8 Breathing Technique
        const toggleBreathingBtn = document.getElementById('toggleBreathingBtn');
        const breathingPlayIcon = document.getElementById('breathingPlayIcon');
        const breathingBtnText = document.getElementById('breathingBtnText');
        const outerCircle = document.getElementById('breathingCircleOuter');
        const innerCircle = document.getElementById('breathingCircleInner');
        const phaseText = document.getElementById('breathingPhaseText');
        const countText = document.getElementById('breathingCount');
        const instructionText = document.getElementById('breathingInstruction');

        let isBreathingActive = false;
        let breathingTimer = null;
        let countdownTimer = null;
        let currentSecond = 4;

        // Gentle Web Audio tone for breathing cues
        let audioCtx = null;
        function playGentleChime(freq = 440) {
            try {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.8);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.8);
            } catch(e) {}
        }

        function runBreathingCycle() {
            if (!isBreathingActive) return;

            // Phase 1: INHALE (4 seconds)
            phaseText.textContent = 'Tarik';
            instructionText.textContent = 'Tarik napas perlahan lewat hidung...';
            currentSecond = 4;
            countText.textContent = currentSecond;
            playGentleChime(440);

            // Expand circles
            if (outerCircle) outerCircle.style.transform = 'scale(1.35)';
            if (innerCircle) {
                innerCircle.style.transform = 'scale(1.25)';
                innerCircle.style.background = 'linear-gradient(135deg, #0d9488, #0f766e)';
            }

            let inhaleCounter = setInterval(() => {
                if (!isBreathingActive) { clearInterval(inhaleCounter); return; }
                currentSecond--;
                if (currentSecond > 0) {
                    countText.textContent = currentSecond;
                } else {
                    clearInterval(inhaleCounter);
                }
            }, 1000);

            // Phase 2: HOLD (7 seconds)
            breathingTimer = setTimeout(() => {
                if (!isBreathingActive) return;
                phaseText.textContent = 'Tahan';
                instructionText.textContent = 'Tahan napas dengan tenang dan rileks...';
                currentSecond = 7;
                countText.textContent = currentSecond;
                playGentleChime(523.25);

                if (innerCircle) {
                    innerCircle.style.background = 'linear-gradient(135deg, #0284c7, #0369a1)';
                }

                let holdCounter = setInterval(() => {
                    if (!isBreathingActive) { clearInterval(holdCounter); return; }
                    currentSecond--;
                    if (currentSecond > 0) {
                        countText.textContent = currentSecond;
                    } else {
                        clearInterval(holdCounter);
                    }
                }, 1000);

                // Phase 3: EXHALE (8 seconds)
                breathingTimer = setTimeout(() => {
                    if (!isBreathingActive) return;
                    phaseText.textContent = 'Hembuskan';
                    instructionText.textContent = 'Hembuskan perlahan lewat mulut sampai habis...';
                    currentSecond = 8;
                    countText.textContent = currentSecond;
                    playGentleChime(349.23);

                    // Shrink circles
                    if (outerCircle) outerCircle.style.transform = 'scale(1)';
                    if (innerCircle) {
                        innerCircle.style.transform = 'scale(1)';
                        innerCircle.style.background = 'linear-gradient(135deg, #0d9488, #0f766e)';
                    }

                    let exhaleCounter = setInterval(() => {
                        if (!isBreathingActive) { clearInterval(exhaleCounter); return; }
                        currentSecond--;
                        if (currentSecond > 0) {
                            countText.textContent = currentSecond;
                        } else {
                            clearInterval(exhaleCounter);
                        }
                    }, 1000);

                    // Next cycle
                    breathingTimer = setTimeout(() => {
                        if (isBreathingActive) {
                            runBreathingCycle();
                        }
                    }, 8000);

                }, 7000);

            }, 4000);
        }

        function stopBreathingCycle() {
            isBreathingActive = false;
            clearTimeout(breathingTimer);
            if (outerCircle) outerCircle.style.transform = 'scale(1)';
            if (innerCircle) {
                innerCircle.style.transform = 'scale(1)';
                innerCircle.style.background = 'linear-gradient(135deg, #0d9488, #0f766e)';
            }
            phaseText.textContent = 'Siap';
            countText.textContent = '4';
            instructionText.textContent = 'Tekan tombol di bawah untuk memulai panduan relaksasi.';
            breathingBtnText.textContent = 'Mulai Panduan Napas';
            breathingPlayIcon.innerHTML = '<polygon points="5 3 19 12 5 21 5 3"></polygon>';
            toggleBreathingBtn.style.backgroundColor = '#0f766e';
        }

        if (toggleBreathingBtn) {
            toggleBreathingBtn.addEventListener('click', function() {
                if (!isBreathingActive) {
                    isBreathingActive = true;
                    breathingBtnText.textContent = 'Jeda Panduan';
                    breathingPlayIcon.innerHTML = '<rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect>';
                    toggleBreathingBtn.style.backgroundColor = '#475569';
                    runBreathingCycle();
                } else {
                    stopBreathingCycle();
                }
            });
        }

    });
    </script>
</body>
</html>
