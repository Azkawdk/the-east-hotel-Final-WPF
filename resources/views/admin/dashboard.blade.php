@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stats Row --}}
<div class="row g-3 mb-4">
  @php
  $cards = [
    ['icon'=>'bi-calendar-check','label'=>'Total Reservasi','val'=>$stats['total_reservations'],'color'=>'#4f46e5'],
    ['icon'=>'bi-hourglass-split','label'=>'Pending','val'=>$stats['pending_reservations'],'color'=>'#f59e0b'],
    ['icon'=>'bi-box-arrow-in-down','label'=>'Check-in Hari Ini','val'=>$stats['checkins_today'],'color'=>'#10b981'],
    ['icon'=>'bi-box-arrow-up','label'=>'Check-out Hari Ini','val'=>$stats['checkouts_today'],'color'=>'#6366f1'],
    ['icon'=>'bi-door-open','label'=>'Kamar Tersedia','val'=>$stats['rooms_available_today'],'color'=>'#22c55e'],
    ['icon'=>'bi-door-closed','label'=>'Kamar Terisi','val'=>$stats['rooms_occupied_today'],'color'=>'#ef4444'],
    ['icon'=>'bi-credit-card','label'=>'Pembayaran Pending','val'=>$stats['payments_pending'],'color'=>'#f97316'],
    ['icon'=>'bi-cash-stack','label'=>'Pendapatan Bulan Ini','val'=>'Rp '.number_format($stats['revenue_this_month'],0,',','.'),'color'=>'#c9a84c'],
  ];
  @endphp

  @foreach($cards as $c)
  <div class="col-sm-6 col-xl-3">
    <div class="card stat-card h-100" style="border-left-color:{{ $c['color'] }}">
      <div class="card-body d-flex align-items-center gap-3 py-3">
        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
          style="width:46px;height:46px;background:{{ $c['color'] }}22">
          <i class="bi {{ $c['icon'] }} fs-5" style="color:{{ $c['color'] }}"></i>
        </div>
        <div>
          <div class="stat-num">{{ $c['val'] }}</div>
          <div class="stat-label">{{ $c['label'] }}</div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Recent Reservations --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center py-3">
    <span><i class="bi bi-clock-history me-2"></i>Reservasi Terbaru</span>
    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Kode</th><th>Tamu</th><th>Kamar</th>
            <th>Check-in</th><th>Check-out</th><th>Total</th>
            <th>Status</th><th>Pembayaran</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recent as $r)
          <tr onclick="location.href='{{ route('admin.reservations.show', $r) }}'" style="cursor:pointer">
            <td><code class="text-primary">{{ $r->code }}</code></td>
            <td>{{ $r->guest?->full_name }}</td>
            <td>{{ $r->details->first()?->room?->room_number ?? '–' }}</td>
            <td>{{ \Carbon\Carbon::parse($r->check_in)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($r->check_out)->format('d M Y') }}</td>
            <td class="fw-semibold">Rp {{ number_format($r->total_amount,0,',','.') }}</td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->status, 'type'=>'reservation'])</td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->payment?->status ?? 'pending', 'type'=>'payment'])</td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted py-4">Belum ada reservasi</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
