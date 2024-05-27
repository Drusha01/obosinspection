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
use App\Livewire\Admin\Inspector\ActivityLogs\ActivityLogs as InspectorActivityLogs;
use App\Livewire\Admin\Inspector\Dashboard\Dashboard as InspectorDashboard;
use App\Livewire\Admin\Inspector\Equipments\Category\Category as InspectorCategory;
use App\Livewire\Admin\Inspector\Equipments\Items\Items as InspectorItems;
use App\Livewire\Admin\Inspector\Inspections\CompletedSchedules\CompletedSchedules as InspectorCompletedSchedules;
use App\Livewire\Admin\Inspector\Inspections\InspectionSchedules\InspectionSchedules as InspectorInspectionSchedules;
use App\Livewire\Admin\Inspector\Profile\Profile as InspectorProfile;


// inspector team leader
use App\Livewire\Admin\InspectorTeamLeader\ActivityLogs\ActivityLogs as InspectorTeamLeaderActivityLogs;
use App\Livewire\Admin\InspectorTeamLeader\Dashboard\Dashboard as InspectorTeamLeaderDashboard;
use App\Livewire\Admin\InspectorTeamLeader\Equipments\Category\Category as InspectorTeamLeaderCategory;
use App\Livewire\Admin\InspectorTeamLeader\Equipments\Items\Items as InspectorTeamLeaderItems;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\CompletedInspections\CompletedInspections as InspectorTeamLeaderCompletedInspections;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\InspectionSchedules\InspectionSchedules as InspectorTeamLeaderInspectionSchedules;
use App\Livewire\Admin\InspectorTeamLeader\Profile\Profile as InspectorTeamLeaderProfile;
use App\Livewire\Admin\InspectorTeamLeader\Violations\Violations as InspectorTeamLeaderViolations;




// administrator
use App\Livewire\Admin\Administrator\ActivityLogs\ActivityLogs as AdministratorActivityLogs;
use App\Livewire\Admin\Administrator\BarangayLocations\BarangayLocations as AdministratorBarangayLocations;
use App\Livewire\Admin\Administrator\Billings\BuildingBillings\BuildingBillings as AdministratorBuildingBillings;
use App\Livewire\Admin\Administrator\Billings\EquipmentBillings\EquipmentBillings as AdministratorEquipmentBillings;
use App\Livewire\Admin\Administrator\Billings\SanitaryBillings\SanitaryBillings as AdministratorSanitaryBillings;
use App\Livewire\Admin\Administrator\Billings\SignageBillings\SignageBillings as AdministratorSignageBillings;
use App\Livewire\Admin\Administrator\Dashboard\Dashboard  as AdministratorDashboard;
use App\Livewire\Admin\Administrator\Equipments\Category\Category as AdministratorCategory;
use App\Livewire\Admin\Administrator\Equipments\Items\Items as AdministratorItems;
use App\Livewire\Admin\Administrator\Establishments\Businesses\Businesses as AdministratorBusinesses;
use App\Livewire\Admin\Administrator\Establishments\Owner\Owner as AdministratorOwner;
use App\Livewire\Admin\Administrator\Inspections\CompletedInspections\CompletedInspections as AdministratorCompletedInspections;
use App\Livewire\Admin\Administrator\Inspections\InspectionSchedules\InspectionSchedules as AdministratorInspectionSchedules;
use App\Livewire\Admin\Administrator\Profile\Profile as AdministratorProfile;
use App\Livewire\Admin\Administrator\Users\Administrator\Administrator as AdministratorAdministrator;
use App\Livewire\Admin\Administrator\Users\InspectorGroups\InspectorGroups as AdministratorInspectorGroups;
use App\Livewire\Admin\Administrator\Users\Inspectors\Inspectors as AdministratorInspectors;
use App\Livewire\Admin\Administrator\Users\TeamLeaderInspector\TeamLeaderInspector as AdministratorTeamLeaderInspector;
use App\Livewire\Admin\Administrator\Violations\Violations as AdministratorViolations;



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
        Route::prefix('establishments')->group(function () {
            Route::get('/businesses', AdministratorBusinesses::class)->name('administrator-establishments-businesses');
            Route::get('/owners', AdministratorOwner::class)->name('administrator-establishments-owners');
        });
        Route::prefix('equipments')->group(function () {
            Route::get('/categories', AdministratorCategory::class)->name('administrator-equipments-categories');
            Route::get('/items', AdministratorItems::class)->name('administrator-equipments-items');
        });
        Route::prefix('billings')->group(function () {
            Route::get('/equipments-billings', AdministratorEquipmentBillings::class)->name('administrator-billings-equipments-billings');
            Route::get('/building-billings', AdministratorBuildingBillings::class)->name('administrator-billings-building-billings');
            Route::get('/signage-billings', AdministratorSignageBillings::class)->name('administrator-billings-signage-billings');
            Route::get('/sanitary-billings', AdministratorSanitaryBillings::class)->name('administrator-billings-sanitary-billings');
        });
        Route::prefix('inspections')->group(function () {
            Route::get('/inspection-schedules', AdministratorInspectionSchedules::class)->name('administrator-inspections-inspection-schedules');
            Route::get('/completed-inspections', AdministratorCompletedInspections::class)->name('administrator-inspections-completed-inspections');
        });
        Route::prefix('users')->group(function () {
            Route::get('/administrators', AdministratorAdministrator::class)->name('administrator-users-administrators');
            Route::get('/team-leader-inspectors', AdministratorTeamLeaderInspector::class)->name('administrator-users-team-leader-inspectors');
            Route::get('/inspectors', AdministratorInspectors::class)->name('administrator-users-inspectors');
            Route::get('/group-inspectors', AdministratorInspectorGroups::class)->name('administrator-users-group-inspectors');
        });
        Route::get('/violations', AdministratorViolations::class)->name('administrator-violations');
        Route::get('/barangay-locations', AdministratorBarangayLocations::class)->name('administrator-barangay-locations');
        Route::get('/activity-logs', AdministratorActivityLogs::class)->name('administrator-activity-logs');
        Route::get('/profile', AdministratorProfile::class)->name('administrator-profile');
    });
});