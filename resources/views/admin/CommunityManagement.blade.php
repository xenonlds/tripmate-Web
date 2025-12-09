<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Community Management - TripMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
        .container { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
        .page-title { font-size: 32px; font-weight: 700; color: #1e293b; margin-bottom: 32px; }

        /* Tabs */
        .function-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
        }
        .tab-btn {
            padding: 12px 24px;
            background: none;
            border: none;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.2s;
        }
        .tab-btn:hover { color: #1e293b; }
        .tab-btn.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        /* Tab Content */
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        /* Controls */
        .controls-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .search-filter-group {
            display: flex;
            gap: 12px;
            flex: 1;
            max-width: 600px;
        }
        .search-box { flex: 1; max-width: 300px; min-width: 200px; }
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

        .filter-box select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            background: white;
            cursor: pointer;
            min-width: 150px;
        }

        .filter-box select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
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
        tbody tr:hover { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge.blocked { background: #fee2e2; color: #991b1b; }
        .badge.active { background: #dcfce7; color: #166534; }
        .badge.hidden { background: #fef3c7; color: #92400e; }
        .badge.warning { background: #fecaca; color: #b91c1c; }
        .badge.info { background: #dbeafe; color: #1e40af; }

        /* Buttons */
        .btn {
            padding: 6px 12px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn:hover { background: #f8fafc; }
        .btn-primary { background: #3b82f6; color: white; border-color: #3b82f6; }
        .btn-primary:hover { background: #2563eb; }
        .btn-danger { background: #ef4444; color: white; border-color: #ef4444; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: white; border-color: #f59e0b; }
        .btn-warning:hover { background: #d97706; }
        .btn-sm { padding: 4px 8px; font-size: 12px; }
        .btn-secondary { background: #64748b; color: white; border-color: #64748b; }
        .btn-secondary:hover { background: #475569; }

        /* Pagination */
        #paginationContainer {
            background: white;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        #paginationContainer button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        #paginationContainer button:disabled:hover {
            background: white;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
            padding: 20px;
        }
        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            padding: 24px;
            max-height: 90vh;
            overflow-y: auto;
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
            font-size: 20px;
            color: #1e293b;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-btn:hover { color: #1e293b; }

        /* Form */
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-group small {
            display: block;
            margin-top: 4px;
            color: #64748b;
            font-size: 12px;
        }

        /* Alert */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
        }
        .spinner {
            border: 3px solid #f1f5f9;
            border-top: 3px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Grid Layout */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .card-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }
        .card-body {
            padding: 16px 24px;
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .nav-links {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }
            .search-filter-group {
                flex-direction: column;
                max-width: 100%;
            }
            .search-box {
                max-width: 100%;
            }
            .filter-box select {
                width: 100%;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 12px 16px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
            .grid-2 {
                grid-template-columns: 1fr;
            }
            #paginationContainer {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
            #paginationContainer > div:first-child,
            #paginationContainer > div:last-child {
                display: none;
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
        <li><a href="/admin/MemberManagement">Members</a></li>
        <li><a href="/admin/CommunityManagement" class="active">Community</a></li>
        <li><a href="/admin/BusinessManagement">Business</a></li>
      </ul>

      <div class="user-section">
        <div class="user-info">
          <p>Admin User</p>
          <span>Administrator</span>
        </div>
        <button class="logout-btn" onclick="logout()">Logout</button>
      </div>
    </div>
  </header>

    <div class="container">
        <h1 class="page-title">Community Management</h1>

        <!-- Function Tabs -->
        <div class="function-tabs">
            <button class="tab-btn active" onclick="switchTab('content')">
                <i class="fas fa-edit"></i> Content CRUD
            </button>
            <button class="tab-btn" onclick="switchTab('blocking')">
                <i class="fas fa-ban"></i> User Blocking
            </button>
            <button class="tab-btn" onclick="switchTab('warnings')">
                <i class="fas fa-exclamation-triangle"></i> Warnings
            </button>
        </div>

        <!-- Tab 1: Content CRUD -->
        <div id="contentTab" class="tab-content active">
            <div class="controls-section">
                <div class="search-filter-group">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Search posts..." onkeyup="debounceSearch()">
                    </div>
                    <div class="filter-box">
                        <select id="statusFilter" onchange="loadPosts(1)">
                            <option value="">All Posts</option>
                            <option value="active">Active Only</option>
                            <option value="hidden">Hidden Only</option>
                        </select>
                    </div>
                    <div class="filter-box">
                        <select id="reportFilter" onchange="filterPosts()">
                            <option value="">All Reports</option>
                            <option value="0">No Reports</option>
                            <option value="1">Has Reports</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 12px;">
                    <button class="btn btn-primary" onclick="createPost()">
                        <i class="fas fa-plus"></i> Create Post
                    </button>
                    <button class="btn btn-secondary" onclick="refreshPosts()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Reports</th>
                            <th>Warnings</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody">
                        <!-- Data will be loaded via JavaScript -->
                        <tr>
                            <td colspan="6">
                                <div class="loading">
                                    <div class="spinner"></div>
                                    <p>Loading posts...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Controls -->
            <div id="paginationContainer" style="display: none; margin-top: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                <div style="font-size: 12px; color: #94a3b8;">
                    <i class="fas fa-keyboard"></i> Use arrow keys or P/N to navigate
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <button class="btn btn-sm" id="prevBtn" onclick="changePage('prev')" disabled>
                        <i class="fas fa-chevron-left"></i> Previous
                    </button>
                    <div id="pageInfo" style="font-size: 14px; color: #64748b; padding: 0 16px;">
                        Page <strong>1</strong> of <strong>1</strong>
                    </div>
                    <button class="btn btn-sm" id="nextBtn" onclick="changePage('next')" disabled>
                        Next <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div style="font-size: 12px; color: #94a3b8;" id="pageStats">
                    Showing posts 1-10
                </div>
            </div>
        </div>

        <!-- Tab 2: User Blocking -->
        <div id="blockingTab" class="tab-content">
            <div class="controls-section">
                <h2 style="font-size: 20px; color: #1e293b;">Blocked Users</h2>
                <button class="btn btn-primary" onclick="loadBlockedUsers()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Reason</th>
                            <th>Blocked Date</th>
                            <th>Expires</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="blockedUsersTableBody">
                        <tr>
                            <td><strong>Jane Smith</strong></td>
                            <td>jane.smith@example.com</td>
                            <td>Inappropriate content</td>
                            <td>12/1/2024</td>
                            <td><span class="badge blocked">Permanent</span></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="confirmUnblock('user2')">
                                    <i class="fas fa-unlock"></i> Unblock
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab 3: Warnings -->
        <div id="warningsTab" class="tab-content">
            <div class="controls-section">
                <h2 style="font-size: 20px; color: #1e293b;">Warning System</h2>
                <button class="btn btn-primary" onclick="loadWarningData()">
                    <i class="fas fa-sync"></i> Refresh
                </button>
            </div>

            <div class="grid-2">
                <!-- Recent Warnings -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Warnings</h3>
                    </div>
                    <div class="card-body" id="recentWarningsContent">
                        <div style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="color: #1e293b;">Beach Resort Review</strong>
                                <span class="badge warning">2 warnings</span>
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                By: Jane Smith
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users with Most Warnings -->
                <div class="card">
                    <div class="card-header">
                        <h3>Users at Risk</h3>
                    </div>
                    <div class="card-body" id="usersAtRiskContent">
                        <div style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="color: #1e293b;">Jane Smith</strong>
                                <span class="badge warning">Medium Risk</span>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                                Total warnings: 2
                                <span class="badge blocked" style="margin-left: 8px;">Blocked</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2>Edit Post</h2>
                <button class="close-btn" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editPostForm">
                <div id="editAlertContainer"></div>

                <!-- Post Preview -->
                <div id="editPostPreview" class="card" style="margin-bottom: 16px; display: none;">
                    <div class="card-header">
                        <h3><i class="fas fa-eye"></i> Current Post Preview</h3>
                    </div>
                    <div class="card-body">
                        <div id="editPreviewImages" style="margin-bottom: 12px;"></div>
                        <div style="font-size: 12px; color: #64748b;">
                            <strong>Author:</strong> <span id="editPreviewAuthor"></span><br>
                            <strong>Created:</strong> <span id="editPreviewDate"></span><br>
                            <strong>Likes:</strong> <span id="editPreviewLikes"></span> |
                            <strong>Status:</strong> <span id="editPreviewStatus"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" id="editTitle" required maxlength="100" placeholder="Enter post title">
                    <small><span id="titleCharCount">0</span>/100 characters</small>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea id="editDescription" maxlength="255" rows="4" placeholder="Enter post description (optional)"></textarea>
                    <small><span id="descCharCount">0</span>/255 characters</small>
                </div>

                <div class="alert" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; margin-bottom: 16px;">
                    <i class="fas fa-info-circle"></i>
                    <span>Note: Images cannot be edited. To change images, please create a new post.</span>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')" style="flex: 1;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Issue Warning Modal -->
    <div id="warningModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Issue Warning</h2>
                <button class="close-btn" onclick="closeModal('warningModal')">&times;</button>
            </div>
            <form id="warningForm" onsubmit="handleWarning(event)">
                <input type="hidden" id="warningPostId">
                <input type="hidden" id="warningTouristId">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Warning will be issued to the post author.</span>
                </div>
                <div class="form-group">
                    <label>Warning Type</label>
                    <select id="warningType" required>
                        <option value="minor">Minor (1st offense)</option>
                        <option value="moderate">Moderate (2nd offense - Post hidden)</option>
                        <option value="severe">Severe (3rd offense - User blocked)</option>
                    </select>
                    <small>Warning escalation is automatic based on user's history</small>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <select id="warningReason" required>
                        <option value="">Select a reason...</option>
                        <option value="Inappropriate content">Inappropriate content</option>
                        <option value="Spam">Spam</option>
                        <option value="Harassment">Harassment</option>
                        <option value="Hate speech">Hate speech</option>
                        <option value="False information">False information</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Details</label>
                    <textarea id="warningDetails" rows="3" placeholder="Additional details about the violation..."></textarea>
                </div>
                <button type="submit" class="btn btn-warning" style="width: 100%;">
                    <i class="fas fa-exclamation-triangle"></i> Issue Warning
                </button>
            </form>
        </div>
    </div>

    <!-- Block User Modal -->
    <div id="blockModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Block User</h2>
                <button class="close-btn" onclick="closeModal('blockModal')">&times;</button>
            </div>
            <form id="blockForm" onsubmit="handleBlockUser(event)">
                <input type="hidden" id="blockTouristId">
                <div class="alert alert-error">
                    <i class="fas fa-ban"></i>
                    <span>This will prevent the user from posting and hide all their content.</span>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" id="blockReason" required placeholder="Why are you blocking this user?">
                </div>
                <div class="form-group">
                    <label>Details (Optional)</label>
                    <textarea id="blockDetails" rows="2" placeholder="Additional context..."></textarea>
                </div>
                <div class="form-group">
                    <label>Duration (days)</label>
                    <input type="number" id="blockDuration" min="1" placeholder="Leave empty for permanent">
                    <small>Leave empty for permanent block</small>
                </div>
                <button type="submit" class="btn btn-danger" style="width: 100%;">
                    <i class="fas fa-ban"></i> Block User
                </button>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentEditPostId = null;
        let currentPage = 1;
        let totalPages = 1;

        // ============================================
        // TAB SWITCHING
        // ============================================
        function switchTab(tab) {
            // Remove active from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active to clicked tab
            event.target.closest('.tab-btn').classList.add('active');
            document.getElementById(tab + 'Tab').classList.add('active');

            // Load data for specific tabs
            if (tab === 'content') {
                loadPosts();
            } else if (tab === 'blocking') {
                loadBlockedUsers();
            } else if (tab === 'warnings') {
                loadWarningData();
            }
        }

        // ============================================
        // CONTENT CRUD - LOAD POSTS
        // ============================================
        function createPost() {
            window.location.href = '/admin/community/create';
        }

        function loadPosts(page = 1) {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const tbody = document.getElementById('postsTableBody');

            tbody.innerHTML = '<tr><td colspan="6"><div class="loading"><div class="spinner"></div><p>Loading posts...</p></div></td></tr>';

            const startTime = performance.now(); // Track loading time

            fetch(`/admin/CommunityManagement?page=${page}&search=${encodeURIComponent(search)}&status=${status}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.json();
            })
            .then(data => {
                const endTime = performance.now();
                console.log(`✅ Posts loaded in ${(endTime - startTime).toFixed(2)}ms`);
                console.log(`📊 Total posts fetched: ${data.posts?.length || 0}`);
                console.log(`📄 Page ${data.currentPage || 1} of ${data.totalPages || 1}`);

                // Update pagination state - IMPORTANT: Parse as integers
                currentPage = parseInt(data.currentPage) || 1;
                totalPages = parseInt(data.totalPages) || 1;
                updatePaginationControls();

                // Count hidden vs active posts
                if (data.posts && data.posts.length > 0) {
                    const hiddenCount = data.posts.filter(p => p.is_hidden || p.hidden_by_admin).length;
                    const activeCount = data.posts.length - hiddenCount;
                    console.log(`   ├─ Active: ${activeCount}`);
                    console.log(`   ├─ Hidden: ${hiddenCount}`);
                    console.log(`   └─ Posts with warnings: ${data.posts.filter(p => p.warning_count > 0).length}`);
                }

                if (!data.posts || data.posts.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No posts found</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = data.posts.map(post => {
                    // Show both active and hidden posts with appropriate badges
                    let statusBadge;
                    if (post.hidden_by_admin) {
                        statusBadge = '<span class="badge blocked">Hidden by Admin</span>';
                    } else if (post.is_hidden) {
                        statusBadge = '<span class="badge hidden">Hidden by User</span>';
                    } else {
                        statusBadge = '<span class="badge active">Active</span>';
                    }

                    const blockedBadge = post.is_blocked
                        ? '<span class="badge blocked">Blocked</span>'
                        : '';

                    // Special badge for admin posts
                    const isAdminPost = post.tourist_name === 'TripMate Admin';
                    const adminBadge = isAdminPost
                        ? '<span class="badge info" style="margin-left: 4px;">ADMIN</span>'
                        : '';

                    return `
                        <tr>
                            <td>
                                <strong>${escapeHtml(post.title)}</strong>
                                ${post.description ? '<br><small style="color: #64748b;">' + escapeHtml(post.description.substring(0, 60)) + '...</small>' : ''}
                            </td>
                            <td>
                                ${escapeHtml(post.tourist_name)}
                                ${adminBadge}
                                ${blockedBadge}
                            </td>
                            <td>${statusBadge}</td>
                            <td><span class="badge info">${post.report_count} reports</span></td>
                            <td><span class="badge warning">${post.warning_count} warnings</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-primary" onclick="window.open('/admin/community/${post.postID}', '_blank')" title="View full post details">
                                        <i class="fas fa-eye"></i> Details
                                    </button>
                                    <button class="btn btn-sm" onclick='editPost(${JSON.stringify(post).replace(/'/g, "&#39;")})' title="Edit post">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    ${post.hidden_by_admin ? `
                                    <button class="btn btn-sm btn-warning" onclick="togglePostVisibility('${post.postID}', false)" title="Unhide post (Admin)">
                                        <i class="fas fa-eye"></i> Unhide
                                    </button>
                                    ` : `
                                    <button class="btn btn-sm btn-secondary" onclick="togglePostVisibility('${post.postID}', true)" title="Hide post (Admin)">
                                        <i class="fas fa-eye-slash"></i> Hide
                                    </button>
                                    `}
                                    ${post.is_hidden ? '<span class="badge" style="font-size: 10px; background: #94a3b8;">User Private</span>' : ''}
                                    ${!isAdminPost ? `
                                    <button class="btn btn-warning btn-sm" onclick="showWarningModal('${post.postID}', '${post.tourist_id}')">
                                        <i class="fas fa-exclamation-triangle"></i> Warn
                                    </button>
                                    ` : ''}
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            })
            .catch(err => {
                console.error('Error loading posts:', err);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Error loading posts. Please try again.</span>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        // ============================================
        // EDIT POST
        // ============================================
        function editPost(post) {
            currentEditPostId = post.postID;

            // Show modal first
            document.getElementById('editModal').classList.add('active');

            const titleInput = document.getElementById('editTitle');
            const descInput = document.getElementById('editDescription');

            // Set values
            titleInput.value = post.title || '';
            descInput.value = post.description || '';

            // Update character counts immediately
            updateCharCountDisplay('titleCharCount', post.title || '', 100);
            updateCharCountDisplay('descCharCount', post.description || '', 255);

            // Show preview if images exist
            const previewContainer = document.getElementById('editPostPreview');
            const imagesContainer = document.getElementById('editPreviewImages');

            if (previewContainer && imagesContainer && post.Images) {
                try {
                    const images = JSON.parse(post.Images);
                    if (images && images.length > 0) {
                        imagesContainer.innerHTML = images.map(img =>
                            `<img src="${img}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 8px; border: 2px solid #e2e8f0;" onerror="this.style.display='none'">`
                        ).join('');
                        previewContainer.style.display = 'block';
                    } else {
                        previewContainer.style.display = 'none';
                    }
                } catch (e) {
                    if (previewContainer) previewContainer.style.display = 'none';
                }
            } else if (previewContainer) {
                previewContainer.style.display = 'none';
            }

            // Update preview info with null checks
            const previewAuthor = document.getElementById('editPreviewAuthor');
            const previewDate = document.getElementById('editPreviewDate');
            const previewLikes = document.getElementById('editPreviewLikes');
            const previewStatus = document.getElementById('editPreviewStatus');

            if (previewAuthor) previewAuthor.textContent = post.tourist_name || 'Unknown';
            if (previewDate) previewDate.textContent = post.created_at ? new Date(post.created_at).toLocaleDateString() : 'Unknown';
            if (previewLikes) previewLikes.textContent = post.like_count || 0;
            if (previewStatus) {
                if (post.hidden_by_admin) {
                    previewStatus.innerHTML = '<span class="badge blocked">Hidden by Admin</span>';
                } else if (post.is_hidden) {
                    previewStatus.innerHTML = '<span class="badge hidden">Hidden by User</span>';
                } else {
                    previewStatus.innerHTML = '<span class="badge active">Active</span>';
                }
            }

            // Clear any previous alerts
            const alertContainer = document.getElementById('editAlertContainer');
            if (alertContainer) alertContainer.innerHTML = '';
        }

        // Initialize character count listeners (call once on page load)
        function initCharCountListeners() {
            const titleInput = document.getElementById('editTitle');
            const descInput = document.getElementById('editDescription');

            if (titleInput) {
                titleInput.addEventListener('input', function() {
                    updateCharCountDisplay('titleCharCount', this.value, 100);
                });
            }

            if (descInput) {
                descInput.addEventListener('input', function() {
                    updateCharCountDisplay('descCharCount', this.value, 255);
                });
            }
        }

        // Update character count display
        function updateCharCountDisplay(countId, value, maxLength) {
            const countEl = document.getElementById(countId);
            if (!countEl) return;

            const length = value.length;
            countEl.textContent = length;

            // Change color based on usage
            if (length > maxLength * 0.9) {
                countEl.style.color = '#dc2626';
            } else if (length > maxLength * 0.7) {
                countEl.style.color = '#f59e0b';
            } else {
                countEl.style.color = '#64748b';
            }
        }

        document.getElementById('editPostForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const alertContainer = document.getElementById('editAlertContainer');
            const titleInput = document.getElementById('editTitle');
            const descInput = document.getElementById('editDescription');

            // Client-side validation
            if (!titleInput.value.trim()) {
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Title is required</span>
                    </div>
                `;
                titleInput.focus();
                return;
            }

            if (titleInput.value.trim().length < 3) {
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Title must be at least 3 characters long</span>
                    </div>
                `;
                titleInput.focus();
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            alertContainer.innerHTML = '';

            try {
                const response = await fetch(`/admin/community/${currentEditPostId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: titleInput.value.trim(),
                        description: descInput.value.trim()
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    alertContainer.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <span>Post updated successfully!</span>
                        </div>
                    `;

                    // Wait a moment to show success message
                    setTimeout(() => {
                        closeModal('editModal');
                        loadPosts(currentPage);
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to update post');
                }
            } catch (error) {
                console.error('Error updating post:', error);
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>${error.message || 'Failed to update post. Please try again.'}</span>
                    </div>
                `;
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
            }
        });

        // ============================================
        // ISSUE WARNING
        // ============================================
        function showWarningModal(postId, touristId) {
            document.getElementById('warningPostId').value = postId;
            document.getElementById('warningTouristId').value = touristId;
            document.getElementById('warningModal').classList.add('active');
        }

        document.getElementById('warningForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Issuing...';

            try {
                const response = await fetch('/admin/community/warning/issue', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        post_id: document.getElementById('warningPostId').value,
                        warning_type: document.getElementById('warningType').value,
                        reason: document.getElementById('warningReason').value,
                        details: document.getElementById('warningDetails').value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    closeModal('warningModal');
                    loadPosts();
                    // Reset form
                    document.getElementById('warningForm').reset();
                } else {
                    alert('Error: ' + (data.message || 'Failed to issue warning'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to issue warning. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Issue Warning';
            }
        });

        // ============================================
        // DELETE POST
        // ============================================
        function togglePostVisibility(postId, shouldHide) {
            const action = shouldHide ? 'hide' : 'unhide';
            if (!confirm(`Are you sure you want to ${action} this post?`)) return;

            const status = shouldHide ? 'hidden' : 'active';

            fetch(`/admin/community/${postId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ status: status })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert(`Post ${action}d successfully!`);
                    loadPosts();
                } else {
                    alert('Error: ' + (data.message || `Failed to ${action} post`));
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert(`Failed to ${action} post. Please try again.`);
            });
        }

        // ============================================
        // BLOCK USER
        // ============================================
        function blockUserModal(touristId) {
            document.getElementById('blockTouristId').value = touristId;
            document.getElementById('blockModal').classList.add('active');
        }

        document.getElementById('blockForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Blocking...';

            try {
                const response = await fetch('/admin/community/user/block', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        tourist_id: document.getElementById('blockTouristId').value,
                        reason: document.getElementById('blockReason').value,
                        details: document.getElementById('blockDetails').value,
                        duration_days: document.getElementById('blockDuration').value || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    closeModal('blockModal');
                    loadPosts();
                    // Reset form
                    document.getElementById('blockForm').reset();
                } else {
                    alert('Error: ' + (data.message || 'Failed to block user'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to block user. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-ban"></i> Block User';
            }
        });

        // ============================================
        // LOAD BLOCKED USERS
        // ============================================
        function loadBlockedUsers() {
            const tbody = document.getElementById('blockedUsersTableBody');
            tbody.innerHTML = '<tr><td colspan="6"><div class="loading"><div class="spinner"></div><p>Loading blocked users...</p></div></td></tr>';

            fetch('/admin/community/users/blocked')
            .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.json();
            })
            .then(data => {
                if (!data.blocked_users || data.blocked_users.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-user-check"></i>
                                    <p>No blocked users</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    return;
                }

                tbody.innerHTML = data.blocked_users.map(user => {
                    const blockedDate = new Date(user.blocked_at).toLocaleDateString();
                    const expiresText = user.expires_at
                        ? new Date(user.expires_at).toLocaleDateString()
                        : 'Permanent';

                    return `
                        <tr>
                            <td><strong>${escapeHtml(user.tourist_name)}</strong></td>
                            <td>${escapeHtml(user.tourist_email)}</td>
                            <td>${escapeHtml(user.reason)}</td>
                            <td>${blockedDate}</td>
                            <td>
                                ${user.expires_at
                                    ? '<span class="badge warning">' + expiresText + '</span>'
                                    : '<span class="badge blocked">Permanent</span>'}
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="confirmUnblock('${user.tourist_id}')">
                                    <i class="fas fa-unlock"></i> Unblock
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            })
            .catch(err => {
                console.error('Error loading blocked users:', err);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6">
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Error loading blocked users. Please try again.</span>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        // ============================================
        // UNBLOCK USER
        // ============================================
        function confirmUnblock(touristId) {
            if (!confirm('Are you sure you want to unblock this user?')) {
                return;
            }
            unblockUser(touristId);
        }

        async function unblockUser(touristId) {
            try {
                const response = await fetch('/admin/community/user/unblock', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ tourist_id: touristId })
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    loadBlockedUsers();
                } else {
                    alert('Error: ' + (data.message || 'Failed to unblock user'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to unblock user. Please try again.');
            }
        }

        // ============================================
        // LOAD WARNING DATA
        // ============================================
        function loadWarningData() {
            loadRecentWarnings();
            loadUsersAtRisk();
        }

        // Helper function to fetch all posts across all pages
        async function fetchAllPosts() {
            try {
                // First, get page 1 to know total pages
                const firstResponse = await fetch('/admin/CommunityManagement?page=1', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const firstData = await firstResponse.json();

                let allPosts = [...firstData.posts];
                const totalPages = firstData.totalPages || 1;

                // If there are more pages, fetch them all
                if (totalPages > 1) {
                    const pagePromises = [];
                    for (let page = 2; page <= totalPages; page++) {
                        pagePromises.push(
                            fetch(`/admin/CommunityManagement?page=${page}`, {
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            }).then(r => r.json())
                        );
                    }

                    const additionalPages = await Promise.all(pagePromises);
                    additionalPages.forEach(pageData => {
                        if (pageData.posts) {
                            allPosts = [...allPosts, ...pageData.posts];
                        }
                    });
                }

                return allPosts;
            } catch (error) {
                console.error('Error fetching all posts:', error);
                return [];
            }
        }

        function loadRecentWarnings() {
            const container = document.getElementById('recentWarningsContent');
            container.innerHTML = '<div class="loading"><div class="spinner"></div><p>Loading warnings...</p></div>';

            // Fetch ALL posts to show all warnings (not just page 1)
            // We need to make multiple requests to get all pages
            fetchAllPosts().then(allPosts => {
                // Get posts with warnings
                const postsWithWarnings = allPosts
                    .filter(post => post.warning_count > 0)
                    .sort((a, b) => b.warning_count - a.warning_count)
                    .slice(0, 5);

                if (postsWithWarnings.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <p>No recent warnings</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = postsWithWarnings.map(post => `
                    <div style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <strong style="color: #1e293b;">${escapeHtml(post.title)}</strong>
                            <span class="badge warning">${post.warning_count} warnings</span>
                        </div>
                        <div style="font-size: 12px; color: #64748b;">
                            By: ${escapeHtml(post.tourist_name)}
                        </div>
                    </div>
                `).join('');
            })
            .catch(err => {
                console.error('Error:', err);
                container.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Error loading warnings</span>
                    </div>
                `;
            });
        }

        function loadUsersAtRisk() {
            const container = document.getElementById('usersAtRiskContent');
            container.innerHTML = '<div class="loading"><div class="spinner"></div><p>Loading data...</p></div>';

            // Fetch ALL posts to aggregate warnings correctly
            fetchAllPosts().then(allPosts => {
                // Aggregate warnings by user
                const userWarnings = {};
                allPosts.forEach(post => {
                    if (post.warning_count > 0) {
                        if (!userWarnings[post.tourist_id]) {
                            userWarnings[post.tourist_id] = {
                                name: post.tourist_name,
                                tourist_id: post.tourist_id,
                                warnings: 0,
                                is_blocked: post.is_blocked
                            };
                        }
                        userWarnings[post.tourist_id].warnings += post.warning_count;
                    }
                });

                const usersArray = Object.values(userWarnings)
                    .sort((a, b) => b.warnings - a.warnings)
                    .slice(0, 5);

                if (usersArray.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-user-check"></i>
                            <p>No users at risk</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = usersArray.map(user => {
                    let riskLevel = 'info';
                    let riskText = 'Low Risk';
                    if (user.warnings >= 3) {
                        riskLevel = 'blocked';
                        riskText = 'High Risk';
                    } else if (user.warnings >= 2) {
                        riskLevel = 'warning';
                        riskText = 'Medium Risk';
                    }

                    return `
                        <div style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <strong style="color: #1e293b;">${escapeHtml(user.name)}</strong>
                                <span class="badge ${riskLevel}">${riskText}</span>
                            </div>
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">
                                Total warnings: ${user.warnings}
                                ${user.is_blocked ? '<span class="badge blocked" style="margin-left: 8px;">Blocked</span>' : ''}
                            </div>
                            ${!user.is_blocked ? `
                                <button class="btn btn-danger btn-sm" onclick="blockUserModal('${user.tourist_id}')">
                                    <i class="fas fa-ban"></i> Block User
                                </button>
                            ` : ''}
                        </div>
                    `;
                }).join('');
            })
            .catch(err => {
                console.error('Error:', err);
                container.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Error loading data</span>
                    </div>
                `;
            });
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function refreshPosts() {
            loadPosts(1); // Reset to page 1 when refreshing
        }

        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadPosts(1), 500); // Reset to page 1 when searching
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
                '`': '&#x60;'
            };
            return text.replace(/[&<>"'`]/g, m => map[m]);
        }

        // ============================================
        // PAGINATION FUNCTIONS
        // ============================================
        function changePage(direction) {
            console.log(`🔄 ChangePage called: direction=${direction}, currentPage=${currentPage} (type: ${typeof currentPage})`);
            if (direction === 'prev' && currentPage > 1) {
                const nextPage = parseInt(currentPage) - 1;
                console.log(`⬅️ Going to previous page: ${nextPage}`);
                loadPosts(nextPage);
                scrollToTop();
            } else if (direction === 'next' && currentPage < totalPages) {
                const nextPage = parseInt(currentPage) + 1;
                console.log(`➡️ Going to next page: ${nextPage}`);
                loadPosts(nextPage);
                scrollToTop();
            }
        }

        function scrollToTop() {
            // Smooth scroll to the table
            const table = document.querySelector('.table-card');
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function updatePaginationControls() {
            const container = document.getElementById('paginationContainer');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const pageInfo = document.getElementById('pageInfo');
            const pageStats = document.getElementById('pageStats');

            // Show/hide pagination
            if (totalPages > 1) {
                container.style.display = 'flex';
            } else {
                container.style.display = 'none';
                return;
            }

            // Update page info
            pageInfo.innerHTML = `Page <strong>${currentPage}</strong> of <strong>${totalPages}</strong>`;

            // Update stats (assuming 10 posts per page)
            const postsPerPage = 10;
            const startPost = ((currentPage - 1) * postsPerPage) + 1;
            const endPost = Math.min(currentPage * postsPerPage, totalPages * postsPerPage);
            pageStats.innerHTML = `Showing posts ${startPost}-${endPost}`;

            // Enable/disable buttons
            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;

            // Update button styles
            if (prevBtn.disabled) {
                prevBtn.style.opacity = '0.5';
                prevBtn.style.cursor = 'not-allowed';
            } else {
                prevBtn.style.opacity = '1';
                prevBtn.style.cursor = 'pointer';
            }

            if (nextBtn.disabled) {
                nextBtn.style.opacity = '0.5';
                nextBtn.style.cursor = 'not-allowed';
            } else {
                nextBtn.style.opacity = '1';
                nextBtn.style.cursor = 'pointer';
            }
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // Keyboard shortcuts for pagination
        document.addEventListener('keydown', function(e) {
            // Only work when no modal is open and not typing in inputs
            if (document.querySelector('.modal.active') ||
                document.activeElement.tagName === 'INPUT' ||
                document.activeElement.tagName === 'TEXTAREA') {
                return;
            }

            // Left arrow or 'p' for previous page
            if ((e.key === 'ArrowLeft' || e.key === 'p') && currentPage > 1) {
                changePage('prev');
            }
            // Right arrow or 'n' for next page
            else if ((e.key === 'ArrowRight' || e.key === 'n') && currentPage < totalPages) {
                changePage('next');
            }
        });

        // Load initial data
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();

            // Initialize character count listeners for edit modal
            initCharCountListeners();
        });

        // ============================================
        // LOGOUT FUNCTION
        // ============================================
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ url('/logout') }}";
            }
        }
    </script>
</body>
</html>
