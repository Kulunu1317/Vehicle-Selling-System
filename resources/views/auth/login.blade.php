@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card p-4 shadow-sm">
            <h3 class="text-center mb-4"><i class="fa-solid fa-right-to-bracket"></i> Login</h3>

            <form action="/login" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fa-solid fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="mb-0">Don't have an account? <a href="/register" class="text-decoration-none fw-bold">Register here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection