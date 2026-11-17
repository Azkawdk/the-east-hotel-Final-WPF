@extends('admin.layouts.app')
@section('page-title', 'Daftar Tamu')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-people me-2"></i>Tamu</h5>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-5">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama / email / telepon…" value="{{ request('q') }}">
      </div>
      <div class="col-sm-2 d-flex gap-1">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
        <a href="{{ route('admin.guests.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Negara</th><th>Reservasi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          @forelse($guests as $g)
          <tr>
            <td class="fw-semibold">{{ $g->full_name }}</td>
            <td>{{ $g->email }}</td>
            <td>{{ $g->phone }}</td>
            <td>{{ $g->country ?? '–' }}</td>
            <td class="text-center">
              <span class="badge bg-primary rounded-pill">{{ $g->reservations_count }}</span>
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.guests.show', $g) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                <a href="{{ route('admin.guests.edit', $g) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('admin.guests.destroy', $g) }}"
                  onsubmit="return confirm('Hapus tamu {{ $g->full_name }}?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-5">
            <i class="bi bi-people fs-2 d-block mb-2"></i>Belum ada data tamu
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($guests->hasPages())
  <div class="card-footer d-flex justify-content-between align-items-center py-2">
    <small class="text-muted">Total: {{ $guests->total() }} tamu</small>
    {{ $guests->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
