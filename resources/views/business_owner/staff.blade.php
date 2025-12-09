<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TripMate Hotel Manager - Staff Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite('resources/css/navigation.css')
  @vite('resources/css/header.css')
  @vite('resources/css/page/staff.css')
  @vite('resources/css/pagination.css')
  <style>
    nav a.active-st {
      background-color: white;
      color: #1e293b;
      font-weight: 600;
      box-shadow: 0 0 0 1px #e2e8f0;
    }

    nav a.active-st svg {
      stroke: #1e293b;
    }

    
  </style>
</head>

<body>
  @include('component.header')
  @include('../form/add_staff')
  @include('../form/update/up_staff')
  <main class="container" role="main">
    <div class="page-header">
      <div>
        <h1>Staff Management</h1>
        <p>Manage your hotel staff members</p>
      </div>
      <button class="btn-add" type="button" aria-label="Add Staff Member">
        <svg aria-hidden="true" focusable="false" stroke="currentColor" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="14" height="14">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        Add Staff Member
      </button>
    </div>
    <section class="card" aria-labelledby="search-filter-title">
      <h2 id="search-filter-title">Search &amp; Filter</h2>
      <div class="search-filter">
        <div class="search-input-wrapper">
          <input type="search" aria-label="Search staff by name, email, or position" placeholder="Search staff by name, email, or position..." />
          <i class="fas fa-search" aria-hidden="true"></i>
        </div>
        <select class="select-department" aria-label="Filter by department">
          <option>All Departments</option>
          <option>Reception</option>
          <option>Housekeeping</option>
          <option>Kitchen</option>
          <option>Maintenance</option>
        </select>
      </div>
    </section>
    <section class="card" aria-labelledby="staff-members-title">
      <h2 id="staff-members-title">
        Staff Members  <span id="staff-count">{{ count($staffs) }}</span> / {{ $total }}
      </h2>
      <p>Manage your hotel staff members and their information</p>
      <table role="table" aria-describedby="staff-members-desc">
        <thead>
          <tr>
            <th scope="col">Staff ID</th>
            <th scope="col">Name</th>
            <th scope="col">Country</th>
            <th scope="col">Location</th>
            <th scope="col">Contact</th>
            <th scope="col">Status</th>
            <th scope="col">Join Date</th>
            <th scope="col" colspan="2" style="text-align:right;">Actions</th>
          </tr>
        </thead>
        <tbody id="TableBody">

          @foreach($staffs as $staff)
          <tr>
            <td>
              <div class="staff-name">{{ $staff['staff_id'] }}</div>
            </td>
            <td>
              <div class="staff-name">{{ $staff['User']['name'] ?? 'N/A' }}</div>
              <div class="staff-email">{{ $staff['User']['email'] ?? 'N/A' }}</div>
            </td>
            <td>{{$staff['country'] }}</td>
            <td><span class="badge reception" aria-label="Reception department">{{ $staff['department'] }}</span></td>
            <td>{{ $staff['contact_number'] }}</td>

            @php
            $status = strtolower($staff['User']['status'] ?? 'inactive');
            $isActive = $status === 'active';
            @endphp

            <td>
              <span
                class="status-badge {{ $isActive ? 'active' : 'inactive' }}"
                aria-label="{{ ucfirst($status) }} status">
                @if($isActive)
                <i class="fa fa-check-circle text-green-600"></i>
                @else
                <i class="fa fa-times-circle text-gray-400"></i>
                @endif
                {{ ucfirst($status) }}
              </span>
            </td>


            <td>{{ $staff['registration_date'] }}</td>
            <td>
              <!-- <button
                class="btn-action"
                type="button"
                data-user-id="{{  $staff['User']['user_id'] }}"
                data-status="{{ $isActive ? 'active' : 'inactive' }}"
                aria-label="{{ ($isActive ? 'Deactivate' : 'Activate') . ' ' . ($staff->user->name ?? 'Unknown') }}">
                {{ $isActive ? 'Deactivate' : 'Activate' }}
              </button> -->
            </td>


            <td>
              <button data-id="{{ $staff['staff_id'] }}" class="btn-icon stfModal_openBtn" type="button">
                <svg aria-hidden="true" focusable="false" stroke="currentColor" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" width="16" height="16">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                </svg>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
<div id="pagination" class="pagination" style="margin-top: 15px; text-align: center;">
  @if ($page > 1)
    <a href="#" class="page-link prev" data-page="{{ $page - 1 }}">« Previous</a>
  @endif

  @for ($i = 1; $i <= $totalPages; $i++)
    <a href="#" 
       class="page-link {{ $page == $i ? 'active' : '' }}" 
       data-page="{{ $i }}">
        {{ $i }}
    </a>
  @endfor

  @if ($page < $totalPages)
    <a href="#" class="page-link next" data-page="{{ $page + 1 }}">Next »</a>
  @endif
</div>
</div>

    </section>
  </main>
  @vite('resources/js/staff.js')

</body>




</html>