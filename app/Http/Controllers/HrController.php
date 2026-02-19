<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Package, HrPackage, Advertisement};
use Illuminate\Support\Facades\Auth; // <-- Added this import
use Carbon\Carbon;

class HrController extends Controller
{
    public function dashboard()
    {
        $myAds = Advertisement::where('user_id', Auth::id())->get();
        $packages = Package::all();
        // Add this line to fetch the packages the HR user has bought!
        $myPurchasedPackages = HrPackage::with('package')->where('user_id', Auth::id())->where('ads_remaining', '>', 0)->get();
        
        return view('hr.dashboard', compact('myAds', 'packages', 'myPurchasedPackages'));
    }

    public function buyPackage(Package $package)
    {
        HrPackage::create([
            'user_id' => Auth::id(), // <-- Fixed here
            'package_id' => $package->id,
            'ads_remaining' => $package->max_ads,
            'expires_at' => Carbon::now()->add($package->expiry_time, $package->expiry_unit)
        ]);
        
        return back()->with('success', 'Package purchased successfully!');
    }

    public function placeAd(Request $request, HrPackage $hrPackage)
    {
        if ($hrPackage->ads_remaining <= 0) {
            return back()->with('error', 'Package advertisement limit reached.');
        }
        
        $data = $request->except('_token');
        
        // Handle image upload from dynamic form
        if ($request->hasFile('Vehicle_Image')) {
            $imgName = time().'.'.$request->Vehicle_Image->extension();
            $request->Vehicle_Image->move(public_path('assets'), $imgName);
            $data['Vehicle_Image'] = 'assets/'.$imgName;
        }

        Advertisement::create([
            'user_id' => Auth::id(), // <-- Fixed here
            'hr_package_id' => $hrPackage->id,
            'vehicle_data' => $data,
            'status' => 'pending'
        ]);

        $hrPackage->decrement('ads_remaining');
        
        return back()->with('success', 'Advertisement submitted for admin approval.');
    }

    // Add these to HrController.php
    
    public function notifications()
    {
        // Get all ads placed by this specific HR user
        $myAdIds = Advertisement::where('user_id', Auth::id())->pluck('id');
        
        // Get all owner submissions linked to those ads for the Notification Panel
        $notifications = \App\Models\OwnerSubmission::with(['owner', 'advertisement'])
                            ->whereIn('advertisement_id', $myAdIds)
                            ->latest()
                            ->get();
                            
        return view('hr.notifications', compact('notifications'));
    }

    public function viewSubmissions(Advertisement $advertisement)
    {
        // Security Check: Make sure ONLY the HR who placed the ad can see these details!
        if ($advertisement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized. You can only view submissions for your own advertisements.');
        }

        $submissions = $advertisement->ownerSubmissions()->with('owner')->latest()->get();
        
        return view('hr.submissions', compact('advertisement', 'submissions'));
    }
}