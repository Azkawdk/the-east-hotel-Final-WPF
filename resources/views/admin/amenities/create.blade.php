@extends('admin.layouts.app')
@section('page-title', 'Tambah Fasilitas')
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.amenities.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Tambah Fasilitas</h5>
</div>
<div class="card" style="max-width:480px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.amenities.store') }}">
    @csrf
    @include('admin.amenities._form')
    <button type="submit" class="btn btn-primary w-100 mt-3"><i class="bi bi-check-lg me-1"></i>Simpan</button>
    </form>
  </div>
</div>
@endsection
