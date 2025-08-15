<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [AnimeController::class, 'index'])->name('home');

# Optional: simple aliases for nav (semua diarahkan ke index dengan kategori)
Route::get('/top', fn() => redirect()->route('home', ['category' => 'top']))->name('top');
Route::get('/season', fn() => redirect()->route('home', ['category' => 'season']))->name('season');
