@extends('admin.layouts.app')
@section('page-title', 'Profil Tamu – '.$guest->full_name)
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.guests.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">{{ $guest->full_name }}</h5>
</div>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-person me-1"></i>Data Tamu</div>
      <div class="card-body">
        <dl class="row mb-0" style="font-size:.88rem">
          <dt class="col-5 text-muted">Nama</dt><dd class="col-7">{{ $guest->full_name }}</dd>
          <dt class="col-5 text-muted">Email</dt><dd class="col-7">{{ $guest->email }}</dd>
          <dt class="col-5 text-muted">Telepon</dt><dd class="col-7">{{ $guest->phone }}</dd>
          <dt class="col-5 text-muted">Negara</dt><dd class="col-7">{{ $guest->country ?? '–' }}</dd>
          <dt class="col-5 text-muted">ID/NIK</dt><dd class="col-7">{{ $guest->id_number ?? '–' }}</dd>
          <dt class="col-5 text-muted">Terdaftar</dt><dd class="col-7">{{ $guest->created_at->format('d M Y') }}</dd>
        </dl>
        <a href="{{ route('admin.guests.edit', $guest) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
          <i class="bi bi-pencil me-1"></i>Edit Data
        </a>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-header"><i class="bi bi-clock-history me-1"></i>Riwayat Reservasi</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="font-size:.88rem">
            <thead class="table-light">
              <tr><th>Kode</th><th>Check-in</th><th>Check-out</th><th>Total</th><th>Status</th><th>Pembayaran</th></tr>
            </thead>
            <tbody>
              @forelse($guest->reservations as $r)
              <tr onclick="location.href='{{ route('admin.reservations.show', $r) }}'" style="cursor:pointer">
                <td><code class="text-primary">{{ $r->code }}</code></td>
                <td>{{ \Carbon\Carbon::parse($r->check_in)->format('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($r->check_out)->format('d M Y') }}</td>
                <td>Rp {{ number_format($r->total_amount,0,',','.') }}</td>
                <td>@include('admin.partials.status-badge', ['status'=>$r->status, 'type'=>'reservation'])</td>
                <td>@include('admin.partials.status-badge', ['status'=>$r->payment?->status ?? 'pending', 'type'=>'payment'])</td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-muted py-3">Belum ada reservasi</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
