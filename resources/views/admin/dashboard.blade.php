@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h2><i class="fa-solid fa-user-shield"></i> Admin Dashboard</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs mb-4" id="adminTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#users">Pending Users</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ads">Pending Ads</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#create-package">Create Package</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#active-packages">Active Packages</a></li>
        </ul>

        <div class="tab-content">
            {{-- Pending Users Tab --}}
            <div class="tab-pane fade show active" id="users">
                <div class="card p-3">
                    <h4>Registration Requests</h4>
                    <table class="table table-bordered mt-3">
                        <thead class="table-dark">
                            <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($pendingUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-info text-dark text-uppercase">{{ $user->role }}</span></td>
                                <td>
                                    <form action="{{ route('admin.user.action', [$user->id, 'approved']) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i> Approve</button>
                                    </form>
                                    <form action="{{ route('admin.user.action', [$user->id, 'rejected']) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-danger btn-sm"><i class="fa-solid fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">No pending users.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pending Ads Tab --}}
            <div class="tab-pane fade" id="ads">
                <div class="card p-3">
                    <h4>Advertisement Approvals</h4>
                    <table class="table table-bordered mt-3">
                        <thead class="table-dark">
                            <tr><th>HR Poster</th><th>Brand</th><th>Category</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($pendingAds as $ad)
                            <tr>
                                <td>{{ $ad->hrUser->name }}</td>
                                <td>{{ $ad->vehicle_data['Vehicle_Brand'] ?? 'N/A' }}</td>
                                <td>{{ $ad->vehicle_data['Vehicle_Category'] ?? 'N/A' }}</td>
                                <td>
                                    <form action="{{ route('admin.ad.action', [$ad->id, 'approved']) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-success btn-sm"><i class="fa-solid fa-check"></i> Approve</button>
                                    </form>
                                    <form action="{{ route('admin.ad.action', [$ad->id, 'rejected']) }}" method="POST" class="d-inline">
                                        @csrf <button class="btn btn-danger btn-sm"><i class="fa-solid fa-times"></i> Reject</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">No pending advertisements.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Create Package Tab (Dynamic Form Builder) --}}
            <div class="tab-pane fade" id="create-package">
                <div class="card p-4">
                    <h4>Create Sales Category Package</h4>
                    <form action="{{ route('admin.package.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3"><input type="text" name="name" class="form-control" placeholder="Package Name (e.g., Summer Promo)" required></div>
                            <div class="col-md-6 mb-3"><input type="number" name="max_ads" class="form-control" placeholder="Max Advertisements" required></div>
                            <div class="col-md-4 mb-3"><input type="number" name="expiry_time" class="form-control" placeholder="Expiry Time (e.g., 30)" required></div>
                            <div class="col-md-4 mb-3">
                                <select name="expiry_unit" class="form-select" required>
                                    <option value="days">Days</option><option value="hours">Hours</option><option value="minutes">Minutes</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><input type="number" step="0.01" name="price" class="form-control" placeholder="Package Price ($)" required></div>
                            <div class="col-md-6 mb-3">
                                <select name="tier" class="form-select" required>
                                    <option value="normal">Normal Tier</option><option value="silver">Silver Tier</option>
                                    <option value="gold">Gold Tier</option><option value="diamond">Diamond Tier</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3"><input type="file" name="image" class="form-control" accept="image/*" required></div>
                        </div>

                        <hr>
                        <h5><i class="fa-solid fa-clipboard-list"></i> Custom Form Builder</h5>
                        <p class="text-muted small">Add extra questions that HR must fill out when placing an ad with this package.</p>
                        <div id="dynamic-questions-container">
                            </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mb-4" onclick="addQuestion()"><i class="fa-solid fa-plus"></i> Add Question Field</button>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Submit Package</button>
                    </form>
                </div>
            </div>

            {{-- Active Packages Tab (UPDATED WITH IMAGE & DELETE) --}}
            <div class="tab-pane fade" id="active-packages">
                <div class="row">
                    @foreach($activePackages as $pkg)
                    <div class="col-md-4 mb-3">
                        <div class="card tier-{{ $pkg->tier }} h-100">
                            
                            {{-- NEW: Package Image --}}
                            @if($pkg->image)
                                <img src="{{ asset($pkg->image) }}" class="card-img-top" alt="{{ $pkg->name }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white text-center py-5"><i class="fa-solid fa-box fa-3x"></i></div>
                            @endif

                            <div class="card-body">
                                <h5>{{ $pkg->name }} <span class="badge bg-dark float-end">{{ $pkg->tier }}</span></h5>
                                <p class="mb-1">Ads: {{ $pkg->max_ads }} | Price: ${{ $pkg->price }}</p>
                                <p class="mb-1">Valid for: {{ $pkg->expiry_time }} {{ $pkg->expiry_unit }}</p>
                                <hr>
                                <h6>Required Fields:</h6>
                                <ul>
                                    <li>Vehicle Brand</li><li>Vehicle Category</li><li>Price & Location</li><li>Vehicle Image</li>
                                    {{-- Safety Check for Extra Questions --}}
                                    @if(!empty($pkg->extra_questions))
                                        @foreach($pkg->extra_questions as $q)
                                            <li>{{ $q }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            {{-- NEW: Delete Button --}}
                            <div class="card-footer bg-white border-top-0 pb-3">
                                <form action="{{ route('admin.package.delete', $pkg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="fa-solid fa-trash-can"></i> Delete Package</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addQuestion() {
        const container = document.getElementById('dynamic-questions-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
            <input type="text" name="extra_questions[]" class="form-control" placeholder="Enter custom question (e.g., Mileage, Engine Capacity)">
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()"><i class="fa-solid fa-trash"></i></button>
        `;
        container.appendChild(div);
    }
</script>
@endsection