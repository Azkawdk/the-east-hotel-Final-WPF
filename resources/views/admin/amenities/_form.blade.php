<div class="mb-3">
  <label class="form-label">Nama <span class="text-danger">*</span></label>
  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
    value="{{ old('name', $amenity->name ?? '') }}" maxlength="100" required>
  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Deskripsi</label>
  <input type="text" name="description" class="form-control"
    value="{{ old('description', $amenity->description ?? '') }}" maxlength="255">
</div>
<div class="mb-3">
  <label class="form-label">Lingkup <span class="text-danger">*</span></label>
  <select name="scope" class="form-select @error('scope') is-invalid @enderror" required>
    <option value="hotel" {{ old('scope', $amenity->scope ?? '') === 'hotel' ? 'selected':'' }}>Hotel (fasilitas umum)</option>
    <option value="room" {{ old('scope', $amenity->scope ?? '') === 'room' ? 'selected':'' }}>Kamar (fasilitas per kamar)</option>
  </select>
  @error('scope')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
