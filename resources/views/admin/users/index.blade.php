@extends('admin.layouts.app')
@section('page-title', 'Daftar Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-person-gear me-2"></i>Pengguna Sistem</h5>
  <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg me-1"></i>Tambah Pengguna
  </a>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td class="fw-semibold">
            {{ $user->name }}
            @if($user->id === auth()->id())
            <span class="badge bg-info text-white ms-1" style="font-size:.65rem">Anda</span>
            @endif
          </td>
          <td>{{ $user->email }}</td>
          <td>
            <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
              {{ ucfirst($user->role) }}
            </span>
          </td>
          <td>
            @if($user->is_active)
              <span class="badge bg-success">Aktif</span>
            @else
              <span class="badge bg-danger">Nonaktif</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              @if($user->id !== auth()->id())
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Hapus' }} pengguna {{ $user->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="{{ $user->is_active ? 'Nonaktifkan' : 'Hapus' }}">
                  <i class="bi bi-person-x"></i>
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pengguna</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
