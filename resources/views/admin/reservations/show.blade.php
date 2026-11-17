@extends('admin.layouts.app')
@section('page-title', 'Detail Reservasi – '.$reservation->code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold">
    <a href="{{ route('admin.reservations.index') }}" class="text-muted me-2"><i class="bi bi-arrow-left"></i></a>
    Reservasi <code class="text-primary">{{ $reservation->code }}</code>
  </h5>
  @include('admin.partials.status-badge', ['status'=>$reservation->status, 'type'=>'reservation'])
</div>

<div class="row g-3">
  {{-- Info Tamu --}}
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-person me-1"></i>Tamu</div>
      <div class="card-body">
        <dl class="mb-0 row" style="font-size:.88rem">
          <dt class="col-5 text-muted">Nama</dt><dd class="col-7 fw-semibold">{{ $reservation->guest?->full_name }}</dd>
          <dt class="col-5 text-muted">Email</dt><dd class="col-7">{{ $reservation->guest?->email }}</dd>
          <dt class="col-5 text-muted">Telepon</dt><dd class="col-7">{{ $reservation->guest?->phone }}</dd>
          <dt class="col-5 text-muted">Negara</dt><dd class="col-7">{{ $reservation->guest?->country ?? '–' }}</dd>
        </dl>
        <a href="{{ route('admin.guests.show', $reservation->guest_id) }}" class="btn btn-sm btn-outline-secondary mt-2 w-100">
          <i class="bi bi-person-lines-fill me-1"></i>Lihat Profil Tamu
        </a>
      </div>
    </div>
  </div>

  {{-- Detail Reservasi --}}
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-calendar me-1"></i>Detail Menginap</div>
      <div class="card-body">
        <dl class="mb-0 row" style="font-size:.88rem">
          @foreach($reservation->details as $d)
          <dt class="col-5 text-muted">Kamar</dt><dd class="col-7 fw-semibold">{{ $d->room?->room_number }} – {{ $d->roomType?->name }}</dd>
          <dt class="col-5 text-muted">Lantai</dt><dd class="col-7">{{ $d->room?->floor }}</dd>
          @endforeach
          <dt class="col-5 text-muted">Check-in</dt><dd class="col-7">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}</dd>
          <dt class="col-5 text-muted">Check-out</dt><dd class="col-7">{{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}</dd>
          <dt class="col-5 text-muted">Malam</dt><dd class="col-7">{{ $reservation->nights }}</dd>
          <dt class="col-5 text-muted">Tamu</dt><dd class="col-7">{{ $reservation->guests_count }} orang</dd>
        </dl>
        @if($reservation->special_requests)
        <div class="alert alert-info py-2 mt-2 mb-0" style="font-size:.8rem">
          <i class="bi bi-chat-left-dots me-1"></i>{{ $reservation->special_requests }}
        </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Pembayaran --}}
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-header"><i class="bi bi-credit-card me-1"></i>Pembayaran</div>
      <div class="card-body">
        @php $pay = $reservation->payment; @endphp
        <dl class="mb-2 row" style="font-size:.88rem">
          <dt class="col-6 text-muted">Subtotal</dt><dd class="col-6">Rp {{ number_format($reservation->subtotal,0,',','.') }}</dd>
          <dt class="col-6 text-muted">Service</dt><dd class="col-6">Rp {{ number_format($reservation->service_amount,0,',','.') }}</dd>
          <dt class="col-6 text-muted">Pajak</dt><dd class="col-6">Rp {{ number_format($reservation->tax_amount,0,',','.') }}</dd>
          <dt class="col-6 text-muted fw-bold">Total</dt><dd class="col-6 fw-bold text-primary fs-6">Rp {{ number_format($reservation->total_amount,0,',','.') }}</dd>
          <dt class="col-6 text-muted">Metode</dt><dd class="col-6">{{ str_replace('_',' ', $pay?->method ?? '–') }}</dd>
          <dt class="col-6 text-muted">Status</dt>
          <dd class="col-6">@include('admin.partials.status-badge', ['status'=>$pay?->status ?? 'pending', 'type'=>'payment'])</dd>
          <dt class="col-6 text-muted">Referensi</dt><dd class="col-6"><code>{{ $pay?->reference ?? '–' }}</code></dd>
          <dt class="col-6 text-muted">Dibayar</dt><dd class="col-6">{{ $pay?->paid_at ? \Carbon\Carbon::parse($pay->paid_at)->format('d M Y H:i') : '–' }}</dd>
        </dl>

        {{-- Update status pembayaran --}}
        @if($pay)
        <form method="POST" action="{{ route('admin.payments.update', $pay) }}" class="border-top pt-2">
          @csrf @method('PATCH')
          <div class="row g-1">
            <div class="col">
              <select name="status" class="form-select form-select-sm">
                @foreach(['pending','paid','failed','refunded'] as $s)
                  <option value="{{ $s }}" {{ $pay->status===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-auto">
              <button class="btn btn-sm btn-warning">Update</button>
            </div>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Update Status Reservasi --}}
<div class="card mt-3">
  <div class="card-header"><i class="bi bi-arrow-repeat me-1"></i>Ubah Status Reservasi</div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.reservations.status', $reservation) }}" class="row g-2 align-items-end">
      @csrf @method('PATCH')
      <div class="col-sm-3">
        <label class="form-label small mb-1">Status Baru</label>
        <select name="status" class="form-select form-select-sm">
          @foreach(['pending','confirmed','checked_in','checked_out','cancelled'] as $s)
            <option value="{{ $s }}" {{ $reservation->status===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-5">
        <label class="form-label small mb-1">Alasan (opsional untuk pembatalan)</label>
        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Alasan pembatalan…">
      </div>
      <div class="col-sm-2">
        <button class="btn btn-sm btn-primary w-100">Simpan Status</button>
      </div>
    </form>
  </div>
</div>
@endsection
