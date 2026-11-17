<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – The East Hotel Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1a2332 0%, #2c3e50 60%, #c9a84c22 100%);
      min-height: 100vh;
      display: flex; align-items: center; justify-content: center;
    }
    .login-card {
      width: 100%; max-width: 400px;
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 20px 60px rgba(0,0,0,.35);
      overflow: hidden;
    }
    .login-header {
      background: linear-gradient(135deg, #1a2332, #2c3e50);
      padding: 2.5rem 2rem 2rem;
      text-align: center;
    }
    .login-header h4 { color: #c9a84c; font-weight: 700; margin-bottom: .25rem; }
    .login-header small { color: rgba(255,255,255,.55); font-size: .8rem; }
    .login-body { padding: 2rem; }
    .btn-login {
      background: #c9a84c; border: none; color: #1a2332;
      font-weight: 700; letter-spacing: .03em; padding: .65rem;
    }
    .btn-login:hover { background: #b8963c; color: #1a2332; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-header">
      <i class="bi bi-building fs-1 text-warning mb-2 d-block"></i>
      <h4>THE EAST HOTEL</h4>
      <small>Sistem Manajemen Hotel</small>
    </div>
    <div class="login-body">
      @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">
          <i class="bi bi-exclamation-triangle me-1"></i>{{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
              placeholder="admin@theeasthotel.test" value="{{ old('email') }}" required autofocus>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Kata Sandi</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
          </div>
        </div>
        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" name="remember" id="remember">
          <label class="form-check-label text-muted" for="remember">Ingat saya</label>
        </div>
        <button type="submit" class="btn btn-login w-100 rounded-pill">
          <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
        </button>
      </form>

      <p class="text-center text-muted mt-4 mb-0" style="font-size:.75rem">
        &copy; {{ date('Y') }} The East Hotel &bull; Admin Panel
      </p>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
