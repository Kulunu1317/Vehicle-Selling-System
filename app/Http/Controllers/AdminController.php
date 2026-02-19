<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\Advertisement;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->get();
        $pendingAds = Advertisement::with('hrUser')->where('status', 'pending')->get();
        $activePackages = Package::all();
        
        return view('admin.dashboard', compact('pendingUsers', 'pendingAds', 'activePackages'));
    }

    public function createPackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'max_ads' => 'required|integer',
            'expiry_time' => 'required|integer',
            'expiry_unit' => 'required|in:minutes,hours,days',
            'price' => 'required|numeric',
            'tier' => 'required|in:normal,silver,gold,diamond',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        // Handle Image Upload to public/assets
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('assets'), $imageName);
            $data['image'] = 'assets/'.$imageName;
        }

        // Process Extra Questions (Text type)
        // Expecting an array from the frontend: ['question1', 'question2']
        if ($request->has('extra_questions')) {
            $data['extra_questions'] = array_filter($request->extra_questions);
        } else {
            $data['extra_questions'] = [];
        }

        Package::create($data);
        return back()->with('success', 'Sales Category Package created successfully!');
    }

    public function handleUser(User $user, $action)
    {
        $user->update(['status' => $action]);
        return back()->with('success', "User has been {$action}.");
    }

    public function handleAd(Advertisement $ad, $action)
    {
        $ad->update(['status' => $action]);
        return back()->with('success', "Advertisement has been {$action}.");
    }
    // Add this to AdminController.php
    public function deletePackage(Package $package)
    {
        // Optional: If you want to delete the image from the public folder too
        if ($package->image && file_exists(public_path($package->image))) {
            unlink(public_path($package->image));
        }

        $package->delete();
        return back()->with('success', 'Sales Category Package has been permanently deleted!');
    }
}