@extends('layouts.main')
@section('title', 'Review Orders')
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;margin:0;font-family:'Plus Jakarta Sans',sans-serif;overflow-x:hidden}
img,canvas,svg{max-width:100%}
.al{display:flex;min-height:100vh}
.sb{width:240px;background:#fff;border-right:1px solid #dcfce7;display:flex;flex-direction:column;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:50}
.sb-brand{display:flex;align-items:center;gap:.65rem;padding:0 1.25rem 1.25rem;border-bottom:1px solid #dcfce7;margin-bottom:.85rem}
.sb-brand img{width:34px;height:34px;object-fit:contain}
.sb-brand strong{display:block;font-size:.92rem;font-weight:800;color:#166534;line-height:1.1}
.sb-brand span{font-size:.63rem;color:#5a7a5a}
.sb-label{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#5a7a5a;padding:0 1.25rem;margin:.85rem 0 .4rem}
.sbn{list-style:none;padding:0 .65rem;margin:0}
.sbn li a,.sbn li button{display:flex;align-items:center;gap:.65rem;padding:.62rem .75rem;border-radius:10px;font-size:.83rem;font-weight:500;color:#5a7a5a;text-decoration:none;width:100%;background:none;border:none;cursor:pointer;font-family:inherit;transition:all .13s}
.sbn li a:hover,.sbn li button:hover{background:#f0fdf4;color:#166534}
.sbn li a.active{background:#dcfce7;color:#166534;font-weight:700}
.sbn li.lo button{color:#dc2626}.sbn li.lo button:hover{background:#fef2f2}
.mc{margin-left:240px;flex:1;padding:1.75rem;background:#f5f5f0;min-height:100vh;min-width:0}
/* STATUS TABS */
.stabs{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:1.25rem}
.stab{padding:.4rem .9rem;border-radius:20px;font-size:.8rem;font-weight:600;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;transition:all .13s}
.stab:hover{border-color:#16a34a;color:#166534}
.stab.active{background:#166534;color:#fff;border-color:#166534}
.stab-count{background:rgba(255,255,255,.25);border-radius:20px;padding:.05rem .4rem;font-size:.68rem;font-weight:800}
.stab:not(.active) .stab-count{background:#f0fdf4;color:#166534}
/* ALERTS */
.alert-s{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:.7rem 1rem;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
/* TABLE CARD */
.tcard{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4;overflow:hidden}
.stabs,.tcard{min-width:0}
table{width:100%;border-collapse:collapse}
th{padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;background:#fafafa;border-bottom:1px solid #f0fdf4}
td{padding:.75rem 1rem;font-size:.83rem;color:#1a2e1a;border-bottom:1px solid #f9fafb;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.badge{display:inline-flex;align-items:center;border-radius:20px;padding:.2rem .6rem;font-size:.72rem;font-weight:700}
.badge-pending   {background:#fff7ed;color:#c2410c}
.badge-processing{background:#eff6ff;color:#1d4ed8}
.badge-delivered {background:#f0fdf4;color:#166534}
.badge-cancelled {background:#fef2f2;color:#dc2626}
/* STATUS UPDATE */
.su-form{display:flex;align-items:center;gap:.4rem}
.su-select{border:1.5px solid #d1fae5;border-radius:8px;padding:.3rem .55rem;font-size:.78rem;font-family:inherit;background:#f0fdf4;color:#1a2e1a;outline:none;cursor:pointer}
.su-select:focus{border-color:#16a34a}
.su-btn{background:#166534;color:#fff;border:none;border-radius:8px;padding:.32rem .7rem;font-size:.75rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .13s;white-space:nowrap}
.su-btn:hover{background:#14532d}
/* ORDER NOTES */
.note-cell{font-size:.75rem;color:#92400e;background:#fffbeb;border:1px solid #fde68a;border-radius:6px;padding:.2rem .45rem;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.pg-title{font-size:1.35rem;font-weight:800;color:#1a2e1a;margin-bottom:.3rem}
.pg-sub{font-size:.83rem;color:#5a7a5a;margin-bottom:1.25rem}

@media (max-width: 980px){
  .al{flex-direction:column}
  .sb{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sb-brand{padding:0 1rem 1rem}
  .sb-brand img{width:48px;height:48px}
  .sb-label{padding:0 1rem}
  .sbn{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .mc{margin-left:0;padding:1rem}
  .stabs{flex-wrap:wrap}
  .tcard{overflow-x:auto}
  table{min-width:840px}
  .su-form{flex-wrap:wrap}
}
</style>

<div class="al">
  <aside class="sb">
    <div class="sb-brand">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
      <div><strong>GreenOrder</strong><span>Admin Console</span></div>
    </div>
    <ul class="sbn">
      <li><a href="{{ route('admin.dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
      <li><a href="{{ route('admin.products') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>Manage Products</a></li>
      <li><a href="{{ route('admin.customers') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Customers</a></li>
      <li><a href="{{ route('admin.orders') }}" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Review Orders</a></li>
      <li><a href="{{ route('admin.reports') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Analyze Reports</a></li>
    </ul>
    <div class="sb-label">Account</div>
    <ul class="sbn">
      <li><a href="{{ route('admin.profile') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Account Profile</a></li>
      <li class="lo"><form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Log Out</button>
      </form></li>
    </ul>
  </aside>

  <main class="mc">
    <div class="pg-title">Review Orders</div>
    <div class="pg-sub">Monitor and update customer order statuses in real time.</div>

    @if(session('success'))
      <div class="alert-s"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
    @endif

    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.65rem;margin-bottom:1rem">
      <div class="stabs" style="margin-bottom:0">
        @foreach(['all','pending','processing','delivered','cancelled'] as $s)
          <a href="{{ route('admin.orders', ['status'=>$s]) }}"
             class="stab {{ $status===$s ? 'active':'' }}">
            {{ ucfirst($s) }}
            <span class="stab-count">{{ $counts[$s] }}</span>
          </a>
        @endforeach
      </div>
      <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
        <form method="GET" action="{{ route('admin.orders.export') }}" style="display:flex;gap:.4rem;align-items:center">
          <input type="hidden" name="status" value="{{ $status }}">
          <input type="date" name="from" value="{{ request('from') }}" class="fc" style="width:140px;padding:.35rem .6rem;font-size:.78rem;border:1.5px solid #d1fae5;border-radius:8px;font-family:inherit;background:#f9fefb;outline:none">
          <input type="date" name="to"   value="{{ request('to') }}"   class="fc" style="width:140px;padding:.35rem .6rem;font-size:.78rem;border:1.5px solid #d1fae5;border-radius:8px;font-family:inherit;background:#f9fefb;outline:none">
          <button type="submit" style="display:inline-flex;align-items:center;gap:.35rem;background:#166534;color:#fff;border:none;border-radius:8px;padding:.4rem .85rem;font-size:.78rem;font-weight:700;cursor:pointer;font-family:inherit;white-space:nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export CSV
          </button>
        </form>
      </div>
    </div>

    <div class="tcard">
      <table>
        <thead>
          <tr><th>Order #</th><th>Customer</th><th>Type</th><th>Items & Total</th><th>Notes</th><th>Status</th><th>Date</th><th>Update</th></tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
          <tr>
            <td><strong>#{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }}</strong></td>
            <td>
              <div style="font-weight:600;font-size:.85rem">{{ $order->user->name ?? '—' }}</div>
              <div style="font-size:.72rem;color:#9ca3af">{{ $order->user->mobile ?? '' }}</div>
            </td>
            <td style="font-size:.8rem">{{ $order->order_type==='dine_in' ? '🍽️ Dine In' : '📦 Takeout' }}</td>
            <td>
              @foreach($order->items as $item)
                <div style="font-size:.78rem;color:#5a7a5a">{{ $item->quantity }}× {{ $item->product->name ?? '[deleted]' }} — <span style="color:#166534;font-weight:700">₱{{ number_format($item->subtotal,2) }}</span></div>
              @endforeach
              <div style="font-size:.82rem;font-weight:800;color:#1a2e1a;margin-top:.25rem;border-top:1px solid #f0fdf4;padding-top:.2rem">Total: ₱{{ number_format($order->total_amount,2) }}</div>
            </td>
            <td>
              @if($order->notes)
                <span class="note-cell" title="{{ $order->notes }}">📝 {{ $order->notes }}</span>
              @else
                <span style="color:#d1fae5;font-size:.78rem">—</span>
              @endif
            </td>
            <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
            <td style="font-size:.78rem;color:#9ca3af;white-space:nowrap">{{ $order->created_at->format('M j, g:i A') }}</td>
            <td>
              <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="su-form">
                @csrf @method('PATCH')
                <select name="status" class="su-select">
                  @foreach(['pending','processing','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->status===$s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                  @endforeach
                </select>
                <button type="submit" class="su-btn">Save</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;padding:2.5rem;color:#9ca3af">No orders found for this filter.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($orders->hasPages())
      <div style="margin-top:1rem">{{ $orders->appends(['status'=>$status])->links() }}</div>
    @endif
  </main>
</div>
@endsection
