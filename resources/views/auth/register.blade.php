@extends('layouts.main')
@section('title', 'Create Account')

@section('content')
<style>
  body { background: #f0fdf4; }
  .auth-wrapper {
    min-height: 100vh;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 2rem 1rem;
    background:
      radial-gradient(ellipse at 20% 20%, rgba(74,222,128,.18) 0%, transparent 55%),
      radial-gradient(ellipse at 80% 80%, rgba(22,163,74,.12) 0%, transparent 55%),
      #f0fdf4;
  }
  .auth-logo-block { display:flex;flex-direction:column;align-items:center;margin-bottom:1.5rem;animation:fadeDown .5s ease both; }
  .auth-logo-block img { width:72px;height:72px;object-fit:contain;margin-bottom:.65rem;filter:drop-shadow(0 6px 18px rgba(22,101,52,.25)); }
  .auth-logo-block h1 { font-size:1.5rem;font-weight:800;color:#166534;letter-spacing:-.3px;margin-bottom:.15rem; }
  .auth-logo-block p { font-size:.8rem;color:#5a7a5a;font-weight:500; }
  .auth-card {
    background:#fff;border-radius:20px;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 8px 32px rgba(0,0,0,.10),0 0 0 1px rgba(22,163,74,.08);
    padding:2rem 2.5rem;width:100%;max-width:520px;
    animation:fadeUp .5s ease both .1s;
  }
  .auth-card-title { font-size:1.3rem;font-weight:800;color:#1a2e1a;margin-bottom:.25rem; }
  .auth-card-sub { font-size:.84rem;color:#5a7a5a;margin-bottom:1.5rem; }
  .field { margin-bottom:1rem; }
  .field label { display:block;font-size:.8rem;font-weight:700;color:#1a2e1a;margin-bottom:.35rem; }
  .input-wrap { position:relative; }
  .input-wrap svg.ico { position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;width:16px;height:16px; }
  .inp { width:100%;padding:.75rem 2.6rem;border:1.5px solid #d1fae5;border-radius:10px;font-family:inherit;font-size:.88rem;color:#1a2e1a;background:#f0fdf4;outline:none;transition:border-color .2s,box-shadow .2s,background .2s; }
  .inp:focus { border-color:#16a34a;background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.12); }
  .inp.err { border-color:#dc2626;background:#fff; }
  select.inp { appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .85rem center;background-color:#f0fdf4; }
  select.inp:focus { background-color:#fff; }
  .eye-btn { position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;padding:0; }
  .eye-btn:hover { color:#16a34a; }
  .err-msg { font-size:.75rem;color:#dc2626;margin-top:.28rem; }
  .grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
  /* Strength */
  .strength-bar { margin-top:.5rem; }
  .strength-tracks { display:flex;gap:4px;margin-bottom:.3rem; }
  .str-track { flex:1;height:4px;border-radius:2px;background:#d1fae5;transition:background .3s; }
  .str-track.weak { background:#ef4444; }
  .str-track.fair { background:#f59e0b; }
  .str-track.good { background:#22c55e; }
  .str-track.strong { background:#166534; }
  .strength-label { font-size:.72rem;font-weight:700; }
  .strength-rules { margin-top:.6rem;display:grid;grid-template-columns:1fr 1fr;gap:.3rem; }
  .str-rule { font-size:.73rem;display:flex;align-items:center;gap:.35rem;color:#9ca3af; }
  .str-rule.met { color:#16a34a; }
  /* Admin key notice */
  .admin-notice { background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:.65rem .9rem;font-size:.8rem;color:#92400e;margin-bottom:.85rem;display:flex;align-items:center;gap:.45rem; }
  /* Confirm feedback */
  #confirm-feedback { font-size:.76rem;font-weight:700;margin-top:.3rem; }
  /* Submit */
  .btn-submit { width:100%;padding:.85rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.95rem;font-weight:700;cursor:pointer;margin-top:.5rem;transition:opacity .2s,transform .15s,box-shadow .2s;box-shadow:0 4px 14px rgba(22,101,52,.3); }
  .btn-submit:hover { opacity:.92;transform:translateY(-1px); }
  /* Alerts */
  .alert { padding:.75rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.5rem; }
  .alert-danger { background:#fef2f2;border:1px solid #fecaca;color:#991b1b; }
  .alert ul { margin:.3rem 0 0 1rem;font-size:.8rem; }
  /* Footer */
  .auth-footer { text-align:center;margin-top:1.25rem;font-size:.84rem;color:#5a7a5a; }
  .auth-footer a { color:#16a34a;font-weight:700;text-decoration:none; }
  @keyframes fadeDown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:none} }
  @keyframes fadeUp   { from{opacity:0;transform:translateY(14px)}  to{opacity:1;transform:none} }
</style>

<div class="auth-wrapper">
  <div class="auth-logo-block">
    <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
    <h1>GreenOrder</h1>
    <p>Fresh Filipino food for dine-in and takeout</p>
  </div>

  <div class="auth-card">
    <div class="auth-card-title">Create Your Account</div>
    <div class="auth-card-sub">Fill in your details to get started</div>

    @if($errors->any())
      <div class="alert alert-danger">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <div><strong>Please fix the following:</strong>
          <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      </div>
    @endif

    <form method="POST" action="{{ route('auth.register.post') }}" novalidate>
      @csrf

      {{-- Row 1: Username + Full Name --}}
      <div class="grid-2">
        <div class="field">
          <label for="username">Username</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="username" name="username" class="inp {{ $errors->has('username') ? 'err' : '' }}" value="{{ old('username') }}" placeholder="juan_dc" required autocomplete="username">
          </div>
          @error('username')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="field">
          <label for="name">Full Name</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" id="name" name="name" class="inp {{ $errors->has('name') ? 'err' : '' }}" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required autocomplete="name">
          </div>
          @error('name')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Email --}}
      <div class="field">
        <label for="email">Email Address</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <input type="email" id="email" name="email" class="inp {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
        </div>
        @error('email')<div class="err-msg">{{ $message }}</div>@enderror
      </div>

      {{-- Row 2: Birthdate + Mobile --}}
      <div class="grid-2">
        <div class="field">
          <label for="birthdate">Birthdate</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <input type="date" id="birthdate" name="birthdate" class="inp {{ $errors->has('birthdate') ? 'err' : '' }}" value="{{ old('birthdate') }}" required max="{{ date('Y-m-d', strtotime('-13 years')) }}">
          </div>
          @error('birthdate')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
        <div class="field">
          <label for="mobile">Mobile Number</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            <input type="tel" id="mobile" name="mobile" class="inp {{ $errors->has('mobile') ? 'err' : '' }}" value="{{ old('mobile') }}" placeholder="09XXXXXXXXX" required autocomplete="tel">
          </div>
          @error('mobile')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Role --}}
      <div class="field">
        <label for="role">Account Role</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          <select id="role" name="role" class="inp {{ $errors->has('role') ? 'err' : '' }}" required>
            <option value="">— Select role —</option>
            <option value="user"  {{ old('role') === 'user'  ? 'selected' : '' }}>Customer</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
          </select>
        </div>
        @error('role')<div class="err-msg">{{ $message }}</div>@enderror
      </div>

      {{-- Admin Key (hidden by default) --}}
      <div id="admin-key-group" style="display:{{ old('role') === 'admin' ? 'block' : 'none' }};">
        <div class="admin-notice">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
          Admin accounts require an authorization key issued by management.
        </div>
        <div class="field">
          <label for="admin_key">Admin Authorization Key</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="admin_key" name="admin_key" class="inp {{ $errors->has('admin_key') ? 'err' : '' }}" placeholder="Enter admin key" autocomplete="off">
            <button type="button" class="eye-btn" onclick="toggleEye(this,'admin_key')" aria-label="Show key">
              <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('admin_key')<div class="err-msg">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Password --}}
      <div class="field">
        <label for="password">Password</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" id="password" name="password" class="inp {{ $errors->has('password') ? 'err' : '' }}" placeholder="••••••••" required autocomplete="new-password">
          <button type="button" class="eye-btn" onclick="toggleEye(this,'password')" aria-label="Show password">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('password')<div class="err-msg">{{ $message }}</div>@enderror
        <div class="strength-bar">
          <div class="strength-tracks">
            <div class="str-track"></div><div class="str-track"></div>
            <div class="str-track"></div><div class="str-track"></div>
            <div class="str-track"></div>
          </div>
          <span class="strength-label"></span>
        </div>
        <div class="strength-rules">
          <div class="str-rule" data-rule="length"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Min 8 characters</div>
          <div class="str-rule" data-rule="uppercase"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Uppercase letter</div>
          <div class="str-rule" data-rule="lowercase"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Lowercase letter</div>
          <div class="str-rule" data-rule="number"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Number (0–9)</div>
          <div class="str-rule" data-rule="special"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Special character</div>
        </div>
      </div>

      {{-- Confirm Password --}}
      <div class="field">
        <label for="password_confirmation">Confirm Password</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" id="password_confirmation" name="password_confirmation" class="inp" placeholder="••••••••" required autocomplete="new-password">
          <button type="button" class="eye-btn" onclick="toggleEye(this,'password_confirmation')" aria-label="Show password">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div id="confirm-feedback"></div>
      </div>

      <button type="submit" class="btn-submit">Create Account →</button>
    </form>

    <div class="auth-footer">Already have an account? <a href="{{ route('auth.login') }}">Sign in</a></div>
  </div>
</div>

<script>
// Eye toggle
function toggleEye(btn, inputId) {
  const inp = document.getElementById(inputId);
  const hidden = inp.type === 'password';
  inp.type = hidden ? 'text' : 'password';
  btn.innerHTML = hidden
    ? `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
    : `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}

// Role → show/hide admin key
document.getElementById('role').addEventListener('change', function () {
  const group = document.getElementById('admin-key-group');
  const keyInput = document.getElementById('admin_key');
  const show = this.value === 'admin';
  group.style.display = show ? 'block' : 'none';
  keyInput.required = show;
  if (!show) keyInput.value = '';
});

// Password strength
const pwdInp = document.getElementById('password');
pwdInp.addEventListener('input', function () {
  const v = this.value;
  const rules = {
    length:    v.length >= 8,
    uppercase: /[A-Z]/.test(v),
    lowercase: /[a-z]/.test(v),
    number:    /[0-9]/.test(v),
    special:   /[^A-Za-z0-9]/.test(v),
  };
  const score = Object.values(rules).filter(Boolean).length;
  const tracks = document.querySelectorAll('.str-track');
  tracks.forEach((t, i) => {
    t.className = 'str-track';
    if (i < score) {
      if (score <= 1) t.classList.add('weak');
      else if (score <= 2) t.classList.add('fair');
      else if (score <= 3) t.classList.add('good');
      else t.classList.add('strong');
    }
  });
  const lbl = document.querySelector('.strength-label');
  const lblMap = ['','Weak','Fair','Good','Strong','Very Strong'];
  const clrMap = ['','#ef4444','#f59e0b','#22c55e','#166534','#14532d'];
  lbl.textContent = v ? lblMap[score] : '';
  lbl.style.color = clrMap[score];

  document.querySelectorAll('.str-rule').forEach(el => {
    const key = el.dataset.rule;
    const met = rules[key];
    el.classList.toggle('met', met);
    el.querySelector('svg').innerHTML = met
      ? '<polyline points="20 6 9 17 4 12"/>'
      : '<circle cx="12" cy="12" r="10"/>';
  });

  // re-check confirm
  document.getElementById('password_confirmation').dispatchEvent(new Event('input'));
});

// Confirm password match
document.getElementById('password_confirmation').addEventListener('input', function () {
  const fb = document.getElementById('confirm-feedback');
  if (!this.value) { fb.textContent = ''; this.classList.remove('err'); return; }
  const match = this.value === document.getElementById('password').value;
  fb.textContent = match ? '✓ Passwords match' : '✗ Passwords do not match';
  fb.style.color = match ? '#16a34a' : '#dc2626';
  this.classList.toggle('err', !match);
});
</script>
@endsection
