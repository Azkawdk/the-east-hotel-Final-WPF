@extends('admin.layouts.app')
@section('page-title', 'Tambah Pengguna')
@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Tambah Pengguna Baru</h5>
</div>
<div class="card" style="max-width:520px">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    @include('admin.users._form')
    <button type="submit" class="btn btn-primary w-100 mt-3"><i class="bi bi-check-lg me-1"></i>Tambah Pengguna</button>
    </form>
  </div>
</div>
@endsection
