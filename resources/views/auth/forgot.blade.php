@extends('layouts.main')
@section('title', 'Forgot Password')

@section('content')
<style>
.pw-card{background:#fff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.08);padding:1.75rem}
.pw-card-title{font-size:1.05rem;font-weight:800;color:#1a2e1a;margin-bottom:.25rem;display:flex;align-items:center;gap:.6rem}
.pw-card-sub{font-size:.82rem;color:#5a7a5a;margin-bottom:1.5rem}
.field{margin-bottom:1rem}
.field label{display:block;font-size:.8rem;font-weight:700;color:#1a2e1a;margin-bottom:.35rem}
.input-wrap{position:relative}
.input-wrap svg.ico{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;width:16px;height:16px}
.inp{width:100%;padding:.72rem 2.5rem;border:1.5px solid #d1fae5;border-radius:10px;font-family:inherit;font-size:.875rem;color:#1a2e1a;background:#f0fdf4;outline:none;transition:border-color .2s,box-shadow .2s,background .2s}
.inp:focus{border-color:#16a34a;background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.12)}
.inp.err{border-color:#dc2626;background:#fff}
.inp.ok{border-color:#22c55e}
.eye-btn{position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;padding:0}
.eye-btn:hover{color:#16a34a}
.err-msg{font-size:.75rem;color:#dc2626;margin-top:.28rem}
.ok-msg{font-size:.75rem;color:#16a34a;margin-top:.28rem;font-weight:600}
.str-tracks{display:flex;gap:4px;margin-top:.5rem;margin-bottom:.3rem}
.str-track{flex:1;height:4px;border-radius:2px;background:#d1fae5;transition:background .3s}
.str-track.weak{background:#ef4444}
.str-track.fair{background:#f59e0b}
.str-track.good{background:#22c55e}
.str-track.strong{background:#166534}
.str-label{font-size:.72rem;font-weight:700}
.str-rules{margin-top:.6rem;display:grid;grid-template-columns:1fr 1fr;gap:.28rem}
.str-rule{font-size:.72rem;display:flex;align-items:center;gap:.35rem;color:#9ca3af}
.str-rule.met{color:#16a34a}
.btn-save{width:100%;padding:.8rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer;margin-top:.75rem;transition:opacity .2s,transform .15s;box-shadow:0 4px 14px rgba(22,101,52,.25)}
.btn-save:hover{opacity:.92;transform:translateY(-1px)}
</style>
<div class="auth-page">
  <div class="auth-left">
    <div class="auth-left-content">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder" class="auth-left-logo">
      <h1>Reset Your Password</h1>
      <p>Enter your email and choose a new password to regain access.</p>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-card">
      <div class="pw-card">
        <div class="pw-card-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Reset Password
        </div>
        <div class="pw-card-sub">Use your email to set a new password.</div>

        <form method="POST" action="{{ route('password.email') }}" novalidate>
          @csrf
          <div class="field">
            <label>Email Address</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <input type="email" id="email" name="email" class="inp @error('email') err @enderror" value="{{ old('email') }}" placeholder="you@example.com" required>
            </div>
            @error('email')<div class="err-msg">{{ $message }}</div>@enderror
          </div>

          <div class="field">
            <label>New Password</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" id="new_password" name="new_password" class="inp @error('new_password') err @enderror" placeholder="At least 8 characters" required>
              <button type="button" class="eye-btn" onclick="toggleEye(this,'new_password')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            @error('new_password')<div class="err-msg">{{ $message }}</div>@enderror
            <div class="str-tracks">
              <div class="str-track"></div><div class="str-track"></div>
              <div class="str-track"></div><div class="str-track"></div><div class="str-track"></div>
            </div>
            <span class="str-label" id="str-label"></span>
            <div class="str-rules">
              <div class="str-rule" data-rule="length"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Min 8 chars</div>
              <div class="str-rule" data-rule="upper"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Uppercase</div>
              <div class="str-rule" data-rule="lower"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Lowercase</div>
              <div class="str-rule" data-rule="num"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Number</div>
              <div class="str-rule" data-rule="sym"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/></svg>Symbol</div>
            </div>
          </div>

          <div class="field">
            <label>Confirm New Password</label>
            <div class="input-wrap">
              <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="inp" placeholder="Repeat new password" required>
              <button type="button" class="eye-btn" onclick="toggleEye(this,'new_password_confirmation')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div id="conf-fb" style="font-size:.75rem;font-weight:700;margin-top:.28rem;"></div>
          </div>

          <button type="submit" class="btn-save">Update Password</button>
        </form>

        <div class="auth-card-footer" style="margin-top:1rem;">
          <a href="{{ route('auth.login') }}" onclick="event.preventDefault(); window.location.replace('{{ route('auth.login') }}');">Back to Sign In</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleEye(btn, id) {
  const inp = document.getElementById(id);
  const h = inp.type === 'password';
  inp.type = h ? 'text' : 'password';
  btn.innerHTML = h
    ? `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`
    : `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
}

const pwInp = document.getElementById('new_password');
pwInp.addEventListener('input', function () {
  const v = this.value;
  const rules = { length: v.length>=8, upper:/[A-Z]/.test(v), lower:/[a-z]/.test(v), num:/[0-9]/.test(v), sym:/[^A-Za-z0-9]/.test(v) };
  const score = Object.values(rules).filter(Boolean).length;
  document.querySelectorAll('.str-track').forEach((t, i) => {
    t.className = 'str-track';
    if (i < score) t.classList.add(score<=1?'weak':score<=2?'fair':score<=3?'good':'strong');
  });
  const lbl = document.getElementById('str-label');
  lbl.textContent = v ? ['','Weak','Fair','Good','Strong','Very Strong'][score] : '';
  lbl.style.color = ['','#ef4444','#f59e0b','#22c55e','#166534','#14532d'][score];
  document.querySelectorAll('.str-rule').forEach(el => {
    const met = rules[el.dataset.rule];
    el.classList.toggle('met', met);
    el.querySelector('svg').innerHTML = met ? '<polyline points="20 6 9 17 4 12"/>' : '<circle cx="12" cy="12" r="10"/>';
  });
  checkConfirm();
});

const confInp = document.getElementById('new_password_confirmation');
confInp.addEventListener('input', checkConfirm);
function checkConfirm() {
  const fb = document.getElementById('conf-fb');
  const v = confInp.value;
  if (!v) { fb.textContent = ''; confInp.classList.remove('err','ok'); return; }
  const match = v === pwInp.value;
  fb.textContent = match ? 'Password match' : 'Password do not match';
  fb.style.color = match ? '#16a34a' : '#dc2626';
  confInp.classList.toggle('err', !match);
  confInp.classList.toggle('ok', match);
}
</script>
@endsection
