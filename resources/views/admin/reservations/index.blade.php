@extends('admin.layouts.app')
@section('page-title', 'Daftar Reservasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2"></i>Reservasi</h5>
  <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Buat Reservasi
  </a>
</div>

{{-- Filter --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-4">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari kode / nama / email…" value="{{ request('q') }}">
      </div>
      <div class="col-sm-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          @foreach($statuses as $s)
            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-2">
        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="Dari tanggal">
      </div>
      <div class="col-sm-2">
        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="Sampai tanggal">
      </div>
      <div class="col-sm-2 d-flex gap-1">
        <button class="btn btn-sm btn-primary flex-fill"><i class="bi bi-search"></i> Cari</button>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Kode</th><th>Tamu</th><th>Kamar</th>
            <th>Check-in</th><th>Check-out</th><th>Malam</th>
            <th>Total</th><th>Status Res.</th><th>Pembayaran</th><th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($reservations as $r)
          <tr>
            <td><code class="text-primary fw-bold">{{ $r->code }}</code></td>
            <td>
              <div class="fw-semibold" style="font-size:.85rem">{{ $r->guest?->full_name }}</div>
              <div class="text-muted" style="font-size:.75rem">{{ $r->guest?->email }}</div>
            </td>
            <td>{{ $r->details->first()?->room?->room_number ?? '–' }}</td>
            <td>{{ \Carbon\Carbon::parse($r->check_in)->format('d M Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($r->check_out)->format('d M Y') }}</td>
            <td class="text-center">{{ $r->nights }}</td>
            <td class="fw-semibold">Rp {{ number_format($r->total_amount,0,',','.') }}</td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->status, 'type'=>'reservation'])</td>
            <td>@include('admin.partials.status-badge', ['status'=>$r->payment?->status ?? 'pending', 'type'=>'payment'])</td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.reservations.show', $r) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                  <i class="bi bi-eye"></i>
                </a>
                <form method="POST" action="{{ route('admin.reservations.destroy', $r) }}"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus reservasi {{ $r->code }}?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Reservasi">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada reservasi
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($reservations->hasPages())
  <div class="card-footer d-flex justify-content-between align-items-center py-2">
    <small class="text-muted">Total: {{ $reservations->total() }} reservasi</small>
    {{ $reservations->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
