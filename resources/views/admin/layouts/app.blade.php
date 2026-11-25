<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('page-title', 'Dashboard') — The East Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --sidebar-w: 268px;
      --brand-dark: #0f1923;
      --brand-mid:  #162130;
      --brand-gold: #c9a84c;
      --brand-gold-light: #e2c97e;
      --surface:    #f8f9fc;
      --border:     #eef0f5;
      --text-primary: #1a2332;
      --text-muted:   #8492a6;
      --radius: .875rem;
    }

    * { box-sizing: border-box; }
    body {
      background: var(--surface);
      font-family: 'Inter', sans-serif;
      font-size: .875rem;
      color: var(--text-primary);
      margin: 0;
    }

    /* ═══════════ SIDEBAR ═══════════ */
    #sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: var(--brand-dark);
      position: fixed; top: 0; left: 0; z-index: 1000;
      display: flex; flex-direction: column;
      transition: transform .28s cubic-bezier(.4,0,.2,1);
      box-shadow: 4px 0 24px rgba(0,0,0,.18);
    }

    /* Brand */
    .sidebar-brand {
      padding: 1.4rem 1.4rem 1.1rem;
      border-bottom: 1px solid rgba(255,255,255,.07);
      display: flex; align-items: center; gap: .75rem;
    }
    .sidebar-brand .brand-icon {
      width: 38px; height: 38px; border-radius: .6rem;
      background: linear-gradient(135deg, var(--brand-gold), #a07830);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .sidebar-brand .brand-name {
      font-family: 'Playfair Display', serif;
      color: #fff; font-size: .95rem; font-weight: 700;
      line-height: 1.2; letter-spacing: .01em;
    }
    .sidebar-brand .brand-sub { color: rgba(255,255,255,.38); font-size: .68rem; letter-spacing: .08em; text-transform: uppercase; }

    /* Nav groups */
    .sidebar-body { flex: 1; overflow-y: auto; padding: .6rem 0; }
    .sidebar-body::-webkit-scrollbar { width: 3px; }
    .sidebar-body::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 2px; }

    .nav-group-label {
      font-size: .6rem; font-weight: 600; letter-spacing: .12em;
      text-transform: uppercase; color: rgba(255,255,255,.25);
      padding: .9rem 1.4rem .3rem;
    }

    .sidebar-link {
      display: flex; align-items: center; gap: .65rem;
      padding: .52rem 1rem .52rem 1.4rem;
      margin: .08rem .6rem;
      border-radius: .55rem;
      color: rgba(255,255,255,.58);
      text-decoration: none;
      font-size: .825rem; font-weight: 500;
      transition: background .18s, color .18s;
      position: relative;
    }
    .sidebar-link i { font-size: 1rem; flex-shrink: 0; }
    .sidebar-link:hover {
      background: rgba(255,255,255,.07);
      color: rgba(255,255,255,.92);
    }
    .sidebar-link.active {
      background: rgba(201,168,76,.14);
      color: var(--brand-gold-light);
    }
    .sidebar-link.active::before {
      content: '';
      position: absolute; left: 0; top: 20%; bottom: 20%;
      width: 3px; border-radius: 0 3px 3px 0;
      background: var(--brand-gold);
      margin-left: -.6rem;
    }

    /* User footer */
    .sidebar-footer {
      padding: .9rem 1.1rem;
      border-top: 1px solid rgba(255,255,255,.07);
    }
    .user-pill {
      display: flex; align-items: center; gap: .65rem;
      padding: .5rem .6rem;
      border-radius: .55rem;
      margin-bottom: .6rem;
      background: rgba(255,255,255,.05);
    }
    .user-avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: linear-gradient(135deg, var(--brand-gold), #8a6520);
      display: flex; align-items: center; justify-content: center;
      font-size: .78rem; color: #fff; font-weight: 700; flex-shrink: 0;
    }
    .user-name  { color: rgba(255,255,255,.88); font-size: .78rem; font-weight: 600; line-height: 1.2; }
    .user-role  { color: rgba(255,255,255,.35); font-size: .67rem; }
    .btn-logout {
      width: 100%; padding: .4rem .75rem;
      background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: .5rem;
      color: rgba(255,255,255,.55);
      font-size: .78rem; font-weight: 500;
      cursor: pointer; transition: background .18s, color .18s;
      display: flex; align-items: center; justify-content: center; gap: .4rem;
    }
    .btn-logout:hover { background: rgba(239,68,68,.18); color: #fca5a5; border-color: rgba(239,68,68,.3); }

    /* ═══════════ MAIN ═══════════ */
    #main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

    /* Topbar */
    #topbar {
      background: #fff;
      border-bottom: 1px solid var(--border);
      padding: .75rem 1.75rem;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 900;
      box-shadow: 0 1px 0 var(--border);
    }
    .topbar-title { font-size: .9rem; font-weight: 600; color: var(--text-primary); }
    .topbar-meta  { display: flex; align-items: center; gap: 1rem; }
    .topbar-time  {
      font-size: .78rem; color: var(--text-muted);
      background: var(--surface); border: 1px solid var(--border);
      padding: .28rem .75rem; border-radius: 2rem;
      display: flex; align-items: center; gap: .4rem;
    }

    /* Content */
    .content-area { padding: 1.75rem; flex: 1; }

    /* ═══════════ CARDS ═══════════ */
    .card {
      border: 1px solid var(--border);
      box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 16px rgba(0,0,0,.03);
      border-radius: var(--radius);
      background: #fff;
    }
    .card-header {
      background: transparent;
      border-bottom: 1px solid var(--border);
      font-weight: 600; font-size: .85rem;
      padding: 1rem 1.25rem;
    }

    /* ═══════════ STAT CARDS ═══════════ */
    .stat-card {
      border: 1px solid var(--border) !important;
      border-radius: var(--radius) !important;
      background: #fff;
      transition: transform .2s, box-shadow .2s;
      overflow: hidden;
      position: relative;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.08) !important; }
    .stat-card::after {
      content: '';
      position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
      background: var(--brand-gold);
      opacity: .6;
    }
    .stat-card .stat-num {
      font-size: 1.9rem; font-weight: 700;
      color: var(--text-primary); line-height: 1; margin-bottom: .2rem;
    }
    .stat-num-sm { font-size: 1.25rem !important; }
    .stat-card .stat-label { font-size: .75rem; color: var(--text-muted); font-weight: 500; letter-spacing: .02em; }
    .stat-card .stat-icon {
      width: 44px; height: 44px; border-radius: .65rem;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.15rem; flex-shrink: 0;
      background: rgba(201,168,76,.12);
      color: var(--brand-gold);
    }

    /* ═══════════ TABLES ═══════════ */
    .table { font-size: .825rem; }
    .table thead th {
      font-size: .7rem; text-transform: uppercase; letter-spacing: .06em;
      color: var(--text-muted); font-weight: 600;
      background: #fafbfd; border-bottom: 2px solid var(--border);
      padding: .75rem 1rem; white-space: nowrap;
    }
    .table tbody td { padding: .8rem 1rem; vertical-align: middle; border-color: #f5f6f9; }
    .table-hover tbody tr:hover { background: #fafbfe; }

    /* ═══════════ BADGES ═══════════ */
    .badge-status { font-size: .7rem; padding: .3em .65em; border-radius: .4em; font-weight: 600; }

    /* ═══════════ ALERTS ═══════════ */
    .alert { border-radius: .65rem; border: none; font-size: .825rem; }
    .alert-success { background: #ecfdf5; color: #065f46; }
    .alert-danger  { background: #fef2f2; color: #991b1b; }

    /* ═══════════ BUTTONS ═══════════ */
    .btn { border-radius: .5rem; font-size: .825rem; font-weight: 500; }
    .btn-primary { background: var(--brand-dark); border-color: var(--brand-dark); }
    .btn-primary:hover { background: #1e3048; border-color: #1e3048; }

    /* ═══════════ FORMS ═══════════ */
    .form-control, .form-select {
      border-color: var(--border); border-radius: .5rem;
      font-size: .825rem; color: var(--text-primary);
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--brand-gold);
      box-shadow: 0 0 0 .2rem rgba(201,168,76,.18);
    }

    /* ═══════════ MOBILE ═══════════ */
    @media (max-width: 991px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.show { transform: translateX(0); }
      #main { margin-left: 0; }
    }

    /* Overlay for mobile */
    #sidebar-overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 999;
    }
    #sidebar-overlay.show { display: block; }
  </style>
</head>
<body>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

{{-- ══════════ SIDEBAR ══════════ --}}
<nav id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">
      <i class="bi bi-building text-white" style="font-size:1.1rem"></i>
    </div>
    <div>
      <div class="brand-name">The East Hotel</div>
      <div class="brand-sub">Admin Panel</div>
    </div>
  </div>

  <div class="sidebar-body">
    <div class="nav-group-label">Menu Utama</div>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active':'' }}">
      <i class="bi bi-grid-1x2"></i> Dashboard
    </a>

    <div class="nav-group-label">Operasional</div>
    <a href="{{ route('admin.reservations.index') }}" class="sidebar-link {{ request()->routeIs('admin.reservations*') ? 'active':'' }}">
      <i class="bi bi-calendar-check"></i> Reservasi
    </a>
    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments*') ? 'active':'' }}">
      <i class="bi bi-credit-card-2-front"></i> Pembayaran
    </a>
    <a href="{{ route('admin.guests.index') }}" class="sidebar-link {{ request()->routeIs('admin.guests*') ? 'active':'' }}">
      <i class="bi bi-people"></i> Tamu
    </a>

    <div class="nav-group-label">Data Master</div>
    <a href="{{ route('admin.room-types.index') }}" class="sidebar-link {{ request()->routeIs('admin.room-types*') ? 'active':'' }}">
      <i class="bi bi-house-door"></i> Tipe Kamar
    </a>
    <a href="{{ route('admin.rooms.index') }}" class="sidebar-link {{ request()->routeIs('admin.rooms.index') || request()->routeIs('admin.rooms.create') || request()->routeIs('admin.rooms.edit') ? 'active':'' }}">
      <i class="bi bi-door-open"></i> Kamar
    </a>
    <a href="{{ route('admin.amenities.index') }}" class="sidebar-link {{ request()->routeIs('admin.amenities*') ? 'active':'' }}">
      <i class="bi bi-stars"></i> Fasilitas
    </a>

    @if(auth()->user()?->role === 'admin')
    <div class="nav-group-label">Pengaturan</div>
    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active':'' }}">
      <i class="bi bi-person-gear"></i> Pengguna
    </a>
    @endif
  </div>

  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}</div>
      <div>
        <div class="user-name">{{ auth()->user()?->name }}</div>
        <div class="user-role">{{ ucfirst(auth()->user()?->role) }}</div>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <i class="bi bi-box-arrow-right"></i> Keluar
      </button>
    </form>
  </div>
</nav>

{{-- ══════════ MAIN CONTENT ══════════ --}}
<div id="main">
  {{-- Topbar --}}
  <div id="topbar">
    <div class="d-flex align-items-center gap-3">
      <button class="btn btn-sm btn-light d-lg-none px-2" onclick="toggleSidebar()">
        <i class="bi bi-list fs-5"></i>
      </button>
      <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
    </div>
    <div class="topbar-meta">
      <div class="topbar-time">
        <i class="bi bi-clock text-muted"></i>
        <span id="live-clock">{{ now()->setTimezone('Asia/Makassar')->format('d M Y, H:i') }} WIB</span>
      </div>
    </div>
  </div>

  {{-- Flash messages --}}
  @if(session('success') || $errors->any())
  <div class="px-4 pt-3">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
        <button type="button" class="btn-close ms-auto py-2" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span>{{ $errors->first() }}</span>
        <button type="button" class="btn-close ms-auto py-2" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>
  @endif

  <div class="content-area">
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebar-overlay').classList.toggle('show');
  }
  // Live clock
  function updateClock() {
    const now = new Date();
    const opts = { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:false, timeZone:'Asia/Makassar' };
    document.getElementById('live-clock').textContent = now.toLocaleString('id-ID', opts) + ' WIB';
  }
  setInterval(updateClock, 1000);
</script>
@stack('scripts')
</body>
</html>
