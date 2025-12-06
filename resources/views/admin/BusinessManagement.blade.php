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
    }

    .status-pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-approved {
      background: #dcfce7;
      color: #166534;
    }

    .status-declined {
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

    /* Pagination */
    .pagination {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 24px;
    }

    .page-info {
      font-size: 14px;
      color: #64748b;
    }

    .page-buttons {
      display: flex;
      gap: 8px;
    }

    .page-btn {
      padding: 8px 16px;
      border: 1px solid #e2e8f0;
      background: white;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s;
      font-family: 'Inter', sans-serif;
      font-weight: 500;
    }

    .page-btn:hover {
      background: #f8fafc;
    }

    .page-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
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

      .pagination {
        flex-direction: column;
        gap: 16px;
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
                <li><a href="/dashboards" class="active">Overview</a></li>
                <li><a href="/admin/MemberManagement">Members</a></li>
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
          <option value="">All</option>
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
          <tr>
            <td>Hotel Paradise</td>
            <td>Hotel</td>
            <td>Kuala Lumpur</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Hotel Paradise')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Hotel Paradise')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Hotel Paradise')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Beach Resort</td>
            <td>Hotel</td>
            <td>Penang</td>
            <td><span class="status-badge status-approved">Approved</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Beach Resort')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Beach Resort')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Beach Resort')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Skyline Hotel</td>
            <td>Hotel</td>
            <td>Johor Bahru</td>
            <td><span class="status-badge status-declined">Declined</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Skyline Hotel')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Skyline Hotel')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Skyline Hotel')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Mountain View Inn</td>
            <td>Hotel</td>
            <td>Cameron Highlands</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Mountain View Inn')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Mountain View Inn')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Mountain View Inn')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>City Central Hotel</td>
            <td>Hotel</td>
            <td>Malacca</td>
            <td><span class="status-badge status-approved">Approved</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('City Central Hotel')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('City Central Hotel')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('City Central Hotel')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Golden Dragon Hotel</td>
            <td>Hotel</td>
            <td>Ipoh</td>
            <td><span class="status-badge status-declined">Declined</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Golden Dragon Hotel')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Golden Dragon Hotel')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Golden Dragon Hotel')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Blue Lagoon Resort</td>
            <td>Hotel</td>
            <td>Langkawi</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Blue Lagoon Resort')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Blue Lagoon Resort')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Blue Lagoon Resort')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Palm Grove Hotel</td>
            <td>Hotel</td>
            <td>Kuantan</td>
            <td><span class="status-badge status-approved">Approved</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Palm Grove Hotel')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Palm Grove Hotel')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Palm Grove Hotel')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Sunset Hotel</td>
            <td>Hotel</td>
            <td>Sabah</td>
            <td><span class="status-badge status-declined">Declined</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Sunset Hotel')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Sunset Hotel')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Sunset Hotel')">Decline</button>
              </div>
            </td>
          </tr>
          <tr>
            <td>Riverfront Inn</td>
            <td>Hotel</td>
            <td>Sarawak</td>
            <td><span class="status-badge status-pending">Pending</span></td>
            <td>
              <div class="operation-buttons">
                <button class="btn btn-details" onclick="viewDetails('Riverfront Inn')">Details</button>
                <button class="btn btn-approve" onclick="approveBusiness('Riverfront Inn')">Approve</button>
                <button class="btn btn-decline" onclick="declineBusiness('Riverfront Inn')">Decline</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination">
        <div class="page-info">Page 1 of 2</div>
        <div class="page-buttons">
          <button class="page-btn" disabled>Previous</button>
          <button class="page-btn">Next</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    function filterTable() {
      const searchInput = document.getElementById('searchInput').value.toLowerCase();
      const statusFilter = document.getElementById('statusFilter').value;
      const table = document.getElementById('businessTable');
      const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

      for (let row of rows) {
        const name = row.cells[0].textContent.toLowerCase();
        const location = row.cells[2].textContent.toLowerCase();
        const status = row.cells[3].textContent.trim();

        const matchesSearch = name.includes(searchInput) || location.includes(searchInput);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
      }
    }

    function viewDetails(name) {
      alert('View details for: ' + name);
      // Implement view details functionality
    }

    function approveBusiness(name) {
      if (confirm('Are you sure you want to approve ' + name + '?')) {
        alert('Approved: ' + name);
        // Implement approve functionality
      }
    }

    function declineBusiness(name) {
      if (confirm('Are you sure you want to decline ' + name + '?')) {
        alert('Declined: ' + name);
        // Implement decline functionality
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
