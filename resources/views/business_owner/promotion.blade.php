<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TripMate Hotel Manager - Promotions</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  @vite('resources/css/navigation.css')
  @vite('resources/css/header.css')
  @vite('resources/css/page/promotion.css')
  <style>
    nav a.active-pro {
      background-color: white;
      color: #1e293b;
      font-weight: 600;
      box-shadow: 0 0 0 1px #e2e8f0;
    }

    nav a.active-pro svg {
      stroke: #1e293b;
    }

    .label-red {
    background-color: red;
    color: white; 
    padding: 2px 6px;
    border-radius: 4px;
}
  </style>
</head>

<body>


  @include('component.header')
  @include('../form/add_promotion')
  <main>
    <div class="page-header container">
      <div>
        <h1>Promotions Management</h1>
        <p>Create and manage promotional offers</p>
      </div>
      <button class="btn-create" type="button" aria-label="Create Promotion" id="openModalBtn">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
          <line x1="12" y1="5" x2="12" y2="19" stroke="white" stroke-width="2" stroke-linecap="round" />
          <line x1="5" y1="12" x2="19" y2="12" stroke="white" stroke-width="2" stroke-linecap="round" />
        </svg>
        Create Promotion
      </button>
    </div>
    <section class="promotions-list" aria-label="List of promotions">
      @foreach($promotions as $promo)
      <article class="promotion-card">
        <h2 class="promotion-title">{{ $promo['title'] }}</h2>

        <div class="labels-row">
          @if(!empty($promo['discount_percent']))
          <span class="label-green">{{ $promo['discount_percent'] }}% off</span>
          @endif
          <!-- @if(!empty($promo['status']))
          <span class="{{ $promo['status'] === 'deactive' ? 'label-red' : 'label-green' }}">
            {{ ucfirst($promo['status']) }}
          </span>
          @endif -->

        </div>

        @if(!empty($promo['description']))
        <p class="promotion-subtitle">{{ $promo['description'] }}</p>
        @endif

        <div class="promotion-info">
          <div>
            <i class="fas fa-calendar-alt"></i>
            <strong>Duration:</strong>
            <span style="float:right;">
              {{ !empty($promo['start_date']) ? date('Y/m/d', strtotime($promo['start_date'])) : 'N/A' }}
              -
              {{ !empty($promo['end_date']) ? date('Y/m/d', strtotime($promo['end_date'])) : 'N/A' }}
            </span>
          </div>
          <div>
            <i class="fas fa-user-friends"></i>
            <strong>Usage:</strong>
            <span style="float:right;">
              {{ $promo['usage_count'] ?? 0 }} / {{ $promo['usage_limit'] ?? '∞' }}
            </span>
          </div>
          @if(!empty($promo['min_spending']))
          <div>
            <i class="fas fa-dollar-sign"></i>
            <strong>Min. Spending:</strong>
            <span style="float:right;">RM {{ number_format($promo['min_spending'], 2) }}</span>
          </div>
          @endif
          @if(!empty($promo['code']))
          <div>
            <i class="fas fa-barcode"></i>
            <strong>Code:</strong>
            <span class="code">{{ $promo['code'] }}</span>
          </div>
          @endif
        </div>

        <div class="actions-row">
  
        
        </div>
      </article>
      @endforeach



    </section>
  </main>
  @vite('resources/js/promote.js')
</body>

</html>