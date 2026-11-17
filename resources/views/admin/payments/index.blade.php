@extends('admin.layouts.app')
@section('page-title', 'Daftar Pembayaran')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0 fw-bold"><i class="bi bi-credit-card me-2"></i>Pembayaran</h5>
</div>

<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-3">
        <input type="text" name="q" class="form-control form-control-sm" placeholder="Kode reservasi / nama tamu…" value="{{ request('q') }}">
      </div>
      <div class="col-sm-2">
        <select name="status" class="form-select form-select-sm">
          <option value="">Semua Status</option>
          @foreach($statuses as $s)
            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-2">
        <select name="method" class="form-select form-select-sm">
          <option value="">Semua Metode</option>
          @foreach($methods as $m)
            <option value="{{ $m }}" {{ request('method')===$m?'selected':'' }}>{{ str_replace('_',' ', ucfirst($m)) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-2 d-flex gap-1">
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Cari</button>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x"></i></a>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr><th>Kode Res.</th><th>Tamu</th><th>Jumlah</th><th>Metode</th><th>Status</th><th>Referensi</th><th>Dibayar</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          @forelse($payments as $pay)
          <tr>
            <td><code class="text-primary">{{ $pay->reservation?->code }}</code></td>
            <td>{{ $pay->reservation?->guest?->full_name }}</td>
            <td class="fw-semibold">Rp {{ number_format($pay->amount,0,',','.') }}</td>
            <td>{{ str_replace('_',' ', ucfirst($pay->method)) }}</td>
            <td>@include('admin.partials.status-badge', ['status'=>$pay->status, 'type'=>'payment'])</td>
            <td><code style="font-size:.75rem">{{ $pay->reference ?? '–' }}</code></td>
            <td style="font-size:.8rem">{{ $pay->paid_at ? \Carbon\Carbon::parse($pay->paid_at)->format('d M Y H:i') : '–' }}</td>
            <td>
              <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#payModal{{ $pay->id }}">
                <i class="bi bi-pencil-square"></i>
              </button>
              <!-- Modal -->
              <div class="modal fade" id="payModal{{ $pay->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header"><h6 class="modal-title">Update Pembayaran</h6>
                      <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.payments.update', $pay) }}">
                    @csrf @method('PATCH')
                    <div class="modal-body">
                      <div class="mb-2">
                        <label class="form-label small">Status</label>
                        <select name="status" class="form-select form-select-sm">
                          @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ $pay->status===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-2">
                        <label class="form-label small">Metode</label>
                        <select name="method" class="form-select form-select-sm">
                          @foreach($methods as $m)
                            <option value="{{ $m }}" {{ $pay->method===$m?'selected':'' }}>{{ str_replace('_',' ', ucfirst($m)) }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div>
                        <label class="form-label small">Catatan</label>
                        <input type="text" name="notes" class="form-control form-control-sm" value="{{ $pay->notes }}">
                      </div>
                    </div>
                    <div class="modal-footer py-2">
                      <button type="submit" class="btn btn-sm btn-primary w-100">Simpan</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted py-5">
            <i class="bi bi-credit-card fs-2 d-block mb-2"></i>Belum ada data pembayaran
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($payments->hasPages())
  <div class="card-footer d-flex justify-content-between align-items-center py-2">
    <small class="text-muted">Total: {{ $payments->total() }}</small>
    {{ $payments->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
