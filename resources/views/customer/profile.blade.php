@extends('layouts.main')
@section('title', 'My Profile')

@section('content')
<style>
body{background:#f0fdf4;overflow-x:hidden}
img,canvas,svg{max-width:100%}
/* Navbar same as home */
.nav{background:#fff;border-bottom:1px solid #dcfce7;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.nav-brand{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-brand img{width:100px;height:100px;object-fit:contain}
.nav-brand-text strong{font-size:1rem;font-weight:800;color:#166534;display:block;line-height:1.1}
.nav-brand-text span{font-size:.65rem;color:#5a7a5a}
.nav-r{display:flex;align-items:center;gap:.5rem}
.nb{background:#f0fdf4;border:none;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#166534;text-decoration:none;transition:background .15s;position:relative;flex-shrink:0}
.nb:hover{background:#dcfce7}
.uchip{display:flex;align-items:center;gap:.45rem;background:#f0fdf4;border-radius:10px;padding:.35rem .8rem;border:1px solid #d1fae5;text-decoration:none}
.uav{width:26px;height:26px;border-radius:50%;background:#166534;color:#fff;font-weight:800;font-size:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.unm{font-size:.82rem;font-weight:600;color:#1a2e1a}

/* Page */
.page-wrap{max-width:860px;margin:0 auto;padding:2rem}
.page-title{font-size:1.5rem;font-weight:800;color:#1a2e1a;margin-bottom:1.5rem;display:flex;align-items:center;gap:.6rem}

/* Profile grid */
.profile-grid{display:grid;grid-template-columns:1fr 1.5fr;gap:1.5rem}

/* Profile card */
.profile-card{background:#fff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.08);overflow:hidden}
.profile-card-header{background:linear-gradient(135deg,#166534,#16a34a);padding:2rem;text-align:center}
.profile-avatar{width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:800;color:#fff;margin:0 auto 1rem}
.profile-name{font-size:1.2rem;font-weight:800;color:#fff;margin-bottom:.2rem}
.profile-username{font-size:.83rem;color:rgba(255,255,255,.8);margin-bottom:.7rem}
.profile-badge{display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);border-radius:20px;padding:.3rem .85rem;font-size:.75rem;font-weight:700;color:#fff}
.profile-card-body{padding:1.5rem}
.info-row{display:flex;align-items:center;justify-content:space-between;padding:.72rem 0;border-bottom:1px solid #f0fdf4;font-size:.875rem}
.info-row:last-child{border-bottom:none}
.info-label{color:#5a7a5a;font-weight:600;display:flex;align-items:center;gap:.5rem}
.info-icon{width:28px;height:28px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;color:#16a34a;flex-shrink:0}
.info-value{font-weight:700;color:#1a2e1a}
.audit-btn{display:inline-flex;align-items:center;gap:.4rem;margin-top:1rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.55rem .9rem;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s}
.audit-btn:hover{background:#14532d}

/* Password card */
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
.str-tracks{display:flex;gap:4px;margin-top:.5rem;margin-bottom:.3rem}
.str-track{flex:1;height:4px;border-radius:2px;background:#d1fae5;transition:background .3s}
.str-track.weak{background:#ef4444}.str-track.fair{background:#f59e0b}.str-track.good{background:#22c55e}.str-track.strong{background:#166534}
.str-label{font-size:.72rem;font-weight:700}
.str-rules{margin-top:.6rem;display:grid;grid-template-columns:1fr 1fr;gap:.28rem}
.str-rule{font-size:.72rem;display:flex;align-items:center;gap:.35rem;color:#9ca3af}
.str-rule.met{color:#16a34a}
.btn-save{width:100%;padding:.8rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.9rem;font-weight:700;cursor:pointer;margin-top:.75rem;transition:opacity .2s,transform .15s;box-shadow:0 4px 14px rgba(22,101,52,.25)}
.btn-save:hover{opacity:.92;transform:translateY(-1px)}
.alert{padding:.75rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.5rem}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
/* Audit modal */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;z-index:300;padding:1.25rem}
.modal.show{display:flex}
.modal-card{background:#fff;border-radius:16px;max-width:640px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;border:1px solid #e5e7eb}
.modal-hd{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4}
.modal-hd h3{margin:0;font-size:1rem;font-weight:800;color:#1a2e1a}
.modal-close{background:none;border:none;font-size:1.2rem;cursor:pointer;color:#9ca3af;padding:.1rem .25rem}
.modal-body{padding:1rem 1.25rem;max-height:60vh;overflow:auto}
.log-item{display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;padding:.55rem 0;border-bottom:1px solid #f9fafb;font-size:.85rem}
.log-item:last-child{border-bottom:none}
.log-msg{font-weight:600;color:#1a2e1a}
.log-meta{font-size:.72rem;color:#9ca3af;white-space:nowrap}
.log-chip{display:inline-flex;align-items:center;gap:.25rem;background:#f0fdf4;color:#166534;border-radius:20px;padding:.12rem .5rem;font-size:.7rem;font-weight:700;margin-left:.35rem}

@media (max-width: 900px){
  .nav{height:auto;padding:.75rem 1rem;flex-wrap:wrap;gap:.6rem}
  .nav-brand img{width:32px;height:32px}
  .nav-brand{min-width:0;flex:1}
  .nav-brand-text span{display:none}
  .nav-r{flex-wrap:wrap;width:100%;justify-content:space-between}
  .unm{display:none}
  .page-wrap{padding:1rem}
  .profile-grid{grid-template-columns:1fr}
  .profile-card-header{padding:1.5rem}
  .profile-card-body,.pw-card{padding:1.25rem}
  .audit-actions{flex-direction:column;align-items:stretch}
  .audit-btn{width:100%;justify-content:center}
}
@media (max-width: 420px){
  .nav{padding:.65rem .85rem}
  .nav-brand img{width:34px;height:34px}
  .page-title{font-size:1.25rem}
  .profile-avatar{width:72px;height:72px;font-size:1.8rem}
  .info-row{gap:.6rem;align-items:flex-start}
  .info-value{max-width:55%;text-align:right;word-break:break-word}
  .str-rules{grid-template-columns:1fr}
}
</style>

<!-- Navbar -->
<nav class="nav">
  <a href="{{ route('customer.home') }}" class="nav-brand">
    <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
    <div class="nav-brand-text"><strong>GreenOrder</strong><span>Food Ordering Platform</span></div>
  </a>
  <div class="nav-r">
    <a href="{{ route('customer.home') }}" class="nb" title="Home">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </a>
    <a href="{{ route('customer.orders') }}" class="nb" title="My Orders">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </a>
    <a href="{{ route('customer.profile') }}" class="uchip">
      <div class="uav">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
      <span class="unm">{{ auth()->user()->name }}</span>
    </a>
    <form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
      <button type="submit" class="nb" title="Sign Out">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </form>
  </div>
</nav>

<div class="page-wrap">
  <div class="page-title">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
    My Profile
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.25rem;">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:1.25rem;">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
  @endif

  <div class="profile-grid">
    <div class="profile-card">
      <div class="profile-card-header">
        <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="profile-name">{{ auth()->user()->name }}</div>
        <div class="profile-username">{{ '@'.auth()->user()->username }}</div>
        <span class="profile-badge">
          <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Customer
        </span>
      </div>
      <div class="profile-card-body">
        @php $u = auth()->user(); @endphp
        <div class="info-row">
          <div class="info-label"><div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>Username</div>
          <div class="info-value">{{ '@'.$u->username }}</div>
        </div>
        <div class="info-row">
          <div class="info-label"><div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>Email</div>
          <div class="info-value">{{ $u->email }}</div>
        </div>
        <div class="info-row">
          <div class="info-label"><div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg></div>Mobile</div>
          <div class="info-value">{{ $u->mobile }}</div>
        </div>
        <div class="info-row">
          <div class="info-label"><div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>Birthdate</div>
          <div class="info-value">{{ $u->birthdate?->format('F j, Y') ?? '-' }}</div>
        </div>
        <div class="info-row">
          <div class="info-label"><div class="info-icon"><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>Member Since</div>
          <div class="info-value">{{ $u->created_at->format('M j, Y') }}</div>
        </div>
        <button type="button" class="audit-btn" id="audit-open">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/></svg>
          View Activity Log
        </button>
      </div>
    </div>

    <div class="pw-card">
      <div class="pw-card-title">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Change Password
      </div>
      <div class="pw-card-sub">Update your password to keep your account secure.</div>

      @if($errors->any())
        <div class="alert alert-danger">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <div>{{ $errors->first() }}</div>
        </div>
      @endif

      <form method="POST" action="{{ route('profile.password') }}" novalidate>
        @csrf @method('PUT')

        <div class="field">
          <label>Current Password</label>
          <div class="input-wrap">
            <svg class="ico" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <input type="password" id="current_password" name="current_password" class="inp @error('current_password') err @enderror" placeholder="Your current password" required>
            <button type="button" class="eye-btn" onclick="toggleEye(this,'current_password')">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          @error('current_password')<div class="err-msg">{{ $message }}</div>@enderror
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
    </div>
  </div>
</div>

<div class="modal" id="audit-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="audit-title">
    <div class="modal-hd">
      <h3 id="audit-title">Recent Activity</h3>
      <button type="button" class="modal-close" id="audit-close" aria-label="Close">x</button>
    </div>
    <div class="modal-body">
      @if($auditLogs->isEmpty())
        <div style="color:#9ca3af;font-size:.85rem;text-align:center;padding:1.5rem 0">No recent activity yet.</div>
      @else
        @foreach($auditLogs as $log)
          <div class="log-item">
            <div class="log-msg">
              {{ $log->message }}
              <span class="log-chip">Order #{{ str_pad($log->order_id, 4, '0', STR_PAD_LEFT) }}</span>
              @if($log->actor_role)
                <span style="color:#9ca3af;font-weight:600">({{ ucfirst($log->actor_role) }})</span>
              @endif
            </div>
            <div class="log-meta">{{ $log->created_at->format('M j, Y g:i A') }}</div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>

<script>
const auditModal = document.getElementById('audit-modal');
const auditOpen = document.getElementById('audit-open');
const auditClose = document.getElementById('audit-close');
function openAudit() {
  auditModal.classList.add('show');
  auditModal.setAttribute('aria-hidden', 'false');
}
function closeAudit() {
  auditModal.classList.remove('show');
  auditModal.setAttribute('aria-hidden', 'true');
}
auditOpen?.addEventListener('click', openAudit);
auditClose?.addEventListener('click', closeAudit);
auditModal?.addEventListener('click', (e) => {
  if (e.target === auditModal) closeAudit();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && auditModal.classList.contains('show')) closeAudit();
});

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
  const rules = { length:v.length>=8, upper:/[A-Z]/.test(v), lower:/[a-z]/.test(v), num:/[0-9]/.test(v), sym:/[^A-Za-z0-9]/.test(v) };
  const score = Object.values(rules).filter(Boolean).length;
  document.querySelectorAll('.str-track').forEach((t,i) => {
    t.className='str-track';
    if(i<score) t.classList.add(score<=1?'weak':score<=2?'fair':score<=3?'good':'strong');
  });
  const lbl=document.getElementById('str-label');
  lbl.textContent=v?['','Weak','Fair','Good','Strong','Very Strong'][score]:'';
  lbl.style.color=['','#ef4444','#f59e0b','#22c55e','#166534','#14532d'][score];
  document.querySelectorAll('.str-rule').forEach(el=>{
    const met=rules[el.dataset.rule];
    el.classList.toggle('met',met);
    el.querySelector('svg').innerHTML=met?'<polyline points="20 6 9 17 4 12"/>':'<circle cx="12" cy="12" r="10"/>';
  });
  checkConfirm();
});
const confInp=document.getElementById('new_password_confirmation');
confInp.addEventListener('input',checkConfirm);
function checkConfirm(){
  const fb=document.getElementById('conf-fb'),v=confInp.value;
  if(!v){fb.textContent='';confInp.classList.remove('err','ok');return;}
  const match=v===pwInp.value;
  fb.textContent=match?'Passwords match':'Passwords do not match';
  fb.style.color=match?'#16a34a':'#dc2626';
  confInp.classList.toggle('err',!match);confInp.classList.toggle('ok',match);
}
</script>
@endsection
