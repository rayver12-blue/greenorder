@extends('layouts.main')
@section('title', 'Account Profile')

@section('content')
<style>
/* ── Sidebar (reuse from dashboard) ── */
body{overflow-x:hidden}
img,canvas,svg{max-width:100%}
.admin-layout{display:flex;min-height:100vh}
.admin-layout,.profile-grid,.modal-card{min-width:0}
.sidebar{width:260px;background:#fff;border-right:1px solid #dcfce7;display:flex;flex-direction:column;padding:1.5rem 0;position:fixed;top:0;left:0;height:100vh;overflow-y:auto}
.sidebar-brand{display:flex;align-items:center;gap:.75rem;padding:0 1.5rem 1.5rem;border-bottom:1px solid #dcfce7;margin-bottom:1rem}
.sidebar-brand img{width:36px;height:36px;object-fit:contain}
.sidebar-brand-text strong{display:block;font-size:1rem;font-weight:800;color:#166534}
.sidebar-brand-text span{font-size:.72rem;color:#5a7a5a}
.sidebar-label{font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#5a7a5a;padding:0 1.5rem;margin:1rem 0 .5rem}
.sidebar-nav{list-style:none;padding:0 .75rem}
.sidebar-nav li a,.sidebar-nav li button{display:flex;align-items:center;gap:.75rem;padding:.7rem .85rem;border-radius:10px;font-size:.875rem;font-weight:500;color:#5a7a5a;text-decoration:none;width:100%;background:none;border:none;cursor:pointer;font-family:inherit;transition:all .15s}
.sidebar-nav li a:hover,.sidebar-nav li button:hover{background:#f0fdf4;color:#166534}
.sidebar-nav li a.active{background:#dcfce7;color:#166534;font-weight:700}
.sidebar-nav li.logout button{color:#dc2626}
.sidebar-nav li.logout button:hover{background:#fef2f2}
.main-content{margin-left:260px;flex:1;padding:2rem;background:#f5f5f0;min-height:100vh;min-width:0}

/* ── Profile page ── */
.profile-grid{display:grid;grid-template-columns:1fr 1.4fr;gap:1.5rem;max-width:900px;min-width:0}
.profile-card,.pw-card{min-width:0}
.profile-card{background:#fff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 16px rgba(0,0,0,.08);overflow:hidden}
.profile-card-header{background:linear-gradient(135deg,#166534,#16a34a);padding:2rem;text-align:center;position:relative}
.profile-avatar{width:88px;height:88px;border-radius:50%;background:rgba(255,255,255,.2);border:3px solid rgba(255,255,255,.5);display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:800;color:#fff;margin:0 auto 1rem;backdrop-filter:blur(4px)}
.profile-name{font-size:1.25rem;font-weight:800;color:#fff;margin-bottom:.25rem}
.profile-username{font-size:.85rem;color:rgba(255,255,255,.8);margin-bottom:.75rem}
.profile-role-badge{display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);border-radius:20px;padding:.3rem .85rem;font-size:.78rem;font-weight:700;color:#fff}
.profile-card-body{padding:1.5rem}
.info-row{display:flex;align-items:center;justify-content:space-between;padding:.75rem 0;border-bottom:1px solid #f0fdf4;font-size:.875rem}
.info-row:last-child{border-bottom:none}
.info-label{color:#5a7a5a;font-weight:600;display:flex;align-items:center;gap:.5rem}
.info-value{font-weight:700;color:#1a2e1a}
.info-icon{width:30px;height:30px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;color:#16a34a}
.audit-btn{display:inline-flex;align-items:center;gap:.4rem;margin-top:1rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.55rem .9rem;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s}
.audit-btn:hover{background:#14532d}
.audit-actions{display:flex;gap:.5rem;flex-wrap:wrap}
.login-btn{background:#0f766e}
.login-btn:hover{background:#115e59}

/* ── Change Password card ── */
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

/* Strength */
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
.alert{padding:.75rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:flex-start;gap:.5rem}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.page-title{font-size:1.5rem;font-weight:800;color:#1a2e1a;margin-bottom:1.5rem}

/* â”€â”€ Audit modal â”€â”€ */
.modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;z-index:300;padding:1.25rem}
.modal.show{display:flex}
.modal-card{background:#fff;border-radius:16px;max-width:820px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;border:1px solid #e5e7eb}
.modal-hd{display:flex;align-items:center;justify-content:space-between;gap:.75rem;padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4}
.modal-hd h3{margin:0;font-size:1rem;font-weight:800;color:#1a2e1a}
.modal-actions{display:flex;align-items:center;gap:.5rem}
.modal-close{background:none;border:none;font-size:1.2rem;cursor:pointer;color:#9ca3af;padding:.1rem .25rem}
.modal-body{padding:1rem 1.25rem;max-height:60vh;overflow:auto}
.log-item{display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;padding:.55rem 0;border-bottom:1px solid #f9fafb;font-size:.85rem}
.log-item:last-child{border-bottom:none}
.log-msg{font-weight:600;color:#1a2e1a}
.log-meta{font-size:.72rem;color:#9ca3af;white-space:nowrap}
.log-chip{display:inline-flex;align-items:center;gap:.25rem;background:#f0fdf4;color:#166534;border-radius:20px;padding:.12rem .5rem;font-size:.7rem;font-weight:700;margin-left:.35rem}
.log-chip.alt{background:#f8fafc;color:#475569}
.btn-print{display:inline-flex;align-items:center;gap:.45rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.45rem .8rem;font-size:.75rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s}
.btn-print:hover{background:#14532d}

@media (max-width: 980px){
  .admin-layout{flex-direction:column}
  .sidebar{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sidebar-brand{padding:0 1rem 1rem}
  .sidebar-label{padding:0 1rem}
  .sidebar-nav{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .main-content{margin-left:0;padding:1rem}
  .profile-grid{grid-template-columns:1fr;max-width:100%}
  .profile-card-header{padding:1.5rem}
  .profile-card-body,.pw-card{padding:1.25rem}
  .audit-actions{flex-direction:column;align-items:stretch}
  .audit-btn{width:100%;justify-content:center}
}

@media print{
  body{background:#fff}
  .sidebar,.main-content,.sidebar-overlay{display:none !important}
  #audit-modal{position:static;display:block !important;background:none;padding:0}
  #audit-modal .modal-card{max-width:100%;box-shadow:none;border:1px solid #e5e7eb}
  #audit-modal .modal-close,#audit-modal .btn-print{display:none !important}
  #audit-modal .modal-body{max-height:none;overflow:visible}
}
</style>

<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
      <div class="sidebar-brand-text"><strong>GreenOrder</strong><span>Admin Console</span></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
      <li><a href="{{ route('admin.products') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>Manage Products</a></li>
      <li><a href="{{ route('admin.customers') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Customers</a></li>
      <li><a href="{{ route('admin.orders') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Review Orders</a></li>
      <li><a href="{{ route('admin.reports') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Analyze Report</a></li>
    </ul>
    <div class="sidebar-label">Account</div>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.profile') }}" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Account Profile</a></li>
      <li class="logout">
        <form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
          <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Log Out</button>
        </form>
      </li>
    </ul>
  </aside>

  <main class="main-content">
    <div class="page-title">Account Profile</div>

    @if(session('success'))
      <div class="alert alert-success" style="max-width:900px;margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger" style="max-width:900px;margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ session('error') }}
      </div>
    @endif

    <div class="profile-grid">

      <!-- LEFT: Profile Card -->
      <div class="profile-card">
        <div class="profile-card-header">
          <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
          <div class="profile-name">{{ auth()->user()->name }}</div>
          <div class="profile-username">{{ '@'.auth()->user()->username }}</div>
          <span class="profile-role-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            {{ ucfirst(auth()->user()->role) }}
          </span>
        </div>
        <div class="profile-card-body">
          @php $u = auth()->user(); @endphp
          @foreach([
            ['person','Username','@'.$u->username],
            ['mail','Email',$u->email],
            ['phone','Mobile',$u->mobile],
            ['calendar','Birthdate',$u->birthdate?->format('F j, Y') ?? '—'],
            ['clock','Member Since',$u->created_at->format('M j, Y')],
          ] as [$icon,$label,$value])
          <div class="info-row">
            <div class="info-label">
              <div class="info-icon">
                @if($icon==='person')
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                @elseif($icon==='mail')
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                @elseif($icon==='phone')
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                @elseif($icon==='calendar')
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                @else
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                @endif
              </div>
              {{ $label }}
            </div>
            <div class="info-value">{{ $value }}</div>
          </div>
          @endforeach
          <div class="audit-actions">
            <button type="button" class="audit-btn" id="audit-open">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/></svg>
              View Audit Logs
            </button>
            <button type="button" class="audit-btn login-btn" id="login-open">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
              View Login Logs
            </button>
          </div>
        </div>
      </div>

      <!-- RIGHT: Change Password -->
      <div class="pw-card">
        <div class="pw-card-title">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          Change Password
        </div>
        <div class="pw-card-sub">Keep your account secure by updating your password regularly.</div>

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
            <!-- Strength meter -->
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

    </div><!-- /profile-grid -->
  </main>
</div>

<div class="modal" id="audit-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="audit-title">
    <div class="modal-hd">
      <h3 id="audit-title">All Order Audit Logs</h3>
      <div class="modal-actions">
        <button type="button" class="btn-print" id="audit-print">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v-5a2 2 0 0 1-2-2h-2"/><path d="M6 14h12v8H6z"/></svg>
          Print Logs
        </button>
        <button type="button" class="modal-close" id="audit-close" aria-label="Close">x</button>
      </div>
    </div>
    <div class="modal-body" id="audit-print-area">
      @if(($auditLogs ?? collect())->isEmpty())
        <div style="color:#9ca3af;font-size:.85rem;text-align:center;padding:1.5rem 0">No audit logs yet.</div>
      @else
        @foreach($auditLogs as $log)
          <div class="log-item">
            <div class="log-msg">
              {{ $log->message }}
              <span class="log-chip">Order #{{ str_pad($log->order_id, 4, '0', STR_PAD_LEFT) }}</span>
              @if($log->actor)
                <span class="log-chip alt">By: {{ $log->actor->name }}</span>
              @elseif($log->actor_role)
                <span class="log-chip alt">By: {{ ucfirst($log->actor_role) }}</span>
              @endif
            </div>
            <div class="log-meta">{{ $log->created_at->format('M j, Y g:i A') }}</div>
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>

<div class="modal" id="login-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="login-title">
    <div class="modal-hd">
      <h3 id="login-title">Recent Logins</h3>
      <button type="button" class="modal-close" id="login-close" aria-label="Close">x</button>
    </div>
    <div class="modal-body">
      @if(($loginLogs ?? collect())->isEmpty())
        <div style="color:#9ca3af;font-size:.85rem;text-align:center;padding:1.5rem 0">No login logs yet.</div>
      @else
        @foreach($loginLogs as $log)
          <div class="log-item">
            <div class="log-msg">
              Logged in {{ $log->logged_in_at->format('M j, Y g:i A') }}
              <span class="log-chip">User: {{ $log->user?->name ?? 'Unknown' }}</span>
              <span class="log-chip">IP {{ $log->ip_address ?? 'Unknown' }}</span>
              <span class="log-chip alt">{{ ucfirst($log->role) }}</span>
              @if($log->user_agent)
                <div style="margin-top:.25rem;font-size:.72rem;color:#94a3b8">
                  {{ \Illuminate\Support\Str::limit($log->user_agent, 120) }}
                </div>
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
const auditPrint = document.getElementById('audit-print');
const loginModal = document.getElementById('login-modal');
const loginOpen = document.getElementById('login-open');
const loginClose = document.getElementById('login-close');
function openAudit() {
  auditModal.classList.add('show');
  auditModal.setAttribute('aria-hidden', 'false');
}
function closeAudit() {
  auditModal.classList.remove('show');
  auditModal.setAttribute('aria-hidden', 'true');
}
function printAuditLogs() {
  if (!auditModal.classList.contains('show')) openAudit();
  window.print();
}
auditOpen?.addEventListener('click', openAudit);
auditClose?.addEventListener('click', closeAudit);
auditPrint?.addEventListener('click', printAuditLogs);
auditModal?.addEventListener('click', (e) => {
  if (e.target === auditModal) closeAudit();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && auditModal.classList.contains('show')) closeAudit();
});

function openLogin() {
  loginModal.classList.add('show');
  loginModal.setAttribute('aria-hidden', 'false');
}
function closeLogin() {
  loginModal.classList.remove('show');
  loginModal.setAttribute('aria-hidden', 'true');
}
loginOpen?.addEventListener('click', openLogin);
loginClose?.addEventListener('click', closeLogin);
loginModal?.addEventListener('click', (e) => {
  if (e.target === loginModal) closeLogin();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && loginModal.classList.contains('show')) closeLogin();
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
  fb.textContent = match ? '✓ Passwords match' : '✗ Passwords do not match';
  fb.style.color = match ? '#16a34a' : '#dc2626';
  confInp.classList.toggle('err', !match);
  confInp.classList.toggle('ok', match);
}
</script>
@endsection
