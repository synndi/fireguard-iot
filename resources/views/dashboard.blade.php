
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireAlarm — Monitoring System</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, "Segoe UI", sans-serif;
            background: #0b1120;
            color: #e5edf8;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 245px;
            background: #0e1728;
            border-right: 1px solid #1c2a3d;
            padding: 28px 20px;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 55px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: #17365a;
            color: #60adff;
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-size: 21px;
        }

        .brand h2 {
            font-size: 17px;
            letter-spacing: 1px;
        }

        .brand p {
            font-size: 11px;
            color: #71839e;
            margin-top: 4px;
        }

        .menu-label {
            font-size: 10px;
            color: #63748c;
            letter-spacing: 1.5px;
            margin: 25px 0 13px 10px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 14px;
            text-decoration: none;
            color: #8fa1b9;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 13px;
            transition: .2s;
        }

        .menu a.active,
        .menu a:hover {
            color: #70b5ff;
            background: #162b46;
        }

        .sidebar-bottom {
            margin-top: 65px;
            padding: 15px;
            border: 1px solid #23334a;
            border-radius: 10px;
        }

        .sidebar-bottom p {
            font-size: 11px;
            color: #8092aa;
            margin-top: 7px;
        }

        /* MAIN */
        .main {
            flex: 1;
            min-width: 0;
            padding: 30px 38px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 38px;
        }

        .topbar h1 {
            font-size: 23px;
            font-weight: 600;
        }

        .topbar p {
            color: #778ba5;
            font-size: 12px;
            margin-top: 7px;
        }

        .date {
            color: #9aabc0;
            background: #121e30;
            border: 1px solid #26374d;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 12px;
        }

        /* SECTION */
        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 17px;
        }

        .section-heading h3 {
            font-size: 15px;
            font-weight: 600;
        }

        .section-heading span {
            font-size: 11px;
            color: #778ba5;
        }

        /* STATUS */
        .status-panel {
            background: linear-gradient(110deg, #102b43, #122238);
            border: 1px solid #23466a;
            border-radius: 12px;
            padding: 24px 27px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }

        .status-panel small {
            color: #8da8c4;
            font-size: 10px;
            letter-spacing: 1.5px;
        }

        .status-panel h2 {
            font-size: 27px;
            margin-top: 9px;
            color: #65d6a2;
        }

        .status-panel p {
            color: #91a6bf;
            font-size: 12px;
            margin-top: 7px;
        }

        .status-symbol {
            font-size: 38px;
            color: #65d6a2;
        }

        /* SENSOR CARDS */
        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 17px;
            margin-bottom: 28px;
        }

        .sensor-card {
            background: #111c2d;
            border: 1px solid #23334a;
            border-radius: 11px;
            padding: 21px;
        }

        .sensor-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sensor-top span {
            font-size: 11px;
            color: #91a4bc;
            letter-spacing: .5px;
        }

        .sensor-icon {
            color: #60adff;
            font-size: 20px;
        }

        .sensor-value {
            font-size: 31px;
            font-weight: 600;
            margin-top: 22px;
        }

        .sensor-description {
            color: #71849e;
            font-size: 11px;
            margin-top: 7px;
        }

        /* CONTENT GRID */
        .content-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 18px;
            margin-bottom: 25px;
        }

        .panel {
            background: #111c2d;
            border: 1px solid #23334a;
            border-radius: 11px;
            padding: 21px;
            min-width: 0;
        }

        .chart-area {
            height: 240px;
            margin-top: 20px;
        }

        /* TABLE */
        .table-panel {
            background: #111c2d;
            border: 1px solid #23334a;
            border-radius: 11px;
            padding: 21px;
            overflow: hidden;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            min-width: 400px;
        }

        th {
            text-align: left;
            font-size: 10px;
            color: #7588a2;
            font-weight: 500;
            padding: 12px;
            border-bottom: 1px solid #29394e;
        }

        td {
            font-size: 12px;
            color: #c5d2e2;
            padding: 14px 12px;
            border-bottom: 1px solid #202f43;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            background: #123a30;
            color: #65d6a2;
            font-size: 10px;
        }

        .empty {
            color: #8293aa;
            text-align: center;
            padding: 25px;
            font-size: 12px;
        }

        footer {
            color: #60738c;
            font-size: 10px;
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 190px;
            }

            .main {
                padding: 25px 20px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .app {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
                border-right: none;
                border-bottom: 1px solid #26374d;
            }

            .brand {
                margin-bottom: 15px;
            }

            .menu-label,
            .sidebar-bottom {
                display: none;
            }

            .menu {
                display: flex;
                gap: 5px;
                overflow-x: auto;
            }

            .menu a {
                white-space: nowrap;
                margin: 0;
                padding: 10px;
            }

            .main {
                padding: 22px 15px;
            }

            .topbar {
                gap: 10px;
                align-items: flex-start;
            }

            .topbar h1 {
                font-size: 19px;
            }

            .date {
                font-size: 10px;
            }

            .sensor-grid {
                gap: 10px;
            }

            .sensor-card {
                padding: 15px;
            }

            .sensor-value {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="app">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">⌁</div>
            <div>
                <h2>FIREALARM</h2>
                <p>IoT Monitoring System</p>
            </div>
        </div>

        <div class="menu-label">MENU UTAMA</div>

        <nav class="menu">
            <a href="{{ route('dashboard') }}" class="active">
                ◫ &nbsp; Dashboard
            </a>

            <a href="#activity">
                ▤ &nbsp; Riwayat Sensor
            </a>

            <a href="#device">
                ◉ &nbsp; Informasi Sistem
            </a>
        </nav>

        <div class="sidebar-bottom">
            <span style="font-size:12px;">FireGuard System</span>
            <p>Monitoring kebakaran berbasis IoT</p>
        </div>

    </aside>


    <main class="main">

        <header class="topbar">
            <div>
                <h1>Dashboard Overview</h1>
                <p>Pantau kondisi sensor dan sistem keamanan secara terpusat.</p>
            </div>

            <div class="date">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </header>


        <div class="section-heading">
            <h3>System Condition</h3>
            <span>Latest reading</span>
        </div>


        <section class="status-panel">

            <div>
                <small>FIREGUARD STATUS</small>

                <h2>
                    {{ strtoupper($latest->status ?? 'MENUNGGU DATA') }}
                </h2>

                <p>
                    @if($latest)
                        Data terakhir:
                        {{ $latest->created_at->format('H:i:s') }}
                    @else
                        Belum ada data sensor yang diterima.
                    @endif
                </p>
            </div>

            <div class="status-symbol">⬡</div>

        </section>


        <div class="section-heading">
            <h3>Sensor Monitoring</h3>
            <span>Device readings</span>
        </div>


        <section class="sensor-grid">

            <div class="sensor-card">

                <div class="sensor-top">
                    <span>MQ-2 GAS SENSOR</span>
                    <span class="sensor-icon">⌁</span>
                </div>

                <div class="sensor-value">
                    {{ $latest->mq2_value ?? '--' }}
                </div>

                <div class="sensor-description">
                    Nilai pembacaan gas / asap
                </div>

            </div>


            <div class="sensor-card">

                <div class="sensor-top">
                    <span>FLAME SENSOR</span>
                    <span class="sensor-icon">◈</span>
                </div>

                <div class="sensor-value">
                    {{ $latest->flame_value ?? '--' }}
                </div>

                <div class="sensor-description">
                    Nilai digital yang diterima dari perangkat
                </div>

            </div>

        </section>


        <section class="content-grid">

            <div class="panel">

                <div class="section-heading">
                    <h3>MQ-2 Activity</h3>
                    <span>Last 10 readings</span>
                </div>

                <div class="chart-area">
                    <canvas id="sensorChart"></canvas>
                </div>

            </div>


            <div class="panel" id="device">

                <div class="section-heading">
                    <h3>System Information</h3>
                </div>

                <div style="padding:12px 0; border-bottom:1px solid #26364b;">
                    <div style="font-size:11px;color:#8294ad;">MQTT BROKER</div>
                    <div style="font-size:13px;margin-top:7px;">
                        broker.emqx.io
                    </div>
                </div>

                <div style="padding:12px 0; border-bottom:1px solid #26364b;">
                    <div style="font-size:11px;color:#8294ad;">MQTT TOPIC</div>
                    <div style="font-size:13px;margin-top:7px;">
                        firealarm/status
                    </div>
                </div>

                <div style="padding:12px 0; border-bottom:1px solid #26364b;">
                    <div style="font-size:11px;color:#8294ad;">TOTAL DATA TERSIMPAN</div>
                    <div style="font-size:23px;margin-top:7px;">
                        {{ $totalLogs }}
                    </div>
                </div>

                <div style="padding:12px 0;">
                    <div style="font-size:11px;color:#8294ad;">DATABASE</div>
                    <div style="font-size:13px;margin-top:7px;">
                        MySQL
                    </div>
                </div>

            </div>

        </section>


        <section class="table-panel" id="activity">

            <div class="section-heading">
                <h3>Recent Sensor Activity</h3>
                <span>Latest records</span>
            </div>

            <div class="table-wrap">
                <table>

                    <thead>
                        <tr>
                            <th>WAKTU</th>
                            <th>MQ-2 VALUE</th>
                            <th>FLAME VALUE</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($logs->reverse() as $log)

                            <tr>
                                <td>
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>

                                <td>{{ $log->mq2_value }}</td>

                                <td>{{ $log->flame_value }}</td>

                                <td>
                                    <span class="status-badge">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="empty">
                                    Belum ada data sensor.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </section>


        <footer>
            FIREGUARD IoT MONITORING SYSTEM · {{ date('Y') }}
        </footer>

    </main>

</div>


<script>
    const labels = @json($chartLabels);
    const values = @json($chartValues);

    const ctx = document.getElementById('sensorChart');

    new Chart(ctx, {
        type: 'line',

        data: {
            labels: labels,

            datasets: [{
                label: 'MQ-2 Value',
                data: values,

                borderColor: '#4da3ff',
                backgroundColor: 'rgba(77, 163, 255, 0.10)',

                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#65b5ff',

                fill: true,
                tension: 0.35
            }]
        },

        options: {
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#8294ad',
                        font: {
                            size: 10
                        }
                    }
                },

                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#24354a'
                    },
                    ticks: {
                        color: '#8294ad',
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>
