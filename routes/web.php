<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Test;

// middleware
use App\Http\Middleware\Authenticated;
use App\Http\Middleware\checkRoles;
use App\Http\Middleware\IsAdministrator;
use App\Http\Middleware\IsInspector;
use App\Http\Middleware\IsInspectorTeamLeader;
use App\Http\Middleware\IsValid;
use App\Http\Middleware\Logout;
use App\Http\Middleware\Unauthenticated;

// authentication
use App\Livewire\Authentication\Disabled as AuthenticationDisabled;
use App\Livewire\Authentication\Login as AuthenticationLogin;
use App\Livewire\Authentication\Logout as AuthenticationLogout;

// inspector
use App\Livewire\Admin\Inspector\Dashboard\Dashboard as InspectorDashboard;

// inspector team leader
use App\Livewire\Admin\InspectorTeamLeader\Dashboard\Dashboard as InspectorTeamLeaderDashboard;

// administrator
use App\Livewire\Admin\Administrator\Dashboard\Dashboard  as AdministratorDashboard;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',Test::class)->name('test');


Route::get('/logout', AuthenticationLogout::class)->middleware(Logout::class)->name('logout');

Route::middleware([Unauthenticated::class])->group(function () {
    Route::get('/login', AuthenticationLogin::class)->name('login');
});

Route::get('/', function () {})->middleware([Authenticated::class,IsValid::class,checkRoles::class]);



// inspector
Route::middleware([Authenticated::class,IsValid::class,IsInspector::class])->group(function () {
    Route::prefix('inspector')->group(function () {
        Route::get('/dashboard', InspectorDashboard::class)->name('inspector-dashboard');
    });
});

// team leader
Route::middleware([Authenticated::class,IsValid::class,IsInspectorTeamLeader::class])->group(function () {
    Route::prefix('inspector-team-leader')->group(function () {
        Route::get('/dashboard', InspectorTeamLeaderDashboard::class)->name('inspector-team-leader-dashboard');
    });
});
// administrator
Route::middleware([Authenticated::class,IsValid::class,IsAdministrator::class])->group(function () {
    Route::prefix('administrator')->group(function () {
        Route::get('/dashboard', AdministratorDashboard::class)->name('administrator-dashboard');
    });
});