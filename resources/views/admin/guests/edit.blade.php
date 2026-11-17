@extends('admin.layouts.app')
@section('page-title', 'Edit Tamu')
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.guests.show', $guest) }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Edit Tamu – {{ $guest->full_name }}</h5>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.guests.update', $guest) }}">
    @csrf @method('PUT')
    <div class="mb-3">
      <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
      <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
        value="{{ old('full_name', $guest->full_name) }}" maxlength="120" required>
      @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Email <span class="text-danger">*</span></label>
      <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
        value="{{ old('email', $guest->email) }}" maxlength="150" required>
      @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Telepon <span class="text-danger">*</span></label>
      <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
        value="{{ old('phone', $guest->phone) }}" maxlength="30" required>
      @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label">Negara</label>
      <input type="text" name="country" class="form-control"
        value="{{ old('country', $guest->country) }}" maxlength="80">
    </div>
    <div class="mb-3">
      <label class="form-label">No. KTP / Paspor</label>
      <input type="text" name="id_number" class="form-control"
        value="{{ old('id_number', $guest->id_number) }}" maxlength="50">
    </div>
    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
    </form>
  </div>
</div>
@endsection
