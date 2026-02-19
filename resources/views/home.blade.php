@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fa-solid fa-house"></i> Home Screen - Approved Advertisements</h2>

    {{-- Search Bar: Only visible to Vehicle Owners --}}
    @if(auth()->user()->role === 'owner')
        <form action="{{ route('home') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Vehicle Category Type (e.g., Road, Sea, Sky)..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i> Search</button>
            </div>
        </form>
    @endif

    <div class="row">
        @forelse($ads as $ad)
            <div class="col-md-4 mb-4">
                {{-- Dynamic Border Color based on Tier --}}
                <div class="card h-100 tier-{{ $ad->package_tier }}">
                    
                    {{-- Display Vehicle Image --}}
                    @if(isset($ad->vehicle_data['Vehicle_Image']))
                        <img src="{{ asset($ad->vehicle_data['Vehicle_Image']) }}" class="card-img-top" alt="Vehicle Image" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white text-center py-5"><i class="fa-solid fa-car fa-3x"></i></div>
                    @endif

                    <div class="card-body">
                        {{-- Tier Badge --}}
                        <span class="badge bg-dark mb-2 text-uppercase">{{ $ad->package_tier }} Package</span>
                        
                        <h5 class="card-title">{{ $ad->vehicle_data['Vehicle_Brand'] ?? 'Unknown Brand' }}</h5>
                        <p class="text-muted mb-1"><i class="fa-solid fa-tags"></i> Category: {{ $ad->vehicle_data['Vehicle_Category'] ?? 'N/A' }}</p>
                        <p class="text-muted mb-1"><i class="fa-solid fa-money-bill"></i> Price: ${{ $ad->vehicle_data['Vehicle_Price'] ?? 'N/A' }}</p>
                        <p class="text-muted mb-3"><i class="fa-solid fa-location-dot"></i> {{ $ad->vehicle_data['Location'] ?? 'N/A' }}</p>

                        {{-- Dynamic Extra Questions Display --}}
                        @foreach($ad->vehicle_data as $key => $value)
                            @if(!in_array($key, ['Vehicle_Brand', 'Vehicle_Category', 'Vehicle_Price', 'Location', 'Vehicle_Image', '_token']))
                                <small class="d-block text-secondary"><strong>{{ str_replace('_', ' ', $key) }}:</strong> {{ $value }}</small>
                            @endif
                        @endforeach
                    </div>

                    <div class="card-footer bg-white border-top-0">
                        {{-- Action Buttons --}}
                        @if(auth()->user()->role === 'owner')
                            <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#submitDetailsModal{{ $ad->id }}">
                                <i class="fa-solid fa-pen-to-square"></i> Enter Details
                            </button>

                            {{-- Enter Details Modal --}}
                            <div class="modal fade" id="submitDetailsModal{{ $ad->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ url('/ads/'.$ad->id.'/submit') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Enter Your Vehicle Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <textarea name="message" class="form-control" rows="4" placeholder="Enter your details and contact info here..." required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Send to HR</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No advertisements found.</h4>
            </div>
        @endforelse
    </div>
</div>
@endsection