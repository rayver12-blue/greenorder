@extends('layouts.main')
@section('title', 'Admin Dashboard')
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;margin:0;font-family:'Plus Jakarta Sans',sans-serif;overflow-x:hidden}
img,canvas,svg{max-width:100%}
.al{display:flex;min-height:100vh}
/* SIDEBAR */
.sb{width:240px;background:#fff;border-right:1px solid #dcfce7;display:flex;flex-direction:column;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:50}
.sb-brand{display:flex;align-items:center;gap:.65rem;padding:0 1.25rem 1.25rem;border-bottom:1px solid #dcfce7;margin-bottom:.85rem}
.sb-brand img{width:80px;height:80px;object-fit:contain}
.sb-brand strong{display:block;font-size:.92rem;font-weight:800;color:#166534;line-height:1.1}
.sb-brand span{font-size:.63rem;color:#5a7a5a}
.sb-label{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#5a7a5a;padding:0 1.25rem;margin:.85rem 0 .4rem}
.sbn{list-style:none;padding:0 .65rem;margin:0}
.sbn li a,.sbn li button{display:flex;align-items:center;gap:.65rem;padding:.62rem .75rem;border-radius:10px;font-size:.83rem;font-weight:500;color:#5a7a5a;text-decoration:none;width:100%;background:none;border:none;cursor:pointer;font-family:inherit;transition:all .13s}
.sbn li a:hover,.sbn li button:hover{background:#f0fdf4;color:#166534}
.sbn li a.active{background:#dcfce7;color:#166534;font-weight:700}
.sbn li.lo button{color:#dc2626}
.sbn li.lo button:hover{background:#fef2f2}
/* MAIN */
.mc{margin-left:240px;flex:1;padding:1.75rem;background:#f5f5f0;min-height:100vh}
/* PAGE HEADER */
.ph{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.5rem;gap:1rem}
.ph-l small{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#5a7a5a}
.ph-l h1{font-size:1.5rem;font-weight:800;color:#1a2e1a;margin:.2rem 0 .25rem}
.ph-l p{font-size:.83rem;color:#5a7a5a}
.ph-r{display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;flex-shrink:0}
.date-badge{background:#fff;border:1px solid #dcfce7;border-radius:10px;padding:.4rem .85rem;font-size:.8rem;font-weight:600;color:#166534}
.btn-report{background:#166534;color:#fff;border:none;border-radius:10px;padding:.45rem 1.1rem;font-size:.8rem;font-weight:700;cursor:pointer;text-decoration:none;font-family:inherit;transition:background .15s}
.btn-report:hover{background:#14532d}
/* STAT CARDS */
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1rem}
.stat{background:#fff;border-radius:14px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4}
.stat-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem}
.stat-lbl{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#5a7a5a}
.stat-ico{width:34px;height:34px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;color:#16a34a}
.stat-val{font-size:1.65rem;font-weight:800;color:#1a2e1a;margin-bottom:.2rem}
.stat-sub{font-size:.74rem;color:#9ca3af}
/* MINI STATS */
.mini{display:grid;grid-template-columns:repeat(5,1fr);gap:.85rem;margin-bottom:1.25rem}
.mcard{background:#fff;border-radius:14px;padding:1rem 1.1rem;box-shadow:0 1px 3px rgba(0,0,0,.06);text-align:center;border:1px solid #f0fdf4}
.mval{font-size:1.5rem;font-weight:800;color:#1a2e1a;margin-bottom:.15rem}
.mlbl{font-size:.73rem;font-weight:600;color:#9ca3af}
/* CHARTS ROW */
.charts-row{display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1.25rem}
.card{background:#fff;border-radius:14px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4}
.card-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.card-title{font-size:.92rem;font-weight:800;color:#1a2e1a}
.card-badge{background:#f0fdf4;color:#166534;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:20px}
.charts-row > *,.card,.stat,.mcard,.tcard{min-width:0}
#revChart,#donutChart{display:block;width:100%!important;max-width:100%}
/* TABLE CARD */
.tcard{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4;overflow:hidden}
.tcard-hd{padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f0fdf4}
.tcard-hd span{font-size:.92rem;font-weight:800;color:#1a2e1a}
.tcard-hd a{font-size:.8rem;color:#166534;font-weight:600;text-decoration:none}
table{width:100%;border-collapse:collapse}
th{padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;background:#fafafa;border-bottom:1px solid #f0fdf4}
td{padding:.75rem 1rem;font-size:.84rem;color:#1a2e1a;border-bottom:1px solid #f9fafb}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.badge{display:inline-flex;align-items:center;border-radius:20px;padding:.2rem .6rem;font-size:.72rem;font-weight:700}
.badge-pending   {background:#fff7ed;color:#c2410c}
.badge-processing{background:#eff6ff;color:#1d4ed8}
.badge-delivered {background:#f0fdf4;color:#166534}
.badge-cancelled {background:#fef2f2;color:#dc2626}
/* FLASH */
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:.7rem 1rem;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}

@media (max-width: 980px){
  .al{flex-direction:column}
  .sb{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sb-brand{padding:0 1rem 1rem}
  .sb-brand img{width:48px;height:48px}
  .sb-label{padding:0 1rem}
  .sbn{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .mc{margin-left:0;padding:1rem}
  .ph{flex-direction:column;align-items:flex-start}
  .ph-r{align-items:flex-start}
  .stats{grid-template-columns:1fr}
  .mini{grid-template-columns:repeat(2,1fr)}
  .charts-row{grid-template-columns:1fr}
  .tcard{overflow-x:auto}
  table{min-width:720px}
  .card-hd{flex-wrap:wrap;gap:.5rem}
  #donut-legend{grid-template-columns:1fr !important}
}
@media (max-width: 560px){
  .mini{grid-template-columns:1fr}
}
</style>

<div class="al">
  <!-- SIDEBAR -->
  <aside class="sb">
    <div class="sb-brand">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
      <div><strong>GreenOrder</strong><span>Admin Console</span></div>
    </div>
    <ul class="sbn">
      <li><a href="{{ route('admin.dashboard') }}" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
      <li><a href="{{ route('admin.products') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>Manage Products</a></li>
      <li><a href="{{ route('admin.customers') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Customers</a></li>
      <li><a href="{{ route('admin.orders') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Review Orders</a></li>
      <li><a href="{{ route('admin.reports') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Analyze Reports</a></li>
    </ul>
    <div class="sb-label">Account</div>
    <ul class="sbn">
      <li><a href="{{ route('admin.profile') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Account Profile</a></li>
      <li class="lo"><form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
        <button type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Log Out</button>
      </form></li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="mc">
    @if(session('success'))
      <div class="alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>{{ session('success') }}</div>
    @endif

    <div class="ph">
      <div class="ph-l">
        <small>Admin Dashboard</small>
        <h1>Store Performance</h1>
        <p>Real-time revenue, order flow, and fulfillment overview.</p>
      </div>
      <div class="ph-r">
        <div class="date-badge">📅 {{ now()->format('D, M j, Y') }}</div>
        <a href="{{ route('admin.reports') }}" class="btn-report">Open Sales Report</a>
      </div>
    </div>

    <!-- STAT CARDS -->
    <div class="stats">
      <div class="stat">
        <div class="stat-hd">
          <div class="stat-lbl">Total Revenue</div>
          <div class="stat-ico"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        </div>
        <div class="stat-val">₱{{ number_format($totalRevenue,0) }}</div>
        <div class="stat-sub">From {{ $completedOrders }} completed orders</div>
      </div>
      <div class="stat">
        <div class="stat-hd">
          <div class="stat-lbl">Today</div>
          <div class="stat-ico"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        </div>
        <div class="stat-val">₱{{ number_format($todayRevenue,0) }}</div>
        <div class="stat-sub">{{ $todayOrders }} order(s) placed today</div>
      </div>
      <div class="stat">
        <div class="stat-hd">
          <div class="stat-lbl">This Month</div>
          <div class="stat-ico"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
        </div>
        <div class="stat-val">₱{{ number_format($monthRevenue,0) }}</div>
        <div class="stat-sub">{{ $monthDelivered }} delivered this month</div>
      </div>
    </div>

    <!-- MINI STATS -->
    <div class="mini">
      <div class="mcard"><div class="mval">{{ $totalProducts }}</div><div class="mlbl">Products</div></div>
      <div class="mcard"><div class="mval">{{ $totalOrders }}</div><div class="mlbl">Total Orders</div></div>
      <div class="mcard"><div class="mval" style="color:#c2410c">{{ $pendingOrders }}</div><div class="mlbl">Pending</div></div>
      <div class="mcard"><div class="mval" style="color:#166534">{{ $completedOrders }}</div><div class="mlbl">Completed</div></div>
      <div class="mcard"><div class="mval" style="color:#dc2626">{{ $cancelledOrders }}</div><div class="mlbl">Cancelled</div></div>
    </div>

    <!-- CHARTS ROW -->
    <div class="charts-row">
      <div class="card">
        <div class="card-hd">
          <span class="card-title">Daily Revenue — Last 14 Days</span>
          <span class="card-badge">Delivered orders</span>
        </div>
        <div style="position: relative; height: 280px;">
          <canvas id="revChart"></canvas>
        </div>
      </div>
      <div class="card">
        <div class="card-hd">
          <span class="card-title">Order Status</span>
          <span class="card-badge">All time</span>
        </div>
        <div style="position: relative; height: 220px;">
          <canvas id="donutChart"></canvas>
        </div>
        <div id="donut-legend" style="margin-top:.75rem;display:grid;grid-template-columns:1fr 1fr;gap:.35rem; padding: 0 .5rem;"></div>
      </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="tcard">
      <div class="tcard-hd">
        <span>Recent Orders</span>
        <a href="{{ route('admin.orders') }}">View all →</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Order #</th><th>Customer</th><th>Type</th><th>Items</th><th>Total</th><th>Status</th><th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentOrders as $order)
          <tr>
            <td><strong>#{{ str_pad($order->id,4,'0',STR_PAD_LEFT) }}</strong></td>
            <td>
              <div style="font-weight:600">{{ $order->user->name ?? 'Guest' }}</div>
              <div style="font-size:.72rem;color:#9ca3af">{{ $order->user->username ?? '' }}</div>
            </td>
            <td>
              <span style="font-size:.78rem">{{ $order->order_type==='dine_in' ? '🍽️ Dine In' : '📦 Takeout' }}</span>
            </td>
            <td>
              @foreach($order->items as $item)
                <div style="font-size:.78rem;color:#5a7a5a">{{ $item->quantity }}× {{ $item->product->name ?? '[deleted]' }}</div>
              @endforeach
            </td>
            <td style="font-weight:700;color:#166534">₱{{ number_format($order->total_amount,2) }}</td>
            <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
            <td style="font-size:.78rem;color:#9ca3af">{{ $order->created_at->format('M j, g:i A') }}</td>
          </tr>
          @empty
          <tr><td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af">No orders yet. They'll appear here when customers place orders.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </main>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData      = @json($chartData);
const statusData     = @json($statusBreakdown);
const totalOrders    = {{ $totalOrders }};

// ── Revenue line chart ─────────────────────────────────────
const revCtx = document.getElementById('revChart').getContext('2d');
  new Chart(revCtx, {
  type: 'line',
  data: {
    labels: chartData.map(d => d.date),
    datasets: [{
      label: 'Revenue (₱)',
      data: chartData.map(d => d.revenue),
      borderColor: '#16a34a',
      backgroundColor: 'rgba(22,163,74,.07)',
      borderWidth: 2.5,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#16a34a',
      pointRadius: chartData.map(d => d.revenue > 0 ? 4 : 2),
      pointHoverRadius: 6,
    }, {
      label: 'Orders',
      data: chartData.map(d => d.orders),
      borderColor: '#3b82f6',
      backgroundColor: 'transparent',
      borderWidth: 1.5,
      borderDash: [4,4],
      fill: false,
      tension: 0.4,
      pointRadius: 0,
      yAxisID: 'y2',
    }, {
      label: 'Items Sold',
      data: chartData.map(d => d.sold),
      borderColor: '#f59e0b',
      backgroundColor: 'transparent',
      borderWidth: 1.5,
      borderDash: [2,4],
      fill: false,
      tension: 0.4,
      pointRadius: 0,
      yAxisID: 'y2',
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode:'index', intersect:false },
    plugins: {
      legend: { display: true, position:'top', labels:{ font:{size:11}, boxWidth:12 } },
      tooltip: {
        callbacks: {
          label: ctx => {
            if (ctx.datasetIndex === 0) return ' ₱' + ctx.raw.toFixed(2);
            if (ctx.dataset.label === 'Items Sold') return ' ' + ctx.raw + ' items';
            return ' ' + ctx.raw + ' orders';
          }
        }
      }
    },
    scales: {
      y:  { beginAtZero:true, grid:{ color:'#f0fdf4' }, ticks:{ callback: v=>'₱'+v, font:{size:11} } },
      y2: { beginAtZero:true, position:'right', grid:{ display:false }, ticks:{ font:{size:10} } },
      x:  { grid:{ display:false }, ticks:{ font:{size:10} } }
    }
  }
});

// ── Donut chart ───────────────────────────────────────────
const donutCtx = document.getElementById('donutChart').getContext('2d');
const hasData = statusData.some(s => s.value > 0);
new Chart(donutCtx, {
  type: 'doughnut',
  data: {
    labels: statusData.map(s=>s.label),
    datasets:[{
      data: hasData ? statusData.map(s=>s.value) : [1],
      backgroundColor: hasData ? statusData.map(s=>s.color) : ['#e5e7eb'],
      borderWidth: 0,
      hoverOffset: 4,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '65%',
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: ctx => hasData ? ` ${ctx.label}: ${ctx.raw} orders` : ' No orders yet'
        }
      }
    }
  }
});

// Legend
const leg = document.getElementById('donut-legend');
(hasData ? statusData : [{label:'No orders',value:0,color:'#e5e7eb'}]).forEach(s=>{
  const pct = totalOrders > 0 && s.value > 0 ? Math.round(s.value/totalOrders*100) : 0;
  leg.innerHTML += `<div style="display:flex;align-items:center;gap:.3rem;font-size:.72rem">
    <span style="width:8px;height:8px;border-radius:50%;background:${s.color};flex-shrink:0"></span>
    <span style="color:#5a7a5a">${s.label}</span>
    <span style="font-weight:700;margin-left:auto;color:#1a2e1a">${s.value}${pct?` (${pct}%)`:''}</span>
  </div>`;
});
</script>
@endpush
