@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-car"></i> Vehicle Details Received</h2>
        <a href="{{ route('hr.dashboard') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <div class="card mb-4 border-primary shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Responses for your Ad: {{ $advertisement->vehicle_data['Vehicle_Brand'] ?? 'Unknown Brand' }}</h5>
        </div>
    </div>

    <div class="row">
        @forelse($submissions as $submission)
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 bg-white">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-dark"><i class="fa-solid fa-user-tie"></i> {{ $submission->owner->name }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item px-0"><i class="fa-solid fa-envelope text-muted"></i> <strong>Email:</strong> {{ $submission->owner->email }}</li>
                            <li class="list-group-item px-0"><i class="fa-solid fa-phone text-muted"></i> <strong>Phone:</strong> {{ $submission->owner->phone ?? 'Not provided' }}</li>
                            <li class="list-group-item px-0"><i class="fa-regular fa-calendar text-muted"></i> <strong>Submitted:</strong> {{ $submission->created_at->format('M d, Y - h:i A') }}</li>
                        </ul>
                        
                        <h6 class="text-primary mt-2">Vehicle Details / Message Submitted:</h6>
                        <div class="p-3 bg-light border rounded">
                            {{-- nl2br preserves line breaks if the owner typed a long message --}}
                            {!! nl2br(e($submission->message)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h4 class="text-muted"><i class="fa-solid fa-inbox fa-2x mb-3"></i><br>No Vehicle Owners have submitted details for this ad yet.</h4>
            </div>
        @endforelse
    </div>
</div>
@endsection