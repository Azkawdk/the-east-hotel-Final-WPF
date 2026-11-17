<div class="row g-3">
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">Informasi Tipe Kamar</div>
      <div class="card-body">
        <div class="mb-3">
          <label class="form-label">Nama Tipe Kamar <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $roomType->name ?? '') }}" required maxlength="100">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
          <textarea name="description" class="form-control @error('description') is-invalid @enderror"
            rows="4" required maxlength="2000">{{ old('description', $roomType->description ?? '') }}</textarea>
          @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="row g-2">
          <div class="col-sm-6">
            <label class="form-label">Kapasitas (tamu) <span class="text-danger">*</span></label>
            <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
              value="{{ old('capacity', $roomType->capacity ?? 2) }}" min="1" max="10" required>
            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Harga/malam (Rp) <span class="text-danger">*</span></label>
            <input type="number" name="base_price" class="form-control @error('base_price') is-invalid @enderror"
              value="{{ old('base_price', $roomType->base_price ?? '') }}" min="0" step="1000" required>
            @error('base_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-sm-6">
            <label class="form-label">Luas (m²)</label>
            <input type="number" name="size_m2" class="form-control"
              value="{{ old('size_m2', $roomType->size_m2 ?? '') }}" min="1">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Tipe Tempat Tidur</label>
            <input type="text" name="bed_type" class="form-control"
              value="{{ old('bed_type', $roomType->bed_type ?? '') }}" maxlength="60">
          </div>
          <div class="col-12">
            <label class="form-label">URL Gambar</label>
            <input type="url" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
              value="{{ old('image_url', $roomType->image_url ?? '') }}" placeholder="https://…">
            @error('image_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $roomType->is_active ?? true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Aktif (tersedia untuk pemesanan)</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card">
      <div class="card-header">Fasilitas Kamar</div>
      <div class="card-body">
        @foreach($amenities as $a)
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="amenity_ids[]"
            id="am_{{ $a->id }}" value="{{ $a->id }}"
            {{ in_array($a->id, old('amenity_ids', $selectedAmenities ?? [])) ? 'checked' : '' }}>
          <label class="form-check-label" for="am_{{ $a->id }}">{{ $a->name }}</label>
        </div>
        @endforeach
      </div>
    </div>
    <div class="d-grid mt-3">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-check-lg me-1"></i>Simpan Tipe Kamar
      </button>
    </div>
  </div>
</div>
