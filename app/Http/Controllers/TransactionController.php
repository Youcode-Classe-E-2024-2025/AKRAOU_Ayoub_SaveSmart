<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
        $balance = Profile::where('id', session('active_profile'))->first()->balance;
        $condition = 'gt:0';
     if ($request->type === 'expense') {
           $condition = $balance < $request->amount  ? 'lte:'.$balance : 'gt:0';
        }
        $this->validate($request, [
            'type' => 'required|string|in:income,expense',
            'amount' => 'required|numeric|'.$condition,
            'description' => 'required|string',
            'category_id' => 'required|numeric',
        ]);

        Transaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'profile_id' => session('active_profile'),
        ]);

        if ($request->type === 'income') {
            Profile::where('id', session('active_profile'))->increment('balance', $request->amount);
        } elseif ($request->type === 'expense') {
            Profile::where('id', session('active_profile'))->decrement('balance', $request->amount);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'type' => 'required|string|in:income,expense',
            'amount' => 'required|numeric|lte:10',
            'description' => 'required|string',
            'category_id' => 'required|numeric',
        ]);

        if ($request->type === 'income') {
            Profile::where('id', session('active_profile'))->decrement('balance', $transaction->amount);
        } elseif ($request->type === 'expense') {
            Profile::where('id', session('active_profile'))->increment('balance', $transaction->amount);
        }

        $transaction->update([
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        if ($request->type === 'income') {
            Profile::where('id', session('active_profile'))->increment('balance', $request->amount);
        } elseif ($request->type === 'expense') {
            Profile::where('id', session('active_profile'))->decrement('balance', $request->amount);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->type === 'income') {
            Profile::where('id', session('active_profile'))->decrement('balance', $transaction->amount);
        } elseif ($transaction->type === 'expense') {
            Profile::where('id', session('active_profile'))->increment('balance', $transaction->amount);
        }
        $transaction->delete();
        return redirect()->back();
    }
}
