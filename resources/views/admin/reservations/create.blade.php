@extends('admin.layouts.app')
@section('page-title', 'Buat Reservasi Baru')

@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i>Buat Reservasi Baru</h5>
</div>

<form method="POST" action="{{ route('admin.reservations.store') }}">
@csrf
<div class="row g-3">
  {{-- Tipe Kamar & Tanggal --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Detail Menginap</div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
          <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required>
            <option value="">– Pilih Tipe Kamar –</option>
            @foreach($roomTypes as $t)
            <option value="{{ $t->id }}" {{ old('room_type_id')==$t->id?'selected':'' }}>
              {{ $t->name }} – Rp {{ number_format($t->base_price,0,',','.') }}/malam (maks. {{ $t->capacity }} tamu)
            </option>
            @endforeach
          </select>
          @error('room_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row g-2">
          <div class="col">
            <label class="form-label">Check-in <span class="text-danger">*</span></label>
            <input type="date" name="check_in" class="form-control @error('check_in') is-invalid @enderror"
              value="{{ old('check_in', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
            @error('check_in')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col">
            <label class="form-label">Check-out <span class="text-danger">*</span></label>
            <input type="date" name="check_out" class="form-control @error('check_out') is-invalid @enderror"
              value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" required>
            @error('check_out')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label">Jumlah Tamu <span class="text-danger">*</span></label>
          <input type="number" name="guests_count" class="form-control @error('guests_count') is-invalid @enderror"
            value="{{ old('guests_count', 1) }}" min="1" max="10" required>
          @error('guests_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mt-3">
          <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
          <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
            <option value="pay_at_hotel" {{ old('payment_method')==='pay_at_hotel'?'selected':'' }}>Bayar di Hotel</option>
            <option value="cash" {{ old('payment_method')==='cash'?'selected':'' }}>Tunai</option>
            <option value="bank_transfer" {{ old('payment_method')==='bank_transfer'?'selected':'' }}>Transfer Bank</option>
            <option value="simulation" {{ old('payment_method')==='simulation'?'selected':'' }}>Simulasi</option>
          </select>
          @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mt-3">
          <label class="form-label">Permintaan Khusus</label>
          <textarea name="special_requests" class="form-control" rows="2" placeholder="Lantai tinggi, kamar adjoining, dll…">{{ old('special_requests') }}</textarea>
        </div>
      </div>
    </div>
  </div>

  {{-- Data Tamu --}}
  <div class="col-md-6">
    <div class="card">
      <div class="card-header">Data Tamu</div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
          <input type="text" name="guest[full_name]" class="form-control @error('guest.full_name') is-invalid @enderror"
            value="{{ old('guest.full_name') }}" required>
          @error('guest.full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Email <span class="text-danger">*</span></label>
          <input type="email" name="guest[email]" class="form-control @error('guest.email') is-invalid @enderror"
            value="{{ old('guest.email') }}" required>
          @error('guest.email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Telepon <span class="text-danger">*</span></label>
          <input type="text" name="guest[phone]" class="form-control @error('guest.phone') is-invalid @enderror"
            value="{{ old('guest.phone') }}" placeholder="+62 812 xxxx xxxx" required>
          @error('guest.phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Negara</label>
          <input type="text" name="guest[country]" class="form-control"
            value="{{ old('guest.country') }}" placeholder="Indonesia">
        </div>
      </div>
    </div>
    <div class="d-grid mt-3">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-check-lg me-1"></i>Buat Reservasi
      </button>
    </div>
  </div>
</div>
</form>
@endsection
