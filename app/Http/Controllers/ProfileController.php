<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Profile;
use App\Models\SavingGoal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            // 'avatar' => ['required', 'string'],
        ]);

        Profile::create([
            'name' => $request->name,
            'avatar' => 'avatar-boy.png',
            'account_id' => Auth::id()
        ]);

        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        session(['active_profile' => $profile->id]);
        $categories = Category::where('profile_id', $profile->id)->orWhere('profile_id', null)->get();
        $transactions = Transaction::where('profile_id', $profile->id)->get();
        $saving_goals = SavingGoal::where('profile_id', $profile->id)->orderBy('created_at', 'desc')->get();
        $balance = Profile::where('id', $profile->id)->value('balance');
        $expenses = Transaction::where('profile_id', $profile->id)->where('type', 'expense')->sum('amount');
        return view('profiles.index', compact('profile', 'categories', 'transactions', 'saving_goals', 'balance', 'expenses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        return view('profiles.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }
   
    public function updateSavings(Request $request, Profile $profile)
    {
        $this->validate($request,[
            'savings' => 'required|numeric|gte:' . (-$profile->savings) .'|lte:' . $profile->balance,
        ]);

        $profile->savings += $request->savings;
        $profile->balance -= $request->savings;
        $profile->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();
        return redirect()->route('home');
    }
}
