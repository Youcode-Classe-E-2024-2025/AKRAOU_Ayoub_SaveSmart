<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AccountController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AccountController::class, 'login'])->name('login')->middleware('guest');
Route::get('/signup', [AccountController::class, 'create'])->name('signup')->middleware('guest');
Route::post('/signup', [AccountController::class, 'register'])->name('register')->middleware('guest');
Route::post('/logout', [AccountController::class, 'logout'])->name('logout')->middleware('auth');



Route::get('/', [AccountController::class, 'index'])->name('home')->middleware('auth');

Route::get('/profile/create', [ProfileController::class, 'create'])->name('profiles.create')->middleware('auth');
Route::put('/profile/create', [ProfileController::class, 'store'])->name('profiles.store')->middleware('auth');
Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profiles.show')->middleware('auth');

Route::get('/profile/{id}/edit', [ProfileController::class, 'edit'])->name('profiles.edit')->middleware('auth');
Route::patch('/profile/{id}/edit', [ProfileController::class, 'update'])->name('profiles.update')->middleware('auth');
Route::patch('/profile/{profile}/savings', [ProfileController::class, 'updateSavings'])->name('profiles.updateSavings')->middleware('auth');
Route::delete('/profile/{id}/edit', [ProfileController::class, 'destroy'])->name('profiles.destroy')->middleware('auth');

Route::put('/transaction/store', [TransactionController::class, 'store'])->name('transactions.store')->middleware('auth');
Route::patch('/transaction/{transaction}/edit', [TransactionController::class, 'update'])->name('transactions.update')->middleware('auth');
Route::delete('/transaction/{transaction}/edit', [TransactionController::class, 'destroy'])->name('transactions.destroy')->middleware('auth');

Route::put('/goal/store', [SavingGoalController::class, 'store'])->name('goals.store')->middleware('auth');
Route::patch('/goal/{goal}/edit', [SavingGoalController::class, 'update'])->name('goals.update')->middleware('auth');
Route::patch('/goal/{goal}/convert', [SavingGoalController::class, 'convertToExpense'])->name('goals.convert')->middleware('auth');
Route::delete('/goal/{goal}/edit', [SavingGoalController::class, 'destroy'])->name('goals.destroy')->middleware('auth');

Route::put('/category/store', [CategoryController::class, 'store'])->name('categories.store')->middleware('auth');
Route::delete('/category/{category}/edit', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('auth');