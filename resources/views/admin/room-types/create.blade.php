@extends('admin.layouts.app')
@section('page-title', 'Tambah Tipe Kamar')

@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.room-types.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Tambah Tipe Kamar</h5>
</div>

<form method="POST" action="{{ route('admin.room-types.store') }}">
@csrf
@include('admin.room-types._form')
</form>
@endsection
