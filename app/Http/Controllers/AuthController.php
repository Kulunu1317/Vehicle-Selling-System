<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->has('is_vehicle_owner') ? 'owner' : 'hr',
            'status' => 'pending'
        ]);
        return redirect()->route('login')->with('success', 'Registration submitted. Awaiting Admin approval.');
    }

    public function login(Request $request) {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->status !== 'approved') {
                Auth::logout();
                return back()->with('error', 'Account pending or rejected.');
            }
            if ($user->role == 'admin') return redirect()->route('admin.dashboard');
            if ($user->role == 'hr') return redirect()->route('hr.dashboard');
            return redirect()->route('home');
        }
        return back()->with('error', 'Invalid credentials.');
    }
    public function logout() { Auth::logout(); return redirect()->route('login'); }
}