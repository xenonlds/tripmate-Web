<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Staff Management - TripMate</title>
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

    .add-staff-btn {
      background: #3b82f6;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: background 0.2s;
    }

    .add-staff-btn:hover {
      background: #2563eb;
    }

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

    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 12px;
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

    .btn-details {
      color: #3b82f6;
      border-color: #3b82f6;
    }

    .btn-details:hover {
      background: #dbeafe;
    }

    .btn-update {
      color: #10b981;
      border-color: #10b981;
    }

    .btn-update:hover {
      background: #d1fae5;
    }

    .btn-toggle {
      color: #f59e0b;
      border-color: #f59e0b;
    }

    .btn-toggle:hover {
      background: #fef3c7;
    }

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
        <li><a href="/admin/StaffManagement" class="active">Staff</a></li>
        <li><a href="/admin/MemberManagement">Members</a></li>
        <li><a href="/admin/CommunityManagement">Community</a></li>
        <li><a href="/admin/BusinessManagement">Business</a></li>
      </ul>

      <div class="user-section">
        <div class="user-info">
          <p>{{ session('name', 'Admin') }}</p>
          <span>Administrator</span>
        </div>
        <button class="logout-btn" onclick="logout()">Logout</button>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="page-header">
      <h1 class="page-title">Staff Management</h1>
      <button class="add-staff-btn" onclick="addStaff()">
        <i class="fas fa-plus"></i> Add Staff
      </button>
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

    <div class="controls-section">
      <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search by name or email..." onkeyup="filterTable()">
      </div>
      <div class="filter-box">
        <select id="statusFilter" onchange="filterTable()">
          <option value="">All Status</option>
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
      </div>
    </div>

    <div class="table-card">
      <table id="staffTable">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Last Login</th>
            <th>Operation</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staffUsers as $staff)
          <tr>
            <td>{{ $staff['user_id'] }}</td>
            <td>{{ $staff['name'] }}</td>
            <td>{{ $staff['email'] }}</td>
            <td>
              <span class="status-badge status-{{ $staff['status'] }}">
                {{ ucfirst($staff['status']) }}
              </span>
            </td>
            <td>{{ $staff['last_login'] ? date('Y-m-d H:i', strtotime($staff['last_login'])) : 'Never' }}</td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('{{ $staff['user_id'] }}')">
                  Details
                </button>
                <button class="btn btn-update" onclick="updateStaff('{{ $staff['user_id'] }}')">
                  Update
                </button>
                <button class="btn btn-toggle" onclick="toggleStatus('{{ $staff['user_id'] }}', '{{ $staff['status'] }}')">
                  {{ $staff['status'] === 'active' ? 'Deactivate' : 'Activate' }}
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
              No staff users found
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>

      @if($totalPages > 1)
      <div class="pagination">
        <button class="page-btn" {{ $page <= 1 ? 'disabled' : '' }}
                onclick="window.location.href='?page={{ $page - 1 }}'">
          Previous
        </button>

        @for($i = 1; $i <= $totalPages; $i++)
          <button class="page-btn {{ $i == $page ? 'active' : '' }}"
                  onclick="window.location.href='?page={{ $i }}'">
            {{ $i }}
          </button>
        @endfor

        <button class="page-btn" {{ $page >= $totalPages ? 'disabled' : '' }}
                onclick="window.location.href='?page={{ $page + 1 }}'">
          Next
        </button>
      </div>
      @endif
    </div>
  </div>

  <script>
    function filterTable() {
      const searchInput = document.getElementById('searchInput').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
      const table = document.getElementById('staffTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let row of rows) {
        if (row.cells.length < 6) continue; // Skip empty state row

        const name = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        const status = row.cells[3].textContent.toLowerCase();

        const matchesSearch = name.includes(searchInput) || email.includes(searchInput);
        const matchesStatus = statusFilter === '' || status.includes(statusFilter);

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
      }
    }

    function viewDetails(userId) {
      window.location.href = `/admin/staff/${userId}`;
    }

    function addStaff() {
      window.location.href = '{{ route("admin.staff.create") }}';
    }

    function updateStaff(userId) {
      window.location.href = `/admin/staff/${userId}/edit`;
    }

    function toggleStatus(userId, currentStatus) {
      const action = currentStatus === 'active' ? 'deactivate' : 'activate';
      if (confirm(`Are you sure you want to ${action} this staff member?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/staff/${userId}/toggle-status`;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;

        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
      }
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
