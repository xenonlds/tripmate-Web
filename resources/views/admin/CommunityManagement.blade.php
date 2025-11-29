<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Community Management - TripMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
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
            margin-bottom: 32px;
        }

        /* Search and Filter Section */
        .controls-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .search-box {
            flex: 1;
            max-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }

        .search-box input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            padding: 16px 24px;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        td {
            padding: 16px 24px;
            font-size: 14px;
            color: #475569;
            border-bottom: 1px solid #f1f5f9;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        /* Report Count Badge */
        .report-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background: #fee2e2;
            color: #991b1b;
        }

        .report-badge.low {
            background: #fef3c7;
            color: #92400e;
        }

        .report-badge.none {
            background: #dcfce7;
            color: #166534;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.hidden {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Operation Buttons */
        .operation-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-view {
            color: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-view:hover {
            background: #dbeafe;
        }

        .btn-hide {
            color: #f59e0b;
            border-color: #f59e0b;
        }

        .btn-hide:hover {
            background: #fef3c7;
        }

        .btn-delete {
            color: #ef4444;
            border-color: #ef4444;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 24px;
        }

        .page-btn {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .page-btn:hover {
            background: #f8fafc;
        }

        .page-btn.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-header h2 {
            font-size: 24px;
            color: #1e293b;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #64748b;
        }

        .post-detail {
            margin-bottom: 20px;
        }

        .post-detail h3 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #1e293b;
        }

        .post-detail p {
            color: #475569;
            line-height: 1.6;
        }

        .post-image {
            max-width: 100%;
            border-radius: 8px;
            margin: 16px 0;
        }

        .reports-list {
            margin-top: 20px;
        }

        .report-item {
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .report-item strong {
            color: #1e293b;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-links {
                display: none;
            }

            table {
                font-size: 13px;
            }

            th,
            td {
                padding: 12px 16px;
            }
        }

        @media (max-width: 768px) {
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            .operation-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
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
                <li><a href="/admin/dashboards">Overview</a></li>
                <li><a href="/admin/StaffManagement">Staff</a></li>
                <li><a href="/admin/MemberManagement">Members</a></li>
                <li><a href="/admin/CommunityManagement" class="active">Community</a></li>
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

    <!-- Main Container -->
    <div class="container">
        <h1 class="page-title">Community Management</h1>

        @if (session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Search Section -->
        <div class="controls-section">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search community posts..."
                    onkeyup="debounceSearch()">
            </div>
        </div>

        <!-- Community Posts Table -->
        <div class="table-card">
            <table id="communityTable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Reports</th>
                        <th>Likes</th>
                        <th>Created</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($posts ?? [] as $post)
                        <tr data-post-id="{{ $post->postID ?? $post['postID'] }}">
                            <td>
                                <div
                                    style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $post->title ?? $post['title'] }}
                                </div>
                            </td>
                            <td>{{ $post->tourist_name ?? ($post['tourist_name'] ?? 'Unknown') }}</td>
                            <td>
                                @php
                                    $status = $post->status ?? ($post['status'] ?? 'active');
                                    $isHidden = $post->is_hidden ?? ($post['is_hidden'] ?? false);
                                @endphp
                                @if ($status === 'active' && !$isHidden)
                                    <span class="status-badge active">Active</span>
                                @elseif($isHidden)
                                    <span class="status-badge hidden">Hidden</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $reportCount = $post->report_count ?? ($post['report_count'] ?? 0);
                                @endphp
                                @if ($reportCount >= 5)
                                    <span class="report-badge">{{ $reportCount }} time(s)</span>
                                @elseif($reportCount > 0)
                                    <span class="report-badge low">{{ $reportCount }} time(s)</span>
                                @else
                                    <span class="report-badge none">0 time(s)</span>
                                @endif
                            </td>
                            <td>{{ $post->like_count ?? ($post['like_count'] ?? 0) }}</td>
                            <td>{{ isset($post->created_at) ? \Carbon\Carbon::parse($post->created_at)->format('M d, Y') : \Carbon\Carbon::parse($post['created_at'] ?? now())->format('M d, Y') }}
                            </td>
                            <td>
                                <div class="operation-buttons">
                                    <button class="btn btn-view"
                                        onclick="viewPost('{{ $post->postID ?? $post['postID'] }}')">View</button>
                                    <button class="btn btn-hide"
                                        onclick="toggleHide('{{ $post->postID ?? $post['postID'] }}', {{ $post->is_hidden ?? ($post['is_hidden'] ?? false) ? 'false' : 'true' }})">
                                        {{ $post->is_hidden ?? ($post['is_hidden'] ?? false) ? 'Unhide' : 'Hide' }}
                                    </button>
                                    <button class="btn btn-delete"
                                        onclick="deletePost('{{ $post->postID ?? $post['postID'] }}', '{{ addslashes($post->title ?? $post['title']) }}')">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                No community posts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination" id="paginationContainer">
                @if (isset($totalPages) && $totalPages > 1)
                    <button class="page-btn" onclick="changePage({{ $page - 1 }})"
                        {{ $page <= 1 ? 'disabled' : '' }}>
                        Previous
                    </button>

                    @for ($i = 1; $i <= $totalPages; $i++)
                        <button class="page-btn {{ $i == $page ? 'active' : '' }}"
                            onclick="changePage({{ $i }})">
                            {{ $i }}
                        </button>
                    @endfor

                    <button class="page-btn" onclick="changePage({{ $page + 1 }})"
                        {{ $page >= $totalPages ? 'disabled' : '' }}>
                        Next
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- View Post Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Post Details</h2>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody" class="loading">
                Loading...
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadPosts(1);
            }, 500);
        }

        function loadPosts(page = 1) {
            const search = document.getElementById('searchInput').value;

            fetch(`/admin/CommunityManagement?page=${page}&search=${encodeURIComponent(search)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateTable(data.posts);
                    updatePagination(data.currentPage, data.totalPages);
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                    alert('Failed to load posts');
                });
        }

        function updateTable(posts) {
            const tbody = document.getElementById('tableBody');

            if (posts.length === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">No community posts found</td></tr>';
                return;
            }

            tbody.innerHTML = posts.map(post => {
                const statusBadge = post.status === 'active' && !post.is_hidden ?
                    '<span class="status-badge active">Active</span>' :
                    post.is_hidden ?
                    '<span class="status-badge hidden">Hidden</span>' :
                    '<span class="status-badge inactive">Inactive</span>';

                const reportBadge = post.report_count >= 5 ?
                    `<span class="report-badge">${post.report_count} time(s)</span>` :
                    post.report_count > 0 ?
                    `<span class="report-badge low">${post.report_count} time(s)</span>` :
                    '<span class="report-badge none">0 time(s)</span>';

                const date = new Date(post.created_at).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });

                return `
          <tr data-post-id="${post.postID}">
            <td>
              <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                ${post.title}
              </div>
            </td>
            <td>${post.tourist_name || 'Unknown'}</td>
            <td>${statusBadge}</td>
            <td>${reportBadge}</td>
            <td>${post.like_count || 0}</td>
            <td>${date}</td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-view" onclick="viewPost('${post.postID}')">View</button>
                <button class="btn btn-hide" onclick="toggleHide('${post.postID}', ${!post.is_hidden})">
                  ${post.is_hidden ? 'Unhide' : 'Hide'}
                </button>
                <button class="btn btn-delete" onclick="deletePost('${post.postID}', '${post.title.replace(/'/g, "\\'")}')">Delete</button>
              </div>
            </td>
          </tr>
        `;
            }).join('');
        }

        function updatePagination(currentPage, totalPages) {
            const container = document.getElementById('paginationContainer');

            if (totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = `
        <button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage <= 1 ? 'disabled' : ''}>
          Previous
        </button>
      `;

            for (let i = 1; i <= totalPages; i++) {
                html += `
          <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
            ${i}
          </button>
        `;
            }

            html += `
        <button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''}>
          Next
        </button>
      `;

            container.innerHTML = html;
        }

        function changePage(page) {
            if (page < 1) return;
            loadPosts(page);
        }

        function viewPost(postId) {
            const modal = document.getElementById('viewModal');
            const modalBody = document.getElementById('modalBody');

            modal.classList.add('active');
            modalBody.innerHTML = '<div class="loading">Loading...</div>';

            fetch(`/admin/community/${postId}`)
                .then(response => response.json())
                .then(data => {
                    const post = data.post;
                    const reports = data.reports || [];

                    let html = `
            <div class="post-detail">
              <h3>Title</h3>
              <p>${post.title}</p>
            </div>
            <div class="post-detail">
              <h3>Description</h3>
              <p>${post.description || 'No description'}</p>
            </div>
            <div class="post-detail">
              <h3>Author</h3>
              <p>${post.tourist_name || 'Unknown'} (${post.tourist_email || 'N/A'})</p>
            </div>
            <div class="post-detail">
              <h3>Status</h3>
              <p>${post.status} ${post.is_hidden ? '(Hidden)' : ''}</p>
            </div>
            <div class="post-detail">
              <h3>Statistics</h3>
              <p>Likes: ${post.like_count || 0} | Comments: ${data.commentsCount || 0}</p>
            </div>
          `;

                    if (post.Images) {
                        try {
                            const images = JSON.parse(post.Images);
                            if (Array.isArray(images) && images.length > 0) {
                                html += '<div class="post-detail"><h3>Images</h3>';
                                images.forEach(img => {
                                    html += `<img src="${img}" class="post-image" alt="Post image">`;
                                });
                                html += '</div>';
                            }
                        } catch (e) {
                            console.error('Error parsing images:', e);
                        }
                    }

                    if (reports.length > 0) {
                        html += `
              <div class="reports-list">
                <h3>Reports (${reports.length})</h3>
                ${reports.map(report => `
                      <div class="report-item">
                        <strong>Reporter:</strong> ${report.reporter_name || 'Unknown'}<br>
                        <strong>Reason:</strong> ${report.reason}<br>
                        <strong>Details:</strong> ${report.details || 'No details provided'}<br>
                        <strong>Status:</strong> ${report.status}<br>
                        <strong>Date:</strong> ${new Date(report.created_at).toLocaleString()}
                      </div>
                    `).join('')}
              </div>
            `;
                    }

                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading post:', error);
                    modalBody.innerHTML =
                        '<div class="post-detail"><p style="color: #ef4444;">Failed to load post details</p></div>';
                });
        }

        function closeModal() {
            document.getElementById('viewModal').classList.remove('active');
        }

        function toggleHide(postId, shouldHide) {
            const status = shouldHide ? 'hidden' : 'active';

            fetch(`/admin/community/${postId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to update post status');
                    }
                })
                .catch(error => {
                    console.error('Error updating post:', error);
                    alert('Failed to update post status');
                });
        }

        function deletePost(postId, title) {
            if (!confirm(
                    `Are you sure you want to delete this post: "${title}"?\n\nThis will also delete all comments, interactions, and reports associated with it.`
                    )) {
                return;
            }

            fetch(`/admin/community/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Failed to delete post');
                    }
                })
                .catch(error => {
                    console.error('Error deleting post:', error);
                    alert('Failed to delete post');
                });
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ url('/logout') }}";
            }
        }

        // Close modal when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>

</html>
