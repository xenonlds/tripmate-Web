<style>
    .owner-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .card-header-custom.secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .card-header-custom.dark {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 600;
        font-size: 1.25rem;
        letter-spacing: 0.5px;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #4a5568;
        min-width: 150px;
        font-size: 0.95rem;
    }

    .info-value {
        color: #2d3748;
        flex: 1;
        font-size: 0.95rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.9rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .badge-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .badge-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        margin: 0 0.25rem;
    }

    .address-item {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border-left: 4px solid #f5576c;
        transition: transform 0.2s ease;
    }

    .address-item:hover {
        transform: translateX(5px);
    }

    .address-item:last-child {
        margin-bottom: 0;
    }

    .no-data {
        text-align: center;
        padding: 2rem;
        color: #a0aec0;
        font-style: italic;
    }

    .icon-text {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-label {
            margin-bottom: 0.25rem;
        }
    }
</style>

<div class="owner-container">

    <!-- Business Owner Card -->
    <div class="info-card">
        <div class="card-header-custom">
            <h5>🏢 Business Owner Information</h5>
        </div>
        <div class="card-body-custom">
            <div class="info-row">
                <span class="info-label">Owner ID:</span>
                <span class="info-value">{{ $owner['owner_id'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Business Name:</span>
                <span class="info-value">{{ $owner['business_name'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Business Type:</span>
                <span class="info-value">{{ $owner['type'] }}</span>
            </div>
             <div class="info-row">
                <span class="info-label">Business Licence:</span>
                <span class="info-value">{{ $owner['business_license_no'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact Number:</span>
                <span class="info-value">{{ $owner['contact_number'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Apply Status:</span>
                <span class="info-value">
                    <span class="status-badge 
                        @if($owner['apply_status'] === 'Approved') badge-success
                        @elseif($owner['apply_status'] === 'Pending') badge-warning
                        @else badge-danger @endif">
                        {{ $owner['apply_status'] }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="info-card">
        <div class="card-header-custom secondary">
            <h5>👤 User Account</h5>
        </div>
        <div class="card-body-custom">
            <div class="info-row">
                <span class="info-label">User ID:</span>
                <span class="info-value">{{ $user['user_id'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span class="info-value">{{ $user['name'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $user['email'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Role:</span>
                <span class="info-value">{{ $user['role'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge {{ $user['status'] === 'active' ? 'badge-success' : 'badge-danger' }}">
                        {{ $user['status'] }}
                    </span>
                </span>
            </div>
        </div>
    </div>

    <!-- Address List -->
    <div class="info-card">
        <div class="card-header-custom dark">
            <h5>📍 Address Information</h5>
        </div>
        <div class="card-body-custom">

            @if(count($address) > 0)
                @foreach($address as $addr)
                <div class="address-item">
                    <div class="info-row">
                        <span class="info-label">Address ID:</span>
                        <span class="info-value">{{ $addr['address_id'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address:</span>
                        <span class="info-value">{{ $addr['address'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">City:</span>
                        <span class="info-value">{{ $addr['city'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Postal Code:</span>
                        <span class="info-value">{{ $addr['postal_code'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Coordinates:</span>
                        <span class="info-value">
                            <span class="badge-info">{{ $addr['latitude'] }}</span>
                            <span class="badge-info">{{ $addr['longitude'] }}</span>
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-data">
                    <p>📭 No address found for this owner.</p>
                </div>
            @endif

        </div>
    </div>

</div>