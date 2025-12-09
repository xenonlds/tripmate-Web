<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Business Management - TripMate</title>
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

    /* Alert Messages */
    .alert {
      max-width: 1400px;
      margin: 24px auto;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
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

    .filter-box select {
      padding: 10px 16px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      background: white;
      cursor: pointer;
    }

    .filter-box select:focus {
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

    /* Status Badges */
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      text-transform: capitalize;
    }

    .status-pending, .status-Pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-approved, .status-Approved, .status-active {
      background: #dcfce7;
      color: #166534;
    }

    .status-declined, .status-Declined {
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
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
    }

    .btn-details {
      color: #3b82f6;
      border-color: #3b82f6;
    }

    .btn-details:hover {
      background: #dbeafe;
    }

    .btn-approve {
      color: #10b981;
      border-color: #10b981;
    }

    .btn-approve:hover {
      background: #d1fae5;
    }

    .btn-decline {
      color: #ef4444;
      border-color: #ef4444;
    }

    .btn-decline:hover {
      background: #fee2e2;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 24px;
      color: #64748b;
    }

    .empty-state i {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.5;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .nav-links {
        display: none;
      }

      table {
        font-size: 13px;
      }

      th, td {
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
        <li><a href="/dashboards">Overview</a></li>
        <li><a href="/admin/MemberManagement">Members</a></li>
        <li><a href="/admin/CommunityManagement">Community</a></li>
        <li><a href="/admin/BusinessManagement" class="active">Business</a></li>
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

  <!-- Alert Messages -->
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

  <!-- Main Container -->
  <div class="container">
    <h1 class="page-title">Business Management</h1>

    <!-- Search and Filter -->
    <div class="controls-section">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search by name or location..." onkeyup="filterTable()">
      </div>
      <div class="filter-box">
        <select id="statusFilter" onchange="filterTable()">
          <option value="">All Status</option>
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Declined">Declined</option>
        </select>
      </div>
    </div>

    <!-- Business Table -->
    <div class="table-card">
      <table id="businessTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Location</th>
            <th>Status</th>
            <th>Operation</th>
          </tr>
        </thead>
        <tbody>
          @forelse($businesses as $business)
          <tr>
            <td>{{ $business['business_name'] }}</td>
            <td>{{ $business['type'] }}</td>
            <td>{{ $business['location'] }}</td>
            <td>
              <span class="status-badge status-{{ $business['status'] }}">
                {{ $business['status'] }}
              </span>
            </td>
            <td>
              <div class="operation-buttons">
                <a href="{{ route('admin.business.details', $business['owner_id']) }}" class="btn btn-details">
                  Details
                </a>
                <form action="{{ route('admin.business.approve', $business['owner_id']) }}" method="POST" style="display: inline;">
                  @csrf
                  <button type="submit" class="btn btn-approve" onclick="return confirm('Are you sure you want to approve this business?')">
                    Approve
                  </button>
                </form>
                <form action="{{ route('admin.business.decline', $business['owner_id']) }}" method="POST" style="display: inline;">
                  @csrf
                  <button type="submit" class="btn btn-decline" onclick="return confirm('Are you sure you want to decline this business?')">
                    Decline
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="empty-state">
                <i class="fas fa-building"></i>
                <p>No business applications found</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function filterTable() {
      const searchInput = document.getElementById('searchInput').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value;
      const table = document.getElementById('businessTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let row of rows) {
        // Skip empty state row
        if (row.cells.length === 1) continue;

        const name = row.cells[0].textContent.toLowerCase();
        const location = row.cells[2].textContent.toLowerCase();
        const status = row.cells[3].textContent.trim();

        const matchesSearch = name.includes(searchInput) || location.includes(searchInput);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
      }
    }

    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = "{{ url('/logout') }}";
      }
    }
  </script>
</body>
</html>