@extends('layouts.main')
@section('title', 'Home')
@section('content')
@php
  $todayKey = strtolower(now()->format('l'));
  $weekdays = ['monday','tuesday','wednesday','thursday','friday','saturday'];
  $allowedTabs = ($todayKey === 'sunday') ? ['common'] : ['common', $todayKey];
@endphp
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;font-family:'Plus Jakarta Sans',sans-serif;margin:0;overflow-x:hidden}
img,canvas,svg{max-width:100%}
.nav{background:#fff;border-bottom:1px solid #dcfce7;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:200;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.nav-brand{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-brand img{width:100px;height:100px;object-fit:contain}
.nav-brand-text strong{font-size:1rem;font-weight:800;color:#166534;display:block;line-height:1.1}
.nav-brand-text span{font-size:.65rem;color:#5a7a5a}
.nav-r{display:flex;align-items:center;gap:.5rem}
.nb{background:#f0fdf4;border:none;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#166534;text-decoration:none;transition:background .15s;position:relative;flex-shrink:0}
.nb:hover{background:#dcfce7}
.cart-dot{position:absolute;top:-4px;right:-4px;background:#16a34a;color:#fff;border-radius:50%;min-width:18px;height:18px;font-size:.6rem;font-weight:800;display:none;align-items:center;justify-content:center;padding:0 3px}
.uchip{display:flex;align-items:center;gap:.45rem;background:#f0fdf4;border-radius:10px;padding:.35rem .8rem;border:1px solid #d1fae5;text-decoration:none}
.uav{width:26px;height:26px;border-radius:50%;background:#166534;color:#fff;font-weight:800;font-size:.75rem;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.unm{font-size:.82rem;font-weight:600;color:#1a2e1a}
.hero{background:linear-gradient(135deg,#14532d,#166534 45%,#16a34a 80%);padding:2.25rem 1.5rem;overflow:hidden;position:relative}
.hero::after{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='52' height='52' viewBox='0 0 52 52' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.04'%3E%3Cpath d='M10 10h4v4h-4zM28 10h4v4h-4zM10 28h4v4h-4zM28 28h4v4h-4z'/%3E%3C/g%3E%3C/svg%3E");pointer-events:none}
.hero-in{max-width:1200px;margin:0 auto;position:relative;z-index:1;min-width:0}
.hero-tag{font-size:.68rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:.45rem}
.hero h1{font-size:1.8rem;font-weight:800;color:#fff;line-height:1.2;margin-bottom:.5rem;max-width:480px}
.hero p{color:rgba(255,255,255,.8);font-size:.86rem;max-width:420px;line-height:1.6}
.hero-pill{display:inline-flex;align-items:center;gap:.35rem;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;padding:.28rem .8rem;font-size:.72rem;font-weight:700;color:#fff;margin-top:.75rem}
.wrap{max-width:1200px;margin:0 auto;padding:1.5rem 1.5rem 3rem}
.flash{padding:.7rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.fs{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.fe{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
.sh{display:flex;align-items:center;justify-content:space-between;margin-bottom:.85rem}
.sh-t{font-size:.98rem;font-weight:800;color:#1a2e1a}
.sh-b{background:#dcfce7;color:#166534;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:20px}
.bs{display:grid;grid-template-columns:repeat(3,1fr);gap:.9rem;margin-bottom:1.5rem}
@media(max-width:680px){.bs{grid-template-columns:1fr 1fr}}
.sb{display:flex;align-items:center;background:#fff;border:1.5px solid #d1fae5;border-radius:50px;padding:.5rem 1.1rem;gap:.6rem;margin-bottom:.65rem;transition:border-color .2s,box-shadow .2s}
.sb:focus-within{border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.1)}
.sb input{border:none;outline:none;font-family:inherit;font-size:.87rem;color:#1a2e1a;flex:1;background:transparent}
.frow{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem}
.fcat{padding:.3rem .75rem;border-radius:20px;font-size:.76rem;font-weight:700;border:1.5px solid #e5e7eb;background:#fff;color:#9ca3af;cursor:pointer;font-family:inherit;transition:all .15s;white-space:nowrap}
.fcat.active{background:#166534;color:#fff;border-color:#166534}
.fcat:hover:not(.active){border-color:#16a34a;color:#166534}
.fprice{display:flex;align-items:center;gap:.35rem;margin-left:auto}
.fprice label{font-size:.74rem;color:#5a7a5a;font-weight:600;white-space:nowrap}
.fprice input[type=range]{accent-color:#16a34a;width:90px;cursor:pointer}
.fprice span{font-size:.74rem;font-weight:700;color:#166534;min-width:52px}
.banner{border-radius:12px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.55rem;font-size:.82rem;font-weight:600;line-height:1.45}
.banner strong{min-width:0}
.banner-green{background:linear-gradient(135deg,#dcfce7,#bbf7d0);border:1.5px solid #86efac;color:#166534}
.banner-orange{background:linear-gradient(135deg,#fff7ed,#fed7aa);border:1.5px solid #fdba74;color:#9a3412}
.tabs{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:1.2rem}
.tab{padding:.38rem .95rem;border-radius:20px;font-size:.8rem;font-weight:600;border:1.5px solid #e5e7eb;background:#fff;color:#9ca3af;font-family:inherit;display:inline-flex;align-items:center;gap:.28rem;transition:all .15s;cursor:pointer;white-space:nowrap}
.tab:not(:disabled):not(.active):hover{border-color:#16a34a;color:#166534}
.tab.active{background:#166534;color:#fff;border-color:#166534}
.tab.today{border-color:#16a34a;color:#16a34a}
.tab.today.active{background:#16a34a;border-color:#16a34a;color:#fff}
.tab:disabled{opacity:.32;cursor:not-allowed}
.tdot{width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.pg{display:grid;grid-template-columns:repeat(auto-fill,minmax(188px,1fr));gap:.9rem}
.pc{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.05);border:1px solid #f0fdf4;transition:transform .18s,box-shadow .18s}
.pc:hover{transform:translateY(-2px);box-shadow:0 4px 18px rgba(0,0,0,.1)}
.pc-cover{width:100%;height:130px;object-fit:cover;display:block;background:#f0fdf4;cursor:pointer}
.pc-b{padding:.8rem;min-width:0}
.pc-n{font-weight:700;font-size:.87rem;margin-bottom:.12rem;color:#1a2e1a;line-height:1.35}
.pc-s{font-size:.72rem;color:#9ca3af;margin-bottom:.2rem;line-height:1.3}
.pc-stars{display:flex;align-items:center;gap:1px;margin-bottom:.3rem}
.star-f{color:#f59e0b;font-size:.75rem}
.star-e{color:#d1d5db;font-size:.75rem}
.star-val{font-size:.68rem;color:#9ca3af;margin-left:.2rem}
.pc-badges{display:flex;gap:.35rem;flex-wrap:wrap;margin-bottom:.4rem}
.stock-badge{display:inline-flex;align-items:center;gap:.25rem;border-radius:20px;padding:.12rem .5rem;font-size:.66rem;font-weight:700}
.stock-low{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
.stock-out{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}
.pc-f{display:flex;align-items:center;justify-content:space-between;gap:.5rem;min-width:0}
.pc-p{font-size:.9rem;font-weight:800;color:#166534}
.addbtn{background:#166534;color:#fff;border:none;border-radius:8px;padding:.28rem .65rem;font-size:.72rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s;white-space:nowrap}
.addbtn:hover{background:#14532d}
.addbtn:disabled{background:#e5e7eb;color:#9ca3af;cursor:not-allowed}
.wbtn{background:none;border:none;cursor:pointer;padding:.18rem .3rem;line-height:1;transition:transform .15s;flex-shrink:0}
.wbtn:hover{transform:scale(1.2)}
.wbtn svg{display:block}
/* gallery thumbs on card */
.pc-gallery{display:flex;gap:.25rem;flex-wrap:wrap;margin-top:.3rem}
.pc-gthumb{width:24px;height:24px;border-radius:3px;object-fit:cover;cursor:pointer;border:1px solid #e5e7eb}
.empty-sec{text-align:center;padding:2.25rem;color:#9ca3af;background:#fff;border-radius:14px;border:1.5px dashed #d1fae5}
/* pagination */
.pg-nav{display:flex;align-items:center;justify-content:center;gap:.4rem;margin-top:1rem;flex-wrap:wrap}
.pg-btn{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 .55rem;border-radius:8px;font-size:.8rem;font-weight:600;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;cursor:pointer;font-family:inherit;transition:all .13s}
.pg-btn:hover:not(:disabled){border-color:#16a34a;color:#166534;background:#f0fdf4}
.pg-btn.cur{background:#166534;color:#fff;border-color:#166534}
.pg-btn:disabled{opacity:.4;cursor:not-allowed}
/* lightbox */
.lb{position:fixed;inset:0;background:rgba(0,0,0,.88);z-index:500;display:none;align-items:center;justify-content:center;flex-direction:column;gap:.75rem}
.lb.open{display:flex}
.lb img{max-width:90vw;max-height:80vh;border-radius:10px;object-fit:contain}
.lb-close{position:absolute;top:1rem;right:1.25rem;background:none;border:none;color:#fff;font-size:2rem;cursor:pointer;line-height:1}
.lb-nav{display:flex;gap:1rem}
.lb-nav button{background:rgba(255,255,255,.15);border:none;color:#fff;border-radius:8px;padding:.4rem .9rem;font-size:.85rem;cursor:pointer;font-family:inherit;font-weight:700}
.lb-nav button:hover{background:rgba(255,255,255,.28)}
/* cart */
.cov{position:fixed;inset:0;background:rgba(0,0,0,.48);z-index:400;opacity:0;pointer-events:none;transition:opacity .22s}
.cov.open{opacity:1;pointer-events:all}
.cpanel{position:fixed;top:0;right:0;height:100vh;width:390px;background:#fff;z-index:401;display:flex;flex-direction:column;transform:translateX(100%);transition:transform .26s cubic-bezier(.4,0,.2,1);box-shadow:-6px 0 30px rgba(0,0,0,.14)}
.cov.open .cpanel{transform:none}
@media(max-width:420px){.cpanel{width:100%}}
.ch{padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4;display:flex;justify-content:space-between;align-items:center}
.ch h3{font-weight:800;font-size:.98rem;color:#1a2e1a}
.cc{background:none;border:none;cursor:pointer;color:#9ca3af;font-size:1.4rem;line-height:1;padding:0}
.cbody{flex:1;overflow-y:auto;padding:1rem 1.25rem}
.ci{display:flex;justify-content:space-between;align-items:center;padding:.7rem 0;border-bottom:1px solid #f9fafb}
.ci-nm{font-weight:700;font-size:.86rem;color:#1a2e1a;margin-bottom:.1rem}
.ci-pr{color:#9ca3af;font-size:.74rem}
.qc{display:flex;align-items:center;gap:.35rem}
.qb{width:24px;height:24px;border:1.5px solid #d1fae5;border-radius:6px;background:#fff;cursor:pointer;color:#166534;font-weight:800;line-height:1;display:flex;align-items:center;justify-content:center;font-size:.95rem;transition:background .12s}
.qb:hover{background:#f0fdf4}
.qn{font-weight:800;min-width:20px;text-align:center;font-size:.86rem;color:#1a2e1a}
.csummary{padding:1rem 1.25rem;border-top:1px solid #f0fdf4;background:#fafafa}
.ot-label{font-size:.76rem;font-weight:700;color:#5a7a5a;margin-bottom:.38rem;text-transform:uppercase;letter-spacing:.05em}
.ot-row{display:grid;grid-template-columns:1fr 1fr;gap:.55rem;margin-bottom:.75rem}
.otbtn{border:2px solid #e5e7eb;border-radius:10px;padding:.55rem;cursor:pointer;background:#fff;font-family:inherit;transition:all .15s;text-align:center}
.otbtn.sel{border-color:#166534;background:#f0fdf4}
.otbtn .em{font-size:1.25rem;display:block;margin-bottom:.15rem}
.otbtn strong{display:block;font-size:.82rem;font-weight:700;color:#1a2e1a}
.otbtn small{font-size:.68rem;color:#9ca3af}
.notes-inp{width:100%;border:1.5px solid #d1fae5;border-radius:10px;padding:.55rem .8rem;font-family:inherit;font-size:.8rem;color:#1a2e1a;background:#f0fdf4;outline:none;resize:none;transition:border-color .2s;margin-bottom:.75rem}
.notes-inp:focus{border-color:#16a34a;background:#fff}
.trow{display:flex;justify-content:space-between;font-size:.82rem;color:#5a7a5a;margin-bottom:.3rem}
.ttotal{display:flex;justify-content:space-between;font-weight:800;font-size:.98rem;color:#1a2e1a;margin-top:.45rem;padding-top:.45rem;border-top:1px solid #e5e7eb}
.ttotal span:last-child{color:#166534}
.cobtn{width:100%;padding:.82rem;background:linear-gradient(135deg,#166534,#16a34a);color:#fff;border:none;border-radius:10px;font-family:inherit;font-size:.92rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(22,101,52,.22);transition:opacity .18s,transform .14s;margin-top:.55rem}
.cobtn:hover{opacity:.91;transform:translateY(-1px)}
.cobtn:disabled{opacity:.45;cursor:not-allowed;transform:none}
.cart-empty{text-align:center;padding:2.5rem 1rem;color:#9ca3af}
.cart-empty svg{opacity:.28;margin-bottom:.65rem}
.cart-empty p{font-size:.85rem;line-height:1.5}
@media(max-width:900px){
  .nav{height:auto;padding:.75rem 1rem;flex-wrap:wrap;gap:.6rem}
  .nav-brand img{width:52px;height:52px}
  .nav-brand{min-width:0;flex:1}
  .nav-brand-text span{display:none}
  .nav-r{flex-wrap:wrap;width:100%;justify-content:space-between}
  .uchip{padding:.3rem .6rem}
  .unm{display:none}
  .hero{padding:1.5rem 1rem}
  .hero h1{font-size:1.4rem}
  .wrap{padding:1rem 1rem 2rem}
  .bs{grid-template-columns:1fr 1fr}
}
@media(max-width:600px){
  .bs{grid-template-columns:1fr}
  .pg{grid-template-columns:1fr}
  .nav{padding:.65rem .85rem}
  .nav-brand img{width:42px;height:42px}
  .hero h1{font-size:1.25rem}
}
@media(max-width:420px){
  .nav-r{gap:.4rem}
  .nb{width:36px;height:36px}
  .uchip{padding:.25rem .5rem}
  .uav{width:24px;height:24px;font-size:.7rem}
  .hero{padding:1.25rem .85rem}
  .wrap{padding:.85rem .85rem 1.5rem}
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
    <button class="nb" title="Cart" onclick="openCart()">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      <span id="cart-dot" class="cart-dot">0</span>
    </button>
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

<div class="hero">
  <div class="hero-in">
    <div class="hero-tag">GreenOrder</div>
    <h1>Order Filipino favorites in one smooth flow.</h1>
    <p>Browse daily ulam menus, place orders in seconds, and track every delivery.</p>
    <div class="hero-pill">
      <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      {{ now()->format('l, F j, Y') }}
    </div>
  </div>
</div>

<div class="wrap">
  @if(session('success'))
    <div class="flash fs"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="flash fe"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('error') }}</div>
  @endif

  {{-- BEST SELLERS --}}
  @if($bestSellers->count())
  <div style="margin-bottom:1.5rem">
    <div class="sh"><span class="sh-t">🔥 Best Sellers</span><span class="sh-b">{{ $bestSellers->count() }} picks</span></div>
    <div class="bs">
      @foreach($bestSellers as $p)
      @php $bsRating = $p->avg_rating; @endphp
      <div class="pc">
        <img class="pc-cover" src="{{ $p->image ? asset('images/'.$p->image) : asset('images/food-placeholder.svg') }}" alt="{{ $p->name }}"
             onclick="openLightbox({{ json_encode(array_merge($p->image ? [$p->image] : [], $p->images->pluck('path')->toArray())) }}, 0)">
        <div class="pc-b">
          <div class="pc-n">{{ $p->name }}</div>
          <div class="pc-s">{{ $p->sold_count }} sold</div>
          <div class="pc-stars">
            @for($s=1;$s<=5;$s++)<span class="{{ $s <= round($bsRating) ? 'star-f' : 'star-e' }}">★</span>@endfor
            <span class="star-val">{{ $bsRating > 0 ? $bsRating : '' }}</span>
          </div>
          <div class="pc-badges">
            @if(($p->stock??0)<=0)<span class="stock-badge stock-out">Out of stock</span>
            @elseif(($p->stock??0)<=5)<span class="stock-badge stock-low">Low stock ({{ $p->stock }})</span>@endif
          </div>
          <div class="pc-f">
            <div class="pc-p">₱{{ number_format($p->price,2) }}</div>
            <button class="addbtn" onclick="addToCart({{ $p->id }},'{{ addslashes($p->name) }}',{{ $p->price }},{{ $p->stock??0 }})" {{ ($p->stock??0)<=0?'disabled':'' }}>+ Add</button>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- SEARCH --}}
  <div class="sb">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="srch" placeholder="Search dishes, meals...">
  </div>

  {{-- FILTERS --}}
  <div class="frow">
    <button class="fcat active" data-cat="all" onclick="setCategory('all')">All</button>
    @foreach($categories as $cat)
      <button class="fcat" data-cat="{{ strtolower($cat) }}" onclick="setCategory('{{ strtolower($cat) }}')">{{ ucfirst($cat) }}</button>
    @endforeach
    <div class="fprice">
      <label for="price-range">Max:</label>
      <input type="range" id="price-range" min="0" max="1000" step="10" value="1000" oninput="setPriceMax(this.value)">
      <span id="price-label">₱1000</span>
    </div>
  </div>

  {{-- DAY BANNER --}}
  @if($todayKey === 'sunday')
  <div class="banner banner-orange">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    It's Sunday — only <strong>&nbsp;Common</strong>&nbsp; items are available today.
  </div>
  @else
  <div class="banner banner-green">
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    Today is <strong>&nbsp;{{ now()->format('l') }}&nbsp;</strong> — <strong>Common</strong> and <strong>{{ ucfirst($todayKey) }}</strong> menus are open.
  </div>
  @endif

  {{-- DAY TABS --}}
  <div class="tabs" id="day-tabs">
    <button class="tab" data-day="common" onclick="switchTab('common')">🍽️ Common</button>
    @foreach($weekdays as $d)
      @php $isToday=($d===$todayKey); $enabled=in_array($d,$allowedTabs); @endphp
      <button class="tab {{ $isToday?'today':'' }}" data-day="{{ $d }}" onclick="switchTab('{{ $d }}')" {{ !$enabled?'disabled':'' }}>
        {{ ucfirst($d) }}@if($isToday)<span class="tdot"></span>@endif
      </button>
    @endforeach
  </div>

  {{-- PRODUCT GRID (JS-rendered) --}}
  <div id="products-grid" class="pg"></div>
  <div id="products-empty" class="empty-sec" style="display:none">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3v7"/></svg>
    <p style="font-size:.85rem" id="empty-msg">No dishes found.</p>
  </div>
  <div id="products-loading" style="text-align:center;padding:2rem;color:#9ca3af;display:none;font-size:.85rem">Loading...</div>
  <div class="pg-nav" id="pg-nav"></div>
</div>

{{-- CART DRAWER --}}
<div class="cov" id="cart-ov" onclick="if(event.target===this)closeCart()">
  <div class="cpanel">
    <div class="ch">
      <h3>🛒 Your Cart</h3>
      <button class="cc" onclick="closeCart()">✕</button>
    </div>
    <div class="cbody" id="cart-body">
      <div class="cart-empty">
        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <p>Your cart is empty.<br>Add some dishes to get started!</p>
      </div>
    </div>
    <div class="csummary" id="cart-summary" style="display:none">
      <div class="ot-label">Order Type</div>
      <div class="ot-row">
        <button class="otbtn sel" id="ot-dine" onclick="setOT('dine_in')">
          <span class="em">🍽️</span><strong>Dine In</strong><small>Eat here</small>
        </button>
        <button class="otbtn" id="ot-take" onclick="setOT('takeout')">
          <span class="em">📦</span><strong>Takeout</strong><small>Take away</small>
        </button>
      </div>
      <textarea class="notes-inp" id="onotes" rows="2" placeholder="Special instructions... (optional)"></textarea>
      <div class="trow"><span>Subtotal</span><span id="c-sub">₱0.00</span></div>
      <div class="trow"><span id="ot-lbl" style="font-size:.78rem;color:#9ca3af">Dine In</span></div>
      <div class="ttotal"><span>Total</span><span id="c-total">₱0.00</span></div>
      <form method="POST" action="{{ route('customer.checkout') }}" id="co-form">
        @csrf
        <input type="hidden" name="cart_data" id="f-cart">
        <input type="hidden" name="order_type" id="f-ot" value="dine_in">
        <input type="hidden" name="notes" id="f-notes">
        <button type="submit" class="cobtn" id="cobtn" onclick="submitOrder(event)">Continue to Payment →</button>
      </form>
    </div>
  </div>
</div>

{{-- LIGHTBOX --}}
<div class="lb" id="lightbox" onclick="if(event.target===this)closeLightbox()">
  <button class="lb-close" onclick="closeLightbox()">✕</button>
  <img id="lb-img" src="" alt="">
  <div class="lb-nav">
    <button onclick="lbNav(-1)">← Prev</button>
    <button onclick="lbNav(1)">Next →</button>
  </div>
</div>

@endsection
@push('scripts')
<script>
const TODAY    = '{{ $todayKey }}';
const ALLOWED  = @json($allowedTabs);
const USER_ID  = {{ auth()->id() }};
const CART_KEY = 'greenorder_cart_v1_user_' + USER_ID;
const PRODUCTS_URL  = '{{ route("customer.products.json") }}';
const WISHLIST_URL  = '{{ route("customer.wishlist.toggle", ["product" => "__ID__"]) }}';
const CSRF = '{{ csrf_token() }}';

let activeDay      = ALLOWED.includes(TODAY) ? TODAY : 'common';
let activeCategory = 'all';
let maxPrice       = 1000;
let currentPage    = 1;
let searchTimer    = null;

// ── Tab switching ────────────────────────────────────────────────────────────
function switchTab(day) {
  activeDay   = day;
  currentPage = 1;
  document.querySelectorAll('.tab').forEach(t => t.classList.toggle('active', t.dataset.day === day));
  fetchProducts();
}

// ── Filters ──────────────────────────────────────────────────────────────────
function setCategory(cat) {
  activeCategory = cat;
  currentPage    = 1;
  document.querySelectorAll('.fcat').forEach(b => b.classList.toggle('active', b.dataset.cat === cat));
  fetchProducts();
}

function setPriceMax(val) {
  maxPrice = parseFloat(val);
  document.getElementById('price-label').textContent = '₱' + maxPrice;
  currentPage = 1;
  fetchProducts();
}

document.addEventListener('DOMContentLoaded', () => {
  switchTab(activeDay);
  document.getElementById('srch').addEventListener('input', () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => { currentPage = 1; fetchProducts(); }, 300);
  });
  loadCart();
  renderCart();
});

// ── Fetch products ────────────────────────────────────────────────────────────
function fetchProducts() {
  const grid    = document.getElementById('products-grid');
  const empty   = document.getElementById('products-empty');
  const loading = document.getElementById('products-loading');
  const nav     = document.getElementById('pg-nav');
  const q       = document.getElementById('srch').value.trim();

  grid.innerHTML    = '';
  empty.style.display   = 'none';
  loading.style.display = 'block';
  nav.innerHTML     = '';

  const params = new URLSearchParams({
    day: activeDay, page: currentPage,
    q, category: activeCategory,
    max_price: maxPrice
  });

  fetch(PRODUCTS_URL + '?' + params, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
    .then(r => r.json())
    .then(data => {
      loading.style.display = 'none';
      if (!data.items.length) {
        empty.style.display = 'block';
        document.getElementById('empty-msg').textContent = 'No dishes found for ' + activeDay + '.';
        return;
      }
      grid.innerHTML = data.items.map(p => renderCard(p)).join('');
      renderPagination(data.page, data.last_page);
    })
    .catch(() => { loading.style.display = 'none'; });
}

function renderStars(rating) {
  let html = '<div class="pc-stars">';
  for (let s = 1; s <= 5; s++) {
    html += `<span class="${s <= Math.round(rating) ? 'star-f' : 'star-e'}">★</span>`;
  }
  html += rating > 0 ? `<span class="star-val">${rating}</span>` : '';
  html += '</div>';
  return html;
}

function renderCard(p) {
  const outOfStock = p.stock <= 0;
  const lowStock   = p.stock > 0 && p.stock <= 5;
  const imgSrc     = p.image ? '/images/' + p.image : '/images/food-placeholder.svg';
  const allImgs    = [p.image, ...p.images].filter(Boolean);
  const wFill      = p.wishlisted ? '#ef4444' : 'none';

  let badges = '';
  if (outOfStock) badges = '<span class="stock-badge stock-out">Out of stock</span>';
  else if (lowStock) badges = `<span class="stock-badge stock-low">Low stock (${p.stock})</span>`;

  let gallery = '';
  if (p.images.length) {
    gallery = '<div class="pc-gallery">' +
      p.images.slice(0, 3).map((img, i) =>
        `<img src="/images/${img}" class="pc-gthumb" onclick="openLightbox(${JSON.stringify(allImgs)},${i+1})">`
      ).join('') +
      (p.images.length > 3 ? `<span style="font-size:.65rem;color:#9ca3af;align-self:center">+${p.images.length-3}</span>` : '') +
      '</div>';
  }

  return `<div class="pc">
    <img class="pc-cover" src="${imgSrc}" alt="${p.name}" onclick="openLightbox(${JSON.stringify(allImgs)},0)">
    <div class="pc-b">
      <div class="pc-n">${p.name}</div>
      <div class="pc-s">${p.sold_count} sold</div>
      ${renderStars(p.avg_rating)}
      <div class="pc-badges">${badges}</div>
      ${gallery}
      <div class="pc-f">
        <div class="pc-p">₱${parseFloat(p.price).toFixed(2)}</div>
        <button class="wbtn" onclick="toggleWishlist(${p.id},this)" data-wishlisted="${p.wishlisted?'1':'0'}">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2" fill="${wFill}"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </button>
        <button class="addbtn" onclick="addToCart(${p.id},'${p.name.replace(/'/g,"\\'")}',${p.price},${p.stock})" ${outOfStock?'disabled':''}>+ Add</button>
      </div>
    </div>
  </div>`;
}

function renderPagination(page, lastPage) {
  if (lastPage <= 1) return;
  const nav = document.getElementById('pg-nav');
  let html = `<button class="pg-btn" onclick="goPage(${page-1})" ${page===1?'disabled':''}>← Prev</button>`;
  const start = Math.max(1, page-2), end = Math.min(lastPage, page+2);
  for (let p = start; p <= end; p++) {
    html += `<button class="pg-btn ${p===page?'cur':''}" onclick="goPage(${p})">${p}</button>`;
  }
  html += `<button class="pg-btn" onclick="goPage(${page+1})" ${page===lastPage?'disabled':''}>Next →</button>`;
  nav.innerHTML = html;
}

function goPage(p) {
  currentPage = p;
  fetchProducts();
  document.getElementById('day-tabs').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// ── Wishlist ──────────────────────────────────────────────────────────────────
function toggleWishlist(id, btn) {
  const url = WISHLIST_URL.replace('__ID__', id);
  fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(data => {
      btn.dataset.wishlisted = data.wishlisted ? '1' : '0';
      btn.querySelector('svg').setAttribute('fill', data.wishlisted ? '#ef4444' : 'none');
    });
}

// ── Lightbox ──────────────────────────────────────────────────────────────────
let lbImages = [], lbIndex = 0;
function openLightbox(images, index) {
  lbImages = images.filter(Boolean);
  if (!lbImages.length) return;
  lbIndex = index;
  document.getElementById('lb-img').src = '/images/' + lbImages[lbIndex];
  document.getElementById('lightbox').classList.add('open');
}
function closeLightbox() { document.getElementById('lightbox').classList.remove('open'); }
function lbNav(dir) {
  lbIndex = (lbIndex + dir + lbImages.length) % lbImages.length;
  document.getElementById('lb-img').src = '/images/' + lbImages[lbIndex];
}

// ── Cart ──────────────────────────────────────────────────────────────────────
let cartItems = [], ot = 'dine_in';
function openCart()  { document.getElementById('cart-ov').classList.add('open'); }
function closeCart() { document.getElementById('cart-ov').classList.remove('open'); }

function loadCart() {
  try { cartItems = JSON.parse(localStorage.getItem(CART_KEY)) || []; if (!Array.isArray(cartItems)) cartItems = []; }
  catch(_) { cartItems = []; }
}
function saveCart() { localStorage.setItem(CART_KEY, JSON.stringify(cartItems)); }

function setOT(type) {
  ot = type;
  document.getElementById('ot-dine').classList.toggle('sel', type==='dine_in');
  document.getElementById('ot-take').classList.toggle('sel', type==='takeout');
  document.getElementById('f-ot').value = type;
  document.getElementById('ot-lbl').textContent = type==='dine_in' ? 'Dine In' : 'Takeout';
}

function addToCart(id, name, price, stock) {
  if (stock <= 0) { alert('Sorry, this item is out of stock.'); return; }
  price = parseFloat(price);
  const ex = cartItems.find(i => i.id === id);
  if (ex) ex.qty++; else cartItems.push({id, name, price, qty:1});
  saveCart(); renderCart(); openCart();
}

function changeQty(id, d) {
  const item = cartItems.find(i => i.id === id);
  if (!item) return;
  item.qty += d;
  if (item.qty <= 0) cartItems = cartItems.filter(i => i.id !== id);
  saveCart(); renderCart();
}

function renderCart() {
  const body = document.getElementById('cart-body');
  const sum  = document.getElementById('cart-summary');
  const dot  = document.getElementById('cart-dot');
  const qty  = cartItems.reduce((s,i) => s+i.qty, 0);
  dot.textContent = qty;
  dot.style.display = qty > 0 ? 'flex' : 'none';
  if (!cartItems.length) {
    body.innerHTML = `<div class="cart-empty"><svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg><p>Your cart is empty.<br>Add some dishes to get started!</p></div>`;
    sum.style.display = 'none'; return;
  }
  let subtotal = 0;
  body.innerHTML = cartItems.map(item => {
    const line = parseFloat((item.price * item.qty).toFixed(2));
    subtotal += line;
    return `<div class="ci"><div><div class="ci-nm">${item.name}</div><div class="ci-pr">₱${item.price.toFixed(2)} x ${item.qty} = <b style="color:#166534">₱${line.toFixed(2)}</b></div></div><div class="qc"><button class="qb" onclick="changeQty(${item.id},-1)">-</button><span class="qn">${item.qty}</span><button class="qb" onclick="changeQty(${item.id},+1)">+</button></div></div>`;
  }).join('');
  subtotal = parseFloat(subtotal.toFixed(2));
  document.getElementById('c-sub').textContent   = '₱' + subtotal.toFixed(2);
  document.getElementById('c-total').textContent = '₱' + subtotal.toFixed(2);
  sum.style.display = 'block';
  saveCart();
}

function submitOrder(e) {
  if (!cartItems.length) { e.preventDefault(); return; }
  document.getElementById('f-cart').value  = JSON.stringify(cartItems);
  document.getElementById('f-ot').value    = ot;
  document.getElementById('f-notes').value = document.getElementById('onotes').value.trim();
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeLightbox();
  if (e.key === 'ArrowLeft')  lbNav(-1);
  if (e.key === 'ArrowRight') lbNav(1);
});
</script>
@endpush
