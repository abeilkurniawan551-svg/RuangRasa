<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['fullname'])) {
    $_SESSION['fullname'] = 'Sobat RuangRasa';
    $_SESSION['role'] = 'Member Aktif RuangRasa';
}
$fullname = $_SESSION['fullname'];
$firstDisplayName = explode(' ', $fullname)[0];
$fullDisplayName = htmlspecialchars($fullname);
$roleName = $_SESSION['role'] ?? 'Member Aktif RuangRasa';
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;
$userEmail = $_SESSION['email'] ?? '';

// Auto create event_registrations table if not exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS event_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        user_name VARCHAR(100) NULL,
        user_email VARCHAR(100) NULL,
        event_title VARCHAR(255) NOT NULL,
        event_price VARCHAR(50) DEFAULT 'Gratis',
        event_date VARCHAR(100) NULL,
        notes TEXT NULL,
        ticket_code VARCHAR(50) NULL,
        zoom_link VARCHAR(255) DEFAULT 'https://zoom.us/j/89241109281',
        passcode VARCHAR(50) DEFAULT 'PULIH2026',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {}

// Handle AJAX Realtime Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register_event') {
    header('Content-Type: application/json; charset=UTF-8');
    $eventTitle = trim($_POST['event_title'] ?? '');
    $eventPrice = trim($_POST['event_price'] ?? 'Gratis');
    $eventDate = trim($_POST['event_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (!empty($eventTitle)) {
        try {
            // Check if already registered
            $check = $pdo->prepare("SELECT id, ticket_code FROM event_registrations WHERE (user_id = ? OR (user_email != '' AND user_email = ?)) AND event_title = ?");
            $check->execute([$userId, $userEmail, $eventTitle]);
            $existing = $check->fetch();
            if ($existing) {
                echo json_encode([
                    'status' => 'already_registered',
                    'message' => 'Kamu sudah terdaftar pada sesi ini!',
                    'ticket_code' => $existing['ticket_code'] ?? ('RR-EV-' . date('Ymd') . '-001'),
                    'event_title' => $eventTitle
                ]);
                exit;
            }

            $ticketCode = 'RR-EV-' . date('Ymd') . '-' . rand(100, 999);
            $ins = $pdo->prepare("INSERT INTO event_registrations (user_id, user_name, user_email, event_title, event_price, event_date, notes, ticket_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $ins->execute([$userId, $fullname, $userEmail, $eventTitle, $eventPrice, $eventDate, $notes, $ticketCode]);

            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM event_registrations WHERE user_id = ? OR (user_email != '' AND user_email = ?)");
            $countStmt->execute([$userId, $userEmail]);
            $total = (int)$countStmt->fetchColumn();

            echo json_encode([
                'status' => 'success',
                'message' => 'Pendaftaran berhasil dikonfirmasi secara realtime!',
                'ticket_code' => $ticketCode,
                'total_tickets' => $total,
                'event_title' => $eventTitle
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
            exit;
        }
    }
    echo json_encode(['status' => 'error', 'message' => 'Data event tidak boleh kosong.']);
    exit;
}

// Fetch user's registered events
$userRegisteredEvents = [];
if ($userId || !empty($userEmail)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM event_registrations WHERE user_id = ? OR (user_email != '' AND user_email = ?) ORDER BY id DESC");
        $stmt->execute([$userId, $userEmail]);
        $userRegisteredEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {}
}

// Auto-seed default Hero event ticket if brand new user
if (($userId || !empty($fullname)) && empty($userRegisteredEvents)) {
    try {
        $defaultTicketCode = 'RR-EV-' . date('Ymd') . '-001';
        $pdo->prepare("INSERT INTO event_registrations (user_id, user_name, user_email, event_title, event_price, event_date, ticket_code) VALUES (?, ?, ?, ?, ?, ?, ?)")
            ->execute([$userId, $fullname, $userEmail, 'Navigasi Quarter-Life Crisis', 'Gratis', 'Sabtu • 19:00 WIB', $defaultTicketCode]);
        $userRegisteredEvents = [
            [
                'event_title' => 'Navigasi Quarter-Life Crisis',
                'event_price' => 'Gratis',
                'event_date' => 'Sabtu • 19:00 WIB',
                'ticket_code' => $defaultTicketCode,
                'zoom_link' => 'https://zoom.us/j/89241109281',
                'passcode' => 'PULIH2026'
            ]
        ];
    } catch (Exception $e) {}
}

$registeredTitles = array_map(function($ev) {
    return $ev['event_title'];
}, $userRegisteredEvents);

$ticketCount = count($userRegisteredEvents);

// Indonesian Day and Month Name Formatter
function indonesianDayMonth($timestamp, $includeYear = false) {
    $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
    $months = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
    $dayName = $days[date('l', $timestamp)] ?? date('l', $timestamp);
    $dayNum = date('j', $timestamp);
    $monthName = $months[(int)date('n', $timestamp)] ?? date('M', $timestamp);
    $year = date('Y', $timestamp);
    return $includeYear ? "$dayName, $dayNum $monthName $year" : "$dayName, $dayNum $monthName";
}

// Realtime upcoming dates
$heroTimestamp = (date('l') === 'Saturday' && time() < strtotime('today 19:00:00')) ? strtotime('today 19:00:00') : strtotime('next Saturday 19:00:00');
$heroMonthShort = strtoupper(date('M', $heroTimestamp));
$heroDayNum = date('d', $heroTimestamp);
$heroDateText = indonesianDayMonth($heroTimestamp, true) . ' • 19:00 - 20:30 WIB';
$heroIso = date('Y-m-d\TH:i:s', $heroTimestamp);

$card1Time = strtotime('next Sunday 09:00:00');
$card1DateText = indonesianDayMonth($card1Time) . ' • 09:00 WIB';

$card2Time = strtotime('next Tuesday 19:30:00');
$card2DateText = indonesianDayMonth($card2Time) . ' • 19:30 WIB';

$card3Time = strtotime('next Thursday 20:00:00');
$card3DateText = indonesianDayMonth($card3Time) . ' • 20:00 WIB';

$card4Time = strtotime('next Friday 21:00:00');
$card4DateText = indonesianDayMonth($card4Time) . ' • 21:00 WIB';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event &amp; Ruang Pulih Bersama - RuangRasa</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link rel="stylesheet" href="style.css?v=<?= time() ?>">
    <style>
        /* Specific Styles for Event Wellness */
        .event-container {
            padding: 4px 0 50px 0;
            max-width: 1240px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 26px;
        }

        /* Top Trust Banner */
        .event-trust-bar {
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
        .event-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }
        .event-header-text {
            max-width: 760px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .event-badge-category {
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
        .event-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.5px;
            line-height: 1.25;
        }
        .event-subtitle {
            font-size: 15px;
            color: var(--slate-600);
            line-height: 1.6;
        }

        /* Ticket Counter Pill */
        .event-ticket-summary {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .ticket-summary-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            background: #ccfbf1;
            color: var(--teal-800);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ticket-summary-info {
            display: flex;
            flex-direction: column;
        }
        .ticket-summary-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--slate-400);
            letter-spacing: 0.5px;
        }
        .ticket-summary-val {
            font-size: 16px;
            font-weight: 800;
            color: var(--teal-900);
        }

        /* UPCOMING REGISTERED SESSION TICKER (Revised: sleek, concise, not redundant) */
        .upcoming-ticker-bar {
            background: #ffffff;
            border: 1px solid #ccfbf1;
            border-left: 5px solid var(--teal-600);
            border-radius: var(--radius-xl);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.04);
        }
        .upcoming-date-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: var(--radius-md);
            padding: 6px 14px;
            min-width: 64px;
            text-align: center;
        }
        .upcoming-date-month {
            font-size: 11px;
            font-weight: 800;
            color: var(--teal-700);
            text-transform: uppercase;
        }
        .upcoming-date-day {
            font-size: 20px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1;
        }
        .upcoming-details {
            flex: 1;
            min-width: 260px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .upcoming-badge-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
        }
        .upcoming-status-badge {
            background: #ecfdf5;
            color: #065f46;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: var(--radius-full);
        }
        .upcoming-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--slate-900);
        }
        .upcoming-meta {
            font-size: 12.5px;
            color: var(--slate-500);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .upcoming-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-calendar {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            padding: 8px 14px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-700);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-calendar:hover {
            background: var(--slate-50);
            border-color: var(--slate-300);
            color: var(--slate-900);
        }
        .btn-join-zoom {
            background: var(--teal-800);
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-join-zoom:hover {
            background: var(--teal-900);
        }

        /* HERO FEATURED EVENT CARD */
        .event-hero-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            display: grid;
            grid-template-columns: 440px 1fr;
            overflow: hidden;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
            transition: box-shadow 0.25s ease;
        }
        .event-hero-card:hover {
            box-shadow: 0 10px 30px -4px rgba(15, 23, 42, 0.08);
        }
        .event-hero-img-wrap {
            position: relative;
            background: #f1f5f9;
            min-height: 360px;
            overflow: hidden;
        }
        .event-hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .event-hero-card:hover .event-hero-img {
            transform: scale(1.03);
        }
        .event-hero-floating-tag {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .event-hero-floating-bookmark {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(6px);
            border: none;
            color: var(--slate-700);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .event-hero-floating-bookmark:hover {
            background: #ffffff;
            color: var(--teal-700);
        }
        .event-hero-content {
            padding: 34px 38px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 18px;
        }
        .event-hero-tags {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .event-tag-highlight {
            background: #f0fdf4;
            color: var(--teal-800);
            border: 1px solid #bbf7d0;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: var(--radius-full);
        }
        .event-tag-skp {
            background: #f3e8ff;
            color: #6b21a8;
            border: 1px solid #e9d5ff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: var(--radius-full);
        }
        .event-hero-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.35;
            letter-spacing: -0.3px;
        }
        .event-hero-desc {
            font-size: 14.5px;
            color: var(--slate-600);
            line-height: 1.65;
        }
        .event-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #edf2f7;
            padding: 14px 16px;
            border-radius: var(--radius-lg);
        }
        .event-meta-col {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .event-meta-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-400);
            text-transform: uppercase;
        }
        .event-meta-value {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--slate-800);
        }
        .event-capacity-bar-wrap {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .event-capacity-text {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: 600;
            color: var(--slate-600);
        }
        .event-capacity-track {
            height: 7px;
            background: #e2e8f0;
            border-radius: var(--radius-full);
            overflow: hidden;
        }
        .event-capacity-fill {
            height: 100%;
            background: linear-gradient(90deg, #14b8a6, #0d9488);
            border-radius: var(--radius-full);
            width: 83%;
        }
        .event-speakers-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .event-speakers-avatars {
            display: flex;
            align-items: center;
        }
        .event-speaker-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #ffffff;
            object-fit: cover;
            margin-right: -8px;
        }
        .event-speakers-info {
            font-size: 12.5px;
            color: var(--slate-600);
        }
        .event-speakers-info strong {
            color: var(--slate-900);
        }
        .event-hero-action-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 10px;
            border-top: 1px solid var(--slate-100);
            flex-wrap: wrap;
        }
        .btn-register-hero {
            background: var(--teal-800);
            color: #ffffff;
            border: none;
            padding: 11px 22px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-register-hero:hover {
            background: var(--teal-900);
            transform: translateY(-1px);
        }
        .btn-share-hero {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            color: var(--slate-700);
            padding: 11px 18px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-share-hero:hover {
            background: var(--slate-50);
            color: var(--slate-900);
        }

        /* FILTER TABS & SEARCH */
        .event-filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .event-tabs {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: var(--radius-lg);
        }
        .event-tab-btn {
            background: none;
            border: none;
            padding: 8px 18px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 600;
            color: var(--slate-600);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .event-tab-btn.active {
            background: var(--teal-900);
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }
        .event-search-box {
            position: relative;
            min-width: 280px;
        }
        .event-search-box svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
        }
        .event-search-input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--radius-lg);
            font-size: 13.5px;
            color: var(--slate-800);
        }
        .event-search-input:focus {
            outline: none;
            border-color: var(--teal-600);
        }

        /* EVENT CARDS GRID */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 1200px) {
            .events-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .event-hero-card {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 640px) {
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
        .ev-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
        }
        .ev-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            border-color: var(--slate-300);
        }
        .ev-card-img-wrap {
            position: relative;
            height: 160px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .ev-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .ev-card:hover .ev-card-img {
            transform: scale(1.04);
        }
        .ev-card-badges-top {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ev-type-pill {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: var(--radius-full);
        }
        .ev-price-pill {
            background: var(--teal-700);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: var(--radius-full);
        }
        .ev-price-free {
            background: #16a34a;
        }
        .ev-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
            justify-content: space-between;
            gap: 12px;
        }
        .ev-card-time {
            font-size: 12px;
            font-weight: 600;
            color: var(--teal-800);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .ev-card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--slate-900);
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ev-card-desc {
            font-size: 12.5px;
            color: var(--slate-600);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ev-card-instructor {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            background: #f8fafc;
            border-radius: var(--radius-md);
            font-size: 12px;
            color: var(--slate-700);
        }
        .ev-inst-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
        }
        .ev-card-btn {
            background: #ffffff;
            border: 1.5px solid var(--slate-200);
            padding: 9px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            color: var(--slate-800);
            cursor: pointer;
            text-align: center;
            transition: all 0.15s ease;
            width: 100%;
        }
        .ev-card-btn:hover {
            background: var(--teal-800);
            color: #ffffff;
            border-color: var(--teal-800);
        }
        .ev-card-btn.registered, .btn-register-hero.registered {
            background: #047857 !important;
            color: #ffffff !important;
            border-color: #047857 !important;
            box-shadow: 0 2px 6px rgba(4, 120, 87, 0.25);
            cursor: pointer;
        }
        .ev-card-btn.registered:hover, .btn-register-hero.registered:hover {
            background: #065f46 !important;
            border-color: #065f46 !important;
        }

        /* SECTION FAQ & GUARANTEE */
        .event-info-section {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 28px;
            margin-top: 10px;
            align-items: start;
        }
        @media (max-width: 960px) {
            .event-info-section {
                grid-template-columns: 1fr;
            }
        }
        .event-guarantee-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .guarantee-icon-box {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            background: #ecfdf5;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .guarantee-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.3;
        }
        .guarantee-p {
            font-size: 13.5px;
            color: var(--slate-600);
            line-height: 1.6;
        }
        .guarantee-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            list-style: none;
        }
        .guarantee-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: var(--slate-700);
            line-height: 1.4;
        }
        .guarantee-item svg {
            color: #059669;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .btn-academic-contact {
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            color: var(--teal-800);
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 12.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-academic-contact:hover {
            background: #ccfbf1;
        }

        /* FAQ Accordion Side */
        .event-faq-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-2xl);
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .faq-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--slate-900);
        }
        .accordion-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .accordion-item {
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: border-color 0.2s ease;
        }
        .accordion-item.active {
            border-color: var(--teal-600);
        }
        .accordion-btn {
            width: 100%;
            padding: 14px 18px;
            background: #ffffff;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: left;
            font-size: 14px;
            font-weight: 700;
            color: var(--slate-800);
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .accordion-btn:hover {
            background: #f8fafc;
        }
        .accordion-btn svg {
            transition: transform 0.25s ease;
            color: var(--slate-400);
        }
        .accordion-item.active .accordion-btn svg {
            transform: rotate(180deg);
            color: var(--teal-700);
        }
        .accordion-body {
            display: none;
            padding: 0 18px 16px;
            font-size: 13.5px;
            color: var(--slate-600);
            line-height: 1.6;
        }
        .accordion-item.active .accordion-body {
            display: block;
        }

        /* BOTTOM SOOTHING BANNER */
        .event-bottom-banner {
            background: #f8fafc;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .bottom-banner-text {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .bottom-banner-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teal-700);
            flex-shrink: 0;
        }
        .bottom-banner-p {
            font-size: 13.5px;
            color: var(--slate-700);
            max-width: 680px;
            line-height: 1.5;
        }
        .bottom-banner-p strong {
            color: var(--slate-900);
            display: block;
            margin-bottom: 2px;
        }
        .btn-recommendation {
            background: #ffffff;
            border: 1.5px solid var(--slate-300);
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            color: var(--slate-800);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-recommendation:hover {
            border-color: var(--teal-700);
            color: var(--teal-800);
            background: #f0fdfa;
        }

        /* REGISTRATION MODAL */
        .event-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .event-modal-backdrop.active {
            display: flex;
        }
        .event-modal-window {
            background: #ffffff;
            width: 100%;
            max-width: 520px;
            border-radius: var(--radius-2xl);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalFadeIn 0.25s ease-out;
        }
        .event-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--slate-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .event-modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
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
            <input type="text" class="app-search-input" placeholder="Cari meditasi, nama psikolog, event...">
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
                        <a href="event-wellness.php" class="active">
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

        <!-- MAIN SCROLLABLE EVENT CONTENT -->
        <main class="dashboard-content-scroll">
            <div class="event-container">

                <!-- TOP TRUST BAR -->
                <div class="event-trust-bar">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        <span>Ruang Kolaborasi Terbuka • Terakreditasi HIMPSI &amp; Kemenkes RI</span>
                    </div>
                    <span style="font-size:12px; color:var(--teal-700);">Sesi Interaktif Terenkripsi End-to-End</span>
                </div>

                <!-- HEADER ROW -->
                <div class="event-header-row">
                    <div class="event-header-text">
                        <span class="event-badge-category">Ruang Pulih &amp; Kolaborasi</span>
                        <h1 class="event-title">Event &amp; Ruang Pulih Bersama</h1>
                        <p class="event-subtitle">
                            Ikuti webinar interaktif, kelas yoga restoratif, dan workshop mindfulness bersama para praktisi dan psikolog profesional untuk merawat kewarasan batinmu.
                        </p>
                    </div>

                    <!-- Ticket Status Card -->
                    <div class="event-ticket-summary">
                        <div class="ticket-summary-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>
                        </div>
                        <div class="ticket-summary-info">
                            <span class="ticket-summary-label">Tiket Terdaftar</span>
                            <span class="ticket-summary-val" id="myTicketCount"><?= $ticketCount ?> Tiket Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- UPCOMING REGISTERED SESSION TICKER (Revised for clear UX & zero redundancy) -->
                <div class="upcoming-ticker-bar" id="upcomingTicker">
                    <div class="upcoming-date-box">
                        <span class="upcoming-date-month"><?= $heroMonthShort ?></span>
                        <span class="upcoming-date-day"><?= $heroDayNum ?></span>
                    </div>
                    <div class="upcoming-details">
                        <div class="upcoming-badge-row">
                            <span class="upcoming-status-badge">Tiket Kamu Siap</span>
                            <span style="color:var(--slate-500);" id="tickerCountdown">• Realtime Countdown...</span>
                        </div>
                        <div class="upcoming-title">Navigasi Quarter-Life Crisis: Menemukan Arah di Tengah Tekanan Ekspektasi</div>
                        <div class="upcoming-meta">
                            <span>ID Ruang: <strong>892-4110-9281</strong></span>
                            <span>Passcode: <strong>PULIH2026</strong></span>
                        </div>
                    </div>
                    <div class="upcoming-actions">
                        <button type="button" class="btn-calendar" onclick="addToCalendar()">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <span>Tambah ke Kalender</span>
                        </button>
                        <button type="button" class="btn-join-zoom" onclick="openETicketModal('Navigasi Quarter-Life Crisis', '<?= $heroDateText ?>')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                            <span>Lihat E-Tiket Zoom</span>
                        </button>
                    </div>
                </div>

                <!-- HERO FEATURED EVENT CARD (Streamlined badges & calm layout) -->
                <article class="event-hero-card">
                    <div class="event-hero-img-wrap">
                        <img src="https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=800&q=80" alt="Quarter-Life Crisis" class="event-hero-img" onerror="this.src='image/logo.png'">
                        <span class="event-hero-floating-tag">
                            <span style="width:8px; height:8px; background:#10b981; border-radius:50%; display:inline-block;"></span>
                            Siaran Interaktif Terjadwal
                        </span>
                        <button type="button" class="event-hero-floating-bookmark" title="Simpan Jadwal" onclick="toggleHeroBookmark(this)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                        </button>
                    </div>

                    <div class="event-hero-content">
                        <div>
                            <div class="event-hero-tags">
                                <span class="event-tag-highlight">✨ Event Utama Pekan Ini</span>
                                <span class="event-tag-skp">Gratis • E-Sertifikat 2 SKP</span>
                            </div>

                            <h2 class="event-hero-title" style="margin-top:10px;">
                                Navigasi Quarter-Life Crisis: Menemukan Arah di Tengah Tekanan Ekspektasi
                            </h2>

                            <p class="event-hero-desc" style="margin-top:8px;">
                                Merasa tertinggal dari pencapaian teman sebaya atau cemas memilih jalan karier? Dalam sesi 90 menit ini, pelajari teknik kognitif restrukturisasi dan journaling pelepasan beban untuk memvalidasi fase ketidakpastian hidupmu.
                            </p>
                        </div>

                        <div class="event-meta-grid">
                            <div class="event-meta-col">
                                <span class="event-meta-label">Jadwal &amp; Waktu</span>
                                <span class="event-meta-value"><?= $heroDateText ?></span>
                            </div>
                            <div class="event-meta-col">
                                <span class="event-meta-label">Platform Ruang Pulih</span>
                                <span class="event-meta-value">Zoom Terenkripsi (Kamera Opsional)</span>
                            </div>
                        </div>

                        <!-- Capacity progress bar -->
                        <div class="event-capacity-bar-wrap">
                            <div class="event-capacity-text">
                                <span>Kapasitas Kursi Terisi</span>
                                <span style="color:var(--teal-800); font-weight:700;">Tersisa <span id="heroRemainingSeats">42</span> kursi dari 250 kuota</span>
                            </div>
                            <div class="event-capacity-track">
                                <div class="event-capacity-fill" id="heroCapacityFill"></div>
                            </div>
                        </div>

                        <div class="event-speakers-row">
                            <div class="event-speakers-avatars">
                                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80" alt="dr. Citra" class="event-speaker-avatar">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80" alt="Rian" class="event-speaker-avatar">
                            </div>
                            <div class="event-speakers-info">
                                <strong>dr. Citra Larasati &amp; Rian Adiansyah</strong><br>
                                <span>Psikolog Klinis Dewasa &amp; Mindfulness Coach</span>
                            </div>
                        </div>

                        <div class="event-hero-action-row">
                            <button type="button" class="btn-register-hero <?= in_array('Navigasi Quarter-Life Crisis', $registeredTitles) ? 'registered' : '' ?>" id="btn-hero-event" onclick="handleEventButtonClick('Navigasi Quarter-Life Crisis', 'Gratis', '<?= $heroDateText ?>')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span><?= in_array('Navigasi Quarter-Life Crisis', $registeredTitles) ? '✓ Terdaftar (Buka Tiket)' : 'Daftar Sekarang (Gratis)' ?></span>
                            </button>
                            <button type="button" class="btn-share-hero" onclick="shareEvent()">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                                <span>Bagikan ke Rekan</span>
                            </button>
                        </div>
                    </div>
                </article>

                <!-- FILTER TABS & SEARCH -->
                <div class="event-filter-bar">
                    <div class="event-tabs">
                        <button class="event-tab-btn active" onclick="setEventTab('all', this)">Semua Event</button>
                        <button class="event-tab-btn" onclick="setEventTab('webinar', this)">Webinar Psikologi</button>
                        <button class="event-tab-btn" onclick="setEventTab('mindfulness', this)">Latihan Mindfulness Terpandu</button>
                    </div>

                    <div class="event-search-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="eventSearchInput" class="event-search-input" placeholder="Cari topik atau instruktur..." oninput="searchEvents()">
                    </div>
                </div>

                <!-- 4 EVENT CARDS GRID -->
                <div class="events-grid" id="eventsGrid">
                    
                    <!-- Card 1 -->
                    <article class="ev-card" data-category="mindfulness">
                        <div class="ev-card-img-wrap">
                            <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=600&q=80" alt="Somatic Healing" class="ev-card-img">
                            <div class="ev-card-badges-top">
                                <span class="ev-type-pill">Workshop Interaktif</span>
                                <span class="ev-price-pill">Rp 35.000</span>
                            </div>
                        </div>
                        <div class="ev-card-body">
                            <div>
                                <span class="ev-card-time">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= $card1DateText ?>
                                </span>
                                <h3 class="ev-card-title" style="margin-top:6px;">Somatic Healing &amp; Box Breathing: Melepas Ketegangan Otot</h3>
                                <p class="ev-card-desc" style="margin-top:4px;">
                                    Pelepasan trauma fisik mikro pada bahu, leher, dan rahang melalui gerakan lembut terpandu.
                                </p>
                            </div>

                            <div class="ev-card-instructor">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=100&q=80" alt="Maya" class="ev-inst-avatar">
                                <div>
                                    <div style="font-weight:700;">Maya Anggraini, S.Psi</div>
                                    <div style="font-size:11px; color:var(--slate-500);">Yoga Somatik &amp; Pernapasan</div>
                                </div>
                            </div>

                            <button type="button" class="ev-card-btn <?= in_array('Somatic Healing & Box Breathing', $registeredTitles) ? 'registered' : '' ?>" id="btn-card-somatic" onclick="handleEventButtonClick('Somatic Healing & Box Breathing', 'Rp 35.000', '<?= $card1DateText ?>')">
                                <span><?= in_array('Somatic Healing & Box Breathing', $registeredTitles) ? '✓ Terdaftar (Buka Tiket)' : 'Ambil Tiket' ?></span>
                            </button>
                        </div>
                    </article>

                    <!-- Card 2 -->
                    <article class="ev-card" data-category="webinar">
                        <div class="ev-card-img-wrap">
                            <img src="https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=600&q=80" alt="Menjinakkan Suara Kritis" class="ev-card-img" onerror="this.src='image/logo.png'">
                            <div class="ev-card-badges-top">
                                <span class="ev-type-pill">Webinar Psikologi</span>
                                <span class="ev-price-pill ev-price-free">Gratis</span>
                            </div>
                        </div>
                        <div class="ev-card-body">
                            <div>
                                <span class="ev-card-time">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= $card2DateText ?>
                                </span>
                                <h3 class="ev-card-title" style="margin-top:6px;">Menjinakkan Suara Kritis: Terapi Menulis untuk Self-Compassion</h3>
                                <p class="ev-card-desc" style="margin-top:4px;">
                                    Ubah cara berbicara pada dirimu sendiri dari kritik tajam menjadi penerimaan batin yang menopang.
                                </p>
                            </div>

                            <div class="ev-card-instructor">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&q=80" alt="Dimas" class="ev-inst-avatar">
                                <div>
                                    <div style="font-weight:700;">Dimas Wicaksono, M.Psi</div>
                                    <div style="font-size:11px; color:var(--slate-500);">Psikolog Terapi Naratif</div>
                                </div>
                            </div>

                            <button type="button" class="ev-card-btn <?= in_array('Menjinakkan Suara Kritis', $registeredTitles) ? 'registered' : '' ?>" id="btn-card-kritis" onclick="handleEventButtonClick('Menjinakkan Suara Kritis', 'Gratis', '<?= $card2DateText ?>')">
                                <span><?= in_array('Menjinakkan Suara Kritis', $registeredTitles) ? '✓ Terdaftar (Buka Tiket)' : 'Daftar Gratis' ?></span>
                            </button>
                        </div>
                    </article>

                    <!-- Card 3 -->
                    <article class="ev-card" data-category="webinar">
                        <div class="ev-card-img-wrap">
                            <img src="https://images.unsplash.com/photo-1573497620053-ea5300f94f21?auto=format&fit=crop&w=600&q=80" alt="Ruang Dengar Bersama" class="ev-card-img" onerror="this.src='image/logo.png'">
                            <div class="ev-card-badges-top">
                                <span class="ev-type-pill">Support Group</span>
                                <span class="ev-price-pill" style="background:#e11d48;">Sisa 3 Kursi</span>
                            </div>
                        </div>
                        <div class="ev-card-body">
                            <div>
                                <span class="ev-card-time">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= $card3DateText ?>
                                </span>
                                <h3 class="ev-card-title" style="margin-top:6px;">Ruang Dengar Bersama: Mengurai Dukacita dan Kehilangan</h3>
                                <p class="ev-card-desc" style="margin-top:4px;">
                                    Sesi kelompok tertutup untuk saling mendengarkan tanpa interupsi dan tanpa penilaian moral.
                                </p>
                            </div>

                            <div class="ev-card-instructor">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" alt="Sarah" class="ev-inst-avatar">
                                <div>
                                    <div style="font-weight:700;">Sarah Novita</div>
                                    <div style="font-size:11px; color:var(--slate-500);">Fasilitator Ruang Pulih</div>
                                </div>
                            </div>

                            <button type="button" class="ev-card-btn <?= in_array('Ruang Dengar Bersama', $registeredTitles) ? 'registered' : '' ?>" id="btn-card-dengar" onclick="handleEventButtonClick('Ruang Dengar Bersama', 'Terbatas', '<?= $card3DateText ?>')">
                                <span><?= in_array('Ruang Dengar Bersama', $registeredTitles) ? '✓ Terdaftar (Buka Tiket)' : 'Ajukan Gabung' ?></span>
                            </button>
                        </div>
                    </article>

                    <!-- Card 4 -->
                    <article class="ev-card" data-category="mindfulness">
                        <div class="ev-card-img-wrap">
                            <img src="image/sleep.jpg" alt="Mindful Sleep Ritual" class="ev-card-img" onerror="this.src='https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?auto=format&fit=crop&w=600&q=80'">
                            <div class="ev-card-badges-top">
                                <span class="ev-type-pill">Audio Session Live</span>
                                <span class="ev-price-pill ev-price-free">Gratis</span>
                            </div>
                        </div>
                        <div class="ev-card-body">
                            <div>
                                <span class="ev-card-time">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    <?= $card4DateText ?>
                                </span>
                                <h3 class="ev-card-title" style="margin-top:6px;">Mindful Sleep Ritual: Relaksasi Otot Progresif &amp; Narasi Suara Tidur</h3>
                                <p class="ev-card-desc" style="margin-top:4px;">
                                    Pemindaian tubuh (body scan) dan narasi suara frekuensi delta untuk menenangkan insomnia kronis.
                                </p>
                            </div>

                            <div class="ev-card-instructor">
                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&q=80" alt="Bayu Wicaksono" class="ev-inst-avatar">
                                <div>
                                    <div style="font-weight:700;">Bayu Wicaksono</div>
                                    <div style="font-size:11px; color:var(--slate-500);">Live Audio Sound Therapist</div>
                                </div>
                            </div>

                            <button type="button" class="ev-card-btn <?= in_array('Mindful Sleep Ritual', $registeredTitles) ? 'registered' : '' ?>" id="btn-card-sleep" onclick="handleEventButtonClick('Mindful Sleep Ritual', 'Gratis', '<?= $card4DateText ?>')">
                                <span><?= in_array('Mindful Sleep Ritual', $registeredTitles) ? '✓ Terdaftar (Buka Tiket)' : 'Pasang Pengingat' ?></span>
                            </button>
                        </div>
                    </article>
                    </article>

                </div>

                <!-- SECTION FAQ & QUALITY GUARANTEE -->
                <div class="event-info-section">
                    
                    <!-- Left: Guarantee Card -->
                    <div class="event-guarantee-card">
                        <div class="guarantee-icon-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div>
                            <span class="event-badge-category" style="margin-bottom:6px;">Jaminan Kualitas RuangRasa</span>
                            <h3 class="guarantee-title">Sertifikat Kehadiran &amp; Rangkuman Materi</h3>
                        </div>
                        <p class="guarantee-p">
                            Setiap peserta sesi webinar dan workshop terakreditasi berhak menerima e-sertifikat resmi bersertifikasi SKP serta lembar kerja (worksheet) refleksi yang dapat diunduh langsung dari dashboard.
                        </p>

                        <ul class="guarantee-list">
                            <li class="guarantee-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Sertifikat digital terverifikasi kode QR unik.</span>
                            </li>
                            <li class="guarantee-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Akses rekaman siaran ulang selama 30 hari.</span>
                            </li>
                            <li class="guarantee-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>Buku panduan latihan mandiri (PDF Printable).</span>
                            </li>
                        </ul>

                        <a href="mailto:akademik@RuangRasa.id" class="btn-academic-contact">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <span>Pertanyaan Seputar Sertifikat? Hubungi Layanan Akademik</span>
                        </a>
                    </div>

                    <!-- Right: FAQ Accordions -->
                    <div class="event-faq-card">
                        <div class="faq-header">
                            <div>
                                <span class="event-badge-category">Bantuan &amp; Panduan</span>
                                <h3 class="faq-title" style="margin-top:6px;">Pertanyaan yang Sering Diajukan (FAQ)</h3>
                            </div>
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--slate-400);"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        </div>

                        <div class="accordion-list">
                            
                            <!-- Accordion 1 -->
                            <div class="accordion-item active">
                                <button type="button" class="accordion-btn" onclick="toggleAccordion(this)">
                                    <span>Apakah saya wajib menyalakan kamera selama sesi berlangsung?</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div class="accordion-body">
                                    Tidak wajib. Seluruh sesi webinar dan ruang dengar RuangRasa memprioritaskan kenyamanan mentalmu. Kamu bebas mematikan kamera dan mikrofon, serta berinteraksi via kolom chat interaktif atau mode pendengar anonim.
                                </div>
                            </div>

                            <!-- Accordion 2 -->
                            <div class="accordion-item">
                                <button type="button" class="accordion-btn" onclick="toggleAccordion(this)">
                                    <span>Bagaimana jika saya berhalangan hadir tepat waktu?</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div class="accordion-body">
                                    Jangan khawatir. Setiap peserta yang telah mendaftar akan menerima link rekaman siaran ulang beresolusi tinggi maksimal 1x24 jam setelah sesi berakhir, yang dapat diakses selama 30 hari penuh di dashboard profilmu.
                                </div>
                            </div>

                            <!-- Accordion 3 -->
                            <div class="accordion-item">
                                <button type="button" class="accordion-btn" onclick="toggleAccordion(this)">
                                    <span>Apakah biaya pendaftaran dapat dialihkan atau direfund?</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div class="accordion-body">
                                    Untuk sesi bersubsidi / berbayar, pengalihan tiket ke sesi lain dapat diajukan maksimal H-1 sebelum acara melalui konfirmasi ke layanan akademik RuangRasa.
                                </div>
                            </div>

                            <!-- Accordion 4 -->
                            <div class="accordion-item">
                                <button type="button" class="accordion-btn" onclick="toggleAccordion(this)">
                                    <span>Apakah event RuangRasa dapat menggantikan sesi terapi individual psikolog?</span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </button>
                                <div class="accordion-body">
                                    Event psikoedukasi dan workshop mindfulness bersifat promotif dan preventif untuk pengayaan wawasan serta latihan mandiri. Jika kamu mengalami distres klinis mendalam, kami menyarankan untuk menjadwalkan sesi konsultasi terapi 1-on-1 bersama psikolog berlisensi kami.
                                </div>
                            </div>

                        </div>

                        <div style="font-size:13px; color:var(--slate-500); padding-top:12px; border-top:1px solid var(--slate-100); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                            <span>Butuh bantuan khusus pendaftaran grup atau korporat?</span>
                            <a href="mailto:partnership@RuangRasa.id" style="color:var(--teal-700); font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                Hubungi Tim Partnership
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- SOOTHING BOTTOM BANNER -->
                <div class="event-bottom-banner">
                    <div class="bottom-banner-text">
                        <div class="bottom-banner-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        </div>
                        <div class="bottom-banner-p">
                            <strong>Pelan-pelan saja, tidak perlu buru-buru sembuh.</strong>
                            Pilih ruang dan topik yang paling selaras dengan kebutuhan jiwamu hari ini. RuangRasa ada mendampingi setiap langkahmu.
                        </div>
                    </div>
                    <a href="self-check.php" class="btn-recommendation">Cek Rekomendasi Pribadiku</a>
                </div>

            </div>
        </main>
    </div>

    <!-- REGISTRATION MODAL POPUP -->
    <div class="event-modal-backdrop" id="eventModal" onclick="closeEventModal(event)">
        <div class="event-modal-window" onclick="event.stopPropagation()">
            <div class="event-modal-header">
                <div>
                    <span class="event-badge-category" style="margin:0;">Pendaftaran Sesi Realtime</span>
                    <h3 id="modalEventTitle" style="font-size:17px; font-weight:800; color:var(--slate-900); margin-top:4px;">Nama Event</h3>
                </div>
                <button type="button" class="header-icon-btn" onclick="closeEventModalDirect()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="event-modal-body">
                <div style="background:#f0fdfa; border:1px solid #ccfbf1; padding:14px; border-radius:var(--radius-lg); font-size:13px; color:var(--teal-900);">
                    <div style="font-weight:700;">Konfirmasi Pendaftaran atas Nama:</div>
                    <div style="font-size:14px; font-weight:800; color:#0f766e; margin-top:2px;"><?= $fullDisplayName ?></div>
                    <div style="font-size:12px; color:var(--slate-500);"><?= $roleName ?></div>
                    <div style="margin-top:6px; font-size:12px; color:var(--teal-700);">Link Zoom &amp; E-Tiket otomatis terbit langsung di akunmu saat ini juga.</div>
                </div>

                <div style="display:flex; flex-direction:column; gap:8px;">
                    <label style="font-size:13px; font-weight:700; color:var(--slate-700);">Catatan Tambahan / Harapan dari Sesi Ini (Opsional):</label>
                    <textarea id="modalNote" rows="3" placeholder="Misal: Ingin belajar cara mengelola cemas saat deadline tugas akhir..." style="width:100%; padding:10px 12px; border:1px solid var(--slate-200); border-radius:var(--radius-md); font-size:13px; font-family:inherit;"></textarea>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:10px;">
                    <span style="font-size:14px; font-weight:700; color:var(--slate-700);">Biaya: <span id="modalEventPrice" style="color:var(--teal-800); font-weight:800;">Gratis</span></span>
                    <button type="button" class="btn-register-hero" id="btnConfirmRegisterModal" onclick="confirmRegistration()">
                        Konfirmasi &amp; Terbitkan Tiket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- E-TICKET VIEW MODAL -->
    <div class="event-modal-backdrop" id="ticketModal" onclick="closeTicketModalDirect(event)">
        <div class="event-modal-window" onclick="event.stopPropagation()" style="max-width:440px; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 20px 40px rgba(0,0,0,0.15); border:1px solid #e2e8f0; padding:0;">
            <div style="background:linear-gradient(135deg, #0f766e, #0d9488); padding:20px; color:#ffffff; position:relative;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span style="font-size:11px; font-weight:800; letter-spacing:1px; text-transform:uppercase; background:rgba(255,255,255,0.2); padding:3px 10px; border-radius:20px;">✓ E-Tiket Resmi Aktif</span>
                    <button type="button" onclick="closeTicketModalDirect()" style="background:transparent; border:none; color:#ffffff; cursor:pointer; padding:4px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <h3 id="ticketEventName" style="margin:12px 0 4px 0; font-size:17px; font-weight:800; color:#ffffff; line-height:1.35;">Navigasi Quarter-Life Crisis</h3>
                <div id="ticketEventTime" style="font-size:13px; opacity:0.95;">Sabtu • 19:00 WIB</div>
            </div>

            <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
                <div style="display:flex; justify-content:space-between; border-bottom:1px dashed #cbd5e1; padding-bottom:14px;">
                    <div>
                        <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase;">Peserta Terverifikasi</div>
                        <div style="font-size:15px; font-weight:800; color:#0f172a; margin-top:2px;"><?= $fullDisplayName ?></div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase;">Kode Tiket</div>
                        <div id="ticketCodeDisplay" style="font-size:13px; font-weight:800; color:#0f766e; margin-top:2px; font-family:monospace;">RR-EV-2026-LIVE</div>
                    </div>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:14px;">
                    <div style="font-size:12px; font-weight:700; color:#334155; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                        Akses Siaran Live Zoom (Terenkripsi)
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:#475569; margin-bottom:4px;">
                        <span>Meeting ID:</span>
                        <strong style="color:#0f172a;">892 4110 9281</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:#475569;">
                        <span>Passcode:</span>
                        <strong style="color:#0f172a;">PULIH2026</strong>
                    </div>
                </div>

                <div style="display:flex; gap:10px;">
                    <a href="https://zoom.us" target="_blank" class="btn-register-hero" style="flex:1; text-align:center; justify-content:center; text-decoration:none; display:inline-flex;">
                        Masuk Ruang Zoom
                    </a>
                    <button type="button" class="btn-share-hero" onclick="alert('Tiket digital sudah tersimpan. Silakan simpan jadwal ke kalendermu.');" style="padding:10px 14px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC (REALTIME PERSISTENCE & LIVE COUNTDOWN) -->
    <script>
        // Store registered events in memory & sync with localStorage
        let localRegisteredEvents = <?= json_encode($registeredTitles) ?> || [];
        try {
            const savedLocal = JSON.parse(localStorage.getItem('RuangRasa_registered_events') || '[]');
            savedLocal.forEach(title => {
                if (!localRegisteredEvents.includes(title)) {
                    localRegisteredEvents.push(title);
                }
            });
        } catch(e) {}

        // Tab switching
        function setEventTab(category, btn) {
            document.querySelectorAll('.event-tab-btn').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');

            const cards = document.querySelectorAll('#eventsGrid .ev-card');
            cards.forEach(card => {
                const cardCat = card.getAttribute('data-category') || '';
                if (category === 'all' || cardCat === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Search events
        function searchEvents() {
            const query = (document.getElementById('eventSearchInput').value || '').toLowerCase().trim();
            const cards = document.querySelectorAll('#eventsGrid .ev-card');
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (!query || text.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Accordion toggle
        function toggleAccordion(btn) {
            const item = btn.parentElement;
            const wasActive = item.classList.contains('active');
            document.querySelectorAll('.accordion-item').forEach(el => el.classList.remove('active'));
            if (!wasActive) item.classList.add('active');
        }

        // Hero bookmark
        function toggleHeroBookmark(btn) {
            const svg = btn.querySelector('svg');
            if (btn.classList.contains('saved')) {
                btn.classList.remove('saved');
                svg.setAttribute('fill', 'none');
                btn.style.color = 'var(--slate-700)';
            } else {
                btn.classList.add('saved');
                svg.setAttribute('fill', 'currentColor');
                btn.style.color = 'var(--teal-700)';
            }
        }

        // Share event
        function shareEvent() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(window.location.href);
                alert('Tautan Event Wellness RuangRasa berhasil disalin ke clipboard!');
            } else {
                alert('Bagikan tautan ini: ' + window.location.href);
            }
        }

        // Add to calendar (.ics)
        function addToCalendar() {
            const icsData = "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nSUMMARY:Navigasi Quarter-Life Crisis - RuangRasa\nDESCRIPTION:Zoom Live Room ID: 892-4110-9281, Passcode: PULIH2026\nDTSTART:<?= date('Ymd\THis\Z', $heroTimestamp) ?>\nDTEND:<?= date('Ymd\THis\Z', $heroTimestamp + 5400) ?>\nEND:VEVENT\nEND:VCALENDAR";
            const blob = new Blob([icsData], { type: 'text/calendar;charset=utf-8' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.setAttribute('download', 'RuangRasa-Event-Live.ics');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            alert('Pengingat kalender (.ics) berhasil diunduh. Silakan buka file untuk menyimpannya ke Google Calendar / Apple Calendar.');
        }

        // Modal registration
        let activeRegEvent = { title: '', price: '', date: '' };

        function handleEventButtonClick(title, price, date) {
            if (localRegisteredEvents.includes(title)) {
                openETicketModal(title, date);
            } else {
                openRegistrationModal(title, price, date);
            }
        }

        function openRegistrationModal(title, price, date) {
            activeRegEvent = { title: title, price: price, date: date || '' };
            document.getElementById('modalEventTitle').innerText = title;
            document.getElementById('modalEventPrice').innerText = price;
            document.getElementById('eventModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModalDirect() {
            document.getElementById('eventModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeEventModal(e) {
            if (e.target.id === 'eventModal') closeEventModalDirect();
        }

        function openETicketModal(title, date) {
            document.getElementById('ticketEventName').innerText = title;
            document.getElementById('ticketEventTime').innerText = date || 'Sesi Live Terjadwal';
            const code = 'RR-EV-' + Math.abs(title.split('').reduce((a,b)=>{a=((a<<5)-a)+b.charCodeAt(0);return a&a},0)).toString().substring(0,6);
            document.getElementById('ticketCodeDisplay').innerText = code;
            document.getElementById('ticketModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeTicketModalDirect(e) {
            if (!e || e.target.id === 'ticketModal' || e.currentTarget) {
                document.getElementById('ticketModal').classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }

        // Realtime Confirmation & Server Sync
        async function confirmRegistration() {
            const title = activeRegEvent.title;
            const price = activeRegEvent.price;
            const date = activeRegEvent.date;
            const note = document.getElementById('modalNote') ? document.getElementById('modalNote').value : '';
            const btnConfirm = document.getElementById('btnConfirmRegisterModal');

            if (btnConfirm) {
                btnConfirm.disabled = true;
                btnConfirm.innerText = 'Mendaftarkan...';
            }

            try {
                const formData = new FormData();
                formData.append('action', 'register_event');
                formData.append('event_title', title);
                formData.append('event_price', price);
                formData.append('event_date', date);
                formData.append('notes', note);

                const response = await fetch('event-wellness.php', {
                    method: 'POST',
                    body: formData
                });
                const resData = await response.json();

                // Save to local cache
                if (!localRegisteredEvents.includes(title)) {
                    localRegisteredEvents.push(title);
                    localStorage.setItem('RuangRasa_registered_events', JSON.stringify(localRegisteredEvents));
                }

                // Update Realtime Ticket Counter Pill
                const totalTickets = (resData && resData.total_tickets) ? resData.total_tickets : localRegisteredEvents.length;
                const counterEl = document.getElementById('myTicketCount');
                if (counterEl) counterEl.innerText = `${totalTickets} Tiket Aktif`;

                // Update Seat Capacity Realtime
                const remainingEl = document.getElementById('heroRemainingSeats');
                if (remainingEl && title === 'Navigasi Quarter-Life Crisis') {
                    const currentSeats = parseInt(remainingEl.innerText) || 42;
                    if (currentSeats > 1) remainingEl.innerText = (currentSeats - 1).toString();
                }

                // Update Buttons in Realtime
                updateRegisteredButtonUI(title, date);

                closeEventModalDirect();
                openETicketModal(title, date);
            } catch (err) {
                // Offline / Local fallback
                if (!localRegisteredEvents.includes(title)) {
                    localRegisteredEvents.push(title);
                    localStorage.setItem('RuangRasa_registered_events', JSON.stringify(localRegisteredEvents));
                }
                const counterEl = document.getElementById('myTicketCount');
                if (counterEl) counterEl.innerText = `${localRegisteredEvents.length} Tiket Aktif`;
                updateRegisteredButtonUI(title, date);
                closeEventModalDirect();
                openETicketModal(title, date);
            } finally {
                if (btnConfirm) {
                    btnConfirm.disabled = false;
                    btnConfirm.innerText = 'Konfirmasi & Terbitkan Tiket';
                }
            }
        }

        function updateRegisteredButtonUI(title, date) {
            // Find all buttons matching title
            const buttons = document.querySelectorAll('.ev-card-btn, .btn-register-hero');
            buttons.forEach(btn => {
                const clickAttr = btn.getAttribute('onclick') || '';
                if (clickAttr.includes(title)) {
                    btn.classList.add('registered');
                    const span = btn.querySelector('span');
                    if (span) span.innerText = '✓ Terdaftar (Buka Tiket)';
                    else btn.innerText = '✓ Terdaftar (Buka Tiket)';
                }
            });
        }

        // =====================================================================
        // Realtime Live Countdown Timer (Ticks every second)
        // =====================================================================
        const targetEventTime = new Date("<?= $heroIso ?>").getTime();

        function updateLiveCountdown() {
            const now = new Date().getTime();
            const diff = targetEventTime - now;

            const tickerCountdownEl = document.getElementById('tickerCountdown');
            if (!tickerCountdownEl) return;

            if (diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                let countdownStr = '• Mulai dalam ';
                if (days > 0) countdownStr += `${days} hari `;
                countdownStr += `${hours.toString().padStart(2, '0')} jam ${minutes.toString().padStart(2, '0')} m ${seconds.toString().padStart(2, '0')} d`;

                tickerCountdownEl.innerText = countdownStr;
            } else {
                tickerCountdownEl.innerText = '• Sesi Sedang Berlangsung Sekarang (Live)';
                tickerCountdownEl.style.color = '#10b981';
                tickerCountdownEl.style.fontWeight = '700';
            }
        }

        setInterval(updateLiveCountdown, 1000);
        updateLiveCountdown();

        // Apply local storage registered buttons on load
        localRegisteredEvents.forEach(title => {
            updateRegisteredButtonUI(title, '');
        });
    </script>
</body>
</html>
