<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>Tren & Distribusi Laporan - Palapa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <style>
        :root {
            --bg: #f3f5f8;
            --surface: #ffffff;
            --text: #2a2e38;
            --muted: #8a94a5;
            --line: #e5eaf1;
            --primary: #1f76c2;
            --primary-dark: #165f9e;
            --shadow: 0 14px 28px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at 15% 10%, #f8fbff 0%, #f1f4f8 40%, #edf1f6 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .layout {
            display: flex;
            gap: 24px;
            padding: 16px;
            min-height: 100vh;
        }

        .content {
            flex: 1;
            max-width: calc(100vw - 306px);
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 24px; font-weight: 800; color: #0f66aa; margin: 0; }
        .page-subtitle { font-size: 13px; color: var(--muted); margin: 4px 0 0 0; }

        .section {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
        }

        .section-title { color: #0f66aa; font-size: 18px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }

        .chart-container { position: relative; height: 320px; width: 100%; }

        .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

        .filter-group {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
        }
        .filter-group i { color: #a0aec0; font-size: 16px; }
        .filter-select { border: none; font-size: 13px; font-family: inherit; background: transparent; outline: none; color: #4a5568; }

        @media (max-width: 980px) {
            .layout { flex-direction: column; }
            .content { max-width: none !important; }
            .chart-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="layout" x-data="{ sidebarOpen: true }">
    @include('components.sidebar')

    <main class="content" :style="sidebarOpen ? 'max-width: calc(100vw - 306px);' : 'max-width: calc(100vw - 138px);'">

        <div class="page-header">
            <div>
                <h1 class="page-title">Tren & Distribusi Laporan</h1>
                <p class="page-subtitle">Analisis tren dan distribusi laporan karhutla berdasarkan periode, status, dan wilayah</p>
            </div>
        </div>

        <!-- PBI 36: Tren Laporan Per Periode (Grouped Bar per Status) -->
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="ph ph-chart-bar"></i> Tren Laporan Per Periode & Status
                </h2>
                <form method="GET" action="{{ route('admin.tren-distribusi') }}">
                    <div class="filter-group">
                        <i class="ph ph-calendar-blank"></i>
                        <select name="period" class="filter-select" onchange="this.form.submit()">
                            <option value="7days" {{ $period === '7days' ? 'selected' : '' }}>7 Hari Terakhir</option>
                            <option value="30days" {{ $period === '30days' ? 'selected' : '' }}>30 Hari Terakhir</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="chart-container">
                <canvas id="trenChart"></canvas>
            </div>
        </div>

        <!-- Distribusi Status & Wilayah -->
        <div class="chart-grid">
            <div class="section">
                <div class="section-header">
                    <h2 class="section-title"><i class="ph ph-chart-pie"></i> Distribusi Per Status</h2>
                </div>
                <div class="chart-container" style="height: 260px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div class="section">
                <div class="section-header">
                    <h2 class="section-title"><i class="ph ph-map-pin"></i> Distribusi Per Wilayah</h2>
                </div>
                <div class="chart-container" style="height: 260px;">
                    <canvas id="wilayahChart"></canvas>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    Chart.register(ChartDataLabels);

    const labels = {!! json_encode($chartLabels) !!};
    const trenByStatus = {!! json_encode($trenByStatus) !!};

    // --- PBI 36: Grouped Bar Chart per Status ---
    const trenCtx = document.getElementById('trenChart').getContext('2d');

    const statusConfig = [
        { key: 'pending',  label: 'Pending',  color: '#3182ce' },
        { key: 'valid',    label: 'Valid',    color: '#2b6cb0' },
        { key: 'diproses', label: 'Diproses', color: '#b7791f' },
        { key: 'selesai',  label: 'Selesai',  color: '#2f855a' },
        { key: 'ditolak',  label: 'Ditolak',  color: '#c53030' },
    ];

    const datasets = statusConfig.map(s => ({
        label: s.label,
        data: trenByStatus[s.key] || labels.map(() => 0),
        backgroundColor: s.color + 'cc',
        borderColor: s.color,
        borderWidth: 1,
        borderRadius: 4,
        borderSkipped: false,
    }));

    new Chart(trenCtx, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: 'Poppins', size: 11 }, padding: 16, boxWidth: 14 }
                },
                datalabels: {
                    color: '#4a5568',
                    font: { family: 'Poppins', size: 9, weight: 'bold' },
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value > 0 ? value : '',
                },
                tooltip: {
                    backgroundColor: '#2d3748',
                    titleFont: { family: 'Poppins', size: 13 },
                    bodyFont: { family: 'Poppins', size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#edf2f7' },
                    ticks: { font: { family: 'Poppins', size: 11 }, stepSize: 1 }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Poppins', size: 11 } }
                }
            }
        }
    });

    // --- Distribusi Status (Donut) ---
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusLabels = {!! json_encode($statusLabels) !!};
    const statusData = {!! json_encode($statusData) !!};

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#3182ce', '#2b6cb0', '#b7791f', '#2f855a', '#c53030'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Poppins', size: 11 }, padding: 12 } },
                datalabels: {
                    color: '#ffffff',
                    font: { family: 'Poppins', size: 12, weight: 'bold' },
                    formatter: (value) => value > 0 ? value : '',
                },
                tooltip: { backgroundColor: '#2d3748', titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Poppins', size: 12 }, padding: 10, cornerRadius: 8 }
            }
        }
    });

    // --- PBI 37: Distribusi Wilayah (Horizontal Bar) ---
    const wilayahCtx = document.getElementById('wilayahChart').getContext('2d');
    const wilayahLabels = {!! json_encode($wilayahLabels) !!};
    const wilayahCounts = {!! json_encode($wilayahCounts) !!};

    new Chart(wilayahCtx, {
        type: 'bar',
        data: {
            labels: wilayahLabels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: wilayahCounts,
                backgroundColor: 'rgba(31, 118, 194, 0.75)',
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                datalabels: {
                    color: '#2d3748',
                    font: { family: 'Poppins', size: 11, weight: 'bold' },
                    anchor: 'end',
                    align: 'right',
                    formatter: (value) => value > 0 ? value : '',
                },
                tooltip: { backgroundColor: '#2d3748', titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Poppins', size: 12 }, padding: 10, cornerRadius: 8, displayColors: false }
            },
            scales: {
                x: { beginAtZero: true, grid: { color: '#edf2f7' }, ticks: { font: { family: 'Poppins', size: 11 }, stepSize: 1 }, grace: '5%' },
                y: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 10 } } }
            }
        }
    });

});
</script>
</body>
</html>