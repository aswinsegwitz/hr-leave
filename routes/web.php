<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    //  Route::resource('leaves', LeaveController::class);


    Route::get('/leave', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/store', [LeaveController::class, 'store'])->name('leaves.store');
    Route::delete('/leave/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    Route::get('/leave/{leave}', [LeaveController::class, 'show'])->name('leaves.show');


    Route::get('/all', [LeaveController::class, 'allLeaves'])->name('leaves.all');
    Route::get('/leave/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::patch('/leave/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
});
