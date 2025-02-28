<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = Auth::user()->profiles;
        // dd($profiles);
        return view('accounts.profiles', compact('profiles'));
    }

    public function login(Request $request)
    {
        if (request()->isMethod('GET')) {
            return view('login');
        }

        if (request()->isMethod('POST')) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                // Authentification réussie
                return redirect()->intended('/'); // Redirigez vers la page souhaitée
            }

            return back()->withErrors([
                'email' => 'Les informations d\'identification ne correspondent pas.',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('signup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:accounts'],
            'password' => ['required', 'confirmed'],
            'type' => ['required', 'string', 'in:personal,family'],
        ]);

        $account = Account::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type
        ]);

        Profile::create([
            'name' => "profile 1",
            'avatar' => 'avatar.webp',
            'account_id' => $account->id
        ]);

        return redirect()->route('login');
    }

    /**
     * Display the specified resource.  
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
