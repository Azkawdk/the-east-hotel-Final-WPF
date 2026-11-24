@extends('admin.layouts.app')
@section('page-title', 'Daftar Tipe Kamar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-house-door me-2"></i>Tipe Kamar</h5>
  @if(auth()->user()->role === 'admin')
  <a href="{{ route('admin.room-types.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Tambah Tipe Kamar
  </a>
  @endif
</div>

<div class="row g-3">
  @forelse($roomTypes as $t)
  <div class="col-md-6 col-xl-4">
    <div class="card h-100">
      @if($t->image_url)
      <img src="{{ $t->image_url }}" class="card-img-top" style="height:160px;object-fit:cover">
      @else
      <div class="d-flex align-items-center justify-content-center bg-light" style="height:120px">
        <i class="bi bi-house-door fs-1 text-muted"></i>
      </div>
      @endif
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-1">
          <h6 class="mb-0 fw-bold">{{ $t->name }}</h6>
          @if($t->is_active)
            <span class="badge bg-success">Aktif</span>
          @else
            <span class="badge bg-secondary">Nonaktif</span>
          @endif
        </div>
        <p class="text-muted mb-2" style="font-size:.8rem">{{ Str::limit($t->description, 90) }}</p>
        <div class="row g-1 mb-2" style="font-size:.8rem">
          <div class="col-6"><i class="bi bi-people me-1 text-muted"></i>Maks. {{ $t->capacity }} tamu</div>
          <div class="col-6"><i class="bi bi-arrows-angle-expand me-1 text-muted"></i>{{ $t->size_m2 ?? '–' }} m²</div>
          <div class="col-6"><i class="bi bi-moon me-1 text-muted"></i>{{ $t->bed_type ?? '–' }}</div>
          <div class="col-6"><i class="bi bi-door-open me-1 text-muted"></i>{{ $t->rooms_count }} kamar</div>
        </div>
        <div class="fw-bold text-primary mb-2">Rp {{ number_format($t->base_price, 0, ',', '.') }} <small class="fw-normal text-muted">/malam</small></div>
        @if($t->amenities->count())
        <div class="d-flex flex-wrap gap-1 mb-2">
          @foreach($t->amenities->take(4) as $a)
          <span class="badge bg-light text-dark border" style="font-size:.7rem">{{ $a->name }}</span>
          @endforeach
          @if($t->amenities->count() > 4)
          <span class="badge bg-light text-muted border" style="font-size:.7rem">+{{ $t->amenities->count()-4 }}</span>
          @endif
        </div>
        @endif
      </div>
      @if(auth()->user()->role === 'admin')
      <div class="card-footer bg-transparent d-flex gap-1">
        <a href="{{ route('admin.room-types.edit', $t) }}" class="btn btn-sm btn-outline-primary flex-fill">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="{{ route('admin.room-types.destroy', $t) }}"
          onsubmit="return confirm('Hapus tipe kamar ini?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
        </form>
      </div>
      @endif
    </div>
  </div>
  @empty
  <div class="col-12 text-center text-muted py-5">
    <i class="bi bi-house-door fs-1 d-block mb-2"></i>Belum ada tipe kamar
  </div>
  @endforelse
</div>
@endsection
