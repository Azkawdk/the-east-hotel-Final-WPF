@extends('admin.layouts.app')
@section('page-title', 'Fasilitas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-stars me-2"></i>Fasilitas</h5>
  @if(auth()->user()->role === 'admin')
  <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Tambah Fasilitas
  </a>
  @endif
</div>

@foreach(['hotel'=>'Fasilitas Hotel', 'room'=>'Fasilitas Kamar'] as $scope => $label)
<h6 class="text-muted mt-3 mb-2">{{ $label }}</h6>
<div class="row g-2 mb-3">
  @foreach($amenities->where('scope', $scope) as $a)
  <div class="col-sm-6 col-md-4 col-lg-3">
    <div class="card h-100">
      <div class="card-body py-2 d-flex align-items-start justify-content-between gap-2">
        <div>
          <div class="fw-semibold" style="font-size:.88rem">{{ $a->name }}</div>
          @if($a->description)
          <div class="text-muted" style="font-size:.75rem">{{ $a->description }}</div>
          @endif
        </div>
        @if(auth()->user()->role === 'admin')
        <div class="d-flex gap-1 flex-shrink-0">
          <a href="{{ route('admin.amenities.edit', $a) }}" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size:.75rem"><i class="bi bi-pencil"></i></a>
          <form method="POST" action="{{ route('admin.amenities.destroy', $a) }}" onsubmit="return confirm('Hapus fasilitas ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-xs btn-outline-danger py-0 px-1" style="font-size:.75rem"><i class="bi bi-trash"></i></button>
          </form>
        </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>
@endforeach
@endsection
