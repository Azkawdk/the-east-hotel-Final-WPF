@extends('admin.layouts.app')
@section('page-title', 'Edit Tipe Kamar – '.$roomType->name)

@section('content')
<div class="d-flex align-items-center mb-3">
  <a href="{{ route('admin.room-types.index') }}" class="btn btn-sm btn-light me-2"><i class="bi bi-arrow-left"></i></a>
  <h5 class="mb-0 fw-bold">Edit – {{ $roomType->name }}</h5>
</div>

<form method="POST" action="{{ route('admin.room-types.update', $roomType) }}">
@csrf @method('PUT')
@include('admin.room-types._form', ['roomType' => $roomType])
</form>
@endsection
