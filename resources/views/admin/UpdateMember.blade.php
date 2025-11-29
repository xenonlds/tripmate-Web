<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Update Member - TripMate</title>
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
      max-width: 800px;
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

    .back-btn {
      background: #64748b;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: background 0.2s;
      text-decoration: none;
      display: inline-block;
    }

    .back-btn:hover {
      background: #475569;
    }

    .form-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      padding: 32px;
    }

    .member-id-badge {
      display: inline-block;
      background: #dbeafe;
      color: #1e40af;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      margin-bottom: 24px;
    }

    .form-section {
      margin-bottom: 32px;
    }

    .form-section:last-child {
      margin-bottom: 0;
    }

    .section-title {
      font-size: 18px;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid #e2e8f0;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    .form-group label {
      font-size: 14px;
      font-weight: 600;
      color: #1e293b;
      margin-bottom: 8px;
    }

    .form-group label .required {
      color: #ef4444;
    }

    .form-group input,
    .form-group select {
      padding: 10px 16px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #3b82f6;
    }

    .form-group .error {
      color: #ef4444;
      font-size: 12px;
      margin-top: 4px;
    }

    .form-group .help-text {
      color: #64748b;
      font-size: 12px;
      margin-top: 4px;
    }

    .form-actions {
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      margin-top: 32px;
      padding-top: 24px;
      border-top: 1px solid #e2e8f0;
    }

    .btn {
      padding: 10px 24px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-primary {
      background: #3b82f6;
      color: white;
    }

    .btn-primary:hover {
      background: #2563eb;
    }

    .btn-secondary {
      background: #e2e8f0;
      color: #64748b;
    }

    .btn-secondary:hover {
      background: #cbd5e1;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-error {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fca5a5;
    }

    .alert-success {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }

    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }

      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
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
      <h1 class="page-title">Update Member</h1>
      <a href="/admin/MemberManagement" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Members
      </a>
    </div>

    @if(session('error'))
      <div class="alert alert-error">
        {{ session('error') }}
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-error">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="form-card">
      <div class="member-id-badge">
        <i class="fas fa-id-card"></i> Tourist ID: {{ $member['tourist_id'] }}
      </div>

      <form action="{{ route('admin.member.update', $member['tourist_id']) }}" method="POST" id="memberForm">
        @csrf
        @method('PUT')

        <!-- Account Information -->
        <div class="form-section">
          <h2 class="section-title">Account Information</h2>
          <div class="form-grid">
            <div class="form-group full-width">
              <label for="name">Full Name <span class="required">*</span></label>
              <input type="text" id="name" name="name" value="{{ old('name', $member['name']) }}" required>
              @error('name')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group full-width">
              <label for="email">Email Address <span class="required">*</span></label>
              <input type="email" id="email" name="email" value="{{ old('email', $member['User']['email'] ?? '') }}" required>
              @error('email')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>

        <!-- Personal Information -->
        <div class="form-section">
          <h2 class="section-title">Personal Information</h2>
          <div class="form-grid">
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $member['phone_number']) }}" placeholder="01X-XXXXXXX">
              @error('phone_number')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="birthdate">Birth Date</label>
              <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $member['birthdate'] ? date('Y-m-d', strtotime($member['birthdate'])) : '') }}">
              @error('birthdate')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" id="age" name="age" value="{{ old('age', $member['age']) }}" min="1" max="120">
              @error('age')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="gender">Gender</label>
              <select id="gender" name="gender">
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $member['gender']) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $member['gender']) == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender', $member['gender']) == 'Other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('gender')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group full-width">
              <label for="nationality">Nationality</label>
              <input type="text" id="nationality" name="nationality" value="{{ old('nationality', $member['nationality']) }}" placeholder="e.g., Malaysian">
              @error('nationality')
                <span class="error">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="window.location.href='/admin/MemberManagement'">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Member
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = "{{ url('/logout') }}";
      }
    }

    // Auto-calculate age from birthdate
    document.getElementById('birthdate').addEventListener('change', function() {
      const birthDate = new Date(this.value);
      const today = new Date();
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();

      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      if (age > 0 && age < 120) {
        document.getElementById('age').value = age;
      }
    });
  </script>
</body>
</html>
