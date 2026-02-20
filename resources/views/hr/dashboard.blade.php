@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fa-solid fa-building-user"></i> Sales Company HR Dashboard</h2>

    <div class="row">
        {{-- Left Column: Available Packages to Buy --}}
        <div class="col-md-6">
            <h4 class="mb-3"><i class="fa-solid fa-cart-plus"></i> Buy Packages</h4>
            @forelse($packages as $pkg)
            <div class="card mb-4 shadow-sm tier-{{ $pkg->tier }}">
                <div class="row g-0">
                    <div class="col-md-4">
                        @if($pkg->image)
                            <img src="{{ asset($pkg->image) }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="Package">
                        @else
                            <div class="bg-secondary text-white h-100 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-box fa-2x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="card-body pb-2">
                            <h5 class="card-title">{{ $pkg->name }} <span class="badge bg-secondary text-uppercase">{{ $pkg->tier }}</span></h5>
                            <p class="card-text mb-1"><small class="text-muted">Allows {{ $pkg->max_ads }} Ads | Expires in {{ $pkg->expiry_time }} {{ $pkg->expiry_unit }}</small></p>
                            <h6 class="text-success fw-bold">${{ $pkg->price }}</h6>
                            
                            <form action="{{ route('hr.buy', $pkg->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button class="btn btn-primary btn-sm w-100 fw-bold mb-2"><i class="fa-solid fa-cart-shopping"></i> Buy Package</button>
                            </form>

                            {{-- View Form Structure Button --}}
                            <button class="btn btn-outline-info btn-sm w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#viewForm{{ $pkg->id }}">
                                <i class="fa-solid fa-eye"></i> View Form Structure
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- View Form Structure Modal --}}
            <div class="modal fade" id="viewForm{{ $pkg->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content border-info">
                        <div class="modal-header bg-info text-dark">
                            <h5 class="modal-title fw-bold"><i class="fa-solid fa-list-check"></i> Form Structure Preview</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body bg-light">
                            <p class="text-muted small mb-3">This is the exact form structure you will be required to fill out if you buy this package to place an advertisement.</p>
                            
                            <div class="card p-3 shadow-sm border-0">
                                <label class="fw-bold small mb-1">Vehicle Brand</label> <input class="form-control mb-2" disabled placeholder="Standard Field">
                                <label class="fw-bold small mb-1">Category (Road, Sea, Sky)</label> <input class="form-control mb-2" disabled placeholder="Standard Field">
                                <label class="fw-bold small mb-1">Price</label> <input class="form-control mb-2" disabled placeholder="Standard Field">
                                <label class="fw-bold small mb-1">Location</label> <input class="form-control mb-2" disabled placeholder="Standard Field">
                                <label class="fw-bold small mb-1">Vehicle Image</label> <input type="file" class="form-control mb-3" disabled>
                                
                                @if(!empty($pkg->extra_questions))
                                    <hr>
                                    <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-star"></i> Custom Required Fields</h6>
                                    @foreach($pkg->extra_questions as $q)
                                        <label class="fw-bold small mb-1">{{ $q }}</label> <input class="form-control mb-2 border-primary" disabled placeholder="Custom Admin Field">
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-secondary shadow-sm"><i class="fa-solid fa-folder-open"></i> No packages are currently available from the Admin.</div>
            @endforelse
        </div>

        {{-- Right Column: Purchased Packages & Placing Ads --}}
        <div class="col-md-6">
            <h4 class="mb-3"><i class="fa-solid fa-boxes-stacked"></i> My Active Packages</h4>
            @forelse($myPurchasedPackages as $myPkg)
            <div class="card mb-3 border-primary shadow-sm">
                <div class="card-body">
                    <h5>{{ $myPkg->package->name }} <span class="badge bg-dark float-end">{{ $myPkg->package->tier }}</span></h5>
                    <p class="mb-2"><strong>Ads Remaining:</strong> <span class="badge bg-primary">{{ $myPkg->ads_remaining }}</span> / {{ $myPkg->package->max_ads }}</p>
                    <p class="small text-muted mb-3"><i class="fa-regular fa-clock"></i> Expires: {{ $myPkg->expires_at->format('Y-m-d H:i') }}</p>
                    
                    {{-- Place Ad Button triggers Modal --}}
                    <button class="btn btn-success w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#placeAdModal{{ $myPkg->id }}">
                        <i class="fa-solid fa-bullhorn"></i> Place Advertisement
                    </button>

                    {{-- Dynamic Ad Placement Modal --}}
                    <div class="modal fade" id="placeAdModal{{ $myPkg->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('hr.place_ad', $myPkg->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">Place Ad using {{ $myPkg->package->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            {{-- Standard Required Fields --}}
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-bold">Vehicle Brand</label>
                                                <input type="text" name="Vehicle_Brand" class="form-control" placeholder="e.g. Toyota, Yamaha" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-bold">Category (Road, Sea, Sky)</label>
                                                <select name="Vehicle_Category" class="form-select" required>
                                                    <option value="Road">Road</option><option value="Sea">Sea</option><option value="Sky">Sky</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-bold">Price ($)</label>
                                                <input type="number" name="Vehicle_Price" class="form-control" placeholder="e.g. 15000" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-bold">Location</label>
                                                <input type="text" name="Location" class="form-control" placeholder="e.g. Colombo, NY" required>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-bold">Vehicle Image</label>
                                                <input type="file" name="Vehicle_Image" class="form-control" accept="image/*" required>
                                            </div>
                                            
                                            {{-- DYNAMIC FIELDS FROM ADMIN PACKAGE --}}
                                            @if(!empty($myPkg->package->extra_questions))
                                                <div class="col-12"><hr></div>
                                                <h6 class="text-primary fw-bold mb-3"><i class="fa-solid fa-clipboard-question"></i> Additional Required Details</h6>
                                                @foreach($myPkg->package->extra_questions as $question)
                                                    <div class="col-md-6 mb-3">
                                                        <label class="fw-bold">{{ $question }}</label>
                                                        <input type="text" name="{{ str_replace(' ', '_', $question) }}" class="form-control" required>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success fw-bold"><i class="fa-solid fa-paper-plane"></i> Submit for Approval</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="alert alert-info shadow-sm"><i class="fa-solid fa-circle-info"></i> You have no active packages. Buy one from the left to start placing ads!</div>
            @endforelse
        </div>
    </div>

    {{-- Bottom Row: My Placed Advertisements & Vehicle Owner Submissions --}}
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fa-solid fa-rectangle-ad"></i> Advertisements Placed By Me</h4>
                    <a href="{{ route('hr.notifications') }}" class="btn btn-warning btn-sm fw-bold shadow-sm">
                        <i class="fa-solid fa-bell"></i> Notification Panel
                    </a>
                </div>
                <div class="card-body bg-light">
                    <div class="row">
                        @forelse($myAds as $ad)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    @if(isset($ad->vehicle_data['Vehicle_Image']))
                                        <img src="{{ asset($ad->vehicle_data['Vehicle_Image']) }}" class="card-img-top" alt="Vehicle Image" style="height: 180px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary text-white text-center py-5"><i class="fa-solid fa-car fa-3x"></i></div>
                                    @endif
                                    
                                    <div class="card-body">
                                        <h5 class="fw-bold">{{ $ad->vehicle_data['Vehicle_Brand'] ?? 'Unknown Brand' }}</h5>
                                        <p class="text-muted mb-2"><i class="fa-solid fa-tags"></i> Category: {{ $ad->vehicle_data['Vehicle_Category'] ?? 'N/A' }}</p>
                                        
                                        <div class="mt-3">
                                            <strong>Status: </strong>
                                            @if($ad->status == 'approved') 
                                                <span class="badge bg-success">Approved (Live)</span>
                                            @elseif($ad->status == 'rejected') 
                                                <span class="badge bg-danger">Rejected</span>
                                            @else 
                                                <span class="badge bg-warning text-dark">Pending Admin Approval</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 pb-3">
                                        {{-- The "View Vehicle Details" button for HR only --}}
                                        <a href="{{ route('hr.ad.submissions', $ad->id) }}" class="btn btn-outline-primary w-100 fw-bold">
                                            <i class="fa-solid fa-users-viewfinder"></i> View Vehicle Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <h5 class="text-muted"><i class="fa-solid fa-folder-open mb-2"></i><br>You haven't placed any advertisements yet.</h5>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection