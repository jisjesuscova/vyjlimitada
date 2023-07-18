<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRol;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\OrganizatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ExcelController;

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

Route::get('/', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

Route::get('/user_id', function () {
    return response()->json(['user_id' => auth()->id()]);
});

Route::get('/session-data', function () {
    return response()->json(auth()->user());
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/control/edit/{id}', function () {
    return view('home');
});

Route::get('/ticket/check/{token}', function () {
    return view('home');
});

Route::get('/pdf/{id}', [PdfController::class, 'generatePdf']);

Route::get('/excel/{id}', [ExcelController::class, 'generateExcel']);

Route::get('/excel/token/{id}', [ExcelController::class, 'generateTokenExcel']);
     
Route::middleware(['auth', 'checkrol:1'])->group(function () {
    Route::get('/team', function () {
        return view('home');
    });

    Route::get('/add_team', function () {
        return view('home');
    });

    Route::get('/setting', function () {
        return view('home');
    });
    
    Route::get('/administrator', [AdministratorController::class, 'index']);
});

Route::middleware(['auth', 'checkrol:2'])->group(function () {
    Route::get('/setting', function () {
        return view('home');
    });

    Route::get('/event', function () {
        return view('home');
    });

    Route::get('/add_event', function () {
        return view('home');
    });

    Route::get('/control', function () {
        return view('home');
    });

    Route::get('/add_control', function () {
        return view('home');
    });

    Route::get('/ticket/{id}', function () {
        return view('home');
    });

    Route::get('/organizator', [OrganizatorController::class, 'index']);
});

Route::middleware(['auth', 'checkrol:3'])->group(function () {
    Route::get('/setting', function () {
        return view('home');
    });

    Route::get('/qr_code', function () {
        return view('home');
    });

    Route::get('/security', [SecurityController::class, 'index']);
});

require __DIR__.'/auth.php';
