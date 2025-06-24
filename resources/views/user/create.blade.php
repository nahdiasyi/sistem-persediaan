@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <!-- Breadcrumb -->
            <div class="card mb-4 shadow-sm" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border: none;">
                <div class="card-body" style="background: rgba(255,255,255,0.1);">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2" style="background: none;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-home me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.index') }}" class="text-white text-decoration-none">User</a>
                            </li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Tambah User</li>
                        </ol>
                        <h4 class="text-white mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Tambah User Baru
                        </h4>
                    </nav>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-lg" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border: none;">
                <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-user-edit me-2"></i>Form Tambah User
                    </h5>
                </div>
                <div class="card-body" style="background: rgba(255,255,255,0.95);">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('user.store') }}" method="POST" id="userForm">
                        @csrf

                        <!-- Informasi Personal -->
                        <div class="card mb-4" style="background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); border: none;">
                            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white fw-bold">
                                    <i class="fas fa-user me-2"></i>Informasi Personal
                                </h6>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="id_user" class="form-label fw-bold">
                                                <i class="fas fa-id-card me-1"></i>ID User
                                            </label>
                                            <input type="text" name="id_user" id="id_user" class="form-control form-control-custom" value="{{ $newId }}" readonly>
                                            <div class="form-text">ID User otomatis dibuat sistem</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="nama_user" class="form-label fw-bold">
                                                <i class="fas fa-user me-1"></i>Nama User <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-custom" name="nama_user" id="nama_user" value="{{ old('nama_user') }}" placeholder="Masukkan nama lengkap user" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="alamat" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-1"></i>Alamat <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control form-control-custom" name="alamat" id="alamat" rows="3" placeholder="Masukkan alamat lengkap user" required>{{ old('alamat') }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="no_telp" class="form-label fw-bold">
                                        <i class="fas fa-phone me-1"></i>No Telp <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-custom" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" placeholder="Contoh: 08123456789" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Akun -->
                        <div class="card mb-4" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border: none;">
                            <div class="card-header" style="background: rgba(255,255,255,0.1); border-bottom: 1px solid rgba(255,255,255,0.2);">
                                <h6 class="mb-0 text-white fw-bold">
                                    <i class="fas fa-key me-2"></i>Informasi Akun
                                </h6>
                            </div>
                            <div class="card-body" style="background: rgba(255,255,255,0.9);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="username" class="form-label fw-bold">
                                                <i class="fas fa-at me-1"></i>Username <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control form-control-custom" name="username" id="username" value="{{ old('username') }}" placeholder="Masukkan username" required>
                                            <div class="form-text">Username harus unik dan tidak boleh sama dengan user lain</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label fw-bold">
                                                <i class="fas fa-lock me-1"></i>Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-custom" name="password" id="password" placeholder="Masukkan password" required>
                                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">Password minimal 6 karakter</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Role -->
                                <div class="form-group mb-3">
                                    <label for="role" class="form-label fw-bold">
                                        <i class="fas fa-user-tag me-1"></i>Role <span class="text-danger">*</span>
                                    </label>
                                    <select name="role" id="role" class="form-control form-control-custom" required>
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                                        <option value="back office" {{ old('role') == 'back office' ? 'selected' : '' }}>Back Office</option>
                                        <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card" style="background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border: none;">
                            <div class="card-body text-center" style="background: rgba(255,255,255,0.1);">
                                <button type="submit" class="btn btn-success btn-lg me-3 btn-custom">
                                    <i class="fas fa-save me-2"></i>Simpan User
                                </button>
                                <a href="{{ route('user.index') }}" class="btn btn-secondary btn-lg btn-custom">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .form-control-custom, .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control-custom:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        transform: translateY(-1px);
    }

    .btn-custom {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }

    .card {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
        animation: slideInUp 0.5s ease-out;
    }

    .form-label {
        color: #495057;
        margin-bottom: 8px;
    }

    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: rgba(255,255,255,0.7);
    }

    .input-group .btn {
        border: 2px solid #e9ecef;
        border-left: none;
    }

    .input-group .form-control:focus + .btn {
        border-color: #667eea;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'password') {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    });

    // Form validation (password minimal 6 karakter)
    const form = document.getElementById('userForm');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            passwordInput.focus();
            return false;
        }
    });

    // Format input no_telp agar hanya angka
    const phoneInput = document.getElementById('no_telp');
    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '');
    });
});
</script>

@endsection
