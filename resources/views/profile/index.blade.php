@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                    <i class="fa-solid fa-circle-user fa-5x mb-2"></i>
                    <h3 class="mb-0">{{ $user->name }}</h3>
                    <span class="badge bg-light text-primary text-uppercase mt-2">{{ $user->role }}</span>
                </div>
                
                <div class="card-body p-4 position-relative">
                    {{-- Edit Icon triggers Modal --}}
                    <button class="btn btn-warning btn-sm position-absolute top-0 end-0 m-3 rounded-circle shadow" data-bs-toggle="modal" data-bs-target="#editProfileModal" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-pen"></i>
                    </button>

                    <h5 class="text-muted mb-4"><i class="fa-solid fa-address-card"></i> Personal Details</h5>
                    
                    <ul class="list-group list-group-flush fs-5">
                        <li class="list-group-item"><i class="fa-solid fa-envelope text-primary me-2"></i> <strong>Email:</strong> <span class="float-end text-muted">{{ $user->email }}</span></li>
                        <li class="list-group-item"><i class="fa-solid fa-phone text-primary me-2"></i> <strong>Phone:</strong> <span class="float-end text-muted">{{ $user->phone ?? 'Not Provided' }}</span></li>
                        <li class="list-group-item"><i class="fa-regular fa-calendar-check text-primary me-2"></i> <strong>Joined:</strong> <span class="float-end text-muted">{{ $user->created_at->format('M d, Y') }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 shadow-lg">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square"></i> Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection