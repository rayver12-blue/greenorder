@extends('layouts.main')
@section('title', 'My Orders')
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;font-family:'Plus Jakarta Sans',sans-serif;margin:0;overflow-x:hidden}
img,canvas,svg{max-width:100%}
.nav{background:#fff;border-bottom:1px solid #dcfce7;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.nav-brand{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-brand img{width:38px;height:38px;object-fit:contain}
.nav-brand-text strong{font-size:1rem;font-weight:800;color:#166534;display:block;line-height:1.1}
.nav-brand-text span{font-size:.65rem;color:#5a7a5a}
.nav-r{display:flex;align-items:center;gap:.5rem}
.nb{background:#f0fdf4;border:none;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#166534;text-decoration:none;transition:background .15s}
.nb:hover{background:#dcfce7}
.uchip{display:flex;align-items:center;gap:.45rem;background:#f0fdf4;border-radius:10px;padding:.35rem .8rem;border:1px solid #d1fae5;text-decoration:none}
.uav{width:26px;height:26px;border-radius:50%;background:#166534;color:#fff;font-weight:800;font-size:.75rem;display:flex;align-items:center;justify-content:center}
.unm{font-size:.82rem;font-weight:600;color:#1a2e1a}
.wrap{max-width:800px;margin:0 auto;padding:1.75rem 1.5rem 3rem}
.pg-title{font-size:1.35rem;font-weight:800;color:#1a2e1a;margin-bottom:1.4rem;display:flex;align-items:center;gap:.55rem}
.flash{padding:.7rem 1rem;border-radius:10px;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.fs{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.fe{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
/* ORDER CARD */
.oc{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 2px 10px rgba(0,0,0,.07);margin-bottom:.9rem;overflow:hidden;border:1px solid #f0fdf4}
.och{padding:.9rem 1.1rem;display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;gap:.75rem}
.och:hover{background:#fafafa}
.och-l{display:flex;align-items:center;gap:.75rem;flex:1;min-width:0}
.och-num{font-weight:800;font-size:.92rem;color:#1a2e1a;white-space:nowrap}
.och-date{font-size:.73rem;color:#9ca3af;margin-top:.08rem}
.ot-pill{display:inline-flex;align-items:center;gap:.25rem;background:#f0fdf4;border-radius:20px;padding:.18rem .55rem;font-size:.7rem;font-weight:700;color:#166534;white-space:nowrap}
.och-r{display:flex;align-items:center;gap:.6rem;flex-shrink:0}
.och-total{font-weight:800;font-size:.92rem;color:#1a2e1a;white-space:nowrap}
.cbtn{background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;border-radius:8px;padding:.28rem .6rem;font-size:.72rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .12s}
.cbtn:hover{background:#fee2e2}
/* STATUS PILLS */
.sp{display:inline-flex;align-items:center;gap:.25rem;border-radius:20px;padding:.22rem .65rem;font-size:.72rem;font-weight:700;white-space:nowrap}
.sp-pending   {background:#fff7ed;color:#c2410c}
.sp-processing{background:#eff6ff;color:#1d4ed8}
.sp-delivered {background:#f0fdf4;color:#166534}
.sp-cancelled {background:#fef2f2;color:#dc2626}
.chev{transition:transform .22s;color:#9ca3af;flex-shrink:0}
.chev.open{transform:rotate(180deg)}
/* BODY */
.ocb{display:none;padding:0 1.1rem 1.1rem}
.ocb.show{display:block}
/* PROGRESS */
.prog{display:flex;align-items:flex-start;margin:0 0 1rem;padding-top:.5rem}
.ps{display:flex;flex-direction:column;align-items:center;flex:1}
.pcirc{width:30px;height:30px;border-radius:50%;border:2.5px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;color:#9ca3af;z-index:1;transition:all .25s;flex-shrink:0}
.pcirc.done{border-color:#16a34a;background:#16a34a;color:#fff}
.pcirc.cur{border-color:#3b82f6;background:#eff6ff;color:#3b82f6}
.plbl{font-size:.68rem;font-weight:600;color:#9ca3af;margin-top:.3rem;text-align:center;line-height:1.2}
.plbl.done{color:#16a34a}
.plbl.cur{color:#3b82f6}
.pline{flex:1;height:2.5px;background:#e5e7eb;margin-top:14px}
.pline.done{background:#16a34a}
/* ITEMS */
.irow{display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid #f9fafb;font-size:.83rem}
.irow:last-child{border-bottom:none}
.inm{color:#1a2e1a;font-weight:600}
.icalc{font-size:.73rem;color:#9ca3af}
.isub{font-weight:700;color:#166534;white-space:nowrap}
.itotal-row{display:flex;justify-content:space-between;font-weight:800;font-size:.92rem;padding:.6rem 0 0;border-top:1.5px solid #f0fdf4;margin-top:.4rem}
.itotal-row span:last-child{color:#166534}
/* NOTES */
.note-box{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.5rem .8rem;font-size:.78rem;color:#92400e;margin-top:.6rem;display:flex;align-items:center;gap:.4rem}
/* CANCEL NOTICE */
.cancel-box{background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.5rem .8rem;font-size:.8rem;color:#dc2626;font-weight:600;margin-bottom:.75rem}
/* AUDIT LOGS */
.alog{margin-top:.8rem;border-top:1.5px dashed #f0fdf4;padding-top:.7rem}
.alog h4{margin:0 0 .5rem;font-size:.82rem;color:#5a7a5a;text-transform:uppercase;letter-spacing:.08em}
.alog-item{display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;padding:.35rem 0;border-bottom:1px solid #f9fafb;font-size:.78rem}
.alog-item:last-child{border-bottom:none}
.alog-msg{color:#1a2e1a;font-weight:600}
.alog-meta{color:#9ca3af;white-space:nowrap;font-size:.72rem}
/* EMPTY */
.empty-pg{text-align:center;padding:4rem 2rem;background:#fff;border-radius:14px;border:1.5px dashed #d1fae5;color:#9ca3af}
.go-btn{display:inline-flex;align-items:center;gap:.4rem;background:#166534;color:#fff;border-radius:10px;padding:.65rem 1.3rem;text-decoration:none;font-weight:700;font-size:.85rem;margin-top:1rem;transition:background .15s}
.go-btn:hover{background:#14532d}

@media (max-width: 900px){
  .nav{height:auto;padding:.75rem 1rem;flex-wrap:wrap;gap:.6rem}
  .nav-brand img{width:32px;height:32px}
  .nav-brand{min-width:0;flex:1}
  .nav-brand-text span{display:none}
  .nav-r{flex-wrap:wrap;width:100%;justify-content:space-between}
  .unm{display:none}
  .uchip{padding:.3rem .6rem}
  .wrap{padding:1rem 1rem 2rem}
  .och{flex-direction:column;align-items:flex-start}
  .och-r{flex-wrap:wrap;width:100%;justify-content:flex-start}
  .prog{overflow-x:auto}
  .ps{min-width:120px}
}
@media (max-width: 560px){
  .och{padding:.85rem .9rem}
  .och-l,.och-r{width:100%}
  .och-l{flex-wrap:wrap}
  .och-total{font-size:.88rem}
  .sp{font-size:.68rem}
  .prog{margin-bottom:.75rem}
  .irow{gap:.5rem;align-items:flex-start}
  .inm,.isub{word-break:break-word}
}
@media (max-width: 420px){
  .nav{padding:.65rem .85rem}
  .wrap{padding:.85rem .85rem 1.5rem}
  .pg-title{font-size:1.15rem}
  .ocb{padding:0 .9rem .9rem}
  .nb{width:36px;height:36px}
}
</style>

<nav class="nav">
  <a href="{{ route('customer.home') }}" class="nav-brand">
    <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
    <div class="nav-brand-text"><strong>GreenOrder</strong><span>Food Ordering Platform</span></div>
  </a>
  <div class="nav-r">
    <a href="{{ route('customer.home') }}" class="nb" title="Home">
      <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
    </a>
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
  <div class="pg-title">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    My Orders
  </div>

  @if(session('success'))
    <div class="flash fs"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="flash fe"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 9v4"/><path d="M12 17h.01"/><circle cx="12" cy="12" r="9"/></svg>{{ session('error') }}</div>
  @endif

  @forelse($orders as $order)
  @php
    $steps    = ['pending','processing','delivered'];
    $si       = array_search($order->status, $steps);
    $cancelled = $order->status === 'cancelled';
    $labels   = ['Order Placed','Preparing','Completed'];
  @endphp
  <div class="oc">
    <div class="och" onclick="toggle({{ $order->id }})">
      <div class="och-l">
        <div>
          <div class="och-num">#{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }}</div>
          <div class="och-date">{{ $order->created_at->format('M j, Y - g:i A') }}</div>
        </div>
        <span class="ot-pill">{{ $order->order_type==='dine_in' ? 'Dine In' : 'Takeout' }}</span>
      </div>
      <div class="och-r">
        <div class="och-total">&#8369;{{ number_format($order->total_amount,2) }}</div>
        @if($order->payment_status)
          <span class="sp" style="background:#ecfeff;color:#0f766e;border:1px solid #a5f3fc;">{{ $order->payment_status }}</span>
        @endif
        <span class="sp sp-{{ $order->status }}">
          @if($order->status==='pending') Placed
          @elseif($order->status==='processing') Preparing
          @elseif($order->status==='delivered') Done
          @else Cancelled @endif
        </span>
        @if(in_array($order->status, ['pending','processing'], true))
          <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Cancel this order?');" onclick="event.stopPropagation();">
            @csrf @method('PATCH')
            <button type="submit" class="cbtn">Cancel</button>
          </form>
        @endif
        <svg class="chev" id="chev-{{ $order->id }}" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>
    <div class="ocb" id="body-{{ $order->id }}">
      @if($cancelled)
        <div class="cancel-box">This order was cancelled.</div>
      @else
        <div class="prog">
          @foreach($steps as $i => $step)
            @php $done = ($si !== false && $i < $si); $cur = ($si !== false && $i === $si); @endphp
            <div class="ps">
              <div class="pcirc {{ $done?'done':($cur?'cur':'') }}">
                @if($done)<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                @else {{ $i+1 }} @endif
              </div>
              <div class="plbl {{ $done?'done':($cur?'cur':'') }}">{{ $labels[$i] }}</div>
            </div>
            @if($i < count($steps)-1)
              <div class="pline {{ $done?'done':'' }}"></div>
            @endif
          @endforeach
        </div>
      @endif

      @foreach($order->items as $item)
      <div class="irow">
        <div>
          <div class="inm">{{ $item->product->name ?? '[Deleted product]' }}</div>
          <div class="icalc">&#8369;{{ number_format($item->unit_price,2) }} x {{ $item->quantity }}</div>
        </div>
        <div class="isub">&#8369;{{ number_format($item->subtotal,2) }}</div>
      </div>
      @endforeach
      <div class="itotal-row"><span>Total</span><span>&#8369;{{ number_format($order->total_amount,2) }}</span></div>

      @if($order->payment_method || $order->payment_status || $order->payment_reference)
      <div class="note-box" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534;">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v15H6.5A2.5 2.5 0 0 1 4 14.5v-10A2.5 2.5 0 0 1 6.5 2z"/><path d="M8 6h8M8 10h8"/></svg>
        <span>
          <strong>Payment:</strong>
          {{ $order->payment_method ?? 'N/A' }}
          @if($order->payment_status)
            · {{ $order->payment_status }}
          @endif
          @if($order->payment_reference)
            · Ref: {{ $order->payment_reference }}
          @endif
        </span>
      </div>
      @endif

      @if($order->notes)
      <div class="note-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <span><strong>Note:</strong> {{ $order->notes }}</span>
      </div>
      @endif

      @php $auditLogs = $order->audits->sortBy('created_at'); @endphp
      @if($auditLogs->isNotEmpty())
        <div class="alog">
          <h4>Order Audit Log</h4>
          <div class="alog-list">
            @foreach($auditLogs as $log)
              <div class="alog-item">
                <div class="alog-msg">
                  {{ $log->message }}
                  @if($log->actor_role)
                    <span style="color:#9ca3af;font-weight:600">({{ ucfirst($log->actor_role) }})</span>
                  @endif
                </div>
                <div class="alog-meta">{{ $log->created_at->format('M j, Y g:i A') }}</div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
  @empty
  <div class="empty-pg">
    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    <p style="font-weight:600;margin:.5rem 0 .25rem">No orders yet</p>
    <p style="font-size:.82rem">Browse the menu and place your first order.</p>
    <a href="{{ route('customer.home') }}" class="go-btn">Browse Menu -></a>
  </div>
  @endforelse

  @if($orders->hasPages())
    <div style="margin-top:1.25rem">{{ $orders->links() }}</div>
  @endif
</div>

<script>
function toggle(id) {
  const b = document.getElementById('body-'+id);
  const c = document.getElementById('chev-'+id);
  const show = b.classList.toggle('show');
  c.classList.toggle('open', show);
}

document.addEventListener('DOMContentLoaded', () => {
  const first = document.querySelector('.oc');
  if(first) {
    const id = first.querySelector('.ocb')?.id?.replace('body-','');
    if(id) toggle(id);
  }

  @if(session('success'))
  const userId = {{ auth()->id() }};
  localStorage.removeItem('greenorder_cart_v1_user_' + userId);
  @endif
});
</script>
@endsection
