<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a2e1a; margin: 0; padding: 24px; }
  .header { text-align: center; border-bottom: 2px dashed #e5e7eb; padding-bottom: 16px; margin-bottom: 16px; }
  .brand { font-size: 20px; font-weight: bold; color: #166534; }
  .subtitle { font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; margin: 4px 0; }
  .order-num { font-size: 22px; font-weight: bold; color: #1a2e1a; margin: 6px 0; }
  .date { font-size: 10px; color: #9ca3af; }
  .status { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; margin-top: 6px; }
  .s-pending    { background: #fff7ed; color: #c2410c; }
  .s-processing { background: #eff6ff; color: #1d4ed8; }
  .s-delivered  { background: #f0fdf4; color: #166534; }
  .s-cancelled  { background: #fef2f2; color: #dc2626; }
  .section { margin-bottom: 14px; }
  .section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 6px; }
  .info-grid { width: 100%; }
  .info-grid td { font-size: 11px; padding: 2px 0; width: 50%; }
  .info-label { color: #9ca3af; font-size: 10px; }
  .info-value { font-weight: bold; color: #1a2e1a; }
  .divider { border: none; border-top: 1.5px dashed #e5e7eb; margin: 12px 0; }
  .items-table { width: 100%; border-collapse: collapse; }
  .items-table th { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #9ca3af; padding: 5px 0; border-bottom: 1px solid #f0fdf4; text-align: left; }
  .items-table td { padding: 6px 0; border-bottom: 1px solid #f9fafb; font-size: 11px; }
  .items-table tr:last-child td { border-bottom: none; }
  .item-name { font-weight: 600; color: #1a2e1a; }
  .item-calc { font-size: 10px; color: #9ca3af; }
  .item-sub { font-weight: bold; color: #166534; text-align: right; }
  .totals { margin-top: 10px; padding-top: 8px; border-top: 1.5px solid #f0fdf4; }
  .total-row { display: flex; justify-content: space-between; font-size: 11px; color: #5a7a5a; margin-bottom: 3px; }
  .grand-row { font-size: 14px; font-weight: bold; color: #1a2e1a; border-top: 1.5px solid #e5e7eb; padding-top: 6px; margin-top: 4px; }
  .grand-row span:last-child { color: #166534; }
  .footer { text-align: center; margin-top: 20px; padding-top: 12px; border-top: 1.5px dashed #e5e7eb; font-size: 10px; color: #9ca3af; line-height: 1.8; }
  .footer strong { color: #166534; }
  table.grand-table { width: 100%; }
  .note-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 6px 10px; font-size: 10px; color: #92400e; margin-top: 8px; }
</style>
</head>
<body>
  <div class="header">
    <div class="brand">GreenOrder</div>
    <div class="subtitle">Official Receipt</div>
    <div class="order-num">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
    <div class="date">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</div>
    <div class="status s-{{ $order->status }}">
      @if($order->status === 'pending') Order Placed
      @elseif($order->status === 'processing') Preparing
      @elseif($order->status === 'delivered') Completed
      @else Cancelled @endif
    </div>
  </div>

  <div class="section">
    <div class="section-title">Customer Details</div>
    <table class="info-grid">
      <tr>
        <td><span class="info-label">Name</span><br><span class="info-value">{{ $order->user->name }}</span></td>
        <td><span class="info-label">Order Type</span><br><span class="info-value">{{ $order->order_type === 'dine_in' ? 'Dine In' : 'Takeout' }}</span></td>
      </tr>
      <tr>
        <td><span class="info-label">Payment Method</span><br><span class="info-value">{{ $order->payment_method ?? 'N/A' }}</span></td>
        <td><span class="info-label">Payment Status</span><br><span class="info-value">{{ $order->payment_status ?? 'N/A' }}</span></td>
      </tr>
      @if($order->payment_reference)
      <tr>
        <td colspan="2"><span class="info-label">Reference No.</span><br><span class="info-value">{{ $order->payment_reference }}</span></td>
      </tr>
      @endif
    </table>
  </div>

  @if($order->notes)
  <div class="note-box">Note: {{ $order->notes }}</div>
  @endif

  <hr class="divider">

  <div class="section">
    <div class="section-title">Items Ordered</div>
    <table class="items-table">
      <thead>
        <tr>
          <th>Item</th>
          <th style="text-align:center">Qty</th>
          <th style="text-align:right">Unit Price</th>
          <th style="text-align:right">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
        <tr>
          <td class="item-name">{{ $item->product->name ?? '[Deleted product]' }}</td>
          <td style="text-align:center;color:#5a7a5a">{{ $item->quantity }}</td>
          <td style="text-align:right;color:#5a7a5a">&#8369;{{ number_format($item->unit_price, 2) }}</td>
          <td class="item-sub">&#8369;{{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <table class="grand-table" style="margin-top:8px;border-top:1.5px solid #f0fdf4;padding-top:6px">
      <tr>
        <td style="font-size:11px;color:#5a7a5a">Subtotal</td>
        <td style="text-align:right;font-size:11px;color:#5a7a5a">&#8369;{{ number_format($order->total_amount, 2) }}</td>
      </tr>
      <tr style="border-top:1.5px solid #e5e7eb">
        <td style="font-size:14px;font-weight:bold;color:#1a2e1a;padding-top:5px">Total Paid</td>
        <td style="text-align:right;font-size:14px;font-weight:bold;color:#166534;padding-top:5px">&#8369;{{ number_format($order->total_amount, 2) }}</td>
      </tr>
    </table>
  </div>

  <div class="footer">
    Thank you for ordering with <strong>GreenOrder</strong>!<br>
    Receipt for Order #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }} &mdash; Generated {{ now()->format('F j, Y') }}<br>
    {{ now()->format('Y') }} &copy; GreenOrder. All rights reserved.
  </div>
</body>
</html>
