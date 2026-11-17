<div class="mb-3">
  <label class="form-label">Nama <span class="text-danger">*</span></label>
  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
    value="{{ old('name', $user->name ?? '') }}" maxlength="100" required>
  @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">Email <span class="text-danger">*</span></label>
  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
    value="{{ old('email', $user->email ?? '') }}" required>
  @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
  <label class="form-label">
    Kata Sandi
    @isset($user)<small class="text-muted">(kosongkan jika tidak diubah)</small>@endisset
    @empty($user)<span class="text-danger">*</span>@endempty
  </label>
  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
    {{ isset($user) ? '' : 'required' }} minlength="8" autocomplete="new-password">
  @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
@if(!isset($user))
<div class="mb-3">
  <label class="form-label">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
  <input type="password" name="password_confirmation" class="form-control" required minlength="8">
</div>
@endif
<div class="mb-3">
  <label class="form-label">Role <span class="text-danger">*</span></label>
  <select name="role" class="form-select @error('role') is-invalid @enderror" required>
    <option value="staff" {{ old('role', $user->role ?? 'staff') === 'staff' ? 'selected' : '' }}>Staff</option>
    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
  </select>
  @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
