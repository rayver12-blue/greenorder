@extends('layouts.main')
@section('title', 'Receipt #' . str_pad($order->id, 4, '0', STR_PAD_LEFT))
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;font-family:'Plus Jakarta Sans',sans-serif;margin:0}
.nav{background:#fff;border-bottom:1px solid #dcfce7;padding:0 1.5rem;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.08)}
.nav-brand{display:flex;align-items:center;gap:.65rem;text-decoration:none}
.nav-brand img{width:38px;height:38px;object-fit:contain}
.nav-brand-text strong{font-size:1rem;font-weight:800;color:#166534;display:block;line-height:1.1}
.nav-brand-text span{font-size:.65rem;color:#5a7a5a}
.nb{background:#f0fdf4;border:none;border-radius:10px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#166534;text-decoration:none;transition:background .15s}
.nb:hover{background:#dcfce7}
.nav-r{display:flex;align-items:center;gap:.5rem}
.wrap{max-width:640px;margin:2rem auto;padding:0 1.25rem 3rem}
.actions{display:flex;align-items:center;gap:.65rem;margin-bottom:1.25rem;flex-wrap:wrap}
.btn-back{display:inline-flex;align-items:center;gap:.4rem;background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0;border-radius:10px;padding:.5rem .9rem;font-size:.82rem;font-weight:700;text-decoration:none;transition:background .15s}
.btn-back:hover{background:#dcfce7}
.btn-print{display:inline-flex;align-items:center;gap:.4rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.5rem .9rem;font-size:.82rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s}
.btn-print:hover{background:#14532d}
.receipt{background:#fff;border-radius:16px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 4px 20px rgba(0,0,0,.07);border:1px solid #f0fdf4;padding:2rem}
.r-header{text-align:center;border-bottom:1.5px dashed #e5e7eb;padding-bottom:1.25rem;margin-bottom:1.25rem}
.r-logo{display:flex;align-items:center;justify-content:center;gap:.6rem;margin-bottom:.5rem}
.r-logo img{width:36px;height:36px;object-fit:contain}
.r-logo strong{font-size:1.15rem;font-weight:800;color:#166534}
.r-title{font-size:.75rem;color:#9ca3af;letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem}
.r-num{font-size:1.5rem;font-weight:800;color:#1a2e1a}
.r-date{font-size:.78rem;color:#9ca3af;margin-top:.2rem}
.r-status{display:inline-flex;align-items:center;gap:.3rem;border-radius:20px;padding:.28rem .75rem;font-size:.75rem;font-weight:700;margin-top:.6rem}
.s-pending   {background:#fff7ed;color:#c2410c}
.s-processing{background:#eff6ff;color:#1d4ed8}
.s-delivered {background:#f0fdf4;color:#166534}
.s-cancelled {background:#fef2f2;color:#dc2626}
.r-section{margin-bottom:1.1rem}
.r-section-title{font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;margin-bottom:.55rem}
.r-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:.4rem .75rem}
.r-info-item span:first-child{font-size:.72rem;color:#9ca3af;display:block}
.r-info-item span:last-child{font-size:.83rem;font-weight:700;color:#1a2e1a}
.r-divider{border:none;border-top:1.5px dashed #e5e7eb;margin:1.1rem 0}
.r-item{display:flex;justify-content:space-between;align-items:center;padding:.45rem 0;border-bottom:1px solid #f9fafb;font-size:.83rem}
.r-item:last-child{border-bottom:none}
.r-item-name{font-weight:600;color:#1a2e1a}
.r-item-calc{font-size:.72rem;color:#9ca3af}
.r-item-sub{font-weight:700;color:#166534;white-space:nowrap}
.r-totals{margin-top:.75rem;padding-top:.75rem;border-top:1.5px solid #f0fdf4}
.r-total-row{display:flex;justify-content:space-between;font-size:.83rem;color:#5a7a5a;margin-bottom:.3rem}
.r-grand{display:flex;justify-content:space-between;font-weight:800;font-size:1.05rem;color:#1a2e1a;margin-top:.5rem;padding-top:.5rem;border-top:1.5px solid #e5e7eb}
.r-grand span:last-child{color:#166534}
.r-footer{text-align:center;margin-top:1.5rem;padding-top:1.1rem;border-top:1.5px dashed #e5e7eb;font-size:.75rem;color:#9ca3af;line-height:1.7}
.r-footer strong{color:#166534}
@media print{
  .nav,.actions{display:none!important}
  body{background:#fff}
  .wrap{margin:0;padding:0;max-width:100%}
  .receipt{box-shadow:none;border:none;border-radius:0;padding:1.5rem}
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
  </div>
</nav>

<div class="wrap">
  <div class="actions">
    <a href="{{ route('customer.orders') }}" class="btn-back">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
      Back to Orders
    </a>
    <button class="btn-print" onclick="window.print()">
      <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
      Print Receipt
    </button>
  </div>

  <div class="receipt">
    <div class="r-header">
      <div class="r-logo">
        <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
        <strong>GreenOrder</strong>
      </div>
      <div class="r-title">Official Receipt</div>
      <div class="r-num">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
      <div class="r-date">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
      <div class="r-status s-{{ $order->status }}">
        @if($order->status === 'pending') ⏳ Order Placed
        @elseif($order->status === 'processing') 🍳 Preparing
        @elseif($order->status === 'delivered') ✅ Completed
        @else ❌ Cancelled @endif
      </div>
    </div>

    <div class="r-section">
      <div class="r-section-title">Customer Details</div>
      <div class="r-info-grid">
        <div class="r-info-item">
          <span>Name</span>
          <span>{{ $order->user->name }}</span>
        </div>
        <div class="r-info-item">
          <span>Order Type</span>
          <span>{{ $order->order_type === 'dine_in' ? 'Dine In' : 'Takeout' }}</span>
        </div>
        <div class="r-info-item">
          <span>Payment Method</span>
          <span>{{ $order->payment_method ?? 'N/A' }}</span>
        </div>
        <div class="r-info-item">
          <span>Payment Status</span>
          <span>{{ $order->payment_status ?? 'N/A' }}</span>
        </div>
        @if($order->payment_reference)
        <div class="r-info-item" style="grid-column:1/-1">
          <span>Reference No.</span>
          <span>{{ $order->payment_reference }}</span>
        </div>
        @endif
      </div>
    </div>

    @if($order->notes)
    <div class="r-section">
      <div class="r-section-title">Special Instructions</div>
      <div style="font-size:.83rem;color:#92400e;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:.5rem .75rem">{{ $order->notes }}</div>
    </div>
    @endif

    <hr class="r-divider">

    <div class="r-section">
      <div class="r-section-title">Items Ordered</div>
      @foreach($order->items as $item)
      <div class="r-item">
        <div>
          <div class="r-item-name">{{ $item->product->name ?? '[Deleted product]' }}</div>
          <div class="r-item-calc">₱{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</div>
        </div>
        <div class="r-item-sub">₱{{ number_format($item->subtotal, 2) }}</div>
      </div>
      @endforeach

      <div class="r-totals">
        <div class="r-total-row"><span>Subtotal</span><span>₱{{ number_format($order->total_amount, 2) }}</span></div>
        <div class="r-grand"><span>Total Paid</span><span>₱{{ number_format($order->total_amount, 2) }}</span></div>
      </div>
    </div>

    <div class="r-footer">
      Thank you for ordering with <strong>GreenOrder</strong>!<br>
      This is an official receipt for Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}.<br>
      {{ now()->format('Y') }} © GreenOrder. All rights reserved.
    </div>
  </div>
</div>
@endsection
