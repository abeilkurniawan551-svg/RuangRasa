<?php
session_start();
if (!isset($_SESSION['fullname'])) {
    $_SESSION['fullname'] = 'Sobat RuangRasa';
    $_SESSION['role'] = 'Member Aktif RuangRasa';
}
$fullname = $_SESSION['fullname'];
$firstDisplayName = explode(' ', $fullname)[0];
$fullDisplayName = htmlspecialchars($fullname);
$roleName = $_SESSION['role'] ?? 'Mahasiswi & Anggota RuangRasa';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Privat Bersama Psikolog Berlisensi - RuangRasa</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <style>
        /* Specific Styles for Konsultasi Psikolog */
        .konsul-container {
            padding: 4px 0 50px 0;
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        /* Top Trust Banner */
        .konsul-trust-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 8px 18px;
            border-radius: var(--radius-full);
            font-size: 13px;
            color: var(--teal-800);
            font-weight: 500;
        }

        /* Header Row */
        .konsul-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            flex-wrap: wrap;
        }
        .konsul-header-text {
            max-width: 760px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .konsul-badge-category {
            align-self: flex-start;
            background: #e6fffa;
            color: var(--teal-700);
            border: 1px solid #b2f5ea;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: var(--radius-full);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .konsul-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.5px;
            line-height: 1.25;
        }
        .konsul-subtitle {
            font-size: 15px;
            color: var(--slate-600);
            line-height: 1.6;
        }
        .konsul-trust-points {
            display: flex;
            gap: 18px;
            margin-top: 4px;
            flex-wrap: wrap;
            font-size: 12.5px;
            color: var(--slate-600);
            font-weight: 600;
        }
        .konsul-trust-point {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .konsul-trust-point svg {
            color: var(--teal-600);
        }

        /* Right Privacy Guarantee Badge */
        .konsul-privacy-card {
            background: #ffffff;
            border: 1.5px solid #ccfbf1;
            border-radius: var(--radius-xl);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 14px rgba(13, 148, 136, 0.05);
            max-width: 320px;
        }
        .privacy-card-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            background: #f0fdfa;
            color: var(--teal-700);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .privacy-card-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .privacy-card-title {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--slate-900);
        }
        .privacy-card-desc {
            font-size: 11.5px;
            color: var(--slate-500);
            line-height: 1.4;
        }

        /* SEARCH & FILTER BAR */
        .konsul-filter-bar {
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 16px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }
        .konsul-search-row {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }
        .konsul-search-wrap {
            position: relative;
            flex: 1;
            min-width: 280px;
        }
        .konsul-search-wrap svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
        }
        .konsul-search-input {
            width: 100%;
            padding: 11px 16px 11px 42px;
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            font-size: 13.5px;
            color: var(--slate-800);
            outline: none;
            transition: all 0.2s ease;
        }
        .konsul-search-input:focus {
            background: #ffffff;
            border-color: var(--teal-600);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        .konsul-select-filter {
            padding: 11px 16px;
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-700);
            cursor: pointer;
        }
        .konsul-pills-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .konsul-pill {
            padding: 7px 15px;
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: var(--radius-full);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .konsul-pill:hover {
            background: #e2e8f0;
            color: var(--slate-900);
        }
        .konsul-pill.active {
            background: var(--teal-900);
            color: #ffffff;
        }

        /* 2-COLUMN LAYOUT: Psychologists List (Left) & Booking Flow Box (Right) */
        .konsul-layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 28px;
            align-items: start;
            width: 100%;
        }
        @media (max-width: 1024px) {
            .konsul-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT: PSYCHOLOGISTS LIST */
        .psych-list-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
            width: 100%;
        }
        .psych-card {
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.2s ease;
            position: relative;
        }
        .psych-card:hover {
            border-color: var(--teal-400);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.07);
        }
        .psych-card.selected {
            border-color: var(--teal-600);
            background: #fcfffe;
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.15);
        }
        .psych-card-header {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .psych-avatar-wrap {
            position: relative;
            width: 72px;
            height: 72px;
            flex-shrink: 0;
        }
        .psych-avatar {
            width: 100%;
            height: 100%;
            border-radius: var(--radius-xl);
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: var(--shadow-sm);
        }
        .psych-verified-badge {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #059669;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
        }
        .psych-main-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }
        .psych-availability-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            font-weight: 700;
        }
        .psych-avail-tag {
            color: #059669;
            background: #ecfdf5;
            padding: 2px 8px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .psych-rating {
            color: #d97706;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .psych-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.3;
        }
        .psych-credentials {
            font-size: 12px;
            color: var(--slate-500);
        }
        .psych-tags-row {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 4px;
        }
        .psych-spec-pill {
            font-size: 11px;
            font-weight: 600;
            color: var(--teal-900);
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 2px 8px;
            border-radius: var(--radius-full);
        }
        .psych-therapy-box {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-lg);
            padding: 10px 14px;
            font-size: 12.5px;
            color: var(--slate-600);
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .psych-therapy-box svg {
            color: var(--teal-700);
            flex-shrink: 0;
            margin-top: 2px;
        }
        .psych-footer-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid var(--slate-100);
            flex-wrap: wrap;
            gap: 12px;
        }
        .psych-price-info {
            display: flex;
            flex-direction: column;
        }
        .psych-price-label {
            font-size: 11px;
            color: var(--slate-500);
        }
        .psych-price-val {
            font-size: 17px;
            font-weight: 800;
            color: var(--teal-950);
        }
        .psych-price-val span {
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-400);
        }
        .btn-select-psych {
            background: var(--teal-800);
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-select-psych:hover {
            background: var(--teal-900);
            transform: translateY(-1px);
        }

        /* RIGHT: INTERACTIVE BOOKING FLOW BOX */
        .booking-sidebar-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            width: 380px;
            min-width: 320px;
        }
        .booking-flow-card {
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .booking-flow-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--slate-100);
        }
        .booking-flow-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .booking-badge-privat {
            background: #ecfdf5;
            color: #065f46;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
        }
        .booking-step-title {
            font-size: 13px;
            font-weight: 800;
            color: var(--slate-800);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .step-circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--teal-100);
            color: var(--teal-900);
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Date selector cards */
        .booking-dates-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }
        .date-select-btn {
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 8px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .date-select-btn:hover {
            border-color: var(--teal-400);
        }
        .date-select-btn.active {
            background: var(--teal-900);
            border-color: var(--teal-900);
            color: #ffffff;
        }
        .date-btn-day {
            font-size: 10px;
            font-weight: 600;
            color: var(--slate-500);
        }
        .date-select-btn.active .date-btn-day {
            color: #a7f3d0;
        }
        .date-btn-num {
            font-size: 14px;
            font-weight: 800;
        }

        /* Time slots */
        .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
            margin-top: 8px;
        }
        .time-slot-btn {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 7px;
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-700);
            text-align: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .time-slot-btn:hover {
            border-color: var(--teal-400);
        }
        .time-slot-btn.active {
            background: var(--teal-800);
            color: #ffffff;
            border-color: var(--teal-800);
        }

        /* Format selector cards (Video, Voice, Chat) */
        .format-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }
        .format-card-btn {
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 10px 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
        }
        .format-card-btn:hover {
            border-color: var(--teal-400);
        }
        .format-card-btn.active {
            background: #f0fdfa;
            border-color: var(--teal-600);
        }
        .format-card-btn svg {
            color: var(--slate-500);
        }
        .format-card-btn.active svg {
            color: var(--teal-700);
        }
        .format-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--slate-800);
        }
        .format-sub {
            font-size: 9.5px;
            color: var(--slate-400);
        }

        /* Payment methods */
        .payment-methods-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .pay-option-btn {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--slate-700);
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .pay-option-btn:hover {
            background: #f8fafc;
        }
        .pay-option-btn.active {
            background: #f0fdfa;
            border-color: var(--teal-600);
            color: var(--teal-900);
            font-weight: 700;
        }

        /* Breakdown Price */
        .booking-summary-box {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-lg);
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 12.5px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            color: var(--slate-600);
        }
        .summary-row.total {
            font-weight: 800;
            color: var(--slate-900);
            font-size: 14px;
            border-top: 1px dashed var(--slate-300);
            padding-top: 6px;
            margin-top: 2px;
        }

        .booking-guarantee-note {
            font-size: 11.5px;
            color: var(--slate-500);
            line-height: 1.45;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }
        .booking-guarantee-note svg {
            color: var(--teal-600);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .btn-confirm-payment {
            background: var(--teal-800);
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: var(--radius-lg);
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            width: 100%;
        }
        .btn-confirm-payment:hover {
            background: var(--teal-900);
            transform: translateY(-1px);
        }

        /* BOTTOM QUESTION BANNER */
        .konsul-bottom-banner {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: var(--radius-2xl);
            padding: 24px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .bottom-banner-text-wrap {
            max-width: 680px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .bottom-banner-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--slate-900);
        }
        .bottom-banner-desc {
            font-size: 13.5px;
            color: var(--slate-600);
            line-height: 1.5;
        }
        .bottom-banner-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-banner-outline {
            background: #ffffff;
            border: 1.5px solid var(--teal-300);
            color: var(--teal-800);
            padding: 9px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-banner-outline:hover {
            background: #e6fffa;
        }
        .btn-banner-solid {
            background: var(--teal-800);
            color: #ffffff;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-banner-solid:hover {
            background: var(--teal-900);
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
            <a href="emergency.php" class="btn-crisis-header">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Pusat Bantuan Krisis (119 ext 8)</span>
            </a>

            <button type="button" class="header-icon-btn" title="Notifikasi">
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
                        <a href="mood-tracker.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
                            <span>Mood Tracker</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="meditasi.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a5 5 0 0 0-5 5v1a5 5 0 0 0 10 0V7a5 5 0 0 0-5-5z"></path><path d="M5 13a7 7 0 0 0 14 0"></path><path d="M12 20v2"></path></svg>
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
                        <a href="konsultasi.php" class="active">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
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
                        <a href="profile.php">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                            <span>Profil &amp; Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Bottom user summary card -->
            <div class="sidebar-user-card">
                <img src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='64' height='64' viewBox='0 0 64 64'><circle cx='32' cy='32' r='32' fill='%23fed7aa'/><circle cx='32' cy='24' r='12' fill='%23ea580c'/><path d='M12 56c0-11 9-16 20-16s20 5 20 16' fill='%230f766e'/></svg>" alt="<?= $fullDisplayName ?>">
                <div class="sidebar-user-details">
                    <span class="sidebar-user-name"><?= $fullDisplayName ?></span>
                    <span class="sidebar-user-sub">Mahasiswi &amp; Anggota RuangRasa</span>
                </div>
            </div>
        </aside>

        <!-- MAIN SCROLLABLE CONTENT -->
        <main class="dashboard-content-scroll">
            <div class="konsul-container">

                <!-- TOP TRUST BAR -->
                <div class="konsul-trust-bar">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Layanan Konseling Klinis Resmi Terakreditasi HIMPSI &amp; Kementerian Kesehatan RI</span>
                    </div>
                    <span style="font-size:12px; color:var(--teal-700);">Sesi Privat Tanpa Rekaman</span>
                </div>

                <!-- HEADER ROW -->
                <div class="konsul-header-row">
                    <div class="konsul-header-text">
                        <span class="konsul-badge-category">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Tersertifikasi STR &amp; Anggota HIMPSI
                        </span>
                        <h1 class="konsul-title">Konsultasi Privat Bersama Psikolog Berlisensi</h1>
                        <p class="konsul-subtitle">
                            Konselor dan psikolog klinis kami siap mendampingimu mengurai benang kusut emosional. Dapatkan ruang aman, rahasia, dan terenkripsi untuk memulihkan diri tanpa penghakiman.
                        </p>

                        <div class="konsul-trust-points">
                            <span class="konsul-trust-point">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                Enkripsi E2E Medis
                            </span>
                            <span class="konsul-trust-point">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                Reschedule Bebas H-3 Jam
                            </span>
                            <span class="konsul-trust-point">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                98.4% Tingkat Kepuasan Klien
                            </span>
                        </div>
                    </div>

                    <!-- Right Privacy Card -->
                    <div class="konsul-privacy-card">
                        <div class="privacy-card-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div class="privacy-card-text">
                            <span class="privacy-card-title">Ruang Perlindungan Klien</span>
                            <span class="privacy-card-desc">Kerahasiaan catatan sesi terjamin UU Perlindungan Data Pribadi No. 27/2022.</span>
                        </div>
                    </div>
                </div>

                <!-- SEARCH & FILTER BAR -->
                <div class="konsul-filter-bar">
                    <div class="konsul-search-row">
                        <div class="konsul-search-wrap">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <input type="text" id="psychSearchInput" class="konsul-search-input" placeholder="Cari nama psikolog atau keluhan (Burnout, Trauma, Relasi, Cemas, Tidur)..." oninput="filterPsychologists()">
                        </div>

                        <select class="konsul-select-filter" id="filterAvailability" onchange="filterPsychologists()">
                            <option value="all">Jadwal: Kapan Saja</option>
                            <option value="today">Tersedia Hari Ini</option>
                            <option value="tomorrow">Tersedia Besok</option>
                        </select>

                        <select class="konsul-select-filter" id="sortPsych" onchange="sortPsychologists(this.value)">
                            <option value="rekomendasi">Urutkan: Rekomendasi</option>
                            <option value="rating">Rating Tertinggi</option>
                            <option value="harga_rendah">Biaya Terendah</option>
                        </select>
                    </div>

                    <div class="konsul-pills-row" id="specPills">
                        <button type="button" class="konsul-pill active" onclick="setSpecFilter('all', this)">Semua Spesialisasi</button>
                        <button type="button" class="konsul-pill" onclick="setSpecFilter('burnout', this)">Karier &amp; Burnout</button>
                        <button type="button" class="konsul-pill" onclick="setSpecFilter('cemas', this)">Kecemasan &amp; Depresi</button>
                        <button type="button" class="konsul-pill" onclick="setSpecFilter('relasi', this)">Masalah Relasi/Keluarga</button>
                        <button type="button" class="konsul-pill" onclick="setSpecFilter('mahasiswa', this)">Mahasiswa &amp; Dewasa Muda</button>
                        <button type="button" class="konsul-pill" onclick="setSpecFilter('trauma', this)">Trauma Masa Lalu</button>
                    </div>
                </div>

                <!-- 2-COLUMN LAYOUT -->
                <div class="konsul-layout-grid">
                    
                    <!-- LEFT COLUMN: PSYCHOLOGISTS LIST -->
                    <div class="psych-list-col" id="psychListContainer">
                        
                        <!-- Psych 1: dr. Citra Larasati -->
                        <article class="psych-card selected" id="cardPsych1" data-id="1" data-name="dr. Citra Larasati, M.Psi., Psikolog" data-price="119000" data-spec="burnout mahasiswa relasi" data-rating="5.0" data-avail="tomorrow">
                            <div class="psych-card-header">
                                <div class="psych-avatar-wrap">
                                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80" alt="dr. Citra Larasati" class="psych-avatar">
                                    <div class="psych-verified-badge" title="Tersertifikasi STR & HIMPSI">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </div>

                                <div class="psych-main-info">
                                    <div class="psych-availability-row">
                                        <span class="psych-avail-tag">● Tersedia Besok • 3 Slot</span>
                                        <span class="psych-rating">★ 5.0 (310+ ulasan)</span>
                                    </div>

                                    <h3 class="psych-name">dr. Citra Larasati, M.Psi., Psikolog</h3>
                                    <span class="psych-credentials">SIPP: 2019-1142-KL • Alumni Universitas Indonesia</span>

                                    <div class="psych-tags-row">
                                        <span class="psych-spec-pill">#Burnout &amp; Produktivitas</span>
                                        <span class="psych-spec-pill">#Quarter-Life Crisis</span>
                                        <span class="psych-spec-pill">#Regulasi Emosi</span>
                                    </div>
                                </div>
                            </div>

                            <div class="psych-therapy-box">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <div>
                                    <strong>Pendekatan Terapi:</strong> Cognitive Behavioral Therapy (CBT), Mindfulness-Based Cognitive Therapy, Solution-Focused Brief Therapy.
                                </div>
                            </div>

                            <div class="psych-footer-row">
                                <div class="psych-price-info">
                                    <span class="psych-price-label">Tarif Terjangkau Mahasiswa/Awal Karier</span>
                                    <span class="psych-price-val">Rp 119.000 <span>/ 60 menit</span></span>
                                </div>

                                <button type="button" class="btn-select-psych" onclick="selectPsychologist(1, 'dr. Citra Larasati, M.Psi., Psikolog', 119000, this)">
                                    <span>Pilih Jadwal &amp; Konsultasi</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                </button>
                            </div>
                        </article>

                        <!-- Psych 2: Arya Daniswara -->
                        <article class="psych-card" id="cardPsych2" data-id="2" data-name="Arya Daniswara, M.Psi., Psikolog Klinis" data-price="129000" data-spec="cemas mahasiswa" data-rating="4.9" data-avail="today">
                            <div class="psych-card-header">
                                <div class="psych-avatar-wrap">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80" alt="Arya Daniswara" class="psych-avatar">
                                    <div class="psych-verified-badge" title="Tersertifikasi STR & HIMPSI">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </div>

                                <div class="psych-main-info">
                                    <div class="psych-availability-row">
                                        <span class="psych-avail-tag" style="background:#fef3c7; color:#b45309;">● Tersedia Hari Ini • 1 Slot Sisa</span>
                                        <span class="psych-rating">★ 4.9 (240+ ulasan)</span>
                                    </div>

                                    <h3 class="psych-name">Arya Daniswara, M.Psi., Psikolog Klinis</h3>
                                    <span class="psych-credentials">SIPP: 2021-0891-KL • Alumni Universitas Gadjah Mada</span>

                                    <div class="psych-tags-row">
                                        <span class="psych-spec-pill">#Kecemasan Akademik</span>
                                        <span class="psych-spec-pill">#Eksistensial</span>
                                        <span class="psych-spec-pill">#Depresi Ringan</span>
                                    </div>
                                </div>
                            </div>

                            <div class="psych-therapy-box">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <div>
                                    <strong>Pendekatan Terapi:</strong> Acceptance &amp; Commitment Therapy (ACT), Humanistic Counseling, Narrative Reframing.
                                </div>
                            </div>

                            <div class="psych-footer-row">
                                <div class="psych-price-info">
                                    <span class="psych-price-label">Sesi Video / Voice / Chat</span>
                                    <span class="psych-price-val">Rp 129.000 <span>/ 60 menit</span></span>
                                </div>

                                <button type="button" class="btn-select-psych" onclick="selectPsychologist(2, 'Arya Daniswara, M.Psi., Psikolog Klinis', 129000, this)">
                                    <span>Pilih Jadwal</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </button>
                            </div>
                        </article>

                        <!-- Psych 3: Fauzan Anshori -->
                        <article class="psych-card" id="cardPsych3" data-id="3" data-name="Fauzan Anshori, M.Psi., Psikolog" data-price="139000" data-spec="trauma tidur" data-rating="4.9" data-avail="nextweek">
                            <div class="psych-card-header">
                                <div class="psych-avatar-wrap">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80" alt="Fauzan Anshori" class="psych-avatar">
                                    <div class="psych-verified-badge" title="Tersertifikasi STR & HIMPSI">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    </div>
                                </div>

                                <div class="psych-main-info">
                                    <div class="psych-availability-row">
                                        <span class="psych-avail-tag" style="background:#f1f5f9; color:var(--slate-600);">● Tersedia Kamis Depan</span>
                                        <span class="psych-rating">★ 4.9 (185+ ulasan)</span>
                                    </div>

                                    <h3 class="psych-name">Fauzan Anshori, M.Psi., Psikolog</h3>
                                    <span class="psych-credentials">SIPP: 2018-0533-KL • Alumni Universitas Padjadjaran</span>

                                    <div class="psych-tags-row">
                                        <span class="psych-spec-pill">#Gangguan Tidur &amp; Insomnia</span>
                                        <span class="psych-spec-pill">#Trauma Masa Lalu</span>
                                        <span class="psych-spec-pill">#Manajemen Marah</span>
                                    </div>
                                </div>
                            </div>

                            <div class="psych-therapy-box">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                <div>
                                    <strong>Pendekatan Terapi:</strong> CBT-I (Insomnia), Trauma-Informed Somatic Practice, Dialectical Emotion Regulation.
                                </div>
                            </div>

                            <div class="psych-footer-row">
                                <div class="psych-price-info">
                                    <span class="psych-price-label">Konseling Mendalam &amp; Asesmen</span>
                                    <span class="psych-price-val">Rp 139.000 <span>/ 60 menit</span></span>
                                </div>

                                <button type="button" class="btn-select-psych" onclick="selectPsychologist(3, 'Fauzan Anshori, M.Psi., Psikolog', 139000, this)">
                                    <span>Pilih Jadwal</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </button>
                            </div>
                        </article>

                    </div>

                    <!-- RIGHT COLUMN: ALUR PEMESANAN SESI (INTERACTIVE BOOKING BOX) -->
                    <div class="booking-sidebar-col">
                        <div class="booking-flow-card">
                            <div class="booking-flow-header">
                                <div>
                                    <span class="booking-flow-title">
                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        Alur Pemesanan Sesi
                                    </span>
                                    <div style="font-size:11.5px; color:var(--slate-500); margin-top:2px;">Psikolog: <strong id="selectedPsychName" style="color:var(--teal-800);">dr. Citra Larasati</strong></div>
                                </div>
                                <span class="booking-badge-privat">Sesi Privat</span>
                            </div>

                            <!-- Step 1: Pilih Tanggal & Waktu -->
                            <div>
                                <div class="booking-step-title">
                                    <span class="step-circle">1</span>
                                    <span>Pilih Tanggal &amp; Waktu</span>
                                    <span style="font-size:11px; font-weight:normal; color:var(--slate-400); margin-left:auto;">Oktober 2026</span>
                                </div>

                                <div class="booking-dates-grid" id="bookingDates">
                                    <button type="button" class="date-select-btn" onclick="selectDate(this, 'Hari Ini, 24 Okt')">
                                        <span class="date-btn-day">Hari Ini</span>
                                        <span class="date-btn-num">24 Okt</span>
                                    </button>
                                    <button type="button" class="date-select-btn active" onclick="selectDate(this, 'Besok, 25 Okt')">
                                        <span class="date-btn-day">Besok</span>
                                        <span class="date-btn-num">25 Okt</span>
                                    </button>
                                    <button type="button" class="date-select-btn" onclick="selectDate(this, 'Sabtu, 26 Okt')">
                                        <span class="date-btn-day">Sabtu</span>
                                        <span class="date-btn-num">26 Okt</span>
                                    </button>
                                    <button type="button" class="date-select-btn" onclick="selectDate(this, 'Minggu, 27 Okt')">
                                        <span class="date-btn-day">Minggu</span>
                                        <span class="date-btn-num">27 Okt</span>
                                    </button>
                                </div>

                                <div style="font-size:11.5px; font-weight:700; color:var(--slate-600); margin-top:10px;">Pilihan Jam Konsultasi (WIB):</div>
                                <div class="time-slots-grid" id="bookingTimes">
                                    <button type="button" class="time-slot-btn" onclick="selectTime(this, '14:00 WIB')">14:00 WIB</button>
                                    <button type="button" class="time-slot-btn active" onclick="selectTime(this, '16:30 WIB')">16:30 WIB</button>
                                    <button type="button" class="time-slot-btn" onclick="selectTime(this, '19:30 WIB')">19:30 WIB</button>
                                </div>
                            </div>

                            <!-- Step 2: Format Konsultasi -->
                            <div>
                                <div class="booking-step-title">
                                    <span class="step-circle">2</span>
                                    <span>Format Konsultasi Terenkripsi</span>
                                </div>

                                <div class="format-cards-grid" id="bookingFormats">
                                    <div class="format-card-btn active" onclick="selectFormat(this, 'Video Call')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                        <span class="format-name">Video Call</span>
                                        <span class="format-sub" style="color:#059669; font-weight:700;">Rekomendasi</span>
                                    </div>
                                    <div class="format-card-btn" onclick="selectFormat(this, 'Voice Call')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                        <span class="format-name">Voice Call</span>
                                        <span class="format-sub">Tanpa Kamera</span>
                                    </div>
                                    <div class="format-card-btn" onclick="selectFormat(this, 'Chat Live')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        <span class="format-name">Chat Live</span>
                                        <span class="format-sub">Catatan Teks</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Metode Pembayaran -->
                            <div>
                                <div class="booking-step-title">
                                    <span class="step-circle">3</span>
                                    <span>Metode Pembayaran</span>
                                </div>

                                <div class="payment-methods-grid" id="bookingPayments">
                                    <div class="pay-option-btn active" onclick="selectPayment(this, 'QRIS Instant')">
                                        <span style="width:8px; height:8px; border-radius:50%; background:#059669; display:inline-block;"></span>
                                        <span>QRIS Instant</span>
                                    </div>
                                    <div class="pay-option-btn" onclick="selectPayment(this, 'GoPay / OVO')">
                                        <span style="width:8px; height:8px; border-radius:50%; background:var(--slate-300); display:inline-block;"></span>
                                        <span>GoPay / OVO</span>
                                    </div>
                                    <div class="pay-option-btn" onclick="selectPayment(this, 'BCA Virtual Acc')">
                                        <span style="width:8px; height:8px; border-radius:50%; background:var(--slate-300); display:inline-block;"></span>
                                        <span>BCA Virtual Acc</span>
                                    </div>
                                    <div class="pay-option-btn" onclick="selectPayment(this, 'Mandiri VA')">
                                        <span style="width:8px; height:8px; border-radius:50%; background:var(--slate-300); display:inline-block;"></span>
                                        <span>Mandiri VA</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Summary Box -->
                            <div class="booking-summary-box">
                                <div class="summary-row">
                                    <span>Biaya Konseling (60 mnt):</span>
                                    <span id="summaryBasePrice">Rp 119.000</span>
                                </div>
                                <div class="summary-row">
                                    <span>Biaya Layanan &amp; Platform:</span>
                                    <span style="color:#059669; font-weight:700;">Gratis</span>
                                </div>
                                <div class="summary-row total">
                                    <span>Total Pembayaran:</span>
                                    <span id="summaryTotalPrice" style="color:var(--teal-900);">Rp 119.000</span>
                                </div>
                            </div>

                            <div class="booking-guarantee-note">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                                <div>
                                    <strong>Garansi RuangRasa:</strong> Privasi 100% terjaga. Bebas reschedule jadwal hingga 3 jam sebelum sesi tanpa biaya tambahan.
                                </div>
                            </div>

                            <button type="button" class="btn-confirm-payment" onclick="submitBooking()">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                <span>Konfirmasi &amp; Lanjut Pembayaran</span>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- BOTTOM ASSISTANCE BANNER -->
                <div class="konsul-bottom-banner">
                    <div class="bottom-banner-text-wrap">
                        <span class="konsul-badge-category" style="margin-bottom:2px;">Panduan Memilih Psikolog yang Tepat</span>
                        <h3 class="bottom-banner-title">Belum yakin psikolog mana yang paling sesuai kebutuhanmu?</h3>
                        <p class="bottom-banner-desc">
                            Gunakan fitur skrining mandiri 2 menit atau ceritakan keluhan awalmu ke asisten RuangRasa untuk mendapatkan rekomendasi klinis yang dipersonalisasi.
                        </p>
                    </div>

                    <div class="bottom-banner-actions">
                        <a href="self-check.php" class="btn-banner-outline">Ikuti Skrining Singkat</a>
                        <a href="chatbot.php" class="btn-banner-solid">Tanya Asisten AI</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let currentPsych = {
            id: 1,
            name: 'dr. Citra Larasati, M.Psi., Psikolog',
            price: 119000,
            date: 'Besok, 25 Okt',
            time: '16:30 WIB',
            format: 'Video Call',
            payment: 'QRIS Instant'
        };

        function selectPsychologist(id, name, price, btn) {
            currentPsych.id = id;
            currentPsych.name = name;
            currentPsych.price = price;

            document.querySelectorAll('.psych-card').forEach(c => c.classList.remove('selected'));
            const card = document.getElementById('cardPsych' + id);
            if (card) card.classList.add('selected');

            document.getElementById('selectedPsychName').innerText = name.split(',')[0];
            const formatted = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('summaryBasePrice').innerText = formatted;
            document.getElementById('summaryTotalPrice').innerText = formatted;
        }

        function selectDate(btn, val) {
            document.querySelectorAll('#bookingDates .date-select-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPsych.date = val;
        }

        function selectTime(btn, val) {
            document.querySelectorAll('#bookingTimes .time-slot-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPsych.time = val;
        }

        function selectFormat(btn, val) {
            document.querySelectorAll('#bookingFormats .format-card-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPsych.format = val;
        }

        function selectPayment(btn, val) {
            document.querySelectorAll('#bookingPayments .pay-option-btn').forEach(b => {
                b.classList.remove('active');
                b.querySelector('span:first-child').style.background = 'var(--slate-300)';
            });
            btn.classList.add('active');
            btn.querySelector('span:first-child').style.background = '#059669';
            currentPsych.payment = val;
        }

        function submitBooking() {
            const msg = `Konfirmasi Pemesanan Sesi:\n• Psikolog: ${currentPsych.name}\n• Jadwal: ${currentPsych.date} • ${currentPsych.time}\n• Format: ${currentPsych.format}\n• Metode: ${currentPsych.payment}\n• Total: Rp ${currentPsych.price.toLocaleString('id-ID')}\n\nLanjutkan ke instruksi pembayaran?`;
            if (confirm(msg)) {
                alert(`Terima kasih! Sesi konseling privat bersama ${currentPsych.name.split(',')[0]} pada ${currentPsych.date} (${currentPsych.time}) berhasil dipesan. Detail link ruang pertemuan privat telah dikirimkan ke email terdaftarmu.`);
            }
        }

        // Filters
        function setSpecFilter(spec, btn) {
            document.querySelectorAll('#specPills .konsul-pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');

            const cards = document.querySelectorAll('#psychListContainer .psych-card');
            cards.forEach(card => {
                const cardSpec = card.getAttribute('data-spec') || '';
                if (spec === 'all' || cardSpec.includes(spec)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterPsychologists() {
            const query = (document.getElementById('psychSearchInput').value || '').toLowerCase().trim();
            const availFilter = document.getElementById('filterAvailability').value;
            const cards = document.querySelectorAll('#psychListContainer .psych-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                const avail = card.getAttribute('data-avail') || '';
                const matchQuery = !query || text.includes(query);
                const matchAvail = (availFilter === 'all') || (avail === availFilter);

                if (matchQuery && matchAvail) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function sortPsychologists(val) {
            const container = document.getElementById('psychListContainer');
            const cards = Array.from(container.children);

            if (val === 'harga_rendah') {
                cards.sort((a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price));
            } else if (val === 'rating') {
                cards.sort((a, b) => parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating));
            }
            cards.forEach(c => container.appendChild(c));
        }
    </script>
</body>
</html>
