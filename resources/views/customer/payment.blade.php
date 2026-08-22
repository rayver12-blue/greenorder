@extends('layouts.main')
@section('title', 'Payment')
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;font-family:'Plus Jakarta Sans',sans-serif;margin:0;overflow-x:hidden;color:#1a2e1a}
a,button,input,select{font:inherit}
.nav{background:#fff;border-bottom:1px solid #dcfce7;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.nav-brand{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-brand img{width:100px;height:100px;object-fit:contain}
.nav-brand-text strong{font-size:1rem;font-weight:800;color:#166534;display:block;line-height:1.1}
.nav-brand-text span{font-size:.65rem;color:#5a7a5a}
.nav-r{display:flex;align-items:center;gap:.5rem}
.nb{background:#f0fdf4;border:none;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#166534;text-decoration:none;transition:background .15s}
.nb:hover{background:#dcfce7}
.uchip{display:flex;align-items:center;gap:.45rem;background:#f0fdf4;border-radius:10px;padding:.35rem .8rem;border:1px solid #d1fae5;text-decoration:none}
.uav{width:26px;height:26px;border-radius:50%;background:#166534;color:#fff;font-weight:800;font-size:.75rem;display:flex;align-items:center;justify-content:center}
.unm{font-size:.82rem;font-weight:600;color:#1a2e1a}
.wrap{max-width:1100px;margin:0 auto;padding:2rem 1.25rem 3rem}
.page-title{display:flex;align-items:center;gap:.65rem;font-weight:800;font-size:1.45rem;color:#1a2e1a;margin-bottom:1.25rem}
.back-btn{display:inline-flex;align-items:center;gap:.35rem;border:1.5px solid #d1fae5;border-radius:9px;background:#fff;color:#166534;padding:.4rem .7rem;font-size:.76rem;font-weight:700;cursor:pointer;transition:background .15s,border-color .15s;margin-bottom:.8rem}
.back-btn:hover{background:#f0fdf4;border-color:#86efac}
.flash{padding:.7rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.fs{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.fe{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.grid{display:grid;grid-template-columns:1.05fr .95fr;gap:1.25rem;align-items:start}
.card{background:#fff;border:1px solid #f0fdf4;border-radius:18px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 3px 18px rgba(0,0,0,.05);overflow:hidden}
.panel-head{padding:1rem 1.15rem;border-bottom:1px solid #f0fdf4;font-weight:800;color:#1a2e1a;display:flex;align-items:center;justify-content:space-between;gap:.75rem}
.panel-head small{color:#5a7a5a;font-weight:600}
.panel-body{padding:1.1rem 1.15rem}
.method-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.7rem;margin-bottom:1rem}
.method-btn{width:100%;border:2px solid #e5e7eb;background:#fff;border-radius:14px;padding:.9rem .7rem;cursor:pointer;transition:all .15s;text-align:left}
.method-btn:hover{border-color:#86efac;background:#f0fdf4}
.method-btn.active{border-color:#166534;background:#f0fdf4;box-shadow:0 0 0 3px rgba(22,163,74,.08)}
.method-icon{font-size:1.35rem;margin-bottom:.3rem;display:block}
.method-title{font-size:.82rem;font-weight:800;color:#1a2e1a;display:block}
.method-sub{font-size:.68rem;color:#6b7280;display:block;line-height:1.4}
.notice{background:#fff7ed;border:1px solid #fed7aa;border-radius:12px;padding:.8rem .9rem;color:#9a4d00;font-size:.78rem;line-height:1.5;margin-bottom:1rem}
.notice strong{display:block;color:#7c2d12;margin-bottom:.15rem}
.same-row{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
.field{margin-bottom:.8rem}
.field label{display:block;font-size:.73rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;margin-bottom:.35rem}
.field input, .field select{width:100%;padding:.7rem .8rem;border:1.5px solid #d1fae5;border-radius:10px;background:#f0fdf4;color:#1a2e1a;outline:none}
.field input:focus, .field select:focus{border-color:#16a34a;background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.08)}
.form-shell{display:none}
.form-shell.active{display:block}
.simulation{display:flex;align-items:center;gap:.45rem;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:.55rem .7rem;font-size:.76rem;font-weight:700;margin-bottom:1rem}
.simulation-dot{width:8px;height:8px;background:#16a34a;border-radius:50%;animation:pulse 1.2s infinite}
@keyframes pulse{0%{transform:scale(1);opacity:1}50%{transform:scale(1.4);opacity:.7}100%{transform:scale(1);opacity:1}}
.summary-list{display:flex;flex-direction:column;gap:.45rem}
.summary-item{display:flex;justify-content:space-between;gap:.7rem;padding:.55rem 0;border-bottom:1px solid #f9fafb;font-size:.82rem}
.summary-item:last-child{border-bottom:none}
.summary-item span:first-child{color:#5a7a5a}
.summary-item span:last-child{font-weight:700;color:#1a2e1a}
.total-box{margin-top:.7rem;padding-top:.7rem;border-top:1.5px solid #f0fdf4;display:flex;justify-content:space-between;align-items:center;font-weight:800;font-size:1.05rem;color:#1a2e1a}
.total-box strong{color:#166534}
.inline-badge{display:inline-flex;align-items:center;gap:.35rem;background:#fff7ed;border:1px solid #fed7aa;border-radius:20px;padding:.18rem .52rem;font-size:.68rem;font-weight:800;color:#9a4d00}
.pay-btn{width:100%;margin-top:1rem;border:none;border-radius:12px;padding:.85rem 1rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;font-weight:800;cursor:pointer;box-shadow:0 5px 16px rgba(22,101,52,.2)}
.pay-btn:hover{opacity:.96}
.pay-btn[disabled]{cursor:wait;opacity:.75}
.cod-box{padding:.8rem .9rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;color:#166534;font-weight:700;font-size:.9rem}
@media (max-width: 900px){
  .grid{grid-template-columns:1fr}
}
@media (max-width: 560px){
  .method-grid{grid-template-columns:1fr}
  .same-row{grid-template-columns:1fr}
  .nav{height:auto;padding:.8rem 1rem;flex-wrap:wrap;gap:.6rem}
  .nav-brand img{width:52px;height:52px}
  .nav-brand{min-width:0;flex:1}
  .nav-brand-text span{display:none}
  .nav-r{flex-wrap:wrap;width:100%;justify-content:space-between}
  .unm{display:none}
}
</style>

<nav class="nav">
  <a href="{{ route('customer.home') }}" class="nav-brand">
    <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
    <div class="nav-brand-text"><strong>GreenOrder</strong><span>Food Ordering Platform</span></div>
  </a>
  <div class="nav-r">
    <a href="{{ route('customer.orders') }}" class="nb" title="My Orders">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </a>
    <a href="{{ route('customer.profile') }}" class="uchip">
      <div class="uav">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
      <span class="unm">{{ auth()->user()->name }}</span>
    </a>
    <form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
      <button type="submit" class="nb" title="Sign Out">
        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </div>
</nav>

<div class="wrap">
  <button type="button" class="back-btn" onclick="goBackToCart()">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
    Back to Cart
  </button>
  <div class="page-title">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M3 9.5A2.5 2.5 0 0 1 5.5 7h13A2.5 2.5 0 0 1 21 9.5v5A2.5 2.5 0 0 1 18.5 17h-13A2.5 2.5 0 0 1 3 14.5v-5z"/><path d="M3 10h18"/><path d="M7 15h3"/></svg>
    Secure Payment
  </div>

  @if(session('error'))
    <div class="flash fe"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4"/><path d="M12 17h.01"/><circle cx="12" cy="12" r="9"/></svg>{{ session('error') }}</div>
  @endif

  <div class="grid">
    <div class="card">
      <div class="panel-head">
        <span>Select Payment</span>

      </div>
      <div class="panel-body">
        <div class="notice">
          <strong>DEMO PAYMENT ONLY</strong>
          This system does not process real payments or connect to real financial accounts.
        </div>

        <form method="POST" action="{{ route('customer.pay') }}" id="payment-form">
          @csrf
          <input type="hidden" name="order_type" value="{{ $checkout['order_type'] ?? 'dine_in' }}">
          <input type="hidden" name="notes" value="{{ $checkout['notes'] ?? '' }}">
          <div class="method-grid">
            <button type="button" class="method-btn" data-method="gcash">
              
              <span class="method-title">GCash</span>
              <span class="method-sub">GCash Payment</span>
            </button>
            <button type="button" class="method-btn" data-method="card">
              
              <span class="method-title">Credit/Debit Card</span>
              <span class="method-sub">Card Payment</span>
            </button>
            <button type="button" class="method-btn" data-method="cod">
              
              <span class="method-title">Cash on Delivery</span>
              <span class="method-sub">Pay when your order arrives</span>
            </button>
          </div>

          <input type="hidden" name="payment_method" id="payment_method" value="gcash">

          <div class="simulation">
            <span class="simulation-dot"></span>
            Simulated checkout in progress — no real payment will be processed.
          </div>

          <div class="form-shell active" id="gcash-form">
            <div class="field">
              <label for="gcash_mobile">GCash Mobile Number</label>
              <input type="text" id="gcash_mobile" name="gcash_mobile" value="09171234567" placeholder="09XXXXXXXXX" maxlength="11">
            </div>
            <div class="same-row">
              <div class="field">
                <label for="gcash_name">Account Name</label>
                <input type="text" id="gcash_name" name="gcash_name" value="Maria Santos" placeholder="Account name">
              </div>
              <div class="field">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" value="{{ number_format($total, 2, '.', '') }}" readonly>
              </div>
            </div>
          </div>

          <div class="form-shell" id="card-form">
            <div class="field">
              <label for="cardholder_name">Cardholder Name</label>
              <input type="text" id="cardholder_name" name="cardholder_name" value="Demo User" placeholder="Cardholder name">
            </div>
            <div class="field">
              <label for="card_number">Card Number</label>
              <input type="text" id="card_number" name="card_number" value="4111 1111 1111 1111" placeholder="4111 1111 1111 1111" maxlength="19">
            </div>
            <div class="same-row">
              <div class="field">
                <label for="expiration_date">Expiration Date</label>
                <input type="text" id="expiration_date" name="expiration_date" value="12/30" placeholder="MM/YY" maxlength="5">
              </div>
              <div class="field">
                <label for="cvv">CVV</label>
                <input type="password" id="cvv" name="cvv" value="123" placeholder="123" maxlength="3">
              </div>
            </div>
            <div class="field">
              <label for="card_amount">Amount</label>
              <input type="number" id="card_amount" name="amount" step="0.01" min="0.01" value="{{ number_format($total, 2, '.', '') }}" readonly>
            </div>
          </div>

          <div class="form-shell" id="cod-form">
            <div class="cod-box">
              Cash on Delivery selected.<br>
              Payment will be collected when the order arrives.
            </div>
            <div class="field" style="margin-top: 1rem;">
              <label for="cod_amount">Order Total</label>
              <input type="number" id="cod_amount" value="{{ number_format($total, 2, '.', '') }}" readonly>
            </div>
          </div>

          <button type="submit" class="pay-btn" id="submit-payment">Pay with GCash</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="panel-head">
        <span>Order Summary</span>
        <span class="inline-badge">Payment Demo</span>
      </div>
      <div class="panel-body">
        <div class="summary-list">
          @foreach($checkout['items'] ?? [] as $item)
            <div class="summary-item">
              <span>{{ $item['name'] ?? 'Item' }} x {{ (int) ($item['qty'] ?? 1) }}</span>
              <span>₱{{ number_format(((float) ($item['price'] ?? 0)) * max(1, (int) ($item['qty'] ?? 1)), 2) }}</span>
            </div>
          @endforeach
        </div>
        <div class="total-box">
          <span>Total</span>
          <strong>₱{{ number_format($total, 2) }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function goBackToCart() {
    if (window.history.length > 1) {
      window.history.back();
      return;
    }
    window.location.href = @json(route('customer.home'));
  }

  const methods = document.querySelectorAll('.method-btn');
  const formShells = document.querySelectorAll('.form-shell');
  const hiddenMethod = document.getElementById('payment_method');
  const submitBtn = document.getElementById('submit-payment');

  function setMethod(method) {
    hiddenMethod.value = method;
    methods.forEach((btn) => btn.classList.toggle('active', btn.dataset.method === method));
    formShells.forEach((shell) => shell.classList.toggle('active', shell.id === method + '-form'));

    const labels = {
      gcash: 'Pay with GCash',
      card: 'Pay with Card',
      cod: 'Place COD Order'
    };

    submitBtn.textContent = labels[method] || 'Complete Payment';
  }

  methods.forEach((btn) => {
    btn.addEventListener('click', () => setMethod(btn.dataset.method));
  });

  const form = document.getElementById('payment-form');
  form.addEventListener('submit', function (event) {
    const method = hiddenMethod.value;
    submitBtn.disabled = true;
    submitBtn.textContent = method === 'cod' ? 'Processing COD Order...' : 'Processing Payment...';
    form.classList.add('loading');
  });

  setMethod('gcash');
</script>
@endsection
