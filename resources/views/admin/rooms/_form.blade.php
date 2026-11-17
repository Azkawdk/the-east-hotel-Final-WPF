<div class="mb-3">
  <label class="form-label">Tipe Kamar <span class="text-danger">*</span></label>
  <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror" required>
    <option value="">– Pilih –</option>
    @foreach($roomTypes as $t)
    <option value="{{ $t->id }}" {{ old('room_type_id', $room->room_type_id ?? '') == $t->id ? 'selected' : '' }}>
      {{ $t->name }}
    </option>
    @endforeach
  </select>
  @error('room_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Nomor Kamar <span class="text-danger">*</span></label>
  <input type="text" name="room_number" class="form-control @error('room_number') is-invalid @enderror"
    value="{{ old('room_number', $room->room_number ?? '') }}" maxlength="10" required>
  @error('room_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Lantai <span class="text-danger">*</span></label>
  <input type="number" name="floor" class="form-control @error('floor') is-invalid @enderror"
    value="{{ old('floor', $room->floor ?? 1) }}" min="0" max="50" required>
  <div class="form-text">0 = Lantai Dasar (Ground Floor)</div>
  @error('floor')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Status <span class="text-danger">*</span></label>
  <select name="status" class="form-select @error('status') is-invalid @enderror" required>
    <option value="available" {{ old('status', $room->status ?? 'available') === 'available' ? 'selected' : '' }}>Tersedia</option>
    <option value="maintenance" {{ old('status', $room->status ?? '') === 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
    <option value="out_of_service" {{ old('status', $room->status ?? '') === 'out_of_service' ? 'selected' : '' }}>Tidak Aktif</option>
  </select>
  @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Catatan</label>
  <input type="text" name="notes" class="form-control"
    value="{{ old('notes', $room->notes ?? '') }}" maxlength="255">
</div>
