
@php
    $statusText = strtoupper((string) ($latest->status ?? ''));
    
    if (
        str_contains($statusText, 'FIRE') ||
        str_contains($statusText, 'FLAME') ||
        str_contains($statusText, 'KEBAKARAN') ||
        str_contains($statusText, 'API')
    ) {
        $statusType = 'fire';
        $statusTitle = 'Api terdeteksi!';
        $statusDescription = 'Terdapat indikasi api. Periksa kondisi di sekitar perangkat.';
        $statusIcon = 'fire';
    } elseif (
        str_contains($statusText, 'GAS') ||
        str_contains($statusText, 'ASAP') ||
        str_contains($statusText, 'SMOKE') ||
        str_contains($statusText, 'WASPADA')
    ) {
        $statusType = 'gas';
        $statusTitle = 'Gas atau asap terdeteksi';
        $statusDescription = 'Sensor menangkap indikasi gas atau asap. Mohon periksa lingkungan.';
        $statusIcon = 'gas';
    } elseif (
        str_contains($statusText, 'AMAN') ||
        str_contains($statusText, 'NORMAL') ||
        str_contains($statusText, 'SAFE')
    ) {
        $statusType = 'safe';
        $statusTitle = 'Semua terlihat aman';
        $statusDescription = 'Belum ada indikasi bahaya yang terdeteksi oleh sensor.';
        $statusIcon = 'safe';
    } else {
        $statusType = 'waiting';
        $statusTitle = 'Menunggu data';
        $statusDescription = 'Belum ada informasi kondisi sensor yang tersedia.';
        $statusIcon = 'waiting';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FireGuard — Pemantauan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg: #f7f6f0;
            --paper: #fffefa;
            --ink: #252923;
            --muted: #85877e;
            --line: #e8e7df;

            --green: #607c5b;
            --green-soft: #edf2e8;

            --orange: #e89a56;
            --orange-soft: #fff0df;

            --red: #d45f4d;
            --red-soft: #fce9e5;

            --yellow: #f3d68b;

            --radius: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        button, a {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* =========================
           LAYOUT
        ========================= */

        .app {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 238px;
            min-height: 100vh;
            background: #f1f0e9;
            border-right: 1px solid var(--line);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 5px;
        }

        .brand-mark {
            width: 43px;
            height: 43px;
            background: #617b59;
            color: white;
            display: grid;
            place-items: center;
            border-radius: 14px;
            transform: rotate(-3deg);
        }

        .brand-mark svg {
            width: 25px;
            height: 25px;
        }

        .brand-name {
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -0.8px;
        }

        .brand-sub {
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .nav-label {
            margin: 44px 8px 13px;
            font-size: 10px;
            color: #99998e;
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 12px;
            font-size: 13px;
            color: #74766d;
            transition: .2s;
        }

        .nav-item svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
        }

        .nav-item:hover {
            background: #e8e8df;
            color: var(--ink);
        }

        .nav-item.active {
            background: #e2e8db;
            color: #536d4d;
            font-weight: 700;
        }

        .sidebar-bottom {
            margin-top: auto;
        }

        .sidebar-note {
            padding: 16px;
            background: #e8eadf;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .note-title {
            font-family: 'Manrope', sans-serif;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .note-text {
            font-size: 11px;
            line-height: 1.7;
            color: #73776b;
            max-width: 155px;
        }

        .note-doodle {
            position: absolute;
            right: -7px;
            bottom: -10px;
            font-size: 43px;
            opacity: .6;
            transform: rotate(-12deg);
        }

        .sidebar-footer {
            font-size: 10px;
            color: #99998f;
            margin: 19px 4px 0;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            flex: 1;
            min-width: 0;
            padding: 36px 45px;
            max-width: 1600px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 34px;
        }

        .eyebrow {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .page-title {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(25px, 3vw, 35px);
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .page-title span {
            color: var(--green);
        }

        .top-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .date-box {
            padding: 11px 15px;
            border: 1px solid var(--line);
            background: var(--paper);
            border-radius: 12px;
            color: #73766e;
            font-size: 12px;
        }

        .profile {
            width: 41px;
            height: 41px;
            border-radius: 13px;
            background: #e7e9dc;
            display: grid;
            place-items: center;
            color: #607653;
            font-weight: 800;
            font-size: 13px;
        }

        /* =========================
           STATUS CARD
        ========================= */

        .status-card {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 240px;
            align-items: center;
            overflow: hidden;
            border-radius: 24px;
            min-height: 245px;
            padding: 34px 40px;
            margin-bottom: 23px;
            transition: background .3s;
        }

        .status-card.safe {
            background: #e9efe4;
        }

        .status-card.gas {
            background: #fff0df;
        }

        .status-card.fire {
            background: #fce7e1;
        }

        .status-card.waiting {
            background: #eeeee9;
        }

        .status-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            margin-bottom: 16px;
            color: #71766c;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--green);
            border-radius: 50%;
        }

        .gas .status-dot {
            background: var(--orange);
        }

        .fire .status-dot {
            background: var(--red);
        }

        .waiting .status-dot {
            background: #a0a19a;
        }

        .status-heading {
            font-family: 'Manrope', sans-serif;
            font-size: clamp(26px, 3vw, 36px);
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -1.3px;
            margin-bottom: 12px;
        }

        .status-description {
            font-size: 13px;
            line-height: 1.8;
            color: #73776d;
            max-width: 430px;
        }

        .status-time {
            font-size: 11px;
            color: #888b81;
            margin-top: 20px;
        }

        .status-illustration {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 180px;
            position: relative;
        }

        .icon-circle {
            width: 155px;
            height: 155px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            position: relative;
            z-index: 1;
        }

        .safe .icon-circle {
            background: #d8e4d0;
            color: #607c5b;
        }

        .gas .icon-circle {
            background: #f6dfbd;
            color: #d38b46;
        }

        .fire .icon-circle {
            background: #f4d0c6;
            color: #cc5948;
        }

        .waiting .icon-circle {
            background: #e1e1dc;
            color: #85867e;
        }

        .status-icon {
            width: 85px;
            height: 85px;
        }

        .safe .status-icon {
            animation: float 3.4s ease-in-out infinite;
        }

        .gas .status-icon {
            animation: float 2.5s ease-in-out infinite;
        }

        .fire .status-icon {
            animation: fireMove 1.1s ease-in-out infinite;
        }

        .waiting .status-icon {
            animation: float 3s ease-in-out infinite;
        }

        .spark {
            position: absolute;
            font-size: 19px;
            opacity: .7;
        }

        .spark.one {
            top: 14px;
            right: 35px;
            transform: rotate(15deg);
        }

        .spark.two {
            left: 26px;
            bottom: 17px;
            transform: rotate(-12deg);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-7px); }
        }

        @keyframes fireMove {
            0%, 100% { transform: scale(1) rotate(-2deg); }
            50% { transform: scale(1.05) rotate(2deg); }
        }

        /* =========================
           SENSOR CARDS
        ========================= */

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin: 32px 0 17px;
        }

        .section-title {
            font-family: 'Manrope', sans-serif;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.4px;
        }

        .section-caption {
            font-size: 11px;
            color: var(--muted);
        }

        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .sensor-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 23px;
            min-width: 0;
        }

        .sensor-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
        }

        .sensor-icon {
            width: 43px;
            height: 43px;
            border-radius: 13px;
            display: grid;
            place-items: center;
        }

        .sensor-icon svg {
            width: 23px;
            height: 23px;
        }

        .sensor-icon.mq {
            background: #fff0df;
            color: #cf8c49;
        }

        .sensor-icon.flame {
            background: #fbe7e1;
            color: #d46450;
        }

        .sensor-tag {
            font-size: 10px;
            background: #f4f3ed;
            color: #85867d;
            border-radius: 20px;
            padding: 7px 9px;
        }

        .sensor-name {
            font-size: 13px;
            font-weight: 700;
            margin-top: 21px;
        }

        .sensor-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 5px;
        }

        .sensor-value {
            font-family: 'Manrope', sans-serif;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: -1.5px;
            margin-top: 20px;
            line-height: 1;
        }

        .sensor-unit {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: #888a80;
            letter-spacing: 0;
        }

        .sensor-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #92938b;
            margin-top: 17px;
            gap: 10px;
        }

        .sensor-bottom strong {
            color: #6e7567;
            font-weight: 600;
        }

        .sensor-line {
            height: 5px;
            width: 100%;
            background: #efeee8;
            border-radius: 20px;
            margin-top: 16px;
            overflow: hidden;
        }

        .sensor-fill {
            height: 100%;
            border-radius: 20px;
            background: #d6a269;
            width: 0;
            transition: width .5s;
        }

        .sensor-fill.flame-fill {
            background: #d97862;
        }

        /* =========================
           CONTENT GRID
        ========================= */

        .content-grid {
            display: grid;
            grid-template-columns: 1.55fr 1fr;
            gap: 18px;
            margin-top: 23px;
        }

        .panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 24px;
            min-width: 0;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 23px;
        }

        .panel-title {
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 800;
        }

        .panel-desc {
            color: var(--muted);
            font-size: 11px;
            margin-top: 5px;
        }

        .chart-label {
            font-size: 10px;
            padding: 7px 10px;
            border-radius: 20px;
            background: #edf2e8;
            color: #61795b;
        }

        .chart-container {
            position: relative;
            height: 235px;
            width: 100%;
        }

        .device-list {
            display: flex;
            flex-direction: column;
        }

        .device-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #efeee8;
        }

        .device-row:last-child {
            border-bottom: none;
        }

        .device-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .device-mini-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: #f0f1e9;
            color: #78866b;
            display: grid;
            place-items: center;
        }

        .device-mini-icon svg {
            width: 18px;
            height: 18px;
        }

        .device-name {
            font-size: 12px;
            font-weight: 700;
        }

        .device-detail {
            font-size: 10px;
            color: var(--muted);
            margin-top: 3px;
        }

        .device-value {
            font-size: 11px;
            color: #777a70;
            font-weight: 600;
            text-align: right;
        }

        .online-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            color: #68815f;
        }

        .online-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #81a474;
        }

        /* =========================
           HISTORY
        ========================= */

        .history-panel {
            margin-top: 23px;
        }

        .history-table-wrap {
            overflow-x: auto;
            width: 100%;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .history-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .5px;
            color: #989990;
            padding: 12px 10px;
            background: #f8f7f2;
        }

        .history-table th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .history-table th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .history-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f0efe9;
            font-size: 11px;
            color: #64675e;
        }

        .history-table tr:last-child td {
            border-bottom: none;
        }

        .history-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .history-status.safe {
            color: #5c7656;
            background: #edf2e8;
        }

        .history-status.gas {
            color: #bd803b;
            background: #fff1df;
        }

        .history-status.fire {
            color: #c55444;
            background: #fbe8e3;
        }

        .history-status.waiting {
            color: #7f8078;
            background: #eeeee9;
        }

        .empty-state {
            text-align: center;
            color: #a0a096;
            padding: 30px 10px;
            font-size: 12px;
        }

        /* =========================
           FOOTER
        ========================= */

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 25px 0 8px;
            font-size: 10px;
            color: #a0a096;
        }

        .footer span:last-child {
            color: #7c8575;
        }

        /* =========================
           TABLET
        ========================= */

        @media (max-width: 1100px) {
            .sidebar {
                width: 205px;
                padding: 25px 15px;
            }

            .main {
                padding: 30px 25px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .status-card {
                grid-template-columns: 1fr 200px;
            }
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 700px) {
            .app {
                display: block;
            }

            .sidebar {
                width: 100%;
                min-height: auto;
                height: auto;
                padding: 16px 18px 12px;
                border-right: none;
                border-bottom: 1px solid var(--line);
                display: block;
            }

            .brand {
                padding: 0 2px;
            }

            .brand-mark {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            .brand-name {
                font-size: 18px;
            }

            .nav-label {
                display: none;
            }

            .nav-menu {
                flex-direction: row;
                gap: 5px;
                margin-top: 17px;
                overflow-x: auto;
                padding-bottom: 3px;
            }

            .nav-item {
                padding: 10px 12px;
                white-space: nowrap;
                font-size: 11px;
                flex-shrink: 0;
            }

            .nav-item svg {
                width: 16px;
                height: 16px;
            }

            .sidebar-bottom,
            .sidebar-footer {
                display: none;
            }

            .main {
                padding: 23px 17px 30px;
            }

            .topbar {
                margin-bottom: 23px;
                gap: 10px;
            }

            .page-title {
                font-size: 25px;
            }

            .eyebrow {
                font-size: 9px;
                margin-bottom: 6px;
            }

            .date-box {
                font-size: 10px;
                padding: 9px 10px;
            }

            .profile {
                width: 35px;
                height: 35px;
                font-size: 11px;
                border-radius: 11px;
            }

            .status-card {
                grid-template-columns: 1fr 110px;
                min-height: 190px;
                padding: 23px 18px;
                border-radius: 20px;
                gap: 5px;
            }

            .status-label {
                font-size: 9px;
                margin-bottom: 12px;
            }

            .status-heading {
                font-size: 23px;
                letter-spacing: -.8px;
            }

            .status-description {
                font-size: 11px;
                line-height: 1.65;
            }

            .status-time {
                font-size: 9px;
                margin-top: 13px;
            }

            .status-illustration {
                height: 130px;
            }

            .icon-circle {
                width: 105px;
                height: 105px;
            }

            .status-icon {
                width: 61px;
                height: 61px;
            }

            .spark {
                font-size: 14px;
            }

            .spark.one {
                top: 5px;
                right: 5px;
            }

            .spark.two {
                left: 0;
                bottom: 10px;
            }

            .section-heading {
                margin: 26px 0 13px;
            }

            .section-title {
                font-size: 15px;
            }

            .section-caption {
                font-size: 9px;
            }

            .sensor-grid {
                gap: 11px;
            }

            .sensor-card {
                padding: 15px;
                border-radius: 17px;
            }

            .sensor-icon {
                width: 35px;
                height: 35px;
                border-radius: 10px;
            }

            .sensor-icon svg {
                width: 19px;
                height: 19px;
            }

            .sensor-tag {
                font-size: 9px;
                padding: 6px 7px;
            }

            .sensor-name {
                font-size: 11px;
                margin-top: 16px;
            }

            .sensor-sub {
                font-size: 9px;
            }

            .sensor-value {
                font-size: 29px;
                margin-top: 17px;
            }

            .sensor-unit {
                font-size: 10px;
            }

            .sensor-bottom {
                font-size: 9px;
                margin-top: 14px;
                flex-wrap: wrap;
            }

            .sensor-line {
                margin-top: 13px;
            }

            .content-grid {
                gap: 13px;
                margin-top: 17px;
            }

            .panel {
                padding: 18px;
                border-radius: 18px;
            }

            .panel-header {
                margin-bottom: 18px;
            }

            .panel-title {
                font-size: 14px;
            }

            .panel-desc {
                font-size: 10px;
            }

            .chart-container {
                height: 205px;
            }

            .device-row {
                padding: 12px 0;
            }

            .history-panel {
                margin-top: 17px;
            }

            .history-table th {
                font-size: 9px;
                padding: 10px 8px;
            }

            .history-table td {
                padding: 12px 8px;
                font-size: 10px;
            }

            .footer {
                font-size: 9px;
                padding-top: 20px;
            }
        }

        @media (max-width: 380px) {
            .main {
                padding-left: 13px;
                padding-right: 13px;
            }

            .status-card {
                grid-template-columns: 1fr 90px;
                padding: 18px 14px;
            }

            .status-heading {
                font-size: 20px;
            }

            .icon-circle {
                width: 90px;
                height: 90px;
            }

            .sensor-card {
                padding: 12px;
            }

            .sensor-value {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        <a href="{{ route('dashboard') }}" class="brand">
            <div class="brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22c5 0 8-3.8 8-8 0-3-2-5.5-4.5-7.5.2 2-1 3.2-2.5 4.1C12.5 7.4 10 4 7 2 7.3 6.5 4 9.4 4 14c0 4.6 3.4 8 8 8Z"/>
                    <path d="M12 18c1.8 0 3-1.3 3-3.1 0-1.2-.8-2.4-2-3.3-.1 1.2-.8 1.8-1.5 2.2-.5-1-1.2-1.7-2-2.4-.1 2.5-1.5 3.7-1.5 5.1C8 17.5 9.7 18 12 18Z"/>
                </svg>
            </div>

            <div>
                <div class="brand-name">FireGuard</div>
                <div class="brand-sub">Sistem Pemantauan</div>
            </div>
        </a>

        <div class="nav-label">MENU UTAMA</div>

        <nav class="nav-menu">

            <a href="{{ route('dashboard') }}" class="nav-item active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="8" height="8" rx="2"/>
                    <rect x="13" y="3" width="8" height="5" rx="2"/>
                    <rect x="13" y="10" width="8" height="11" rx="2"/>
                    <rect x="3" y="13" width="8" height="8" rx="2"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="#sensor" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12a7 7 0 0 1 14 0"/>
                    <path d="M8 12a4 4 0 0 1 8 0"/>
                    <path d="M11 12a1 1 0 0 1 2 0"/>
                    <path d="M12 12v7"/>
                    <circle cx="12" cy="19" r="2"/>
                </svg>
                <span>Data Sensor</span>
            </a>

            <a href="#grafik" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m7 14 4-4 4 3 5-7"/>
                    <circle cx="7" cy="14" r="1"/>
                    <circle cx="11" cy="10" r="1"/>
                    <circle cx="15" cy="13" r="1"/>
                    <circle cx="20" cy="6" r="1"/>
                </svg>
                <span>Grafik</span>
            </a>

            <a href="#riwayat" class="nav-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 3-6.7"/>
                    <path d="M3 4v5h5"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
                <span>Riwayat</span>
            </a>

        </nav>

        <div class="sidebar-bottom">
            <div class="sidebar-note">
                <div class="note-title">Jaga sekitar, ya! ♡</div>
                <div class="note-text">
                    Deteksi dini membantu kita lebih peduli terhadap lingkungan.
                </div>
                <div class="note-doodle">⌁</div>
            </div>

            <div class="sidebar-footer">
                FireGuard · Pemantauan IoT
            </div>
        </div>

    </aside>

    {{-- MAIN CONTENT --}}
    <main class="main">

        {{-- HEADER --}}
        <header class="topbar">
            <div>
                <div class="eyebrow">Halo, selamat datang!</div>
                <h1 class="page-title">Pantau keadaan, <span>jaga keamanan.</span></h1>
            </div>

            <div class="top-right">
                <div class="date-box">
                    {{ now()->translatedFormat('d M Y') }}
                </div>

                <div class="profile">FG</div>
            </div>
        </header>

        {{-- STATUS UTAMA --}}
        <section class="status-card {{ $statusType }}">

            <div class="status-content">

                <div class="status-label">
                    <span class="status-dot"></span>
                    STATUS PEMANTAUAN
                </div>

                <h2 class="status-heading">{{ $statusTitle }}</h2>

                <p class="status-description">
                    {{ $statusDescription }}
                </p>

                <div class="status-time">
                    Data terakhir:
                    @if(isset($latest->created_at))
                        {{ $latest->created_at->format('d M Y, H:i') }} WIB
                    @else
                        Belum tersedia
                    @endif
                </div>

            </div>

            <div class="status-illustration">

                <span class="spark one">✳</span>
                <span class="spark two">✦</span>

                <div class="icon-circle">

                    {{-- ICON AMAN --}}
                    @if($statusIcon === 'safe')
                        <svg class="status-icon" viewBox="0 0 100 100"
                             fill="none" stroke="currentColor"
                             stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M50 13C34 26 20 43 24 61c4 17 18 27 32 25 17-3 27-20 22-37-3-12-12-24-28-36Z"
                                  fill="currentColor" opacity=".12"/>

                            <path d="M50 16C34 29 23 43 25 60c3 17 17 27 31 25 17-4 26-20 21-37-4-13-13-23-27-32Z"/>

                            <path d="M39 57c6-1 11-7 12-14 8 8 13 18 11 26-2 8-9 13-17 11-8-2-13-9-12-17 1-3 3-5 5-6Z"/>

                            <path d="m42 57 7 7 13-15"/>
                        </svg>

                    {{-- ICON GAS --}}
                    @elseif($statusIcon === 'gas')
                        <svg class="status-icon" viewBox="0 0 100 100"
                             fill="none" stroke="currentColor"
                             stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M27 72c-10-1-16-9-15-19 1-9 8-16 18-16 2-14 15-23 29-20 11 2 18 12 19 22 10 1 17 9 16 19-1 9-8 15-18 15H27Z"
                                  fill="currentColor" opacity=".12"/>

                            <path d="M27 72c-10-1-16-9-15-19 1-9 8-16 18-16 2-14 15-23 29-20 11 2 18 12 19 22 10 1 17 9 16 19-1 9-8 15-18 15H27Z"/>

                            <path d="M38 56c-5 5-7 9-7 13"/>
                            <path d="M50 48c-6 7-8 13-6 19"/>
                            <path d="M64 54c-5 6-6 11-4 16"/>
                        </svg>

                    {{-- ICON API --}}
                    @elseif($statusIcon === 'fire'
                    )
                        <svg class="status-icon" viewBox="0 0 100 100"
                             fill="none" stroke="currentColor"
                             stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">

                            <path d="M55 10c3 19-10 24-10 35-7-7-10-15-9-24C18 42 15 55 20 73c5 17 20 24 33 23 18-2 29-18 27-36-2-17-12-32-25-50Z"
                                  fill="currentColor" opacity=".12"/>

                            <path d="M55 10c3 19-10 24-10 35-7-7-10-15-9-24C18 42 15 55 20 73c5 17 20 24 33 23 18-2 29-18 27-36-2-17-12-32-25-50Z"/>

                            <path d="M51 51c-3 10-12 14-11 25 1 8 7 13 15 13 10 0 17-7 16-17 0-8-5-15-11-21-1 7-3 10-9 13Z"
                                  fill="currentColor" opacity=".5"/>
                        </svg>

                    {{-- ICON MENUNGGU --}}
                    @else
                        <svg class="status-icon" viewBox="0 0 100 100"
                             fill="none" stroke="currentColor"
                             stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">

                            <circle cx="50" cy="50" r="32"/>
                            <path d="M50 32v19l13 8"/>
                            <path d="M34 10h32"/>
                            <path d="M39 10v8"/>
                            <path d="M61 10v8"/>
                        </svg>
                    @endif

                </div>
            </div>

        </section>

        {{-- DATA SENSOR --}}
        <section id="sensor">

            <div class="section-heading">
                <h2 class="section-title">Bacaan sensor</h2>
                <p class="section-caption">Data yang diterima perangkat</p>
            </div>

            <div class="sensor-grid">

                {{-- MQ2 --}}
                <div class="sensor-card">

                    <div class="sensor-top">
                        <div class="sensor-icon mq">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 15a4 4 0 0 1 0-8 5 5 0 0 1 9-2 5 5 0 0 1 5 5 4 4 0 0 1-1 5Z"/>
                                <path d="M8 12c-2 2-2 3-2 4"/>
                                <path d="M12 10c-2 3-2 4-2 6"/>
                                <path d="M16 12c-1 2-1 3-1 4"/>
                            </svg>
                        </div>

                        <span class="sensor-tag">MQ-2</span>
                    </div>

                    <div class="sensor-name">Gas & Asap</div>
                    <div class="sensor-sub">Sensor kualitas udara</div>

                    <div class="sensor-value">
                        {{ $latest->mq2_value ?? 0 }}
                        <span class="sensor-unit">ADC</span>
                    </div>

                    <div class="sensor-bottom">
                        <span>Nilai pembacaan</span>
                        <strong>MQ-2</strong>
                    </div>

                    <div class="sensor-line">
                        <div class="sensor-fill"
                             style="width: {{ min(max(($latest->mq2_value ?? 0) / 4095 * 100, 0), 100) }}%">
                        </div>
                    </div>

                </div>

                {{-- FLAME --}}
                <div class="sensor-card">

                    <div class="sensor-top">
                        <div class="sensor-icon flame">
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22c5 0 8-4 8-9-1-3-3-5-5-7 0 3-1 4-3 5-1-4-3-6-5-8 0 5-4 8-4 12 0 4 4 7 9 7Z"/>
                                <path d="M12 18c2 0 3-2 3-4-1-1-2-2-3-3-1 2-3 3-3 5 0 1 1 2 3 2Z"/>
                            </svg>
                        </div>

                        <span class="sensor-tag">FLAME</span>
                    </div>

                    <div class="sensor-name">Sensor Api</div>
                    <div class="sensor-sub">Deteksi keberadaan api</div>

                    <div class="sensor-value">
                        {{ $latest->flame_value ?? 0 }}
                        <span class="sensor-unit">ADC</span>
                    </div>

                    <div class="sensor-bottom">
                        <span>Nilai pembacaan</span>
                        <strong>Flame Sensor</strong>
                    </div>

                    <div class="sensor-line">
                        <div class="sensor-fill flame-fill"
                             style="width: {{ min(max(($latest->flame_value ?? 0) / 4095 * 100, 0), 100) }}%">
                        </div>
                    </div>

                </div>

            </div>

        </section>

        {{-- GRAFIK & PERANGKAT --}}
        <section class="content-grid">

            {{-- GRAFIK --}}
            <div class="panel" id="grafik">

                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Pergerakan sensor</h3>
                        <p class="panel-desc">Perubahan nilai MQ-2 dari waktu ke waktu</p>
                    </div>

                    <span class="chart-label">MQ-2</span>
                </div>

                <div class="chart-container">
                    <canvas id="sensorChart"></canvas>
                </div>

            </div>

            {{-- INFORMASI PERANGKAT --}}
            <div class="panel" id="device">

                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Perangkat</h3>
                        <p class="panel-desc">Informasi sistem pemantauan</p>
                    </div>
                </div>

                <div class="device-list">

                    <div class="device-row">
                        <div class="device-left">
                            <div class="device-mini-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="5" y="3" width="14" height="18" rx="2"/>
                                    <path d="M9 7h6M9 11h6M9 15h2"/>
                                </svg>
                            </div>

                            <div>
                                <div class="device-name">Mikrokontroler</div>
                                <div class="device-detail">ESP32</div>
                            </div>
                        </div>

                        <span class="device-value">ESP32</span>
                    </div>

                    <div class="device-row">
                        <div class="device-left">
                            <div class="device-mini-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12a7 7 0 0 1 14 0"/>
                                    <path d="M8 12a4 4 0 0 1 8 0"/>
                                    <path d="M12 12v7"/>
                                    <circle cx="12" cy="19" r="2"/>
                                </svg>
                            </div>

                            <div>
                                <div class="device-name">Sensor Gas</div>
                                <div class="device-detail">MQ-2</div>
                            </div>
                        </div>

                        <span class="device-value">Aktif</span>
                    </div>

                    <div class="device-row">
                        <div class="device-left">
                            <div class="device-mini-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 3c2 5-2 6-2 9-2-2-2-4-2-5-4 5-4 8 0 11 3 3 7 2 9-1 3-4 1-9-3-14Z"/>
                                </svg>
                            </div>

                            <div>
                                <div class="device-name">Sensor Api</div>
                                <div class="device-detail">Flame Sensor</div>
                            </div>
                        </div>

                        <span class="device-value">Aktif</span>
                    </div>

                    <div class="device-row">
                        <div class="device-left">
                            <div class="device-mini-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.7"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 5h16v11H4z"/>
                                    <path d="M8 20h8"/>
                                    <path d="M12 16v4"/>
                                </svg>
                            </div>

                            <div>
                                <div class="device-name">Sistem</div>
                                <div class="device-detail">Pemantauan IoT</div>
                            </div>
                        </div>

                        <span class="online-status">Terhubung</span>
                    </div>

                </div>

            </div>

        </section>

        {{-- RIWAYAT --}}
        <section class="panel history-panel" id="riwayat">

            <div class="panel-header">
                <div>
                    <h3 class="panel-title">Riwayat pemantauan</h3>
                    <p class="panel-desc">Catatan data sensor yang masuk</p>
                </div>
            </div>

            <div class="history-table-wrap">

                <table class="history-table">

                    <thead>
                        <tr>
                            <th>WAKTU</th>
                            <th>MQ-2</th>
                            <th>FLAME</th>
                            <th>KONDISI</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($logs as $log)

                        @php
                            $logStatus = strtoupper((string) ($log->status ?? ''));

                            if (
                                str_contains($logStatus, 'FIRE') ||
                                str_contains($logStatus, 'FLAME') ||
                                str_contains($logStatus, 'KEBAKARAN') ||
                                str_contains($logStatus, 'API')
                            ) {
                                $logType = 'fire';
                                $logLabel = 'Api terdeteksi';
                            } elseif (
                                str_contains($logStatus, 'GAS') ||
                                str_contains($logStatus, 'ASAP') ||
                                str_contains($logStatus, 'SMOKE') ||
                                str_contains($logStatus, 'WASPADA')
                            ) {
                                $logType = 'gas';
                                $logLabel = 'Gas / Asap';
                            } elseif (
                                str_contains($logStatus, 'AMAN') ||
                                str_contains($logStatus, 'NORMAL') ||
                                str_contains($logStatus, 'SAFE')
                            ) {
                                $logType = 'safe';
                                $logLabel = 'Aman';
                            } else {
                                $logType = 'waiting';
                                $logLabel = $log->status ?? 'Tidak diketahui';
                            }
                        @endphp

                        <tr>
                            <td>
                                {{ $log->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td>{{ $log->mq2_value ?? '-' }}</td>

                            <td>{{ $log->flame_value ?? '-' }}</td>

                            <td>
                                <span class="history-status {{ $logType }}">
                                    {{ $logLabel }}
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="empty-state">
                                Belum ada data pemantauan.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </section>

        {{-- FOOTER --}}
        <footer class="footer">
            <span>FireGuard · Sistem Deteksi Dini</span>
            <span>Dibuat dengan kepedulian ♡</span>
        </footer>

    </main>

</div>

{{-- GRAFIK --}}
<script>
    const labels = @json($chartLabels ?? []);
    const values = @json($chartValues ?? []);

    const chartElement = document.getElementById('sensorChart');

    if (chartElement && labels.length > 0) {
        new Chart(chartElement, {
            type: 'line',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Nilai MQ-2',

                    data: values,

                    borderColor: '#d59a5d',
                    backgroundColor: 'rgba(213, 154, 93, 0.12)',

                    borderWidth: 2,

                    fill: true,

                    tension: 0.35,

                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#d59a5d',
                    pointBorderColor: '#fffefa',
                    pointBorderWidth: 2
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: 'index'
                },

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#292d26',
                        padding: 12,
                        cornerRadius: 10,
                        titleFont: {
                            family: 'DM Sans'
                        },
                        bodyFont: {
                            family: 'DM Sans'
                        }
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        },

                        ticks: {
                            color: '#92938b',
                            font: {
                                size: 10
                            },
                            maxTicksLimit: 6
                        }
                    },

                    y: {
                        beginAtZero: true,

                        grid: {
                            color: '#efeee8',
                            drawBorder: false
                        },

                        border: {
                            display: false,
                            dash: [4, 4]
                        },

                        ticks: {
                            color: '#92938b',
                            font: {
                                size: 10
                            },
                            maxTicksLimit: 5
                        }
                    }
                }
            }
        });
    }
</script>

</body>
</html>
