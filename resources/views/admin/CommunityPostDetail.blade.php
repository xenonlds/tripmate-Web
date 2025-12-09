<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Post Details - TripMate Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Navigation */
        .header-nav {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #2563eb;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 24px;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-title i {
            color: #3b82f6;
        }

        /* Post Content */
        .post-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .post-meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .post-meta-item i {
            color: #64748b;
        }

        .post-meta-item span {
            font-size: 14px;
            color: #475569;
        }

        .post-meta-item strong {
            font-weight: 600;
            color: #1e293b;
        }

        .post-content {
            margin-bottom: 16px;
        }

        .post-content p {
            font-size: 16px;
            line-height: 1.6;
            color: #334155;
            margin-bottom: 12px;
        }

        .post-image {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            margin-top: 16px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.hidden {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.blocked {
            background: #fef3c7;
            color: #92400e;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .info-item {
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        /* Reports Section */
        .report-item {
            padding: 16px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }

        .report-reason {
            font-weight: 600;
            color: #92400e;
        }

        .report-status {
            font-size: 12px;
            padding: 4px 8px;
            background: white;
            border-radius: 4px;
        }

        .report-description {
            font-size: 14px;
            color: #78350f;
            margin-bottom: 8px;
        }

        .report-meta {
            font-size: 12px;
            color: #92400e;
        }

        /* Warnings Section */
        .warning-item {
            padding: 16px;
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .warning-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }

        .warning-severity {
            font-weight: 600;
            color: #991b1b;
            text-transform: uppercase;
        }

        .warning-date {
            font-size: 12px;
            color: #991b1b;
        }

        .warning-reason {
            font-size: 14px;
            color: #7f1d1d;
            margin-bottom: 4px;
        }

        .warning-details {
            font-size: 13px;
            color: #991b1b;
            margin-bottom: 8px;
        }

        .warning-admin {
            font-size: 12px;
            color: #991b1b;
            font-style: italic;
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
            display: block;
        }

        .empty-state p {
            font-size: 14px;
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
                <li><a href="{{ route('admin.dashboards') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.member.index') }}">Members</a></li>
                <li><a href="{{ route('admin.community.index') }}" class="active">Community</a></li>
                <li><a href="{{ route('admin.business.index') }}">Business</a></li>
            </ul>

            <div class="user-section">
                <div class="user-info">
                    <p>{{ session('name', 'Admin User') }}</p>
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
        <a href="{{ route('admin.community.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Community Management
        </a>

        <div class="page-header">
            <h1 class="page-title">Post Details</h1>
            <div class="action-buttons">
                @if($post['hidden_by_admin'] ?? false)
                    <button class="btn btn-warning" onclick="toggleVisibility(false)">
                        <i class="fas fa-eye"></i> Unhide Post (Admin)
                    </button>
                @else
                    <button class="btn btn-secondary" onclick="toggleVisibility(true)">
                        <i class="fas fa-eye-slash"></i> Hide Post (Admin)
                    </button>
                @endif
                @if($post['is_hidden'] ?? false)
                    <span class="badge" style="background: #94a3b8; padding: 8px 12px;">
                        <i class="fas fa-lock"></i> User set as Private
                    </span>
                @endif
                <button class="btn btn-warning" onclick="window.location.href='{{ route('admin.community.index') }}'">
                    <i class="fas fa-exclamation-triangle"></i> Issue Warning
                </button>
                <button class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Delete Post
                </button>
            </div>
        </div>

        <!-- Post Information -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-file-alt"></i>
                Post Information
            </div>

            <div class="post-meta">
                <div class="post-meta-item">
                    <i class="fas fa-hashtag"></i>
                    <span><strong>Post ID:</strong> {{ $post['postID'] }}</span>
                </div>
                <div class="post-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span><strong>Created:</strong> {{ date('M d, Y', strtotime($post['created_at'])) }}</span>
                </div>
                <div class="post-meta-item">
                    <i class="fas fa-heart"></i>
                    <span><strong>Likes:</strong> {{ $post['like_count'] ?? 0 }}</span>
                </div>
                <div class="post-meta-item">
                    <i class="fas fa-comment"></i>
                    <span><strong>Comments:</strong> {{ $commentsCount }}</span>
                </div>
                <div class="post-meta-item">
                    @if($post['hidden_by_admin'] ?? false)
                        <span class="status-badge blocked"><i class="fas fa-ban"></i> Hidden by Admin</span>
                    @elseif($post['is_hidden'] ?? false)
                        <span class="status-badge hidden"><i class="fas fa-eye-slash"></i> Hidden by User</span>
                    @else
                        <span class="status-badge active"><i class="fas fa-check"></i> Active</span>
                    @endif
                </div>
            </div>

            <div class="post-content">
                <h3 style="margin-bottom: 12px; color: #1e293b;">{{ $post['title'] ?? 'Untitled Post' }}</h3>
                <p>{{ $post['description'] ?? 'No description available.' }}</p>

                @if(!empty($post['post_img']))
                    <img src="{{ $post['post_img'] }}" alt="Post Image" class="post-image">
                @endif
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Location</div>
                    <div class="info-value">{{ $post['location'] ?? 'Not specified' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Category</div>
                    <div class="info-value">{{ $post['category'] ?? 'General' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Privacy</div>
                    <div class="info-value">{{ $post['privacy'] ?? 'Public' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">{{ isset($post['updated_at']) ? date('M d, Y', strtotime($post['updated_at'])) : 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Author Information -->
        <div class="card">
            <div class="card-title">
                <i class="fas fa-user"></i>
                Author Information
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $post['tourist_name'] ?? 'Unknown' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $post['tourist_email'] ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tourist ID</div>
                    <div class="info-value">{{ $post['tourist_id'] }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        @if($post['is_blocked'] ?? false)
                            <span class="status-badge blocked"><i class="fas fa-ban"></i> Blocked</span>
                        @else
                            <span class="status-badge active"><i class="fas fa-check"></i> Active</span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="margin-top: 16px;">
                <a href="{{ route('admin.member.show', $post['tourist_id']) }}" class="btn btn-primary">
                    <i class="fas fa-user"></i> View Member Profile
                </a>
                @if(!($post['is_blocked'] ?? false))
                    <button class="btn btn-danger" onclick="blockUser()">
                        <i class="fas fa-ban"></i> Block User
                    </button>
                @endif
            </div>
        </div>

        <!-- Reports -->
        @if(!empty($reports) && count($reports) > 0)
        <div class="card">
            <div class="card-title">
                <i class="fas fa-flag"></i>
                Reports ({{ count($reports) }})
            </div>

            @foreach($reports as $report)
            <div class="report-item">
                <div class="report-header">
                    <span class="report-reason">{{ $report['report_reason'] ?? 'No reason provided' }}</span>
                    <span class="report-status">{{ ucfirst($report['status'] ?? 'pending') }}</span>
                </div>
                @if(!empty($report['description']))
                    <div class="report-description">{{ $report['description'] }}</div>
                @endif
                <div class="report-meta">
                    Reported by Tourist #{{ $report['reporter_id'] ?? 'Unknown' }}
                    on {{ date('M d, Y H:i', strtotime($report['created_at'])) }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Warnings -->
        @if(!empty($warnings) && count($warnings) > 0)
        <div class="card">
            <div class="card-title">
                <i class="fas fa-exclamation-triangle"></i>
                Content Warnings ({{ count($warnings) }})
            </div>

            @foreach($warnings as $warning)
            <div class="warning-item">
                <div class="warning-header">
                    <span class="warning-severity">{{ $warning['severity'] ?? 'Warning' }}</span>
                    <span class="warning-date">{{ date('M d, Y', strtotime($warning['issued_at'])) }}</span>
                </div>
                <div class="warning-reason"><strong>Reason:</strong> {{ $warning['reason'] ?? 'No reason provided' }}</div>
                @if(!empty($warning['details']))
                    <div class="warning-details">{{ $warning['details'] }}</div>
                @endif
                <div class="warning-admin">Issued by: {{ $warning['admin_name'] ?? 'Admin' }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <script>
        function toggleVisibility(hide) {
            if (confirm(`Are you sure you want to ${hide ? 'hide' : 'unhide'} this post?`)) {
                fetch(`/admin/community/{{ $post['postID'] }}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: hide ? 'hidden' : 'active',
                        reason: 'Manual admin moderation'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Post visibility updated successfully');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to update post'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update post visibility');
                });
            }
        }

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                fetch(`/admin/community/{{ $post['postID'] }}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Post deleted successfully');
                        window.location.href = '{{ route('admin.community.index') }}';
                    } else {
                        alert('Error: ' + (data.error || 'Failed to delete post'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete post');
                });
            }
        }

        function blockUser() {
            if (confirm('Are you sure you want to block this user?')) {
                window.location.href = '{{ route('admin.community.index') }}';
                // You can implement the block functionality here
            }
        }
    </script>
</body>
</html>
