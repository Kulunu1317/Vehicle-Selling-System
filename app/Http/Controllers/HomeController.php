<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Advertisement::with(['hrPackage.package', 'hrUser'])
            ->where('status', 'approved')
            ->join('hr_packages', 'advertisements.hr_package_id', '=', 'hr_packages.id')
            ->join('packages', 'hr_packages.package_id', '=', 'packages.id')
            ->select('advertisements.*', 'packages.tier as package_tier');

        // Search Logic: For Vehicle Owners (Category OR Brand)
        if (Auth::user()->role === 'owner' && $request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('advertisements.vehicle_data->Vehicle_Category', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('advertisements.vehicle_data->Vehicle_Brand', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Order by Tier: Diamond > Gold > Silver > Normal
        $ads = $query->orderByRaw("FIELD(packages.tier, 'diamond', 'gold', 'silver', 'normal')")
                     ->latest('advertisements.created_at')
                     ->get();

        return view('home', compact('ads'));
    }

    public function submitDetails(Request $request, Advertisement $advertisement)
    {
        // Only owners can submit details
        if (Auth::user()->role !== 'owner') {
            return back()->with('error', 'Only Vehicle Owners can submit details.');
        }

        $request->validate(['message' => 'required|string']);

        $advertisement->ownerSubmissions()->create([
            'owner_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your details have been sent to the Sales Company HR successfully!');
    }
}