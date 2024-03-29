<?php

use App\Http\Controllers\MpesaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mpesa', [MpesaController::class, 'Mpesa'])->name('mpesa');
Route::post('/process-mpesa', [MpesaController::class, 'ProcessMpesa'])->name('process-mpesa');
Route::get('/payments/verify', [MpesaController::class, 'verify'])->name('verify');
Route::post('/payments/verify', [MpesaController::class, 'verify'])->name('verify');


require __DIR__.'/auth.php';
