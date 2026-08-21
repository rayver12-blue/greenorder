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
    })();
  </script>
  @stack('scripts')
</body>
</html>
