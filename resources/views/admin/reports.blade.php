@extends('layouts.main')
@section('title', 'Sales Reports')
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
.pg-head{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.2rem}
.pg-title{font-size:1.35rem;font-weight:800;color:#1a2e1a;margin-bottom:.3rem}
.pg-sub{font-size:.83rem;color:#5a7a5a;margin-bottom:1.5rem}
.btn-print{display:inline-flex;align-items:center;gap:.45rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.5rem 1rem;font-size:.8rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s}
.btn-print:hover{background:#14532d}
.print-note{font-size:.72rem;color:#9ca3af}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem}
.card{background:#fff;border-radius:14px;padding:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4}
.card-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.card-title{font-size:.92rem;font-weight:800;color:#1a2e1a}
.card-badge{background:#f0fdf4;color:#166534;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:20px}
.tcard{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4;overflow:hidden}
.grid2,.card,.tcard{min-width:0}
#monthChart{display:block;width:100%!important;max-width:100%}
.tcard-hd{padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4;font-size:.92rem;font-weight:800;color:#1a2e1a}
table{width:100%;border-collapse:collapse}
th{padding:.65rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;background:#fafafa;border-bottom:1px solid #f0fdf4}
td{padding:.7rem 1rem;font-size:.83rem;color:#1a2e1a;border-bottom:1px solid #f9fafb}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.rank-badge{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:800;flex-shrink:0}
.r1{background:#fef3c7;color:#92400e}
.r2{background:#e5e7eb;color:#374151}
.r3{background:#fde8d8;color:#9a3412}
.rn{background:#f0fdf4;color:#166534}

@media (max-width: 980px){
  .al{flex-direction:column}
  .sb{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sb-brand{padding:0 1rem 1rem}
  .sb-brand img{width:48px;height:48px}
  .sb-label{padding:0 1rem}
  .sbn{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .mc{margin-left:0;padding:1rem}
  .pg-head{flex-direction:column;align-items:flex-start}
  .grid2{grid-template-columns:1fr}
  .tcard{overflow-x:auto}
  table{min-width:720px}
  .pg-head{gap:.75rem}
}
@media print{
  body{background:#fff}
  .sb{display:none !important}
  .mc{margin-left:0;padding:0}
  .btn-print,.print-note{display:none !important}
  .card,.tcard{box-shadow:none;border:1px solid #e5e7eb}
  .card{break-inside:avoid}
  table{break-inside:auto}
  tr{break-inside:avoid;break-after:auto}
}
</style>

<div class="al">
  <aside class="sb">
    <div class="sb-brand">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
      <div><strong>GreenOrder</strong><span>Admin Console</span></div>
    </div>
    <ul class="sbn">
      <li><a href="{{ route('admin.dashboard') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a></li>
      <li><a href="{{ route('admin.products') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>Manage Products</a></li>
      <li><a href="{{ route('admin.customers') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Customers</a></li>
      <li><a href="{{ route('admin.orders') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Review Orders</a></li>
      <li><a href="{{ route('admin.reports') }}" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>Analyze Reports</a></li>
    </ul>
    <div class="sb-label">Account</div>
    <ul class="sbn">
      <li><a href="{{ route('admin.profile') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Account Profile</a></li>
      <li class="lo"><form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Log Out</button>
      </form></li>
    </ul>
  </aside>

  <main class="mc">
    <div class="pg-head">
      <div>
        <div class="pg-title">Sales Reports</div>
        <div class="pg-sub">Monthly revenue trends and top-selling products based on completed orders.</div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.25rem">
        <button class="btn-print" type="button" onclick="printReport()">
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v-5a2 2 0 0 1-2-2h-2"/><path d="M6 14h12v8H6z"/></svg>
          Print Report
        </button>
        <div class="print-note">Print uses the browser dialog</div>
      </div>
    </div>

    <div class="grid2">
      <div class="card" style="grid-column:1/-1">
        <div class="card-hd">
          <span class="card-title">Monthly Revenue — Last 12 Months</span>
          <span class="card-badge">Delivered orders only</span>
        </div>
        <canvas id="monthChart" height="80"></canvas>
      </div>
    </div>

    <div class="tcard">
      <div class="tcard-hd">🏆 Top 10 Best-Selling Products</div>
      <table>
        <thead><tr><th>#</th><th>Product</th><th>Day</th><th>Units Sold</th><th>Est. Revenue</th></tr></thead>
        <tbody>
          @forelse($topProducts as $i => $p)
          @php $rank = $i+1; @endphp
          <tr>
            <td>
              <div class="rank-badge {{ $rank===1?'r1':($rank===2?'r2':($rank===3?'r3':'rn')) }}">{{ $rank }}</div>
            </td>
            <td><strong>{{ $p->name }}</strong></td>
            <td><span style="background:#f0fdf4;color:#166534;font-size:.72rem;font-weight:700;padding:.18rem .5rem;border-radius:20px">{{ ucfirst($p->day_availability) }}</span></td>
            <td style="font-weight:700">{{ $p->total_sold ?? 0 }}</td>
            <td style="font-weight:800;color:#166534">₱{{ number_format(($p->total_sold ?? 0) * $p->price, 2) }}</td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;padding:2rem;color:#9ca3af">No sales data yet. Revenue will appear here after orders are delivered.</td></tr>
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
const mdata = @json($monthlyRevenue);
const mCtx  = document.getElementById('monthChart').getContext('2d');
function printReport() {
  window.print();
}
new Chart(mCtx, {
  type: 'bar',
  data: {
    labels: mdata.map(d=>d.month),
    datasets:[{
      label: 'Revenue (₱)',
      data: mdata.map(d=>d.revenue),
      backgroundColor: mdata.map(d => d.revenue>0 ? 'rgba(22,163,74,.8)' : 'rgba(209,250,229,.5)'),
      borderColor:     mdata.map(d => d.revenue>0 ? '#16a34a' : '#bbf7d0'),
      borderWidth: 1.5,
      borderRadius: 6,
      borderSkipped: false,
    }]
  },
  options: {
    responsive:true,
    plugins: {
      legend:{display:false},
      tooltip:{callbacks:{label:ctx=>' ₱'+ctx.raw.toFixed(2)}}
    },
    scales:{
      y:{beginAtZero:true,grid:{color:'#f0fdf4'},ticks:{callback:v=>'₱'+v,font:{size:11}}},
      x:{grid:{display:false},ticks:{font:{size:10}}}
    }
  }
});
</script>
@endpush
