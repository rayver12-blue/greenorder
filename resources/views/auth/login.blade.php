@extends('layouts.main')
@section('title', 'Sign In')

@section('content')
<style>
  body { background: #f0fdf4; }
  .auth-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background:
      radial-gradient(ellipse at 20% 20%, rgba(74,222,128,.18) 0%, transparent 55%),
      radial-gradient(ellipse at 80% 80%, rgba(22,163,74,.12) 0%, transparent 55%),
      #f0fdf4;
  }
  .auth-logo-block {
    display: flex; flex-direction: column; align-items: center;
    margin-bottom: .5rem;
    animation: fadeDown .5s ease both;
  }
  .auth-logo-block img { width:110px;height:110px;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(22,101,52,.25)); }
  .auth-logo-block h1 { font-size:1.6rem;font-weight:800;color:#166534;letter-spacing:-.3px;margin-bottom:.2rem; }
  .auth-logo-block p { font-size:.82rem;color:#5a7a5a;font-weight:500; }
  .auth-card {
    background:#fff;border-radius:20px;
    box-shadow:0 1px 3px rgba(0,0,0,.06),0 8px 32px rgba(0,0,0,.10),0 0 0 1px rgba(22,163,74,.08);
    padding:2.25rem 2.5rem;width:100%;max-width:440px;
    margin-bottom: 2rem;
    animation:fadeUp .5s ease both .1s;
  }
  .auth-card-title { font-size:1.30rem;font-weight:800;color:#1a2e1a;margin-bottom:.3rem; }
  .auth-card-sub { font-size:.85rem;color:#5a7a5a;margin-bottom:1rem; }
  .field { margin-bottom:1.1rem; }
  .field label { display:block;font-size:.82rem;font-weight:700;color:#1a2e1a;margin-bottom:.4rem; }
  .field label a { font-weight:500;color:#16a34a;text-decoration:none;float:right; }
  .input-wrap { position:relative; }
  .input-wrap svg.ico { position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;width:17px;height:17px; }
  .inp { width:100%;padding:.78rem 2.75rem;border:1.5px solid #d1fae5;border-radius:10px;font-family:inherit;font-size:.9rem;color:#1a2e1a;background:#f0fdf4;outline:none;transition:border-color .2s,box-shadow .2s,background .2s; }
  .inp:focus { border-color:#16a34a;background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.12); }
  .inp.err { border-color:#dc2626;background:#fff; }
  .eye-btn { position:absolute;right:.85rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;padding:0; }
  .eye-btn:hover { color:#16a34a; }
  .err-msg { font-size:.76rem;color:#dc2626;margin-top:.3rem; }
  .remember-row { display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem; }
  .remember-row input { accent-color:#16a34a;width:16px;height:16px;cursor:pointer; }
  .remember-row label { font-size:.84rem;color:#5a7a5a;cursor:pointer; }
  .btn-submit { width:100%;padding:.85rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.95rem;font-weight:700;cursor:pointer;letter-spacing:.01em;transition:opacity .2s,transform .15s,box-shadow .2s;box-shadow:0 4px 14px rgba(22,101,52,.3); }
  .btn-submit:hover { opacity:.92;transform:translateY(-1px); }
  .divider { display:flex;align-items:center;gap:.75rem;color:#9ca3af;font-size:.78rem;margin:1.25rem 0; }
  .divider::before,.divider::after { content:'';flex:1;height:1px;background:#d1fae5; }
  .auth-footer { text-align:center;font-size:.85rem;color:#5a7a5a; }
  .auth-footer a { color:#16a34a;font-weight:700;text-decoration:none; }
  .alert { padding:.75rem 1rem;border-radius:10px;font-size:.84rem;margin-bottom:1.1rem;display:flex;align-items:flex-start;gap:.5rem; }
  .alert-danger { background:#fef2f2;border:1px solid #fecaca;color:#991b1b; }
  .alert-success { background:#f0fdf4;border:1px solid #bbf7d0;color:#166534; }
  .alert-warning { background:#fffbeb;border:1px solid #fde68a;color:#92400e; }
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
    <div class="auth-card-title">Welcome Back 👋</div>
    <div class="auth-card-sub">Sign in with your email or username</div>

    @if(session('error'))
      <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
    @endif
    @if($errors->has('throttle'))
      <div class="alert alert-warning">{{ $errors->first('throttle') }}</div>
    @endif

    <form method="POST" action="{{ route('auth.login.post') }}" novalidate>
      @csrf
      <div class="field">
        <label for="login">Email or Username</label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          <input type="text" id="login" name="login" class="inp {{ $errors->has('login') ? 'err' : '' }}" value="{{ old('login') }}" placeholder="admin  or  you@example.com" autocomplete="username" required>
        </div>
        @error('login')<div class="err-msg">{{ $message }}</div>@enderror
      </div>

      <div class="field">
        <label for="login-pwd">Password <a href="{{ route('auth.forgot') }}">Forgot password?</a></label>
        <div class="input-wrap">
          <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          <input type="password" id="login-pwd" name="password" class="inp {{ $errors->has('password') ? 'err' : '' }}" placeholder="••••••••" autocomplete="current-password" required>
          <button type="button" class="eye-btn" onclick="toggleEye(this,'login-pwd')" aria-label="Show password">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('password')<div class="err-msg">{{ $message }}</div>@enderror
      </div>

      <div class="remember-row">
        <input type="checkbox" id="remember" name="remember">
        <label for="remember">Keep me signed in for 30 days</label>
      </div>

      <button type="submit" class="btn-submit">Sign In →</button>
    </form>

    <div class="divider">or</div>
    <div class="auth-footer">Don't have an account? <a href="{{ route('auth.register') }}">Create one free</a></div>
  </div>
</div>

<script>
function toggleEye(btn, inputId) {
  const inp = document.getElementById(inputId);
  const hidden = inp.type === 'password';
  inp.type = hidden ? 'text' : 'password';
  btn.innerHTML = hidden
    ? `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
    : `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}
</script>
@endsection


