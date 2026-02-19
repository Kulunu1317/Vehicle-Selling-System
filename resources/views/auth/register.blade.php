@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="text-center mb-4"><i class="fa-solid fa-user-plus"></i> Register</h3>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
                <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                <input type="text" name="phone" class="form-control mb-3" placeholder="Phone Number" required>
                <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                <input type="password" name="password_confirmation" class="form-control mb-3" placeholder="Confirm Password" required>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_vehicle_owner" id="ownerCheck">
                    <label class="form-check-label" for="ownerCheck">Are you a Vehicle Owner?</label>
                </div>
                <button class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</div>
@endsection