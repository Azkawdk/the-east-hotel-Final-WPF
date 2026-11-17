@extends('admin.layouts.app')
@section('page-title', 'Tambah Kamar')
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Tambah Kamar Baru</h5>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.rooms.store') }}">
    @csrf
    @include('admin.rooms._form')
    <button type="submit" class="btn btn-primary w-100 mt-3"><i class="bi bi-check-lg me-1"></i>Tambah Kamar</button>
    </form>
  </div>
</div>
@endsection
