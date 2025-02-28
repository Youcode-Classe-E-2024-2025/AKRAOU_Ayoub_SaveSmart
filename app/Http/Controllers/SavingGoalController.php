<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use Illuminate\Http\Request;

class SavingGoalController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:50',
            'amount' => 'required|numeric|lte:target_amount|gt:0',
            'target_date' => 'required|date|after:today',   
        ]);

        SavingGoal::create([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'profile_id' => session('active_profile'),
        ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(SavingGoal $savingGoal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SavingGoal $savingGoal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SavingGoal $goal)
    {
        $rest = $goal->target_amount - $goal->saved_amount;
        // dd($goal->target_amount, $request->saved_amount);
        $this->validate($request,[
            'saved_amount' => "required|numeric|gt:0",
        ]);

        if($request->saved_amount > $rest) {
             $goal->saved_amount += $rest;
             $goal->save();
             return redirect()->back();
        }

        $goal->saved_amount += $request->saved_amount;
        $goal->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingGoal $savingGoal)
    {
        //
    }
}
