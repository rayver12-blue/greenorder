<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'GreenOrder') — Food Ordering Platform</title>
  <link rel="icon" href="{{ asset('images/greenorder_icon.png') }}" type="image/png">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .logout-modal{position:fixed;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;z-index:1000;padding:1.25rem}
    .logout-modal.show{display:flex}
    .logout-card{background:#fff;border-radius:14px;max-width:420px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.2);border:1px solid #e5e7eb;overflow:hidden}
    .logout-hd{padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4;font-weight:800;color:#1a2e1a}
    .logout-bd{padding:1rem 1.25rem;color:#5a7a5a;font-size:.9rem;line-height:1.5}
    .logout-ft{padding:1rem 1.25rem;border-top:1px solid #f0fdf4;display:flex;gap:.6rem;justify-content:flex-end}
    .logout-btn{border:none;border-radius:10px;padding:.55rem 1rem;font-size:.85rem;font-weight:700;cursor:pointer}
    .logout-cancel{background:#f5f5f0;color:#5a7a5a;border:1.5px solid #e5e7eb}
    .logout-confirm{background:#166534;color:#fff}
    .admin-order-dot{display:inline-block;width:8px;height:8px;border-radius:50%;background:#ef4444;box-shadow:0 0 0 2px rgba(239,68,68,.15);vertical-align:middle;flex-shrink:0}
    .admin-order-dot[hidden]{display:none}
    .admin-order-link{display:inline-flex;align-items:center;gap:.5rem}
    .customer-order-dot{position:absolute;top:-2px;right:-2px;width:9px;height:9px;border-radius:50%;background:#ef4444;box-shadow:0 0 0 2px #fff}
    .customer-order-dot[hidden]{display:none}
    @media (max-width: 480px){
      .logout-ft{flex-direction:column}
      .logout-btn{width:100%}
    }
  </style>
  @stack('head')
</head>
<body>
  @yield('content')
  <div class="logout-modal" id="logout-modal" aria-hidden="true">
    <div class="logout-card" role="dialog" aria-modal="true" aria-labelledby="logout-title">
      <div class="logout-hd" id="logout-title">Confirm Logout</div>
      <div class="logout-bd">Are you sure you want to log out?</div>
      <div class="logout-ft">
        <button type="button" class="logout-btn logout-cancel" id="logout-cancel">Cancel</button>
        <button type="button" class="logout-btn logout-confirm" id="logout-confirm">Log Out</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
    (function () {
      const modal = document.getElementById('logout-modal');
      const btnCancel = document.getElementById('logout-cancel');
      const btnConfirm = document.getElementById('logout-confirm');
      let pendingForm = null;

      function openModal(form) {
        pendingForm = form;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
      }
      function closeModal() {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        pendingForm = null;
      }

      document.addEventListener('submit', (e) => {
        const form = e.target.closest('form[data-logout]');
        if (!form) return;
        e.preventDefault();
        openModal(form);
      });

      btnCancel?.addEventListener('click', closeModal);
      modal?.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
      });
      btnConfirm?.addEventListener('click', () => {
        if (pendingForm) pendingForm.submit();
      });

      const orderLink = document.querySelector('a[href*="/admin/orders"]');
      const orderDot = document.getElementById('new-order-dot');
      const ORDER_COUNT_URL = '{{ route('admin.orders.new-count') }}';

      function updateOrderDot() {
        fetch(ORDER_COUNT_URL, { headers: { Accept: 'application/json' } })
          .then(r => r.json())
          .then(data => {
            const count = Number(data.count || 0);
            const hasNew = count > 0;

            if (orderDot) orderDot.hidden = !hasNew;
            if (orderLink) {
              orderLink.classList.toggle('admin-order-link', hasNew || orderLink.getAttribute('href') === '{{ route('admin.orders') }}');
              let dot = orderLink.querySelector('.admin-order-dot');
              if (!dot) {
                dot = document.createElement('span');
                dot.className = 'admin-order-dot';
                dot.setAttribute('aria-label', 'New orders');
                dot.hidden = !hasNew;
                orderLink.appendChild(dot);
              }
              dot.hidden = !hasNew;
            }
          })
          .catch(() => {});
      }

      if (ORDER_COUNT_URL) {
        updateOrderDot();
        setInterval(updateOrderDot, 15000);
      }

      @if(auth()->check() && auth()->user()->role === 'user')
        const customerOrdersUrl = '{{ route('customer.orders') }}';
        const customerUpdatesUrl = '{{ route('customer.orders.update-count') }}';
        const customerOrderLink = document.querySelector(`a[href="${customerOrdersUrl}"]`);

        function updateCustomerOrderDot() {
          if (!customerOrderLink) return;
          fetch(customerUpdatesUrl, { headers: { Accept: 'application/json' } })
            .then(r => r.json())
            .then(data => {
              let dot = customerOrderLink.querySelector('.customer-order-dot');
              if (!dot) {
                dot = document.createElement('span');
                dot.className = 'customer-order-dot';
                dot.setAttribute('aria-label', 'Order status updated');
                customerOrderLink.appendChild(dot);
              }
              dot.hidden = Number(data.count || 0) === 0;
            })
            .catch(() => {});
        }

        updateCustomerOrderDot();
        setInterval(updateCustomerOrderDot, 15000);
      @endif
    })();
  </script>
  @stack('scripts')
</body>
</html>
