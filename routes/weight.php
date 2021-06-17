<?php

use App\Http\Controllers\Weight\Auth\PasswordController;
use App\Http\Controllers\Weight\Auth\UserController;
use App\Http\Controllers\Weight\BodyStatusController;
use App\Http\Controllers\Weight\UserAmountsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Web Pages
Route::group(['prefix' => ''], function() {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');
    Route::get('/calendar', function () {
        return Inertia::render('Calendar');
    })->name('calendar');
    Route::get('/chart', function () {
        return Inertia::render('Chart');
    })->name('chart');

    Route::middleware(['auth'])->group(function () {
        Route::get('/account', function () {
            return Inertia::render('Account');
        })->name('account');
        Route::get('/change-password', function () {
            return Inertia::render('ChangePassword');
        })->name('change-password');
    });
});

// API
Route::group(['prefix' => 'weight'], function () {
    Route::post('register', [UserController::class, 'register'])->name('weight:register');
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('authWithFirebase', [UserController::class, 'loginWithFirebase']);
    Route::post('user/emailExists', [UserController::class, 'emailExists']);
    Route::group(['prefix' => 'password'], function () {
        Route::post('forget-password', [PasswordController::class, 'forgetPassword'])
            ->name('user:forget-password');
        Route::get('reset-page/{token}', [PasswordController::class, 'resetPage'])->name('user:reset-password-page');
        Route::post('reset', [PasswordController::class, 'reset'])->name('user:reset-password');
    });

    Route::middleware(['auth'])->group(function () {
        Route::post('user', [UserController::class, 'update'])->name('user:update');
        Route::post('logout', [UserController::class, 'logout'])->name('user:logout');
        Route::group(['prefix' => 'password'], function () {
            Route::post('change', [PasswordController::class, 'change'])->name('user:change-password');
        });
        Route::get('weightStates', [UserAmountsController::class, 'weightStatesData']);
        Route::get('weightStatesByMonth', [UserAmountsController::class, 'weightStatesDataByMonth']);
        Route::get('status/categories', [BodyStatusController::class, 'statusCategories']);
        Route::get('status/selected', [BodyStatusController::class, 'selected']);
        Route::group(['prefix' => 'store'], function () {
            Route::post('amounts', [UserAmountsController::class, 'store']);
            Route::post('statuses', [BodyStatusController::class, 'store']);
        });
    });
});
