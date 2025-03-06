<?php

namespace App\Http\Controllers;

use App\Models\SavingGoal;
use App\Models\Transaction;
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
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'target_amount' => 'required|numeric|gt:0',
            'target_date' => 'required|date|after:today',
        ]);


        SavingGoal::create([
            'name' => $request->name,
            'target_amount' => $request->target_amount,
            'target_date' => $request->target_date,
            'profile_id' => session('active_profile'),
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Saving goal created successfully!');
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
        $this->validate($request, [
            'saved_amount.*' => "required|numeric|gt:0|lte:{$goal->profile->savings}",
        ]);

        if ($request->saved_amount[$goal->id] > $rest) {
            $goal->saved_amount += $rest;
            $goal->profile->savings -= $rest;
            $goal->profile->save();
            $goal->save();
            return redirect()->back();
        }

        $goal->saved_amount += $request->saved_amount[$goal->id];
        $goal->profile->savings -= $request->saved_amount[$goal->id];
        $goal->profile->save();
        $goal->save();
        // dd($goal->profile->balance);

        return redirect()->back();
    }

    public function convertToExpense(SavingGoal $goal)
    {
        // dd($goal);

        Transaction::create([
            'profile_id' => $goal->profile_id,
            'category_id' => $goal->category_id,
            'amount' => $goal->saved_amount,
            'type' => 'expense',
            'description' => $goal->name,
        ]);
     

        $goal->status = 'fulfilled';
        $goal->save();

        return redirect()->back()->with('success', 'Saving goal converted to expense!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavingGoal $savingGoal)
    {
        //
    }
}
