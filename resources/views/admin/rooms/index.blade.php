@extends('admin.layouts.app')
@section('page-title', 'Daftar Kamar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-door-open me-2"></i>Kamar</h5>
  @if(auth()->user()->role === 'admin')
  <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Tambah Kamar
  </a>
  @endif
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-4">
        <select name="room_type_id" class="form-select form-select-sm">
          <option value="">Semua Tipe</option>
          @foreach($roomTypes as $t)
            <option value="{{ $t->id }}" {{ request('room_type_id')==$t->id?'selected':'' }}>{{ $t->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          <option value="available" {{ request('status')==='available'?'selected':'' }}>Tersedia (Kosong)</option>
          <option value="occupied" {{ request('status')==='occupied'?'selected':'' }}>Penuh (Terisi)</option>
          <option value="maintenance" {{ request('status')==='maintenance'?'selected':'' }}>Perbaikan</option>
          <option value="out_of_service" {{ request('status')==='out_of_service'?'selected':'' }}>Tidak Aktif</option>
        </select>
      </div>
      <div class="col-sm-2 d-flex gap-1">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filter</button>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>No. Kamar</th><th>Tipe</th><th>Lantai</th>
            <th>Status</th><th>Catatan</th>
            @if(auth()->user()->role === 'admin')<th>Aksi</th>@endif
          </tr>
        </thead>
        <tbody>
          @forelse($rooms as $room)
          <tr>
            <td class="fw-bold">{{ $room->room_number }}</td>
            <td>{{ $room->roomType?->name }}</td>
            <td class="text-center">{{ $room->floor == 0 ? 'GF' : $room->floor }}</td>
            <td>
              @php
                $activeRes = $activeReservations->get($room->id);
              @endphp
              @if($room->status === 'maintenance')
                <span class="badge bg-warning badge-status">Perbaikan</span>
              @elseif($room->status === 'out_of_service')
                <span class="badge bg-secondary badge-status">Tidak Aktif</span>
              @elseif($activeRes)
                @if($activeRes->res_status === 'checked_in')
                  <span class="badge bg-danger badge-status">🏠 Check-in</span>
                @elseif($activeRes->res_status === 'confirmed')
                  <span class="badge bg-danger badge-status">Penuh</span>
                @else
                  <span class="badge badge-status" style="background:#f97316;">Dipesan</span>
                @endif
                <a href="{{ route('admin.reservations.show', $activeRes->reservation_id) }}" class="d-block text-muted text-decoration-none mt-1" style="font-size:.72rem" title="Lihat Reservasi">
                  <i class="bi bi-person-fill text-primary"></i> {{ $activeRes->guest_name }}
                  <code class="text-muted">{{ $activeRes->code }}</code>
                </a>
              @else
                <span class="badge bg-success badge-status">Tersedia</span>
              @endif
            </td>
            <td class="text-muted" style="font-size:.8rem">{{ $room->notes ?? '–' }}</td>
            @if(auth()->user()->role === 'admin')
            <td>
              <div class="d-flex gap-1">
                <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}"
                  onsubmit="return confirm('Hapus kamar {{ $room->room_number }}?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </div>
            </td>
            @endif
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-5">
            <i class="bi bi-door-open fs-2 d-block mb-2"></i>Belum ada kamar
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($rooms->hasPages())
  <div class="card-footer">{{ $rooms->links('pagination::bootstrap-5') }}</div>
  @endif
</div>
@endsection
