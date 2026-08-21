@extends('layouts.main')
@section('title', 'Manage Products')
@section('content')
<style>
*{box-sizing:border-box}
body{background:#f5f5f0;margin:0;font-family:'Plus Jakarta Sans',sans-serif;overflow-x:hidden}
img,canvas,svg{max-width:100%}
.al{display:flex;min-height:100vh}
/* SIDEBAR */
.sb{width:240px;background:#fff;border-right:1px solid #dcfce7;display:flex;flex-direction:column;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;overflow-y:auto;z-index:50}
.sb-brand{display:flex;align-items:center;gap:.65rem;padding:0 1.25rem 1.25rem;border-bottom:1px solid #dcfce7;margin-bottom:.85rem}
.sb-brand img{width:34px;height:34px;object-fit:contain}
.sb-brand-text strong{display:block;font-size:.92rem;font-weight:800;color:#166534;line-height:1.1}
.sb-brand-text span{font-size:.63rem;color:#5a7a5a}
.sb-label{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#5a7a5a;padding:0 1.25rem;margin:.85rem 0 .4rem}
.sbn{list-style:none;padding:0 .65rem;margin:0}
.sbn li a,.sbn li button{display:flex;align-items:center;gap:.65rem;padding:.62rem .75rem;border-radius:10px;font-size:.83rem;font-weight:500;color:#5a7a5a;text-decoration:none;width:100%;background:none;border:none;cursor:pointer;font-family:inherit;transition:all .13s}
.sbn li a:hover,.sbn li button:hover{background:#f0fdf4;color:#166534}
.sbn li a.active{background:#dcfce7;color:#166534;font-weight:700}
.sbn li.lo button{color:#dc2626}
.sbn li.lo button:hover{background:#fef2f2}
/* MAIN */
.mc{margin-left:240px;flex:1;padding:1.75rem;background:#f5f5f0;min-height:100vh;min-width:0}
/* PAGE HEADER */
.ph{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;gap:1rem}
.ph-l h1{font-size:1.4rem;font-weight:800;color:#1a2e1a;margin:0 0 .15rem}
.ph-l p{font-size:.83rem;color:#5a7a5a;margin:0}
/* BUTTONS */
.btn-add{display:inline-flex;align-items:center;gap:.4rem;background:#166534;color:#fff;border:none;border-radius:10px;padding:.6rem 1.1rem;font-size:.85rem;font-weight:700;cursor:pointer;font-family:inherit;transition:background .15s;text-decoration:none;white-space:nowrap}
.btn-add:hover{background:#14532d}
.btn-edit{display:inline-flex;align-items:center;gap:.3rem;background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0;border-radius:8px;padding:.32rem .7rem;font-size:.76rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-edit:hover{background:#dcfce7;border-color:#86efac}
.btn-del{display:inline-flex;align-items:center;gap:.3rem;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;border-radius:8px;padding:.32rem .7rem;font-size:.76rem;font-weight:700;cursor:pointer;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-del:hover{background:#fee2e2;border-color:#fca5a5}
/* ALERTS */
.alert-s{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;border-radius:10px;padding:.7rem 1rem;font-size:.83rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
/* FILTER BAR */
.fbar{display:flex;align-items:flex-end;gap:.75rem;flex-wrap:wrap;background:#fff;border:1px solid #f0fdf4;border-radius:14px;padding:.85rem 1rem;box-shadow:0 1px 3px rgba(0,0,0,.04);margin-bottom:1rem}
.fgroup{display:flex;flex-direction:column;gap:.35rem}
.fgroup label{font-size:.7rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:#5a7a5a}
.finput{min-width:220px}
.fselect{min-width:190px}
.fbtn{display:inline-flex;align-items:center;gap:.35rem;border:none;border-radius:10px;padding:.6rem 1rem;font-size:.8rem;font-weight:800;cursor:pointer;font-family:inherit}
.fbtn.apply{background:#166534;color:#fff}
.fbtn.apply:hover{background:#14532d}
.fbtn.clear{background:#f5f5f0;color:#5a7a5a;border:1.5px solid #e5e7eb}
.fbtn.clear:hover{background:#e5e7eb}
/* TABLE */
.tcard{background:#fff;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.06),0 2px 8px rgba(0,0,0,.05);border:1px solid #f0fdf4;overflow:hidden;margin-bottom:1rem;min-width:0}
table{width:100%;border-collapse:collapse}
th{padding:.7rem 1rem;text-align:left;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#5a7a5a;background:#fafafa;border-bottom:1px solid #f0fdf4;white-space:nowrap}
td{padding:.75rem 1rem;font-size:.83rem;color:#1a2e1a;border-bottom:1px solid #f9fafb;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafafa}
.prod-img{width:42px;height:42px;border-radius:8px;object-fit:cover;flex-shrink:0}
.prod-img-ph{width:42px;height:42px;border-radius:8px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.day-pill{display:inline-flex;background:#f0fdf4;color:#166534;font-size:.72rem;font-weight:700;padding:.2rem .6rem;border-radius:20px;white-space:nowrap}
.actions{display:flex;align-items:center;gap:.4rem;flex-wrap:nowrap}
/* PAGINATION */
.pag{display:flex;align-items:center;gap:.35rem;flex-wrap:wrap;margin-top:.75rem}
.pag a,.pag span{display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:32px;padding:0 .55rem;border-radius:8px;font-size:.8rem;font-weight:600;text-decoration:none;border:1.5px solid #e5e7eb;background:#fff;color:#6b7280;transition:all .13s}
.pag a:hover{border-color:#16a34a;color:#166534;background:#f0fdf4}
.pag .active-page{background:#166534;color:#fff;border-color:#166534}
.pag .disabled{opacity:.4;pointer-events:none;cursor:default}
.pag-info{font-size:.8rem;color:#9ca3af;margin-top:.5rem}
/* MODAL */
.mover{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:300;display:flex;align-items:center;justify-content:center;padding:1rem;opacity:0;pointer-events:none;transition:opacity .22s}
.mover.open{opacity:1;pointer-events:all}
.mbox{background:#fff;border-radius:16px;max-width:520px;width:100%;max-height:92vh;overflow-y:auto;box-shadow:0 8px 40px rgba(0,0,0,.16);transform:translateY(18px);transition:transform .22s}
.mover.open .mbox{transform:none}
.mhd{padding:1.1rem 1.4rem;border-bottom:1px solid #f0fdf4;display:flex;justify-content:space-between;align-items:center}
.mhd h3{font-weight:800;font-size:1rem;color:#1a2e1a}
.mc-btn{background:none;border:none;cursor:pointer;color:#9ca3af;font-size:1.4rem;line-height:1;padding:0;transition:color .13s}
.mc-btn:hover{color:#1a2e1a}
.mbody{padding:1.25rem 1.4rem}
.mfoot{padding:1rem 1.4rem;border-top:1px solid #f0fdf4;display:flex;justify-content:flex-end;gap:.65rem}
/* FORM FIELDS */
.fg{margin-bottom:.9rem}
.fg label{display:block;font-size:.78rem;font-weight:700;color:#1a2e1a;margin-bottom:.32rem}
.fc{width:100%;padding:.62rem .85rem;border:1.5px solid #d1fae5;border-radius:10px;font-family:inherit;font-size:.875rem;color:#1a2e1a;background:#f9fefb;outline:none;transition:border-color .18s,box-shadow .18s}
.fc:focus{border-color:#16a34a;background:#fff;box-shadow:0 0 0 3px rgba(22,163,74,.1)}
.fc-2{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;min-width:0}
.btn-cancel{background:#f5f5f0;color:#5a7a5a;border:1.5px solid #e5e7eb;border-radius:10px;padding:.6rem 1.1rem;font-family:inherit;font-size:.85rem;font-weight:700;cursor:pointer;transition:all .15s}
.btn-cancel:hover{background:#e5e7eb}
.btn-save{background:#166534;color:#fff;border:none;border-radius:10px;padding:.6rem 1.3rem;font-family:inherit;font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s}
.btn-save:hover{background:#14532d}

@media (max-width: 980px){
  .al{flex-direction:column}
  .sb{position:relative;width:100%;height:auto;border-right:0;border-bottom:1px solid #dcfce7}
  .sb-brand{padding:0 1rem 1rem}
  .sb-brand img{width:48px;height:48px}
  .sb-label{padding:0 1rem}
  .sbn{display:grid;grid-template-columns:1fr 1fr;gap:.4rem;padding:0 1rem}
  .mc{margin-left:0;padding:1rem}
  .ph{flex-direction:column;align-items:flex-start}
  .fbar{flex-direction:column;align-items:stretch}
  .finput,.fselect{min-width:0;width:100%}
  .tcard{overflow-x:auto}
  table{min-width:820px}
  .fc-2{grid-template-columns:1fr}
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
  <!-- SIDEBAR -->
  <aside class="sb">
    <div class="sb-brand">
      <img src="{{ asset('images/greenorder_icon.png') }}" alt="GreenOrder">
      <div class="sb-brand-text"><strong>GreenOrder</strong><span>Admin Console</span></div>
    </div>
    <ul class="sbn">
      <li><a href="{{ route('admin.dashboard') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
        Dashboard</a></li>
      <li><a href="{{ route('admin.products') }}" class="active">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
        Manage Products</a></li>
      <li><a href="{{ route('admin.customers') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Customers</a></li>
      <li><a href="{{ route('admin.orders') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Review Orders</a></li>
      <li><a href="{{ route('admin.reports') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Analyze Reports</a></li>
    </ul>
    <div class="sb-label">Account</div>
    <ul class="sbn">
      <li><a href="{{ route('admin.profile') }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Account Profile</a></li>
      <li class="lo">
        <form method="POST" action="{{ route('auth.logout') }}" data-logout>@csrf
          <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Log Out</button>
        </form>
      </li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main class="mc">
    <div class="mb">
      <button class="sb-toggle" type="button" onclick="toggleSidebar()">&#9776;</button>
      <span class="mb-title">Menu</span>
    </div>
    <div class="ph">
      <div class="ph-l">
        <h1>Manage Products</h1>
        <p>Add, edit, or remove menu items from the store.</p>
      </div>
      <button class="btn-add" onclick="openModal('add-modal')">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Product
      </button>
    </div>

    @if(session('success'))
      <div class="alert-s">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
      </div>
    @endif

    <form class="fbar" method="GET" action="{{ route('admin.products') }}">
      <div class="fgroup">
        <label for="q">Search</label>
        <input id="q" name="q" class="fc finput" type="text" value="{{ $search ?? '' }}" placeholder="Search name, category, or description">
      </div>
      <div class="fgroup">
        <label for="category">Category</label>
        <select id="category" name="category" class="fc fselect">
          <option value="all">All categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat }}" @selected(($category ?? 'all') === $cat)>{{ $cat }}</option>
          @endforeach
        </select>
      </div>
      <div class="fgroup">
        <label for="day">Day Availability</label>
        <select id="day" name="day" class="fc fselect">
          <option value="all">All days</option>
          @foreach(['common','monday','tuesday','wednesday','thursday','friday','saturday'] as $d)
            <option value="{{ $d }}" @selected(($day ?? 'all') === $d)>{{ ucfirst($d) }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;gap:.5rem">
        <button class="fbtn apply" type="submit">Apply</button>
        <a class="fbtn clear" href="{{ route('admin.products') }}">Clear</a>
      </div>
    </form>

    <div class="tcard">
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Availability</th>
            <th style="text-align:center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($products as $product)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:.75rem">
                @if($product->image)
                  <img src="{{ asset('images/' . $product->image) }}" class="prod-img" alt="{{ $product->name }}">
                @else
                  <div class="prod-img-ph">[img]</div>
                @endif
                <div>
                  <div style="font-weight:700;font-size:.88rem;color:#1a2e1a">{{ $product->name }}</div>
                  @if($product->description)
                    <div style="font-size:.72rem;color:#9ca3af;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $product->description }}</div>
                  @endif
                </div>
              </div>
            </td>
            <td>
              <div style="font-size:.8rem;font-weight:700;color:#1a2e1a">{{ $product->category ?? '—' }}</div>
            </td>
            <td style="font-weight:800;color:#166534;white-space:nowrap">₱{{ number_format($product->price, 2) }}</td>
            <td>
              @php $inStock = ($product->stock ?? 0) > 0; @endphp
              <span class="day-pill" style="background:{{ $inStock ? '#f0fdf4' : '#fef2f2' }};color:{{ $inStock ? '#166534' : '#dc2626' }}">
                {{ $product->stock ?? 0 }}
              </span>
            </td>
            <td><span class="day-pill">{{ ucfirst($product->day_availability) }}</span></td>
            <td>
              <div class="actions" style="justify-content:center">
                {{-- EDIT BUTTON --}}
                <button class="btn-edit" onclick="openEdit({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->category ?? '') }}', '{{ addslashes($product->description ?? '') }}', {{ $product->price }}, {{ $product->stock ?? 0 }}, '{{ $product->day_availability }}')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  Edit
                </button>
                {{-- DELETE BUTTON --}}
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ addslashes($product->name) }}? This cannot be undone.')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-del">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:3rem;color:#9ca3af">
              <div style="font-size:1.5rem;margin-bottom:.5rem">[img]</div>
              <div style="font-weight:600;margin-bottom:.25rem">No products yet</div>
              <div style="font-size:.8rem">Click "Add Product" to create your first menu item.</div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- CLEAN PAGINATION --}}
    @if($products->hasPages())
    <div>
      <div class="pag">
        {{-- Previous --}}
        @if($products->onFirstPage())
          <span class="disabled">? Prev</span>
        @else
          <a href="{{ $products->previousPageUrl() }}">? Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach($products->getUrlRange(max(1,$products->currentPage()-2), min($products->lastPage(),$products->currentPage()+2)) as $page => $url)
          @if($page == $products->currentPage())
            <span class="active-page">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach

        {{-- Next --}}
        @if($products->hasMorePages())
          <a href="{{ $products->nextPageUrl() }}">Next ?</a>
        @else
          <span class="disabled">Next ?</span>
        @endif
      </div>
      <div class="pag-info">
        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} products
      </div>
    </div>
    @endif
  </main>
</div>

{{-- ADD PRODUCT MODAL --}}
<div class="mover" id="add-modal" onclick="if(event.target===this)closeModal('add-modal')">
  <div class="mbox">
    <div class="mhd">
      <h3>Add New Product</h3>
      <button class="mc-btn" onclick="closeModal('add-modal')">x</button>
    </div>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="mbody">
        <div class="fg">
          <label>Product Name *</label>
          <input type="text" name="name" class="fc" placeholder="e.g. Adobo Rice Meal" required maxlength="100">
        </div>
        <div class="fg">
          <label>Category</label>
          <input type="text" name="category" class="fc" placeholder="e.g. Rice Meals" maxlength="50">
        </div>
        <div class="fg">
          <label>Description</label>
          <textarea name="description" class="fc" placeholder="Brief description..." rows="2" style="resize:vertical" maxlength="500"></textarea>
        </div>
        <div class="fc-2">
          <div class="fg">
            <label>Price (PHP) *</label>
            <input type="number" name="price" class="fc" placeholder="0.00" step="0.01" min="0" max="99999" required>
          </div>
          <div class="fg">
            <label>Day Availability *</label>
            <select name="day_availability" class="fc">
              @foreach(['common','monday','tuesday','wednesday','thursday','friday','saturday'] as $day)
                <option value="{{ $day }}">{{ ucfirst($day) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="fg">
          <label>Stock *</label>
          <input type="number" name="stock" class="fc" placeholder="0" min="0" step="1" required>
        </div>
        <div class="fg">
          <label>Product Image</label>
          <input type="file" name="image" class="fc" accept="image/jpeg,image/png,image/webp" style="padding:.45rem .85rem">
          <div style="font-size:.72rem;color:#9ca3af;margin-top:.25rem">JPG, PNG or WebP — max 2MB</div>
        </div>
      </div>
      <div class="mfoot">
        <button type="button" class="btn-cancel" onclick="closeModal('add-modal')">Cancel</button>
        <button type="submit" class="btn-save">Save Product</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT PRODUCT MODAL --}}
<div class="mover" id="edit-modal" onclick="if(event.target===this)closeModal('edit-modal')">
  <div class="mbox">
    <div class="mhd">
      <h3>Edit Product</h3>
      <button class="mc-btn" onclick="closeModal('edit-modal')">x</button>
    </div>
    <form method="POST" id="edit-form" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="mbody">
        <div class="fg">
          <label>Product Name *</label>
          <input type="text" name="name" id="edit-name" class="fc" required maxlength="100">
        </div>
        <div class="fg">
          <label>Category</label>
          <input type="text" name="category" id="edit-category" class="fc" maxlength="50">
        </div>
        <div class="fg">
          <label>Description</label>
          <textarea name="description" id="edit-desc" class="fc" rows="2" style="resize:vertical" maxlength="500"></textarea>
        </div>
        <div class="fc-2">
          <div class="fg">
            <label>Price (PHP) *</label>
            <input type="number" name="price" id="edit-price" class="fc" step="0.01" min="0" max="99999" required>
          </div>
          <div class="fg">
            <label>Day Availability *</label>
            <select name="day_availability" id="edit-day" class="fc">
              @foreach(['common','monday','tuesday','wednesday','thursday','friday','saturday'] as $day)
                <option value="{{ $day }}">{{ ucfirst($day) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="fg">
          <label>Stock *</label>
          <input type="number" name="stock" id="edit-stock" class="fc" min="0" step="1" required>
        </div>
        <div class="fg">
          <label>Replace Image <span style="font-weight:400;color:#9ca3af">(leave blank to keep current)</span></label>
          <input type="file" name="image" class="fc" accept="image/jpeg,image/png,image/webp" style="padding:.45rem .85rem">
          <div style="font-size:.72rem;color:#9ca3af;margin-top:.25rem">JPG, PNG or WebP — max 2MB</div>
        </div>
      </div>
      <div class="mfoot">
        <button type="button" class="btn-cancel" onclick="closeModal('edit-modal')">Cancel</button>
        <button type="submit" class="btn-save">Update Product</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openEdit(id, name, category, desc, price, stock, day) {
  const base = '{{ rtrim(url("admin/products"), "/") }}/';
  document.getElementById('edit-form').action = base + id;
  document.getElementById('edit-name').value  = name;
  document.getElementById('edit-category').value = category;
  document.getElementById('edit-desc').value  = desc;
  document.getElementById('edit-price').value = price;
  document.getElementById('edit-stock').value = stock ?? 0;
  document.getElementById('edit-day').value   = day;
  openModal('edit-modal');
}

// Close modal on Escape
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    closeModal('add-modal');
    closeModal('edit-modal');
  }
});
</script>
@endsection
