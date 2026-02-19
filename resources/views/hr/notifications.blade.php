@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-bell text-warning"></i> Notification Panel</h2>
        <a href="{{ route('hr.dashboard') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            @forelse($notifications as $alert)
                <div class="alert alert-info shadow-sm d-flex justify-content-between align-items-center">
                    <div>
                        <strong><i class="fa-solid fa-user-circle"></i> {{ $alert->owner->name }}</strong> (Vehicle Owner) 
                        just submitted their vehicle details for your advertisement: 
                        <span class="text-primary fw-bold">{{ $alert->advertisement->vehicle_data['Vehicle_Brand'] ?? 'Ad #'.$alert->advertisement_id }}</span>.
                        <br><small class="text-muted"><i class="fa-regular fa-clock"></i> {{ $alert->created_at->diffForHumans() }}</small>
                    </div>
                    <a href="{{ route('hr.ad.submissions', $alert->advertisement_id) }}" class="btn btn-primary btn-sm shadow-sm">
                        View Details
                    </a>
                </div>
            @empty
                <div class="card p-5 text-center shadow-sm">
                    <h4 class="text-muted"><i class="fa-regular fa-bell-slash fa-2x mb-3"></i><br>No notifications yet.</h4>
                    <p>When Vehicle Owners fill out details on your ads, alerts will appear beautifully right here!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection