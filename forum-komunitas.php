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
    <title>Forum Komunitas Anonim - RuangRasa</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <style>
        /* Specific Styles for Forum Komunitas */
        .forum-container {
            padding: 4px 0 50px 0;
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        /* Top Safety Pill (Polished & non-intrusive) */
        .forum-safety-bar {
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
        .forum-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }
        .forum-header-text {
            max-width: 760px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .forum-badge-category {
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
        }
        .forum-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.5px;
            line-height: 1.25;
        }
        .forum-subtitle {
            font-size: 15px;
            color: var(--slate-600);
            line-height: 1.6;
        }

        /* Support Counter Card */
        .forum-support-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .forum-support-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forum-support-info {
            display: flex;
            flex-direction: column;
        }
        .forum-support-val {
            font-size: 18px;
            font-weight: 800;
            color: var(--teal-900);
            line-height: 1.2;
        }
        .forum-support-label {
            font-size: 12px;
            color: var(--slate-500);
        }

        /* 2-COLUMN LAYOUT: Feed (Left) & Sidebar (Right) */
        .forum-layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 28px;
            align-items: start;
            width: 100%;
        }
        @media (max-width: 1024px) {
            .forum-layout-grid {
                grid-template-columns: 1fr;
            }
        }

        /* LEFT FEED COLUMN - min-width 0 prevents CSS Grid 1fr blowout */
        .forum-feed-col {
            display: flex;
            flex-direction: column;
            gap: 22px;
            min-width: 0;
            width: 100%;
        }


        /* CREATE POST CARD */
        .forum-create-card {
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: border-color 0.2s ease;
        }
        .forum-create-card:focus-within {
            border-color: var(--teal-600);
        }
        .create-user-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .create-user-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .anon-avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
        }
        .create-user-text {
            display: flex;
            flex-direction: column;
        }
        .create-user-alias {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate-800);
        }
        .create-user-alias span {
            color: var(--teal-700);
            background: var(--teal-50);
            padding: 2px 8px;
            border-radius: var(--radius-full);
            border: 1px solid var(--teal-200);
        }
        .create-user-sub {
            font-size: 11.5px;
            color: var(--slate-400);
        }
        .btn-change-alias {
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            padding: 6px 12px;
            border-radius: var(--radius-md);
            font-size: 12px;
            font-weight: 600;
            color: var(--slate-600);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.15s ease;
        }
        .btn-change-alias:hover {
            background: var(--slate-100);
            color: var(--slate-900);
        }
        .create-textarea {
            width: 100%;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            padding: 14px 16px;
            font-size: 14px;
            color: var(--slate-800);
            font-family: inherit;
            resize: vertical;
            min-height: 95px;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .create-textarea:focus {
            border-color: var(--teal-600);
            background: #fafdfc;
        }
        .create-actions-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }
        .create-categories {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }
        .cat-chip {
            padding: 5px 12px;
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 600;
            color: var(--slate-600);
            background: #f1f5f9;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cat-chip:hover {
            background: #e2e8f0;
        }
        .cat-chip.selected {
            background: var(--teal-100);
            color: var(--teal-800);
            border-color: var(--teal-300);
        }
        .btn-send-post {
            background: var(--teal-800);
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-send-post:hover {
            background: var(--teal-900);
            transform: translateY(-1px);
        }

        /* FEED TABS FILTER */
        .forum-tabs-row {
            display: flex;
            gap: 8px;
            border-bottom: 2px solid var(--slate-200);
            padding-bottom: 4px;
            flex-wrap: wrap;
        }
        .forum-tab-link {
            padding: 8px 16px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--slate-500);
            text-decoration: none;
            border-bottom: 2.5px solid transparent;
            margin-bottom: -6px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
        }
        .forum-tab-link:hover {
            color: var(--slate-900);
        }
        .forum-tab-link.active {
            color: var(--teal-800);
            border-bottom-color: var(--teal-800);
            font-weight: 700;
        }

        /* FEED POSTS */
        .forum-posts-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            min-width: 0;
            width: 100%;
        }
        .post-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.2s ease;
            min-width: 0;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .post-card:hover {
            border-color: var(--slate-300);
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .post-author-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .post-author-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--slate-700);
        }
        .post-author-meta {
            display: flex;
            flex-direction: column;
        }
        .post-author-name {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate-800);
        }
        .post-time-meta {
            font-size: 12px;
            color: var(--slate-400);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .post-tags {
            display: flex;
            gap: 6px;
        }
        .post-tag-pill {
            font-size: 11px;
            font-weight: 600;
            color: var(--teal-800);
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 2px 8px;
            border-radius: var(--radius-full);
        }
        .post-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.35;
        }
        .post-content {
            font-size: 14.5px;
            color: var(--slate-700);
            line-height: 1.65;
        }

        /* Post Reaction Buttons (Polished) */
        .post-reactions-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            padding-top: 8px;
            border-top: 1px solid var(--slate-100);
        }
        .btn-reaction {
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            padding: 7px 14px;
            border-radius: var(--radius-full);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--slate-600);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }
        .btn-reaction:hover {
            background: #f1f5f9;
            color: var(--slate-900);
        }
        .btn-reaction.active {
            background: #e6fffa;
            border-color: var(--teal-400);
            color: var(--teal-900);
        }
        .post-comments-count {
            margin-left: auto;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--slate-500);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .post-comments-count:hover {
            color: var(--teal-700);
        }

        /* NESTED EMPATHY COMMENTS */
        .nested-comments-wrap {
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 6px;
        }
        .nested-comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--slate-500);
        }
        .nested-comment-item {
            background: #ffffff;
            border: 1px solid #edf2f7;
            border-radius: var(--radius-lg);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .nested-author {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--slate-800);
        }
        .badge-facilitator {
            background: #ccfbf1;
            color: var(--teal-800);
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .nested-body {
            font-size: 13.5px;
            color: var(--slate-700);
            line-height: 1.55;
        }

        /* RIGHT SIDEBAR */
        .forum-sidebar-col {
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: relative;
            width: 360px;
            min-width: 320px;
        }
        .forum-side-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }
        .side-card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Pedoman Ruang Sehat */
        .guideline-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            list-style: none;
        }
        .guideline-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12.5px;
            color: var(--slate-600);
            line-height: 1.45;
        }
        .guideline-item svg {
            color: #059669;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Crisis Box (Revised: Supportive & Clear, not overwhelming) */
        .side-crisis-card {
            background: linear-gradient(180deg, #fff1f2 0%, #ffffff 100%);
            border: 1.5px solid #fecdd3;
        }
        .side-crisis-title {
            font-size: 15px;
            font-weight: 800;
            color: #9f1239;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .side-crisis-p {
            font-size: 12.5px;
            color: #be123c;
            line-height: 1.5;
        }
        .btn-hotline-forum {
            background: #e11d48;
            color: #ffffff;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .btn-hotline-forum:hover {
            background: #be123c;
        }
        .btn-hotline-secondary {
            background: #ffffff;
            border: 1px solid #fecdd3;
            color: #9f1239;
            padding: 8px 12px;
            border-radius: var(--radius-md);
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-hotline-secondary:hover {
            background: #fff1f2;
        }

        /* Scheduled Audio Circle Card */
        .audio-circle-img {
            width: 100%;
            height: 120px;
            border-radius: var(--radius-lg);
            object-fit: cover;
        }
        .audio-circle-info {
            font-size: 12px;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .btn-join-circle {
            background: #f0fdfa;
            border: 1.5px solid #ccfbf1;
            color: var(--teal-800);
            padding: 8px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            transition: all 0.15s ease;
        }
        .btn-join-circle:hover {
            background: #ccfbf1;
        }

        /* Keyword cloud */
        .keyword-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .keyword-pill {
            padding: 4px 10px;
            background: #f1f5f9;
            border-radius: var(--radius-full);
            font-size: 11.5px;
            font-weight: 600;
            color: var(--slate-600);
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .keyword-pill:hover {
            background: var(--teal-100);
            color: var(--teal-900);
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
            <input type="text" class="app-search-input" placeholder="Cari topik diskusi, cerita kawan...">
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
                        <a href="forum-komunitas.php" class="active">
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

        <!-- MAIN SCROLLABLE FORUM CONTENT -->
        <main class="dashboard-content-scroll">
            <div class="forum-container">

                <!-- TOP SAFETY BAR (Polished & non-intrusive) -->
                <div class="forum-safety-bar">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>Ruang Aman &amp; Terjaga: Dimoderasi fasilitator terlatih • Bebas penghakiman • Anonimitas 100% terlindungi</span>
                    </div>
                    <span style="font-size:12px; color:var(--teal-700);">Zona Bebas Cyber-Bullying</span>
                </div>

                <!-- HEADER ROW -->
                <div class="forum-header-row">
                    <div class="forum-header-text">
                        <span class="forum-badge-category">Komunitas Pulih Bersama</span>
                        <h1 class="forum-title">Forum Komunitas Anonim</h1>
                        <p class="forum-subtitle">
                            Berbagi beban, temukan kawan seperjalanan tanpa rasa takut dihakimi. Identitasmu 100% terjaga anonim dengan identitas samaran acak yang diperbarui berkala.
                        </p>
                    </div>

                    <div class="forum-support-card">
                        <div class="forum-support-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div class="forum-support-info">
                            <span class="forum-support-val" id="supportCount">1,420+</span>
                            <span class="forum-support-label">Dukungan tersalurkan hari ini</span>
                        </div>
                    </div>
                </div>

                <!-- 2-COLUMN LAYOUT -->
                <div class="forum-layout-grid">
                    
                    <!-- LEFT COLUMN: WRITE POST & FEED -->
                    <div class="forum-feed-col">
                        
                        <!-- WRITE POST CARD -->
                        <div class="forum-create-card">
                            <div class="create-user-header">
                                <div class="create-user-left">
                                    <div class="anon-avatar-circle" id="userAvatarChar">B</div>
                                    <div class="create-user-text">
                                        <div class="create-user-alias">Posting sebagai: <span id="currentAlias">#BintangTeduh_42</span></div>
                                        <span class="create-user-sub">Identitas asli terlindung sepenuhnya</span>
                                    </div>
                                </div>
                                <button type="button" class="btn-change-alias" onclick="regenerateAlias()">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"></path></svg>
                                    <span>Ganti Samaran</span>
                                </button>
                            </div>

                            <textarea id="postInputText" class="create-textarea" placeholder="Bagikan apa yang kamu rasakan hari ini secara anonim... Ceritakan tanpa khawatir penilaian."></textarea>

                            <div class="create-actions-row">
                                <div class="create-categories" id="categoryChips">
                                    <button type="button" class="cat-chip selected" onclick="selectCategory(this, 'Kehidupan Kampus')">Kehidupan Kampus</button>
                                    <button type="button" class="cat-chip" onclick="selectCategory(this, 'Karir & Dunia Kerja')">Karir &amp; Dunia Kerja</button>
                                    <button type="button" class="cat-chip" onclick="selectCategory(this, 'Relasi & Keluarga')">Relasi &amp; Keluarga</button>
                                    <button type="button" class="cat-chip" onclick="selectCategory(this, 'Perjalanan Pemulihan')">Perjalanan Pemulihan</button>
                                </div>

                                <button type="button" class="btn-send-post" onclick="publishPost()">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                    <span>Kirim Cerita</span>
                                </button>
                            </div>
                        </div>

                        <!-- FEED TABS -->
                        <div class="forum-tabs-row">
                            <span class="forum-tab-link active" onclick="setFeedTab('terbaru', this)">Diskusi Terbaru</span>
                            <span class="forum-tab-link" onclick="setFeedTab('dukungan', this)">Paling Banyak Didukung</span>
                            <span class="forum-tab-link" onclick="setFeedTab('hangat', this)">Topik Hangat</span>
                            <span class="forum-tab-link" onclick="setFeedTab('bulan', this)">Bulan Ini</span>
                        </div>

                        <!-- FEED POSTS CONTAINER -->
                        <div class="forum-posts-list" id="postsContainer">
                            
                            <!-- Post 1 (Featured Story) -->
                            <article class="post-card">
                                <div class="post-header">
                                    <div class="post-author-row">
                                        <div class="post-author-avatar" style="background:#e0f2fe; color:#0369a1;">KS</div>
                                        <div class="post-author-meta">
                                            <span class="post-author-name">#KembaraSunyi</span>
                                            <span class="post-time-meta">
                                                <span>2 jam lalu</span>
                                                <span>•</span>
                                                <span>Zona Kampus</span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-tags">
                                        <span class="post-tag-pill">#KehidupanKampus</span>
                                        <span class="post-tag-pill">#SemesterAkhir</span>
                                    </div>
                                </div>

                                <div>
                                    <h2 class="post-title">Rasanya mau menyerah dengan skripsi bab 4, merasa tertinggal jauh dari teman seangkatan...</h2>
                                    <p class="post-content" style="margin-top:8px;">
                                        Sudah 3 minggu nggak bisa fokus. Tiap buka laptop tangan gemetar dan overthinking. Tiap lihat status medsos teman yang sudah semhas atau yudisium, dada rasanya sesak dan rasanya seperti orang paling gagal sedunia. Ada yang pernah ngalamin fase seperti ini dan berhasil melewatinya?
                                    </p>
                                </div>

                                <div class="post-reactions-row">
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'dengar')">
                                        <span>👂 Aku Mendengarkan</span>
                                        <span class="count">48</span>
                                    </button>
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'bersama')">
                                        <span>🫂 Kamu Tidak Sendiri</span>
                                        <span class="count">62</span>
                                    </button>
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'peluk')">
                                        <span>🤍 Kirim Pelukan Hangat</span>
                                        <span class="count">35</span>
                                    </button>
                                    <span class="post-comments-count" onclick="toggleComments('commentsPost1')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        <span>9 Tanggapan Mendalam</span>
                                    </span>
                                </div>

                                <!-- Nested Comments -->
                                <div class="nested-comments-wrap" id="commentsPost1">
                                    <div class="nested-comment-header">
                                        <span>Tanggapan Penuh Empati Teratas</span>
                                        <span style="color:var(--teal-700); font-weight:600;">Zona Bebas Penghakiman</span>
                                    </div>

                                    <!-- Comment 1 -->
                                    <div class="nested-comment-item">
                                        <div class="nested-author">
                                            <span style="color:#0369a1;">#CahayaSenja_88</span>
                                            <span style="font-size:11px; color:var(--slate-400); font-weight:normal;">• 1 jam lalu</span>
                                        </div>
                                        <p class="nested-body">
                                            Tarik nafas pelan-pelan ya. Aku alumni yang lulus 6 tahun karena skripsi mandek setahun penuh di bab 4. Rasanya valid banget kamu takut &amp; cemas, tapi ritme hidupmu adalah milikmu sendiri. Coba targetkan 1 paragraf saja per hari. Jangan lihat halaman utuhnya dulu. Kamu pasti bisa lewati ini.
                                        </p>
                                        <div style="display:flex; gap:12px; font-size:11.5px; color:var(--teal-800); font-weight:600;">
                                            <span style="cursor:pointer;" onclick="this.innerText='Menenangkan (25)'">💚 Menenangkan (24)</span>
                                            <span style="cursor:pointer;" onclick="replyPrompt('#CahayaSenja_88')">Balas Tanggapan</span>
                                        </div>
                                    </div>

                                    <!-- Comment 2: Verified Facilitator -->
                                    <div class="nested-comment-item" style="border-left:3px solid var(--teal-600); background:#f0fdfa;">
                                        <div class="nested-author">
                                            <span style="color:var(--teal-950);">Fasilitator Rian</span>
                                            <span class="badge-facilitator">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                                Psikolog Pendamping
                                            </span>
                                            <span style="font-size:11px; color:var(--slate-400); font-weight:normal;">• 45 menit lalu</span>
                                        </div>
                                        <p class="nested-body">
                                            Halo KembaraSunyi, sensasi gemetar dan sesak adalah sinyal tubuhmu sedang berada di mode <em>fight-or-flight</em> karena tekanan kognitif berlebih. Luangkan 2 hari penuh untuk 'detoks' laptop dan tutup sosial media sejenak. Jika butuh ruang bicara langsung yang aman, ruang konseling RuangRasa selalu terbuka untukmu.
                                        </p>
                                    </div>
                                </div>
                            </article>

                            <!-- Post 2 -->
                            <article class="post-card">
                                <div class="post-header">
                                    <div class="post-author-row">
                                        <div class="post-author-avatar" style="background:#fef3c7; color:#b45309;">DM</div>
                                        <div class="post-author-meta">
                                            <span class="post-author-name">#DaunMusimGugur</span>
                                            <span class="post-time-meta">5 jam lalu • Dunia Kerja</span>
                                        </div>
                                    </div>
                                    <div class="post-tags">
                                        <span class="post-tag-pill">#Karir &amp; Kerja</span>
                                        <span class="post-tag-pill">#KemenanganKecil</span>
                                    </div>
                                </div>

                                <div>
                                    <h2 class="post-title">First jobber di agensi yang ritmenya gila-gilaan. Hari ini berhasil bilang "tidak" pada lembur mendadak tanpa rasa bersalah!</h2>
                                    <p class="post-content" style="margin-top:8px;">
                                        Selama 6 bulan pertama, aku selalu iya-iya aja disuruh revisi jam 9 malam. Akibatnya maag kambuh dan insomnia parah. Tadi sore untuk pertama kalinya aku berani nolak dengan sopan dan bilang akan dikerjakan besok pagi sesuai jam kerja. Rasanya deg-degan banget, tapi ternyata duniaku nggak runtuh! Bangga sama diri sendiri hari ini.
                                    </p>
                                </div>

                                <div class="post-reactions-row">
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'bangga')">
                                        <span>👏 Bangga Padamu!</span>
                                        <span class="count">69</span>
                                    </button>
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'inspirasi')">
                                        <span>✨ Sangat Menginspirasi</span>
                                        <span class="count">42</span>
                                    </button>
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'peluk')">
                                        <span>🤍 Kirim Pelukan</span>
                                        <span class="count">19</span>
                                    </button>
                                    <span class="post-comments-count">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        <span>14 Komentar</span>
                                    </span>
                                </div>
                            </article>

                            <!-- Post 3 -->
                            <article class="post-card">
                                <div class="post-header">
                                    <div class="post-author-row">
                                        <div class="post-author-avatar" style="background:#f3e8ff; color:#7e22ce;">EP</div>
                                        <div class="post-author-meta">
                                            <span class="post-author-name">#EmbunPagi_19</span>
                                            <span class="post-time-meta">8 jam lalu • Pemulihan</span>
                                        </div>
                                    </div>
                                    <div class="post-tags">
                                        <span class="post-tag-pill">#PerjalananPemulihan</span>
                                        <span class="post-tag-pill">#RefleksiDiri</span>
                                    </div>
                                </div>

                                <div>
                                    <h2 class="post-title">Sudah 30 hari berturut-turut tanpa serangan panik berat. Proses pemulihan ternyata tidak harus berbentuk lompatan besar.</h2>
                                    <p class="post-content" style="margin-top:8px;">
                                        Tiga bulan lalu, keluar rumah beli bahan makanan pun rasanya seperti mau pingsan. Dengan latihan pernapasan kotak 4-4-4 dari audio meditasi RuangRasa dan pendampingan bertahap, hari ini aku bisa jalan sore di taman selama 45 menit. Untuk teman-teman yang sedang di titik terendah, percayalah bahwa setiap nafas adalah kemajuan kecil yang berharga.
                                    </p>
                                </div>

                                <div class="post-reactions-row">
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'syukur')">
                                        <span>🌱 Ikut Bersyukur</span>
                                        <span class="count">112</span>
                                    </button>
                                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'harapan')">
                                        <span>🕊️ Membawa Harapan</span>
                                        <span class="count">76</span>
                                    </button>
                                    <span class="post-comments-count">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        <span>28 Komentar</span>
                                    </span>
                                </div>
                            </article>

                        </div>

                        <!-- Load More Button -->
                        <button type="button" class="btn-calendar" style="width:100%; justify-content:center; padding:12px; margin-top:8px;" onclick="loadMoreStories(this)">
                            <span>Tampilkan Lebih Banyak Cerita</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>

                    </div>

                    <!-- RIGHT COLUMN: GUIDELINES & HOTLINE -->
                    <div class="forum-sidebar-col">
                        
                        <!-- Box 1: Pedoman Ruang Sehat -->
                        <div class="forum-side-card">
                            <h3 class="side-card-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                <span>Pedoman Ruang Sehat</span>
                            </h3>
                            <span style="font-size:12px; color:var(--slate-400); margin-top:-8px;">Prinsip dasar interaksi di RuangRasa:</span>

                            <ul class="guideline-list">
                                <li class="guideline-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div><strong>Validasi Tanpa Menghakimi:</strong> Hargai sudut pandang dan emosi tiap kawan tanpa meremehkan masalahnya.</div>
                                </li>
                                <li class="guideline-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div><strong>Jaga Kerahasiaan Bersama:</strong> Dilarang mengambil tangkapan layar (screenshot) untuk disebar ke media sosial luar.</div>
                                </li>
                                <li class="guideline-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <div><strong>Gunakan Tag Pemicu (TW):</strong> Sertakan peringatan jika postingan membahas topik trauma sensitif.</div>
                                </li>
                            </ul>

                            <a href="#pedoman" onclick="alert('Dokumen Etika Komunitas lengkap menjamin perlindungan privasi dan interaksi bebas perundungan.'); return false;" style="font-size:12.5px; font-weight:700; color:var(--teal-700); text-decoration:none;">
                                Baca Dokumen Etika Lengkap →
                            </a>
                        </div>

                        <!-- Box 2: Crisis Card (Supportive & Empathic) -->
                        <div class="forum-side-card side-crisis-card">
                            <h3 class="side-crisis-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <span>Butuh Bantuan Mendesak?</span>
                            </h3>
                            <p class="side-crisis-p">
                                Kamu tidak harus menanggung beban seorang diri. Layanan bantuan krisis profesional dan bebas biaya tersedia 24 jam untukmu.
                            </p>

                            <a href="tel:119" class="btn-hotline-forum">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span>Hotline Sejiwa: 119 ext 8</span>
                            </a>

                            <a href="emergency.php" class="btn-hotline-secondary">
                                <span>LISA Suicide Prevention (Bebas Pulsa)</span>
                            </a>
                        </div>

                        <!-- Box 3: Scheduled Audio Circle -->
                        <div class="forum-side-card">
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span class="event-badge-category" style="margin:0;">Sesi Terjadwal</span>
                                <span style="font-size:11.5px; color:#16a34a; font-weight:700;">● Terbuka</span>
                            </div>

                            <img src="https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=400&q=80" alt="Lingkar Tenang" class="audio-circle-img">

                            <div>
                                <h4 style="font-size:15px; font-weight:800; color:var(--slate-900);">Lingkar Tenang: Berdamai dengan Ekspektasi Diri</h4>
                                <p style="font-size:12.5px; color:var(--slate-600); margin-top:4px; line-height:1.5;">
                                    Ruang dengar audio anonim bersama fasilitator berlisensi. Dengarkan tanpa kewajiban harus berbicara.
                                </p>
                            </div>

                            <div class="audio-circle-info">
                                <span>📅 Kamis, 19:30 WIB • 45 Menit</span>
                                <span style="font-weight:700; color:var(--teal-800);">+54 terdaftar</span>
                            </div>

                            <button type="button" class="btn-join-circle" onclick="alert('Kamu berhasil mendaftar di Lingkar Tenang Kamis ini. Link audio room terenkripsi akan muncul di notifikasi.')">
                                Ikut Bergabung
                            </button>
                        </div>

                        <!-- Box 4: Keyword exploration -->
                        <div class="forum-side-card">
                            <h3 class="side-card-title" style="font-size:13px; text-transform:uppercase; letter-spacing:0.5px; color:var(--slate-500);">
                                Eksplorasi Kata Kunci Populer
                            </h3>
                            <div class="keyword-pills">
                                <a href="javascript:void(0)" class="keyword-pill" onclick="filterByKeyword('skripsi')">#BurnoutAkademik</a>
                                <a href="javascript:void(0)" class="keyword-pill" onclick="filterByKeyword('teman')">#ImpostorSyndrome</a>
                                <a href="javascript:void(0)" class="keyword-pill" onclick="filterByKeyword('kerja')">#ToxicWorkplace</a>
                                <a href="javascript:void(0)" class="keyword-pill" onclick="filterByKeyword('overthinking')">#InnerChild</a>
                                <a href="javascript:void(0)" class="keyword-pill" onclick="filterByKeyword('panik')">#AnxietyRelief</a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const aliasList = [
            '#BintangTeduh_42',
            '#LembayungSenja_77',
            '#EmbunSejuk_88',
            '#GemintangDamai_54',
            '#RembulanTenang_31',
            '#PohonRindang_19',
            '#HeningFajar_06',
            '#AnginSepoi_92'
        ];
        let currentAliasIndex = 0;

        function regenerateAlias() {
            currentAliasIndex = (currentAliasIndex + 1) % aliasList.length;
            const newAlias = aliasList[currentAliasIndex];
            document.getElementById('currentAlias').innerText = newAlias;
            document.getElementById('userAvatarChar').innerText = newAlias.charAt(1);
        }

        let selectedCategory = 'Kehidupan Kampus';
        function selectCategory(btn, category) {
            document.querySelectorAll('#categoryChips .cat-chip').forEach(el => el.classList.remove('selected'));
            btn.classList.add('selected');
            selectedCategory = category;
        }

        function publishPost() {
            const text = document.getElementById('postInputText').value.trim();
            if (!text) {
                alert('Tuliskan sepatah kata atau ceritamu terlebih dahulu.');
                return;
            }

            const currentAlias = document.getElementById('currentAlias').innerText;
            const avatarChar = currentAlias.charAt(1);

            const newCard = document.createElement('article');
            newCard.className = 'post-card';
            newCard.style.animation = 'fadeIn 0.3s ease-out';
            newCard.innerHTML = `
                <div class="post-header">
                    <div class="post-author-row">
                        <div class="post-author-avatar" style="background:#ccfbf1; color:#0f766e;">${avatarChar}</div>
                        <div class="post-author-meta">
                            <span class="post-author-name">${currentAlias}</span>
                            <span class="post-time-meta">Baru saja • ${selectedCategory}</span>
                        </div>
                    </div>
                    <div class="post-tags">
                        <span class="post-tag-pill">#${selectedCategory.replace(/\s+/g, '')}</span>
                    </div>
                </div>
                <div>
                    <p class="post-content" style="margin-top:4px;">${escapeHtml(text)}</p>
                </div>
                <div class="post-reactions-row">
                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'dengar')">
                        <span>👂 Aku Mendengarkan</span>
                        <span class="count">1</span>
                    </button>
                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'bersama')">
                        <span>🫂 Kamu Tidak Sendiri</span>
                        <span class="count">0</span>
                    </button>
                    <button type="button" class="btn-reaction" onclick="reactToPost(this, 'peluk')">
                        <span>🤍 Kirim Pelukan Hangat</span>
                        <span class="count">0</span>
                    </button>
                    <span class="post-comments-count">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span>0 Tanggapan</span>
                    </span>
                </div>
            `;

            const container = document.getElementById('postsContainer');
            container.prepend(newCard);

            document.getElementById('postInputText').value = '';
            alert('Ceritamu berhasil dibagikan secara anonim. Terima kasih telah berani membuka ruang hati.');
            
            // Increment global counter
            const countEl = document.getElementById('supportCount');
            countEl.innerText = '1,421+';
        }

        function reactToPost(btn, type) {
            const countEl = btn.querySelector('.count');
            let count = parseInt(countEl.innerText, 10);
            if (btn.classList.contains('active')) {
                btn.classList.remove('active');
                countEl.innerText = Math.max(0, count - 1);
            } else {
                btn.classList.add('active');
                countEl.innerText = count + 1;
            }
        }

        function toggleComments(id) {
            const wrap = document.getElementById(id);
            if (wrap) {
                wrap.style.display = wrap.style.display === 'none' ? 'flex' : 'none';
            }
        }

        function replyPrompt(user) {
            const reply = prompt('Tuliskan balasan empatik untuk ' + user + ':');
            if (reply) {
                alert('Tanggapanmu telah terkirim kepada ' + user + '. Terima kasih telah hadir mendengarkan.');
            }
        }

        function setFeedTab(tab, el) {
            document.querySelectorAll('.forum-tab-link').forEach(link => link.classList.remove('active'));
            el.classList.add('active');
        }

        function loadMoreStories(btn) {
            btn.innerHTML = '<span>Memuat cerita lainnya...</span>';
            setTimeout(() => {
                btn.innerHTML = '<span>Semua cerita terbaru telah ditampilkan</span>';
                btn.disabled = true;
            }, 600);
        }

        function filterByKeyword(kw) {
            const posts = document.querySelectorAll('#postsContainer .post-card');
            posts.forEach(post => {
                if (post.innerText.toLowerCase().includes(kw)) {
                    post.style.display = 'flex';
                } else {
                    post.style.display = 'none';
                }
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
