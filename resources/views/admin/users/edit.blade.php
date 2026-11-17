@extends('admin.layouts.app')
@section('page-title', 'Edit Pengguna')
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Edit – {{ $user->name }}</h5>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')
    @include('admin.users._form', ['user' => $user])
    <div class="form-check form-switch mt-2 mb-3">
      <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
        {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">Akun Aktif</label>
    </div>
    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
    </form>
  </div>
</div>
@endsection
