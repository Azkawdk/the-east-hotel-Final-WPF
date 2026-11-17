<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel – The East Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-w: 260px;
      --brand-dark: #1a2332;
      --brand-gold: #c9a84c;
    }
    body { background: #f4f6f9; }
    /* ── Sidebar ── */
    #sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: var(--brand-dark);
      position: fixed; top:0; left:0; z-index:1000;
      display:flex; flex-direction:column;
      transition: transform .25s;
    }
    #sidebar .brand {
      padding: 1.5rem 1.25rem 1rem;
      border-bottom: 1px solid rgba(255,255,255,.08);
    }
    #sidebar .brand h6 { color: var(--brand-gold); font-weight:700; letter-spacing:.05em; margin:0; }
    #sidebar .brand small { color:rgba(255,255,255,.45); font-size:.7rem; }
    #sidebar .nav-link {
      color: rgba(255,255,255,.65);
      padding: .5rem 1.25rem;
      border-radius: .375rem;
      margin: .1rem .5rem;
      transition: background .15s, color .15s;
      display:flex; align-items:center; gap:.6rem;
    }
    #sidebar .nav-link:hover, #sidebar .nav-link.active {
      color:#fff; background: rgba(255,255,255,.1);
    }
    #sidebar .nav-link.active { border-left: 3px solid var(--brand-gold); }
    #sidebar .nav-section { font-size:.65rem; color:rgba(255,255,255,.3); letter-spacing:.1em;
      padding:.75rem 1.25rem .25rem; text-transform:uppercase; }
    /* ── Main ── */
    #main { margin-left: var(--sidebar-w); min-height:100vh; }
    #topbar { background:#fff; border-bottom:1px solid #e9ecef; padding:.75rem 1.5rem;
      display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:900; }
    .content-area { padding: 1.5rem; }
    /* ── Cards & Tables ── */
    .card { border:none; box-shadow:0 1px 4px rgba(0,0,0,.07); border-radius:.75rem; }
    .card-header { background:transparent; border-bottom:1px solid #f0f0f0; font-weight:600; }
    .table th { font-size:.78rem; text-transform:uppercase; letter-spacing:.04em; color:#6c757d; }
    .badge-status { font-size:.72rem; padding:.3em .6em; border-radius:.4em; }
    /* ── Stat cards ── */
    .stat-card { border-left:4px solid var(--brand-gold); }
    .stat-card .stat-num { font-size:1.75rem; font-weight:700; color:var(--brand-dark); }
    .stat-card .stat-label { font-size:.8rem; color:#6c757d; }
    /* ── Mobile ── */
    @media(max-width:991px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.show { transform: translateX(0); }
      #main { margin-left:0; }
    }
  </style>
</head>
<body>

{{-- SIDEBAR --}}
<nav id="sidebar">
  <div class="brand">
    <h6><i class="bi bi-building me-1"></i>The East Hotel</h6>
    <small>Admin Panel</small>
  </div>
  <div class="mt-2 flex-grow-1 overflow-auto py-2">
    <div class="nav-section">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="nav-section">Operasional</div>
    <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations*') ? 'active':'' }}">
      <i class="bi bi-calendar-check"></i> Reservasi
    </a>
    <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active':'' }}">
      <i class="bi bi-credit-card"></i> Pembayaran
    </a>
    <a href="{{ route('admin.guests.index') }}" class="nav-link {{ request()->routeIs('admin.guests*') ? 'active':'' }}">
      <i class="bi bi-people"></i> Tamu
    </a>

    <div class="nav-section">Data Master</div>
    <a href="{{ route('admin.room-types.index') }}" class="nav-link {{ request()->routeIs('admin.room-types*') ? 'active':'' }}">
      <i class="bi bi-house-door"></i> Tipe Kamar
    </a>
    <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms*') ? 'active':'' }}">
      <i class="bi bi-door-open"></i> Kamar
    </a>
    <a href="{{ route('admin.amenities.index') }}" class="nav-link {{ request()->routeIs('admin.amenities*') ? 'active':'' }}">
      <i class="bi bi-stars"></i> Fasilitas
    </a>

    @if(auth()->user()?->role === 'admin')
    <div class="nav-section">Pengaturan</div>
    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active':'' }}">
      <i class="bi bi-person-gear"></i> Pengguna
    </a>
    @endif
  </div>

  <div class="p-3 border-top" style="border-color:rgba(255,255,255,.08)!important">
    <div class="d-flex align-items-center gap-2 mb-2">
      <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width:32px;height:32px">
        <i class="bi bi-person-fill text-white" style="font-size:.85rem"></i>
      </div>
      <div>
        <div style="color:#fff;font-size:.8rem;font-weight:600">{{ auth()->user()?->name }}</div>
        <div style="color:rgba(255,255,255,.4);font-size:.7rem">{{ ucfirst(auth()->user()?->role) }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="btn btn-sm btn-outline-secondary w-100 text-white border-secondary">
        <i class="bi bi-box-arrow-right me-1"></i>Keluar
      </button>
    </form>
  </div>
</nav>

{{-- MAIN --}}
<div id="main">
  {{-- Topbar --}}
  <div id="topbar">
    <button class="btn btn-sm btn-light d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
      <i class="bi bi-list fs-5"></i>
    </button>
    <div class="fw-semibold text-muted" style="font-size:.9rem">
      @yield('page-title', 'Dashboard')
    </div>
    <div class="d-flex align-items-center gap-2 text-muted" style="font-size:.8rem">
      <i class="bi bi-clock"></i> {{ now()->format('d M Y, H:i') }} WIB
    </div>
  </div>

  {{-- Flash messages --}}
  <div class="px-3 pt-3">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>
        {{ $errors->first() }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>

  <div class="content-area">
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
