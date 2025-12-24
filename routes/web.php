<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/expenses');

Route::get('/expenses/import', [ExpenseController::class, 'import'])->name('expenses.import');
Route::post('/expenses/parse', [ExpenseController::class, 'parse'])->name('expenses.parse');
Route::post('/expenses/bulk-store', [ExpenseController::class, 'storeBulk'])->name('expenses.storeBulk');

Route::resource('categories', CategoryController::class);
Route::resource('expenses', ExpenseController::class);
