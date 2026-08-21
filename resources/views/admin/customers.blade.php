@extends('layouts.main')
@section('title', 'Customers')
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
.pg-title{font-size:1.35rem;font-weight:800;color:#1a2e1a;margin-bottom:.3rem}
.pg-sub{font-size:.83rem;color:#5a7a5a;margin-bottom:1.25rem}
.tcard{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06);border:1px solid #f0fdf4;overflow:hidden}
.tcard{min-width:0}
table{width:100%;border-collapse:collapse}
th{padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;background:#fafafa;border-bottom:1px solid #f0fdf4}
td{padding:.75rem 1rem;font-size:.83rem;color:#1a2e1a;border-bottom:1px solid #f9fafb;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.btn-log{background:#166534;color:#fff;border:none;border-radius:8px;padding:.35rem .7rem;font-size:.75rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .13s;white-space:nowrap}
.btn-log:hover{background:#14532d}
.badge{display:inline-flex;align-items:center;border-radius:20px;padding:.2rem .6rem;font-size:.72rem;font-weight:700;background:#f0fdf4;color:#166534}
.pag{margin-top:1rem}
/* Modal */
#orders-modal{position:fixed;inset:0;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;z-index:300;padding:1.25rem;opacity:0;pointer-events:none;width:100vw;height:100vh;max-width:none;max-height:none;overflow:visible;transform:none}
#orders-modal.show{opacity:1;pointer-events:auto}
#orders-modal .modal-card{background:#fff;border-radius:16px;max-width:760px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.2);overflow:hidden;border:1px solid #e5e7eb}
.info-grid,.modal-card{min-width:0}
.modal-hd{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.25rem;border-bottom:1px solid #f0fdf4}
.modal-hd h3{margin:0;font-size:1rem;font-weight:800;color:#1a2e1a}
.modal-close{background:none;border:none;font-size:1.2rem;cursor:pointer;color:#9ca3af;padding:.1rem .25rem}
.modal-body{padding:1rem 1.25rem;max-height:60vh;overflow:auto}
.info-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.75rem;padding:0 0 .75rem 0;border-bottom:1px solid #f9fafb;margin-bottom:.75rem}
.info-item{background:#f8fafc;border:1px solid #eef2f7;border-radius:10px;padding:.6rem .7rem}
.info-item span{display:block;font-size:.62rem;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;font-weight:700;margin-bottom:.2rem}
.info-item strong{display:block;font-size:.85rem;color:#1a2e1a}
.order-card{border:1px solid #f1f5f9;border-radius:12px;padding:.75rem;margin-bottom:.7rem}
.order-hd{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.45rem}
.order-id{font-weight:800;color:#1a2e1a}
.order-meta{font-size:.75rem;color:#9ca3af}
.order-chip{display:inline-flex;align-items:center;border-radius:20px;padding:.18rem .55rem;font-size:.7rem;font-weight:700}
.order-items{font-size:.8rem;color:#1a2e1a}
.order-items div{display:flex;justify-content:space-between;gap:.75rem;padding:.2rem 0}
.order-total{margin-top:.35rem;font-weight:800;color:#166534}

@media (max-width: 980px){
  .al{flex-direction:column}
  .sb{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sb-brand{padding:0 1rem 1rem}
  .sb-brand img{width:48px;height:48px}
  .sb-label{padding:0 1rem}
  .sbn{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .mc{margin-left:0;padding:1rem}
  .tcard{overflow-x:auto}
  table{min-width:760px}
  .info-grid{grid-template-columns:1fr 1fr}
}
@media (max-width: 560px){
  .info-grid{grid-template-columns:1fr}
  .btn-log{width:100%}
  td .btn-log{display:block}
}
/* Mobile sidebar toggle */
.mb{display:none;align-items:center;gap:.6rem;margin-bottom:1rem}
.mb-title{font-size:.95rem;font-weight:800;color:#1a2e1a}
.sb-toggle{display:none;background:#fff;border:1px solid #dcfce7;border-radius:10px;padding:.45rem .6rem;font-size:.9rem;cursor:pointer}
.sb-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:70}
@media (max-width: 980px){
  .mb{display:flex}
  .sb-toggle{display:inline-flex;align-items:center;justify-content:center}
  .sb{position:fixed;transform:translateX(-100%);transition:transform .2s ease;width:240px;z-index:75}
  .sb.open{transform:translateX(0)}
  .sb-overlay.show{display:block}
}
</style>

<div class="al">
  <div class="sb-overlay" onclick="toggleSidebar(false)"></div>
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
      <li><a href="{{ route('admin.customers') }}" class="active">
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
        <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Log Out</button>
      </form></li>
    </ul>
  </aside>

  <main class="mc">
    <div class="mb">
      <button class="sb-toggle" type="button" onclick="toggleSidebar()">&#9776;</button>
      <span class="mb-title">Menu</span>
    </div>
    <div class="pg-title">Customers</div>
    <div class="pg-sub">View registered customers, their provided info, and full order history.</div>

    <div class="tcard">
      <table>
        <thead>
          <tr><th>Name</th><th>Email</th><th>Mobile</th><th>Birthdate</th><th>Joined</th><th>Orders</th></tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>
              <div style="font-weight:700">{{ $user->name }}</div>
              <div style="font-size:.72rem;color:#9ca3af">{{ '@'.$user->username }}</div>
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->mobile ?? '-' }}</td>
            <td style="font-size:.78rem;color:#9ca3af">
              {{ $user->birthdate ? $user->birthdate->format('M j, Y') : '-' }}
            </td>
            <td style="font-size:.78rem;color:#9ca3af">{{ $user->created_at->format('M j, Y') }}</td>
            <td>
              @php($lastOrder = $user->orders->sortByDesc('created_at')->first())
              <div style="font-size:.72rem;color:#9ca3af;margin-bottom:.35rem">
                {{ $user->orders->count() }} total
                @if($lastOrder)
                  · last {{ $lastOrder->created_at->format('M j, Y') }}
                @endif
              </div>
              <button class="btn-log"
                data-user-id="{{ $user->id }}"
                data-user-name="{{ $user->name }}"
                data-user-email="{{ $user->email }}"
                data-user-mobile="{{ $user->mobile ?? '-' }}"
                data-user-birthdate="{{ $user->birthdate ? $user->birthdate->format('M j, Y') : '-' }}"
                data-user-joined="{{ $user->created_at->format('M j, Y') }}"
              >View Orders</button>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;padding:2.5rem;color:#9ca3af">No customers found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($users->hasPages())
      <div class="pag">{{ $users->links() }}</div>
    @endif
  </main>
</div>

<div class="modal" id="orders-modal" aria-hidden="true">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="orders-title">
    <div class="modal-hd">
      <h3 id="orders-title">Order History</h3>
      <button type="button" class="modal-close" id="orders-close" aria-label="Close">x</button>
    </div>
    <div class="modal-body" id="orders-body"></div>
  </div>
</div>

<script>
const orderMap = @json($orderMap);
const modal = document.getElementById('orders-modal');
const closeBtn = document.getElementById('orders-close');
const body = document.getElementById('orders-body');
const title = document.getElementById('orders-title');

function renderItems(items) {
  if (!items || items.length === 0) {
    return '<div style="color:#9ca3af;font-size:.75rem">No items recorded.</div>';
  }
  return `
    <div class="order-items">
      ${items.map(i => `
        <div>
          <span>${i.quantity} x ${i.name}</span>
          <span>&#8369;${Number(i.subtotal).toFixed(2)}</span>
        </div>
      `).join('')}
    </div>
  `;
}

function openModal(name, info, orders) {
  title.textContent = `Order History: ${name}`;
  const infoBlock = `
    <div class="info-grid">
      <div class="info-item"><span>Email</span><strong>${info.email}</strong></div>
      <div class="info-item"><span>Mobile</span><strong>${info.mobile}</strong></div>
      <div class="info-item"><span>Birthdate</span><strong>${info.birthdate}</strong></div>
      <div class="info-item"><span>Joined</span><strong>${info.joined}</strong></div>
      <div class="info-item"><span>Total Orders</span><strong>${orders ? orders.length : 0}</strong></div>
      <div class="info-item"><span>Customer</span><strong>${name}</strong></div>
    </div>
  `;

  if (!orders || orders.length === 0) {
    body.innerHTML = infoBlock + '<div style="color:#9ca3af;font-size:.85rem;text-align:center;padding:1.5rem 0">No orders yet.</div>';
  } else {
    body.innerHTML = infoBlock + orders.map(o => `
      <div class="order-card">
        <div class="order-hd">
          <div>
            <div class="order-id">Order #${String(o.id).padStart(4,'0')}</div>
            <div class="order-meta">${o.created_at}${o.order_type ? ` · ${o.order_type}` : ''}</div>
          </div>
          <div>
            <span class="order-chip" style="background:${o.status_color}20;color:${o.status_color}">${o.status_label}</span>
          </div>
        </div>
        ${renderItems(o.items)}
        <div class="order-total">Total: &#8369;${Number(o.total_amount).toFixed(2)}</div>
        ${o.notes ? `<div style="margin-top:.35rem;font-size:.75rem;color:#94a3b8">Notes: ${o.notes}</div>` : ''}
      </div>
    `).join('');
  }
  modal.classList.add('show');
  modal.setAttribute('aria-hidden', 'false');
}

function closeModal() {
  modal.classList.remove('show');
  modal.setAttribute('aria-hidden', 'true');
}

document.querySelectorAll('.btn-log').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.dataset.userId;
    const name = btn.dataset.userName;
    const info = {
      email: btn.dataset.userEmail,
      mobile: btn.dataset.userMobile,
      birthdate: btn.dataset.userBirthdate,
      joined: btn.dataset.userJoined,
    };
    openModal(name, info, orderMap[id]);
  });
});
closeBtn.addEventListener('click', closeModal);
modal.addEventListener('click', (e) => {
  if (e.target === modal) closeModal();
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
});
</script>
@endsection





