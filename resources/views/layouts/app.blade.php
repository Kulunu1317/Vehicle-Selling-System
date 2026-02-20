<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Selling System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .tier-diamond { border-top: 5px solid #b9f2ff; }
        .tier-gold { border-top: 5px solid #ffd700; }
        .tier-silver { border-top: 5px solid #c0c0c0; }
        .tier-normal { border-top: 5px solid #dee2e6; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fa-solid fa-car-side"></i> AutoMarket
            </a>
            
            @auth
                <div class="d-flex align-items-center">
                    <span class="text-light me-4">
                        <i class="fa-solid fa-user-circle"></i> {{ auth()->user()->name }} 
                        <span class="badge bg-secondary text-uppercase">{{ auth()->user()->role }}</span>
                    </span>
                    
                    {{-- NEW: Dynamic Dashboard/Home Button --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm fw-bold me-2">
                            <i class="fa-solid fa-house-chimney"></i> Dashboard
                        </a>
                    @elseif(auth()->user()->role === 'hr')
                        <a href="{{ route('hr.dashboard') }}" class="btn btn-outline-light btn-sm fw-bold me-2">
                            <i class="fa-solid fa-house-chimney"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm fw-bold me-2">
                            <i class="fa-solid fa-house-chimney"></i> Home
                        </a>
                    @endif

                    {{-- Profile Button --}}
                    <a href="{{ route('profile') }}" class="btn btn-outline-info btn-sm fw-bold me-3">
                        <i class="fa-solid fa-id-badge"></i> Profile
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-flex m-0">
                        @csrf
                        <button class="btn btn-danger btn-sm fw-bold">
                            <i class="fa-solid fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <div class="container pb-5">
        @if(session('success')) 
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
        @endif
        
        @if(session('error')) 
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>