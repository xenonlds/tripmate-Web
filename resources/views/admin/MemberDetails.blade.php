<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Member Details - TripMate</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

  <style>
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

    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 32px 24px;
    }

    .breadcrumb {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 24px;
      font-size: 14px;
      color: #64748b;
    }

    .breadcrumb a {
      color: #3b82f6;
      text-decoration: none;
    }

    .breadcrumb a:hover {
      text-decoration: underline;
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

    .header-actions {
      display: flex;
      gap: 12px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: #3b82f6;
      color: white;
    }

    .btn-primary:hover {
      background: #2563eb;
    }

    .btn-secondary {
      background: white;
      color: #64748b;
      border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
      background: #f8fafc;
    }

    .btn-danger {
      background: #ef4444;
      color: white;
    }

    .btn-danger:hover {
      background: #dc2626;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-success {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5;
    }

    .content-grid {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 24px;
      margin-bottom: 24px;
    }

    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .card-header {
      padding: 20px 24px;
      border-bottom: 1px solid #e2e8f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-title {
      font-size: 18px;
      font-weight: 700;
      color: #1e293b;
    }

    .card-body {
      padding: 24px;
    }

    .profile-section {
      text-align: center;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      margin: 0 auto 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 48px;
      font-weight: 700;
    }

    .profile-avatar img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
    }

    .profile-name {
      font-size: 24px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 4px;
    }

    .profile-id {
      font-size: 14px;
      color: #64748b;
      margin-bottom: 16px;
    }

    .status-badge {
      display: inline-block;
      padding: 6px 16px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
    }

    .status-active {
      background: #dcfce7;
      color: #166534;
    }

    .status-inactive {
      background: #fee2e2;
      color: #991b1b;
    }

    .info-grid {
      display: grid;
      gap: 16px;
      margin-top: 24px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 12px 0;
      border-bottom: 1px solid #f1f5f9;
    }

    .info-row:last-child {
      border-bottom: none;
    }

    .info-label {
      font-size: 14px;
      color: #64748b;
      font-weight: 500;
    }

    .info-value {
      font-size: 14px;
      color: #1e293b;
      font-weight: 600;
      text-align: right;
      max-width: 60%;
      word-break: break-word;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
    }

    .stat-card {
      background: #f8fafc;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
    }

    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 13px;
      color: #64748b;
      font-weight: 500;
    }

    .stat-icon {
      font-size: 24px;
      margin-bottom: 8px;
      opacity: 0.7;
    }

    .posts-section {
      grid-column: 1 / -1;
    }

    .post-card {
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 16px;
      transition: all 0.2s;
    }

    .post-card:hover {
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .post-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
    }

    .post-info {
      flex: 1;
    }

    .post-title {
      font-size: 18px;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 4px;
    }

    .post-date {
      font-size: 13px;
      color: #64748b;
    }

    .post-actions {
      display: flex;
      gap: 8px;
    }

    .post-btn {
      padding: 6px 12px;
      border: 1px solid #e2e8f0;
      background: white;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
    }

    .post-btn:hover {
      background: #f8fafc;
    }

    .post-btn-hide {
      color: #f59e0b;
      border-color: #f59e0b;
    }

    .post-btn-hide:hover {
      background: #fef3c7;
    }

    .post-btn-delete {
      color: #ef4444;
      border-color: #ef4444;
    }

    .post-btn-delete:hover {
      background: #fee2e2;
    }

    .post-content {
      color: #475569;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 12px;
    }

    .post-images {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 8px;
      margin-bottom: 12px;
    }

    .post-image {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 6px;
    }

    .post-stats {
      display: flex;
      gap: 24px;
      padding-top: 12px;
      border-top: 1px solid #f1f5f9;
      font-size: 13px;
      color: #64748b;
    }

    .post-stat {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .post-stat i {
      color: #3b82f6;
    }

    .hidden-badge {
      display: inline-block;
      padding: 4px 10px;
      background: #fee2e2;
      color: #991b1b;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 8px;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #94a3b8;
    }

    .empty-state i {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    .empty-state p {
      font-size: 16px;
    }

    @media (max-width: 1024px) {
      .content-grid {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .header-actions {
        flex-direction: column;
        width: 100%;
      }

      .btn {
        width: 100%;
        justify-content: center;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .post-header {
        flex-direction: column;
        gap: 12px;
      }

      .post-actions {
        width: 100%;
      }

      .post-btn {
        flex: 1;
      }
    }
  </style>
</head>
<body>
  <header class="header-nav">
    <div class="nav-container">
      <div class="logo-section">
        <img src="{{ asset('image/tripmate.png') }}" alt="TripMate logo">
        <div class="logo-text">
          <h1>TripMate Admin</h1>
        </div>
      </div>

      <ul class="nav-links">
        <li><a href="/admin/dashboards">Overview</a></li>
        <li><a href="/admin/MemberManagement" class="active">Members</a></li>
        <li><a href="/admin/CommunityManagement">Community</a></li>
        <li><a href="/admin/BusinessManagement">Business</a></li>
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

  <div class="container">
    <div class="breadcrumb">
      <a href="/admin/MemberManagement">Members</a>
      <span>/</span>
      <span>{{ $member['name'] ?? 'Member Details' }}</span>
    </div>

    <div class="page-header">
      <h1 class="page-title">Member Details</h1>
      <div class="header-actions">
        <button class="btn btn-secondary" onclick="exportData('{{ $member['tourist_id'] }}')">
          <i class="fas fa-download"></i> Export Data
        </button>
        <button class="btn btn-primary" onclick="editMember('{{ $member['tourist_id'] }}')">
          <i class="fas fa-edit"></i> Edit Member
        </button>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    <div class="content-grid">
      <!-- Profile Card -->
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Profile Information</h2>
        </div>
        <div class="card-body">
          <div class="profile-section">
            <div class="profile-avatar">
              @if(isset($member['profile_image']) && $member['profile_image'])
                <img src="{{ $member['profile_image'] }}" alt="{{ $member['name'] }}">
              @else
                {{ strtoupper(substr($member['name'] ?? 'U', 0, 1)) }}
              @endif
            </div>
            <h3 class="profile-name">{{ $member['name'] ?? 'N/A' }}</h3>
            <p class="profile-id">{{ $member['tourist_id'] }}</p>
            <span class="status-badge status-{{ $member['User']['status'] ?? 'inactive' }}">
              {{ ucfirst($member['User']['status'] ?? 'Inactive') }}
            </span>
          </div>

          <div class="info-grid">
            <div class="info-row">
              <span class="info-label">Email</span>
              <span class="info-value">{{ $member['User']['email'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Phone</span>
              <span class="info-value">{{ $member['phone_number'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Gender</span>
              <span class="info-value">{{ $member['gender'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Age</span>
              <span class="info-value">{{ $member['age'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Nationality</span>
              <span class="info-value">{{ $member['nationality'] ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">Birthdate</span>
              <span class="info-value">
                {{ isset($member['birthdate']) ? date('F d, Y', strtotime($member['birthdate'])) : 'N/A' }}
              </span>
            </div>
            <div class="info-row">
              <span class="info-label">Member Since</span>
              <span class="info-value">
                {{ isset($member['User']['created_at']) ? date('F d, Y', strtotime($member['User']['created_at'])) : 'N/A' }}
              </span>
            </div>
            <div class="info-row">
              <span class="info-label">Last Login</span>
              <span class="info-value">
                {{ isset($member['User']['last_login']) ? date('M d, Y H:i', strtotime($member['User']['last_login'])) : 'Never' }}
              </span>
            </div>
          </div>
        </div>
      </div>
      <!-- Statistics Grid -->
      <div>
        <!-- Activity Statistics -->
        <div class="card" style="margin-bottom: 24px;">
          <div class="card-header">
            <h2 class="card-title">Activity Statistics</h2>
          </div>
          <div class="card-body">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-value">{{ $activityStats['total_posts'] }}</div>
                <div class="stat-label">Total Posts</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">❤️</div>
                <div class="stat-value">{{ $activityStats['total_likes'] }}</div>
                <div class="stat-label">Total Likes</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">💬</div>
                <div class="stat-value">{{ $activityStats['total_comments'] }}</div>
                <div class="stat-label">Comments Made</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">👁️</div>
                <div class="stat-value">{{ $activityStats['active_posts'] }}</div>
                <div class="stat-label">Active Posts</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Booking Statistics -->
        <div class="card" style="margin-bottom: 24px;">
          <div class="card-header">
            <h2 class="card-title">Booking Statistics</h2>
          </div>
          <div class="card-body">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-value">{{ $bookingStats['total_bookings'] }}</div>
                <div class="stat-label">Total Bookings</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-value">{{ $bookingStats['completed_bookings'] }}</div>
                <div class="stat-label">Completed</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-value">{{ $bookingStats['active_bookings'] }}</div>
                <div class="stat-label">Active</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-value">${{ number_format($bookingStats['total_spent'], 2) }}</div>
                <div class="stat-label">Total Spent</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Trip Statistics -->
        <div class="card">
          <div class="card-header">
            <h2 class="card-title">Trip Statistics</h2>
          </div>
          <div class="card-body">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon">🗺️</div>
                <div class="stat-value">{{ $tripStats['total_trips'] }}</div>
                <div class="stat-label">Total Trips</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-value">{{ $tripStats['upcoming_trips'] }}</div>
                <div class="stat-label">Upcoming</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">✔️</div>
                <div class="stat-value">{{ $tripStats['completed_trips'] }}</div>
                <div class="stat-label">Completed</div>
              </div>
              <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-value">{{ $tripStats['shared_trips'] }}</div>
                <div class="stat-label">Shared</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Posts Section -->
    <div class="posts-section">
      <div class="card">
        <div class="card-header">
          <h2 class="card-title">Community Posts ({{ count($posts) }})</h2>
        </div>
        <div class="card-body">
          @forelse($posts as $post)
            <div class="post-card" id="post-{{ $post['postID'] }}">
              <div class="post-header">
                <div class="post-info">
                  <h3 class="post-title">
                    {{ $post['title'] ?? 'Untitled Post' }}
                    @if(isset($post['hidden_by_admin']) && $post['hidden_by_admin'])
                      <span class="hidden-badge" style="background: #dc2626;">ADMIN HIDDEN</span>
                    @elseif(isset($post['is_hidden']) && $post['is_hidden'])
                      <span class="hidden-badge" style="background: #94a3b8;">USER PRIVATE</span>
                    @endif
                  </h3>
                  <p class="post-date">
                    <i class="far fa-calendar"></i>
                    {{ isset($post['created_at']) ? date('F d, Y \a\t H:i', strtotime($post['created_at'])) : 'Unknown date' }}
                  </p>
                </div>
                <div class="post-actions">
                  <button class="post-btn post-btn-hide"
                          onclick="togglePostVisibility('{{ $post['postID'] }}', {{ ($post['hidden_by_admin'] ?? false) ? 'true' : 'false' }})">
                    <i class="fas fa-eye{{ ($post['hidden_by_admin'] ?? false) ? '' : '-slash' }}"></i>
                    {{ ($post['hidden_by_admin'] ?? false) ? 'Unhide' : 'Hide' }} (Admin)
                  </button>
                  <button class="post-btn post-btn-delete"
                          onclick="deletePost('{{ $post['postID'] }}', '{{ addslashes($post['title'] ?? 'this post') }}')">
                    <i class="fas fa-trash"></i>
                    Delete
                  </button>
                </div>
              </div>

              @if(isset($post['description']) && $post['description'])
                <div class="post-content">
                  {{ strlen($post['description']) > 300 ? substr($post['description'], 0, 300) . '...' : $post['description'] }}
                </div>
              @endif

              @if(isset($post['Images']) && $post['Images'])
                @php
                  $images = is_string($post['Images']) ? json_decode($post['Images'], true) : $post['Images'];
                  if ($images && is_array($images)):
                @endphp
                <div class="post-images">
                  @foreach(array_slice($images, 0, 4) as $image)
                    <img src="{{ $image }}" alt="Post image" class="post-image">
                  @endforeach
                </div>
                @php endif; @endphp
              @endif

              <div class="post-stats">
                <div class="post-stat">
                  <i class="fas fa-heart"></i>
                  <span>{{ $post['like_count'] ?? 0 }} Likes</span>
                </div>
                <div class="post-stat">
                  <i class="fas fa-eye"></i>
                  <span>{{ $post['is_hidden'] ? 'Hidden from public' : 'Visible to public' }}</span>
                </div>
                <div class="post-stat">
                  <i class="fas fa-calendar"></i>
                  <span>{{ isset($post['created_at']) ? \Carbon\Carbon::parse($post['created_at'])->diffForHumans() : 'Unknown' }}</span>
                </div>
              </div>
            </div>
          @empty
            <div class="empty-state">
              <i class="fas fa-inbox"></i>
              <p>No community posts yet</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <script>
    function editMember(touristId) {
      window.location.href = `/admin/member/${touristId}/edit`;
    }

    function exportData(touristId) {
      window.location.href = `/admin/member/${touristId}/export`;
    }

    async function togglePostVisibility(postId, isCurrentlyHidden) {
      const action = isCurrentlyHidden ? 'unhide' : 'hide';
      if (!confirm(`Are you sure you want to ${action} this post?`)) {
        return;
      }

      try {
        const response = await fetch(`/admin/member/post/${postId}/toggle-visibility`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });

        const data = await response.json();

        if (data.success) {
          // Reload page to show updated status
          location.reload();
        } else {
          alert('Error: ' + (data.error || 'Failed to update post visibility'));
        }
      } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while updating the post');
      }
    }

    function deletePost(postId, postTitle) {
      if (!confirm(`Are you sure you want to delete "${postTitle}"? This action cannot be undone.`)) {
        return;
      }

      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/admin/member/post/${postId}/delete`;

      const csrfInput = document.createElement('input');
      csrfInput.type = 'hidden';
      csrfInput.name = '_token';
      csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;

      const methodInput = document.createElement('input');
      methodInput.type = 'hidden';
      methodInput.name = '_method';
      methodInput.value = 'DELETE';

      form.appendChild(csrfInput);
      form.appendChild(methodInput);
      document.body.appendChild(form);
      form.submit();
    }

    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = "{{ url('/logout') }}";
      }
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      });
    }, 5000);
  </script>
</body>
</html>
