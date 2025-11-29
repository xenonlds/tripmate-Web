<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - TripMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    @vite('resources/css/navigation.css')
    @vite('resources/css/header.css')
    @vite('resources/css/page/index.css')

    <style>
        nav a.active-ow {
            background-color: white;
            color: #1e293b;
            font-weight: 600;
            box-shadow: 0 0 0 1px #e2e8f0;
        }

        nav a.active-ow svg {
            stroke: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Header Navigation */
        .header-nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section img {
            width: 40px;
            height: 40px;
        }

        .logo-text h1 {
            font-size: 20px;
            color: #1e293b;
            font-weight: 700;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            padding: 8px 16px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-links a:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .nav-links a.active {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 600;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-info {
            text-align: right;
        }

        .user-info p {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-info span {
            font-size: 12px;
            color: #64748b;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
        }

        /* Filters */
        .filters-row {
            margin-bottom: 24px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filters-row label {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .filters-row input[type="month"] {
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .filters-row button {
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-success:hover {
            background: #059669;
        }

        /* Chart Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .chart-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            margin-bottom: 24px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
        }

        .chart-container {
            width: 100%;
            height: 300px;
            position: relative;
        }

        canvas {
            max-width: 100%;
            max-height: 100%;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 16px;
            }

            .user-section {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    @php
        $currentMonth = now()->format('Y-m');
        $firstOfYear  = now()->startOfYear()->format('Y-m');
    @endphp

    <!-- Header Navigation -->
    <header class="header-nav">
        <div class="nav-container">
            <div class="logo-section">
                <img src="{{ asset('image/tripmate.png') }}" alt="TripMate logo">
                <div class="logo-text">
                    <h1>TripMate Admin</h1>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="{{ route('admin.dashboards') }}" class="active">Overview</a></li>
                <li><a href="{{ route('admin.staff.index') }}">Staff</a></li>
                <li><a href="{{ route('admin.member.index') }}">Members</a></li>
                <li><a href="{{ route('admin.community.index') }}">Community</a></li>
                <li><a href="{{ route('admin.business.index') }}">Business</a></li>
            </ul>

            <div class="user-section">
                <div class="user-info">
                    <p>{{ $adminName ?? 'Admin' }}</p>
                    <span>Administrator</span>
                </div>
                <button class="logout-btn" onclick="logout()">Logout</button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container">
        <h1 class="page-title">Admin Dashboard</h1>

        <!-- Filters -->
        <div class="filters-row">
            <label for="startMonth">Select Date Range:</label>
            <input type="month" id="startMonth" value="{{ $firstOfYear }}">
            <span>to</span>
            <input type="month" id="endMonth" value="{{ $currentMonth }}">

            <button class="btn-primary" onclick="loadDashboardData()">Apply</button>
            <button class="btn-success" onclick="exportPDF()">Export PDF</button>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Business Joining Rate Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Business Joining Rate</h2>
                </div>
                <div class="chart-container">
                    <canvas id="businessChart"></canvas>
                </div>
            </div>

            <!-- Community Content Rate Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Community Content Rate</h2>
                </div>
                <div class="chart-container">
                    <canvas id="contentChart"></canvas>
                </div>
            </div>

            <!-- Member Engagement Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Member Engagement</h2>
                </div>
                <div class="chart-container">
                    <canvas id="engagementChart"></canvas>
                </div>
            </div>

            <!-- Reported Content Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h2 class="chart-title">Reported Content</h2>
                </div>
                <div class="chart-container">
                    <canvas id="reportedChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        // ==== INITIAL CHART SETUP (empty, will be filled by API) ====
        const businessCtx   = document.getElementById('businessChart').getContext('2d');
        const contentCtx    = document.getElementById('contentChart').getContext('2d');
        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        const reportedCtx   = document.getElementById('reportedChart').getContext('2d');

        const businessChart = new Chart(businessCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Businesses',
                    data: [],
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'top', align: 'end' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { drawBorder: false }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        const contentChart = new Chart(contentCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Posts',
                    data: [],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'top', align: 'end' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { drawBorder: false }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        const engagementChart = new Chart(engagementCtx, {
            type: 'doughnut',
            data: {
                labels: ['Likes', 'Comments', 'Shares'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#f97316', '#8b5cf6', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'right' } }
            }
        });

        const reportedChart = new Chart(reportedCtx, {
            type: 'doughnut',
            data: {
                labels: ['Spam', 'Harassment', 'Misinformation', 'Other'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: ['#ef4444', '#f97316', '#3b82f6', '#6b7280'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: true, position: 'right' } }
            }
        });

        // ==== LOAD ANALYTICS DATA FROM LARAVEL API ====
        async function loadDashboardData() {
            const start = document.getElementById("startMonth").value;
            const end   = document.getElementById("endMonth").value;

            if (!start || !end) {
                alert("Please select start and end month.");
                return;
            }

            const url = `{{ route('admin.analytics') }}?start=${start}&end=${end}`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.error) {
                    console.error(data.error);
                    alert(data.error);
                    return;
                }

                // Update labels
                businessChart.data.labels = data.months;
                contentChart.data.labels  = data.months;

                // Update values
                businessChart.data.datasets[0].data = data.businessJoining;
                contentChart.data.datasets[0].data  = data.contentRate;

                engagementChart.data.datasets[0].data = [
                    data.engagement.likes,
                    data.engagement.comments,
                    data.engagement.shares
                ];

                reportedChart.data.datasets[0].data = [
                    data.reported.spam,
                    data.reported.harassment,
                    data.reported.misinformation,
                    data.reported.other
                ];

                // Refresh charts
                businessChart.update();
                contentChart.update();
                engagementChart.update();
                reportedChart.update();

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                alert('Failed to load analytics data.');
            }
        }

        // ==== PDF EXPORT ====
        async function exportPDF() {
            const start = document.getElementById("startMonth").value;
            const end   = document.getElementById("endMonth").value;

            if (!start || !end) {
                alert("Please select start and end month.");
                return;
            }

            // Ensure latest data before export
            const analyticsResponse = await fetch(`{{ route('admin.analytics') }}?start=${start}&end=${end}`);
            const analytics = await analyticsResponse.json();

            // Convert charts to images
            const businessImg   = businessChart.toBase64Image();
            const contentImg    = contentChart.toBase64Image();
            const engagementImg = engagementChart.toBase64Image();
            const reportedImg   = reportedChart.toBase64Image();

            // Create POST form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.analytics.export') }}";

            const csrf = document.createElement('input');
            csrf.type  = 'hidden';
            csrf.name  = '_token';
            csrf.value = "{{ csrf_token() }}";
            form.appendChild(csrf);

            const addField = (name, value) => {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = name;
                input.value = value;
                form.appendChild(input);
            };

            addField('start', start);
            addField('end', end);
            addField('analytics', JSON.stringify(analytics));
            addField('businessChart', businessImg);
            addField('contentChart', contentImg);
            addField('engagementChart', engagementImg);
            addField('reportedChart', reportedImg);

            document.body.appendChild(form);
            form.submit();
        }

        // Logout Function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ url('/logout') }}";
            }
        }

        // Auto-load data on page ready
        window.addEventListener('DOMContentLoaded', () => {
            loadDashboardData();
        });
    </script>
</body>

</html>
