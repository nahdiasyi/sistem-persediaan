@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Header Profile -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Profile Pengguna
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="profile-avatar">
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <h5 class="card-title text-primary">{{ $user->nama_user }}</h5>
                            <p class="card-text">
                                <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                            </p>
                            <p class="card-text text-muted">
                                <small>ID User: {{ $user->id_user }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Form Edit Profile -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="profileForm">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="nama_user" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control" id="nama_user" name="nama_user"
                                           value="{{ $user->nama_user }}" required maxlength="100">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-at me-1"></i>Username
                                    </label>
                                    <input type="text" class="form-control" id="username" name="username"
                                           value="{{ $user->username }}" required maxlength="20">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>Alamat
                                    </label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                              maxlength="40">{{ $user->alamat }}</textarea>
                                    <div class="form-text">Maksimal 40 karakter</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="no_telp" class="form-label">
                                        <i class="fas fa-phone me-1"></i>No. Telepon
                                    </label>
                                    <input type="text" class="form-control" id="no_telp" name="no_telp"
                                           value="{{ $user->no_telp }}" maxlength="13">
                                    <div class="form-text">Maksimal 13 digit</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-user-tag me-1"></i>Role
                                    </label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                    <div class="form-text">Role tidak dapat diubah</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Form Ganti Password -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-key me-2"></i>Ganti Password
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="passwordForm">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="current_password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Password Lama
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="current_password"
                                               name="current_password" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password"
                                               name="new_password" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye" id="new_password_icon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Minimal 6 karakter</div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-1"></i>Konfirmasi Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new_password_confirmation"
                                               name="new_password_confirmation" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePassword('new_password_confirmation')">
                                            <i class="fas fa-eye" id="new_password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="mb-3">
                                    <div class="password-strength">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="form-text text-muted" id="password-strength-text">
                                            Kekuatan password akan ditampilkan di sini
                                        </small>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-key me-2"></i>Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 mb-0">Memproses...</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
.profile-avatar {
    margin-bottom: 1rem;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

.password-strength {
    margin-top: 0.5rem;
}

.progress-bar {
    transition: width 0.3s ease;
}

.badge {
    font-size: 0.9em;
}

.btn {
    border-radius: 6px;
}

.invalid-feedback {
    display: block;
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid {
    border-color: #28a745;
}

@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Form Profile
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    // Form Password
    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        updatePassword();
    });

    // Password strength checker
    $('#new_password').on('input', function() {
        checkPasswordStrength($(this).val());
    });

    // Confirm password validation
    $('#new_password_confirmation').on('input', function() {
        validatePasswordConfirmation();
    });

    // Phone number validation
    $('#no_telp').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function updateProfile() {
    const form = $('#profileForm');
    const formData = new FormData(form[0]);

    // Clear previous validation errors
    $('.form-control').removeClass('is-invalid is-valid');
    $('.invalid-feedback').empty();

    $('#loadingModal').modal('show');

    $.ajax({
        url: '{{ route("profile.update") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-HTTP-Method-Override': 'PUT'
        },
        success: function(response) {
            $('#loadingModal').modal('hide');

            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Mark fields as valid
                $('#profileForm .form-control').addClass('is-valid');

                // Update header if nama_user changed
                const newName = $('#nama_user').val();
                $('.card-title.text-primary').text(newName);
            }
        },
        error: function(xhr) {
            $('#loadingModal').modal('hide');

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    const field = $('#' + key);
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text(value[0]);
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            }
        }
    });
}

function updatePassword() {
    const form = $('#passwordForm');

    // Validate password confirmation
    if (!validatePasswordConfirmation()) {
        return;
    }

    // Clear previous validation errors
    $('#passwordForm .form-control').removeClass('is-invalid is-valid');
    $('#passwordForm .invalid-feedback').empty();

    $('#loadingModal').modal('show');

    $.ajax({
        url: '{{ route("profile.update.password") }}',
        method: 'POST',
        data: form.serialize() + '&_method=PUT',
        success: function(response) {
            $('#loadingModal').modal('hide');

            if (response.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reset form and clear password fields
                form[0].reset();
                $('.progress-bar').css('width', '0%');
                $('#password-strength-text').text('Kekuatan password akan ditampilkan di sini');
            }
        },
        error: function(xhr) {
            $('#loadingModal').modal('hide');

            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                $.each(errors, function(key, value) {
                    const field = $('#' + key);
                    field.addClass('is-invalid');
                    field.siblings('.invalid-feedback').text(value[0]);
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                    icon: 'error'
                });
            }
        }
    });
}

function checkPasswordStrength(password) {
    let strength = 0;
    let text = '';
    let color = '';

    if (password.length >= 6) strength += 20;
    if (password.length >= 8) strength += 20;
    if (/[a-z]/.test(password)) strength += 20;
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[0-9]/.test(password)) strength += 10;
    if (/[^A-Za-z0-9]/.test(password)) strength += 10;

    if (strength < 40) {
        text = 'Lemah';
        color = 'danger';
    } else if (strength < 70) {
        text = 'Sedang';
        color = 'warning';
    } else {
        text = 'Kuat';
        color = 'success';
    }

    $('.progress-bar')
        .css('width', strength + '%')
        .removeClass('bg-danger bg-warning bg-success')
        .addClass('bg-' + color);

    $('#password-strength-text').text('Kekuatan password: ' + text);
}

function validatePasswordConfirmation() {
    const newPassword = $('#new_password').val();
    const confirmPassword = $('#new_password_confirmation').val();
    const confirmField = $('#new_password_confirmation');

    if (confirmPassword && newPassword !== confirmPassword) {
        confirmField.addClass('is-invalid');
        confirmField.siblings('.invalid-feedback').text('Konfirmasi password tidak cocok');
        return false;
    } else if (confirmPassword) {
        confirmField.removeClass('is-invalid').addClass('is-valid');
        confirmField.siblings('.invalid-feedback').empty();
    }

    return true;
}
</script>
@endpush
