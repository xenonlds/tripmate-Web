<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - TripMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Navigation */
        .header-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
            width: 45px;
            height: 45px;
            border-radius: 12px;
        }

        .logo-text h1 {
            font-size: 22px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .nav-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            padding: 10px 18px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s;
            position: relative;
        }

        .nav-links a:hover {
            background: #f1f5f9;
            color: #667eea;
            transform: translateY(-2px);
        }

        .nav-links a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-info p {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .user-info span {
            font-size: 12px;
            color: #64748b;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .page-header {
            margin-bottom: 32px;
            color: white;
        }

        .page-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color-start), var(--card-color-end));
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .stat-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .stat-details {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #64748b;
            flex-wrap: wrap;
        }

        .stat-detail-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .stat-detail-item i {
            font-size: 10px;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Activity Timeline */
        .activity-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .activity-item {
            display: flex;
            gap: 16px;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .activity-item:hover {
            background: #f8fafc;
            border-left-color: #667eea;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .activity-icon.blue { background: #dbeafe; color: #3b82f6; }
        .activity-icon.green { background: #d1fae5; color: #10b981; }
        .activity-icon.orange { background: #fed7aa; color: #f97316; }
        .activity-icon.red { background: #fecaca; color: #ef4444; }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .activity-description {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .activity-time {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Top Lists */
        .top-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .top-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px;
            background: #f8fafc;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .top-item:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .top-item-rank {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .top-item-info {
            flex: 1;
            margin: 0 12px;
        }

        .top-item-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .top-item-stat {
            font-size: 12px;
            color: #64748b;
        }

        .top-item-badge {
            padding: 4px 12px;
            background: #dbeafe;
            color: #3b82f6;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Charts Section */
        .charts-section {
            margin-top: 32px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="header-nav">
        <div class="nav-container">
            <div class="logo-section">
                <img src="{{ asset('image/LOGO.png') }}" alt="TripMate Logo">
                <div class="logo-text">
                    <h1>TripMate</h1>
                </div>
            </div>

            <ul class="nav-links">
                <li><a href="{{ route('admin.dashboards') }}" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.staff.index') }}"><i class="fas fa-users-cog"></i> Staff</a></li>
                <li><a href="{{ route('admin.member.index') }}"><i class="fas fa-users"></i> Members</a></li>
                <li><a href="{{ route('admin.community.index') }}"><i class="fas fa-comments"></i> Community</a></li>
                <li><a href="{{ route('admin.business.index') }}"><i class="fas fa-building"></i> Business</a></li>
            </ul>

            <div class="user-section">
                <div class="user-info">
                    <p>{{ $adminName }}</p>
                    <span>Administrator</span>
                </div>
                <a href="{{ route('logout') }}" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Welcome back, {{ explode(' ', $adminName)[0] }}! 👋</h1>
            <p class="page-subtitle">Here's what's happening with your platform today</p>
        </div>

        <!-- Overview Stats -->
        <div class="stats-grid">
            <!-- Users Stats -->
            <div class="stat-card" style="--card-color-start: #3b82f6; --card-color-end: #2563eb;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value">{{ number_format($stats['totalUsers']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-users"></i> {{ number_format($stats['totalTourists']) }} Tourists
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-user-check"></i> {{ number_format($stats['activeTourists']) }} Active
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Business Stats -->
            <div class="stat-card" style="--card-color-start: #10b981; --card-color-end: #059669;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Business Partners</div>
                        <div class="stat-value">{{ number_format($stats['totalBusinessOwners']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-check-circle"></i> {{ number_format($stats['activeBusinesses']) }} Active
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-clock"></i> {{ number_format($stats['pendingBusinesses']) }} Pending
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>

            <!-- Properties Stats -->
            <div class="stat-card" style="--card-color-start: #f59e0b; --card-color-end: #d97706;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Properties</div>
                        <div class="stat-value">{{ number_format($stats['totalHotels'] + $stats['totalRestaurants']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-hotel"></i> {{ number_format($stats['totalHotels']) }} Hotels
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-utensils"></i> {{ number_format($stats['totalRestaurants']) }} Restaurants
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>

            <!-- Community Stats -->
            <div class="stat-card" style="--card-color-start: #8b5cf6; --card-color-end: #7c3aed;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Community Posts</div>
                        <div class="stat-value">{{ number_format($stats['totalPosts']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-eye"></i> {{ number_format($stats['activePosts']) }} Active
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-eye-slash"></i> {{ number_format($stats['hiddenPosts']) }} Hidden
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>

            <!-- Bookings Stats -->
            <div class="stat-card" style="--card-color-start: #06b6d4; --card-color-end: #0891b2;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="stat-value">{{ number_format($stats['totalBookings']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-check"></i> {{ number_format($stats['confirmedBookings']) }} Confirmed
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-hourglass-half"></i> {{ number_format($stats['pendingBookings']) }} Pending
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue Stats -->
            <div class="stat-card" style="--card-color-start: #22c55e; --card-color-end: #16a34a;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-value">RM {{ number_format($stats['totalRevenue'], 2) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-arrow-up"></i> From {{ number_format($stats['totalBookings']) }} bookings
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>

            <!-- Reports Stats -->
            <div class="stat-card" style="--card-color-start: #ef4444; --card-color-end: #dc2626;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Content Reports</div>
                        <div class="stat-value">{{ number_format($stats['totalReports']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-exclamation-circle"></i> {{ number_format($stats['pendingReports']) }} Pending Review
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-flag"></i>
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="stat-card" style="--card-color-start: #ec4899; --card-color-end: #db2777;">
                <div class="stat-header">
                    <div>
                        <div class="stat-label">Platform Activity</div>
                        <div class="stat-value">{{ number_format($stats['totalTrips']) }}</div>
                        <div class="stat-details">
                            <span class="stat-detail-item">
                                <i class="fas fa-map-marked-alt"></i> Trip Plans
                            </span>
                            <span class="stat-detail-item">
                                <i class="fas fa-ban"></i> {{ number_format($stats['totalBlockedUsers']) }} Blocked
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-route"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Recent Activities -->
            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    Recent Activities
                </h2>
                <div class="activity-list">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon {{ $activity['color'] }}">
                                <i class="fas {{ $activity['icon'] }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $activity['title'] }}</div>
                                <div class="activity-description">{{ $activity['description'] }}</div>
                                <div class="activity-time">
                                    <i class="far fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No recent activities</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Performing -->
            <div>
                <!-- Top Locations -->
                <div class="card" style="margin-bottom: 24px;">
                    <h2 class="card-title">
                        <i class="fas fa-trophy"></i>
                        Top Locations
                    </h2>
                    <div class="top-list">
                        @forelse($topData['locations'] as $index => $location)
                            <div class="top-item">
                                <div class="top-item-rank">{{ $index + 1 }}</div>
                                <div class="top-item-info">
                                    <div class="top-item-name">{{ $location['name'] }}</div>
                                    <div class="top-item-stat">
                                        <i class="fas fa-star"></i> {{ number_format($location['rating'], 1) }}
                                    </div>
                                </div>
                                <div class="top-item-badge">
                                    <i class="fas fa-heart"></i> {{ number_format($location['likes']) }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-map-marker-alt"></i>
                                <p>No locations yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Posts -->
                <div class="card">
                    <h2 class="card-title">
                        <i class="fas fa-fire"></i>
                        Top Posts
                    </h2>
                    <div class="top-list">
                        @forelse($topData['posts'] as $index => $post)
                            <div class="top-item">
                                <div class="top-item-rank">{{ $index + 1 }}</div>
                                <div class="top-item-info">
                                    <div class="top-item-name">{{ Str::limit($post['title'], 30) }}</div>
                                    <div class="top-item-stat">{{ $post['id'] }}</div>
                                </div>
                                <div class="top-item-badge">
                                    <i class="fas fa-heart"></i> {{ number_format($post['likes']) }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <p>No posts yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh data every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>
