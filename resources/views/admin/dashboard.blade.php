@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Page Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h5 class="mb-0 fw-bold" style="font-family:'Playfair Display',serif;font-size:1.3rem">
      Selamat datang, {{ auth()->user()?->name }} 👋
    </h5>
    <p class="text-muted mb-0 mt-1" style="font-size:.8rem">
      Berikut ringkasan operasional hotel hari ini — {{ now()->setTimezone('Asia/Makassar')->translatedFormat('l, d F Y') }}
    </p>
  </div>
  <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2 px-3">
    <i class="bi bi-plus-lg"></i> Buat Reservasi
  </a>
</div>

{{-- ── Stat Cards Row 1: Reservasi & Check ── --}}
@php
$allCards = [
  [
    'icon'  => 'bi-calendar-check-fill',
    'label' => 'Total Reservasi',
    'val'   => $stats['total_reservations'],
    'link'  => route('admin.reservations.index'),
  ],
  [
    'icon'  => 'bi-hourglass-split',
    'label' => 'Menunggu Konfirmasi',
    'val'   => $stats['pending_reservations'],
    'link'  => route('admin.reservations.index', ['status'=>'pending']),
  ],
  [
    'icon'  => 'bi-box-arrow-in-down-right',
    'label' => 'Check-in Hari Ini',
    'val'   => $stats['checkins_today'],
    'link'  => route('admin.reservations.index'),
  ],
  [
    'icon'  => 'bi-box-arrow-up-right',
    'label' => 'Check-out Hari Ini',
    'val'   => $stats['checkouts_today'],
    'link'  => route('admin.reservations.index'),
  ],
  [
    'icon'  => 'bi-door-open-fill',
    'label' => 'Kamar Tersedia',
    'val'   => $stats['rooms_available_today'],
    'link'  => route('admin.rooms.index', ['status'=>'available']),
  ],
  [
    'icon'  => 'bi-door-closed-fill',
    'label' => 'Kamar Terisi',
    'val'   => $stats['rooms_occupied_today'],
    'link'  => route('admin.rooms.index', ['status'=>'occupied']),
  ],
  [
    'icon'  => 'bi-credit-card-2-front-fill',
    'label' => 'Pembayaran Pending',
    'val'   => $stats['payments_pending'],
    'link'  => route('admin.payments.index'),
  ],
  [
    'icon'  => 'bi-cash-stack',
    'label' => 'Pendapatan Bulan Ini',
    'val'   => 'Rp '.number_format($stats['revenue_this_month'],0,',','.'),
    'link'  => route('admin.payments.index'),
    'small' => true,
  ],
];
@endphp

<div class="row g-3 mb-4">
  @foreach($allCards as $c)
  <div class="col-sm-6 col-xl-3">
    <a href="{{ $c['link'] }}" class="text-decoration-none">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3 p-4">
          <div class="stat-icon">
            <i class="bi {{ $c['icon'] }}"></i>
          </div>
          <div class="flex-grow-1 min-w-0">
            <div class="stat-num text-truncate {{ isset($c['small']) ? 'stat-num-sm' : '' }}">{{ $c['val'] }}</div>
            <div class="stat-label">{{ $c['label'] }}</div>
          </div>
        </div>
      </div>
    </a>
  </div>
  @endforeach
</div>

{{-- ── Occupancy Bar ── --}}
@php
  $total = $stats['rooms_available_today'] + $stats['rooms_occupied_today'];
  $pct   = $total > 0 ? round(($stats['rooms_occupied_today'] / $total) * 100) : 0;
@endphp
<div class="card mb-4">
  <div class="card-body px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span style="font-size:.82rem;font-weight:600">
        <i class="bi bi-bar-chart-fill me-1" style="color:var(--brand-gold)"></i>
        Tingkat Hunian Hari Ini
      </span>
      <span class="fw-bold" style="color:var(--brand-dark);font-size:.95rem">{{ $pct }}%</span>
    </div>
    <div class="progress" style="height:8px;border-radius:1rem;background:#f1f3f8">
      <div class="progress-bar" style="width:{{ $pct }}%;background:var(--brand-gold);border-radius:1rem;transition:width .6s ease"></div>
    </div>
    <div class="d-flex justify-content-between mt-2" style="font-size:.73rem;color:#8492a6">
      <span><span class="fw-semibold" style="color:var(--brand-dark)">{{ $stats['rooms_available_today'] }}</span> tersedia</span>
      <span><span class="fw-semibold" style="color:var(--brand-dark)">{{ $stats['rooms_occupied_today'] }}</span> terisi dari {{ $total }} kamar operasional</span>
    </div>
  </div>
</div>

{{-- ── Recent Reservations ── --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="d-flex align-items-center gap-2">
      <i class="bi bi-clock-history" style="color:#c9a84c"></i>
      Reservasi Terbaru
    </span>
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary px-3">
      Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
    </a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Kode</th><th>Tamu</th><th>Kamar</th>
            <th>Check-in</th><th>Check-out</th><th>Total</th>
            <th>Status</th><th>Pembayaran</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent as $r)
          <tr onclick="location.href='{{ route('admin.reservations.show', $r) }}'" style="cursor:pointer">
            <td>
              <code class="text-primary fw-semibold" style="font-size:.8rem">{{ $r->code }}</code>
            </td>
            <td>
              <div class="fw-semibold" style="font-size:.82rem">{{ $r->guest?->full_name }}</div>
              <div class="text-muted" style="font-size:.72rem">{{ $r->guest?->email }}</div>
            </td>
            <td>
              <span class="badge" style="background:#f1f3f8;color:#374151;font-size:.72rem;font-weight:600">
                {{ $r->details->first()?->room?->room_number ?? '—' }}
              </span>
            </td>
            <td style="font-size:.82rem">{{ \Carbon\Carbon::parse($r->check_in)->format('d M Y') }}</td>
            <td style="font-size:.82rem">{{ \Carbon\Carbon::parse($r->check_out)->format('d M Y') }}</td>
            <td class="fw-semibold" style="font-size:.82rem">
              Rp {{ number_format($r->total_amount,0,',','.') }}
            </td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->status, 'type'=>'reservation'])</td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->payment?->status ?? 'pending', 'type'=>'payment'])</td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5" style="color:#8492a6">
              <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-25"></i>
              Belum ada reservasi
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
