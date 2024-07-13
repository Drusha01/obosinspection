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

use App\Livewire\Admin\Administrator\Request\Response\Response;

// inspector
use App\Livewire\Admin\Inspector\ActivityLogs\ActivityLogs as InspectorActivityLogs;
use App\Livewire\Admin\Inspector\Certifications\Cetification\Certification as InspectorCertification;
use App\Livewire\Admin\Inspector\Certifications\Generate\Certification as InspectorCertificateGenerate;
use App\Livewire\Admin\Inspector\Inspections\Generate\Generate as InspectorGenerate;
use App\Livewire\Admin\Inspector\Dashboard\Dashboard as InspectorDashboard;
use App\Livewire\Admin\Inspector\Equipments\Category\Category as InspectorCategory;
use App\Livewire\Admin\Inspector\Equipments\Items\Items as InspectorItems;
use App\Livewire\Admin\Inspector\Inspections\CompletedSchedules\CompletedSchedules as InspectorCompletedSchedules;
use App\Livewire\Admin\Inspector\Inspections\DeletedInspections\DeletedInspections as InspectorDeletedInspections;
use App\Livewire\Admin\Inspector\Inspections\InspectionSchedules\InspectionSchedules as InspectorInspectionSchedules;
use App\Livewire\Admin\Inspector\Inspections\OngoingInspections\OngoingInspections as InspectorOngoingInspections;
use App\Livewire\Admin\Inspector\Profile\Profile as InspectorProfile;
use App\Livewire\Admin\Inspector\Violations\Violations as InspectorViolations;


// inspector team leader
use App\Livewire\Admin\InspectorTeamLeader\ActivityLogs\ActivityLogs as InspectorTeamLeaderActivityLogs;
use App\Livewire\Admin\InspectorTeamLeader\Certifications\Cetification\Certification as InspectorTeamLeaderCertification;
use App\Livewire\Admin\InspectorTeamLeader\Certifications\Generate\Certification as InspectorTeamLeaderCertificateGenerate;
use App\Livewire\Admin\InspectorTeamLeader\Dashboard\Dashboard as InspectorTeamLeaderDashboard;
use App\Livewire\Admin\InspectorTeamLeader\Equipments\Category\Category as InspectorTeamLeaderCategory;
use App\Livewire\Admin\InspectorTeamLeader\Equipments\Items\Items as InspectorTeamLeaderItems;
use App\Livewire\Admin\InspectorTeamLeader\Establishments\Businesses\Businesses as InspectorTeamLeaderBusinesses;
use App\Livewire\Admin\InspectorTeamLeader\Establishments\Owners\Owners as InspectorTeamLeaderOwners;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\CompletedInspections\CompletedInspections as InspectorTeamLeaderCompletedInspections;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\DeletedInspections\DeletedInspections as InspectorTeamLeaderDeletedInspections;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\Generate\Generate  as InspectorTeamLeaderGenerate;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\InspectionSchedules\InspectionSchedules as InspectorTeamLeaderInspectionSchedules;
use App\Livewire\Admin\InspectorTeamLeader\Inspections\OngoingInspections\OngoingInspections as InspectorTeamLeaderOngoingInspections; 
use App\Livewire\Admin\InspectorTeamLeader\Inspections\UpcomingInspections\UpcomingInspections as InspectorTeamLeaderUpcomingInspections;
use App\Livewire\Admin\InspectorTeamLeader\Profile\Profile as InspectorTeamLeaderProfile;
use App\Livewire\Admin\InspectorTeamLeader\Violations\Violations as InspectorTeamLeaderViolations;

use App\Livewire\Admin\InspectorTeamLeader\Request\AcceptedRequest\AcceptedRequest as InspectorTeamLeaderAcceptedRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\CompletedRequest\CompletedRequest as InspectorTeamLeaderCompletedRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\DeclinedRequest\DeclinedRequest as InspectorTeamLeaderDeclinedRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\DeletedRequest\DeletedRequest as InspectorTeamLeaderDeletedRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\GeneratePdf\GeneratePdf as InspectorTeamLeaderGeneratePdf;
use App\Livewire\Admin\InspectorTeamLeader\Request\GenerateRequest\GenerateRequest as InspectorTeamLeaderGenerateRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\NoresponseRequest\NoresponseRequest as InspectorTeamLeaderNoresponseRequest;
use App\Livewire\Admin\InspectorTeamLeader\Request\RejectedRequest\RejectedRequest as InspectorTeamLeaderRejectedRequest;


// administrator
use App\Livewire\Admin\Administrator\ActivityLogs\ActivityLogs as AdministratorActivityLogs;
use App\Livewire\Admin\Administrator\BarangayLocations\BarangayLocations as AdministratorBarangayLocations;
use App\Livewire\Admin\Administrator\Billings\BuildingBillings\BuildingBillings as AdministratorBuildingBillings;
use App\Livewire\Admin\Administrator\Billings\BuildingBillingSections\BuildingBillingSections as AdministratorBuildingBillingSections;
use App\Livewire\Admin\Administrator\Billings\EquipmentBillings\EquipmentBillings as AdministratorEquipmentBillings;
use App\Livewire\Admin\Administrator\Billings\EquipmentBillingSections\EquipmentBillingSections as AdministratorEquipmentBillingSections;
use App\Livewire\Admin\Administrator\Billings\SanitaryBillings\SanitaryBillings as AdministratorSanitaryBillings;
use App\Livewire\Admin\Administrator\Billings\SignageBillings\SignageBillings as AdministratorSignageBillings;
use App\Livewire\Admin\Administrator\Certifications\Certification\Certification as AdministratorCertification;
use App\Livewire\Admin\Administrator\Certifications\Generate\Certification  as AdministratorCertificationGenerate;
use App\Livewire\Admin\Administrator\Dashboard\Dashboard  as AdministratorDashboard;
use App\Livewire\Admin\Administrator\Equipments\Category\Category as AdministratorCategory;
use App\Livewire\Admin\Administrator\Equipments\Items\Items as AdministratorItems;
use App\Livewire\Admin\Administrator\Establishments\BusinessCategory\BusinessCategory as AdministratorBusinessCategory;
use App\Livewire\Admin\Administrator\Establishments\Businesses\Businesses as AdministratorBusinesses;
use App\Livewire\Admin\Administrator\Establishments\BusinessOccuClass\BusinessOccuClass as AdministratorBusinessOccuClass;
use App\Livewire\Admin\Administrator\Establishments\BusinessTypes\BusinessTypes as AdministratorBusinessTypes;
use App\Livewire\Admin\Administrator\Establishments\Owner\Owner as AdministratorOwner;
use App\Livewire\Admin\Administrator\Inspections\CompletedInspections\CompletedInspections as AdministratorCompletedInspections;
use App\Livewire\Admin\Administrator\Inspections\DeletedInspections\DeletedInspections as AdministratorDeletedInspections;
use App\Livewire\Admin\Administrator\Inspections\Generate\Generate as AdministratorGenerate;
use App\Livewire\Admin\Administrator\Inspections\GenerateReport\GenerateReport as AdministratorGenerateReport;
use App\Livewire\Admin\Administrator\Inspections\InspectionSchedules\InspectionSchedules as AdministratorInspectionSchedules;
use App\Livewire\Admin\Administrator\Inspections\OngoingInspections\OngoingInspections as AdministratorOngoingInspections;
use App\Livewire\Admin\Administrator\Inspections\UpcomingInspections\UpcomingInspections as AdministratorUpcomingInspections;
use App\Livewire\Admin\Administrator\Profile\Profile as AdministratorProfile;
use App\Livewire\Admin\Administrator\Request\AcceptedRequest\AcceptedRequest as AdministratorAcceptedRequest;
use App\Livewire\Admin\Administrator\Request\DeletedRequest\DeletedRequest as AdministratorDeletedRequest;
use App\Livewire\Admin\Administrator\Request\GenerateRequest\GenerateRequest as AdministratorGenerateRequest;
use App\Livewire\Admin\Administrator\Request\GeneratePdf\GeneratePdf as AdministratorGeneratePdf;
use App\Livewire\Admin\Administrator\Request\NoresponseRequest\NoresponseRequest as AdministratorNoresponseRequest;
use App\Livewire\Admin\Administrator\Request\DeclinedRequest\DeclinedRequest as AdministratorDeclinedRequest;
use App\Livewire\Admin\Administrator\Users\Administrator\Administrator as AdministratorAdministrator;
use App\Livewire\Admin\Administrator\Users\InspectorGroups\InspectorGroups as AdministratorInspectorGroups;
use App\Livewire\Admin\Administrator\Users\Inspectors\Inspectors as AdministratorInspectors;
use App\Livewire\Admin\Administrator\Users\TeamLeaderInspector\TeamLeaderInspector as AdministratorTeamLeaderInspector;
use App\Livewire\Admin\Administrator\Users\WorkRoles\WorkRoles as AdministratorWorkRoles;
use App\Livewire\Admin\Administrator\Violations\Violations as AdministratorViolations;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',Test::class)->name('test');


Route::get('/logout', AuthenticationLogout::class)->middleware(Logout::class)->name('logout');

Route::middleware([Unauthenticated::class])->group(function () {
    Route::get('/login', AuthenticationLogin::class)->name('login');
});

Route::get('/', function () {})->middleware([Authenticated::class,IsValid::class,checkRoles::class])->name('/');

    Route::get('/request-response/{response}/{hash}',Response::class)->name('request-response');

// inspector
Route::middleware([Authenticated::class,IsValid::class,IsInspector::class])->group(function () {
    Route::prefix('inspector')->group(function () {
        Route::get('/dashboard', InspectorDashboard::class)->name('inspector-dashboard');
        Route::prefix('equipments')->group(function () {
            Route::get('/categories', InspectorCategory::class)->name('inspector-equipments-categories');
            Route::get('/items', InspectorItems::class)->name('inspector-equipments-items');
        });
        Route::get('/violations', InspectorViolations::class)->name('inspector-violations');
        Route::get('/activity-logs', InspectorActivityLogs::class)->name('inspector-activity-logs');
        Route::prefix('inspections')->group(function () {
            Route::get('/inspection-schedules', InspectorInspectionSchedules::class)->name('inspector-inspections-inspection-schedules');
            Route::get('/ongoing-inspections', InspectorOngoingInspections::class)->name('inspector-inspections-ongoing-inspections');
            Route::get('/generate/{id}', InspectorGenerate::class)->name('inspector-inspections-generate');
            Route::get('/deleted-inspections', InspectorDeletedInspections::class)->name('inspector-inspections-deleted-inspections');
            Route::get('/generate-report/{id}', AdministratorGenerateReport::class)->name('inspector-inspections-generate-report');
            Route::get('/completed-inspections', InspectorCompletedSchedules::class)->name('inspector-inspections-completed-inspections');
        });
        Route::get('/certifications', InspectorCertification::class)->name('inspector-certifications');
        Route::get('/certifications/generate/{id}', InspectorCertificateGenerate::class)->name('inspector-generate-certifications');
        Route::get('/profile', InspectorProfile::class)->name('inspector-profile');
    });
});

// team leader
Route::middleware([Authenticated::class,IsValid::class,IsInspectorTeamLeader::class])->group(function () {
    Route::prefix('inspector-team-leader')->group(function () {
        Route::get('/dashboard', InspectorTeamLeaderDashboard::class)->name('inspector-team-leader-dashboard');
        Route::prefix('establishments')->group(function () {
            Route::get('/businesses', InspectorTeamLeaderBusinesses::class)->name('inspector-team-leader-establishments-businesses');
            Route::get('/owners', InspectorTeamLeaderOwners::class)->name('inspector-team-leader-establishments-owners');
        });
        Route::prefix('equipments')->group(function () {
            Route::get('/categories', InspectorTeamLeaderCategory::class)->name('inspector-team-leader-equipments-categories');
            Route::get('/items', InspectorTeamLeaderItems::class)->name('inspector-team-leader-equipments-items');
        });
        Route::get('/violations', InspectorTeamLeaderViolations::class)->name('inspector-team-leader-violations');
        Route::get('/activity-logs', InspectorTeamLeaderActivityLogs::class)->name('inspector-team-leader-activity-logs');
        Route::prefix('inspections')->group(function () {
            Route::get('/inspection-schedules', InspectorTeamLeaderInspectionSchedules::class)->name('inspector-team-leader-inspections-inspection-schedules');
            Route::get('/ongoing-inspections', InspectorTeamLeaderOngoingInspections::class)->name('inspector-team-leader-inspections-ongoing-inspections');
            Route::get('/generate/{id}', InspectorTeamLeaderGenerate::class)->name('inspector-team-leader-inspections-generate');
            Route::get('/deleted-inspections', InspectorTeamLeaderDeletedInspections::class)->name('inspector-team-leader-inspections-deleted-inspections');
            Route::get('/generate-report/{id}', AdministratorGenerateReport::class)->name('inspector-team-leader-inspections-generate-report');
            Route::get('/completed-inspections', InspectorTeamLeaderCompletedInspections::class)->name('inspector-team-leader-inspections-completed-inspections');
            Route::get('/upcoming-inspections', InspectorTeamLeaderUpcomingInspections::class)->name('inspector-team-leader-inspections-upcoming-inspections');
        });
        Route::prefix('request')->group(function () {
            Route::get('/accepted-request', InspectorTeamLeaderAcceptedRequest::class)->name('inspector-team-leader-request-accepted-request');
            Route::get('/generate-request', InspectorTeamLeaderGenerateRequest::class)->name('inspector-team-leader-request-generate-request');
            Route::get('/no-response-request', InspectorTeamLeaderNoresponseRequest::class)->name('inspector-team-leader-request-no-response-request');
            Route::get('/declined-request', InspectorTeamLeaderDeclinedRequest::class)->name('inspector-team-leader-request-declined-request');
            Route::get('/deleted-request', InspectorTeamLeaderDeletedRequest::class)->name('inspector-team-leader-request-deleted-request');
            Route::get('/generate-request-pdf/{id}/{start_date}/{end_date}', InspectorTeamLeaderGeneratePdf::class)->name('inspector-team-leader-request-generate-pdf-request');
        });
        Route::get('/certifications', InspectorTeamLeaderCertification::class)->name('inspector-team-leader-certifications');
        Route::get('/certifications/generate/{id}', InspectorTeamLeaderCertificateGenerate::class)->name('inspector-team-leader-generate-certifications');
        Route::get('/profile', InspectorTeamLeaderProfile::class)->name('inspector-team-leader-profile');
    });
});
// administrator
Route::middleware([Authenticated::class,IsValid::class,IsAdministrator::class])->group(function () {
    Route::prefix('administrator')->group(function () {
        Route::get('/dashboard', AdministratorDashboard::class)->name('administrator-dashboard');
        Route::prefix('establishments')->group(function () {
            Route::get('/businesses', AdministratorBusinesses::class)->name('administrator-establishments-businesses');
            Route::get('/owners', AdministratorOwner::class)->name('administrator-establishments-owners');
            Route::get('/business-category', AdministratorBusinessCategory::class)->name('administrator-establishments-business-category');
            Route::get('/business-type', AdministratorBusinessTypes::class)->name('administrator-establishments-business-type');
            Route::get('/business-occupation-classification', AdministratorBusinessOccuClass::class)->name('administrator-establishments-business-occupation-classification');
        });
        Route::prefix('equipments')->group(function () {
            Route::get('/categories', AdministratorCategory::class)->name('administrator-equipments-categories');
            Route::get('/items', AdministratorItems::class)->name('administrator-equipments-items');
        });
        Route::prefix('billings')->group(function () {
            Route::get('/equipments-billings', AdministratorEquipmentBillings::class)->name('administrator-billings-equipments-billings');
            Route::get('/equipments-billing-sections', AdministratorEquipmentBillingSections::class)->name('administrator-billings-equipments-billing-sections');
           
            Route::get('/building-billings', AdministratorBuildingBillings::class)->name('administrator-billings-building-billings');
            Route::get('/building-billing-sections', AdministratorBuildingBillingSections::class)->name('administrator-billings-building-billing-sections');
            Route::get('/signage-billings', AdministratorSignageBillings::class)->name('administrator-billings-signage-billings');
            Route::get('/sanitary-billings', AdministratorSanitaryBillings::class)->name('administrator-billings-sanitary-billings');
        });
        Route::prefix('inspections')->group(function () {
            Route::get('/inspection-schedules', AdministratorInspectionSchedules::class)->name('administrator-inspections-inspection-schedules');
            Route::get('/ongoing-inspections', AdministratorOngoingInspections::class)->name('administrator-inspections-ongoing-inspections');
            Route::get('/deleted-inspections', AdministratorDeletedInspections::class)->name('administrator-inspections-deleted-inspections');
            Route::get('/generate/{id}', AdministratorGenerate::class)->name('administrator-inspections-generate');
            Route::get('/generate-report/{id}', AdministratorGenerateReport::class)->name('administrator-inspections-generate-report');
            Route::get('/completed-inspections', AdministratorCompletedInspections::class)->name('administrator-inspections-completed-inspections');
            Route::get('/upcoming-inspections', AdministratorUpcomingInspections::class)->name('administrator-inspections-upcoming-inspections');
        });
        Route::prefix('request')->group(function () {
            Route::get('/accepted-request', AdministratorAcceptedRequest::class)->name('administrator-request-accepted-request');
            Route::get('/generate-request', AdministratorGenerateRequest::class)->name('administrator-request-generate-request');
            Route::get('/no-response-request', AdministratorNoresponseRequest::class)->name('administrator-request-no-response-request');
            Route::get('/declined-request', AdministratorDeclinedRequest::class)->name('administrator-request-declined-request');
            Route::get('/deleted-request', AdministratorDeletedRequest::class)->name('administrator-request-deleted-request');
            Route::get('/generate-request-pdf/{id}/{start_date}/{end_date}', AdministratorGeneratePdf::class)->name('administrator-request-generate-pdf-request');
        });
        Route::prefix('users')->group(function () {
            Route::get('/administrators', AdministratorAdministrator::class)->name('administrator-users-administrators');
            Route::get('/team-leader-inspectors', AdministratorTeamLeaderInspector::class)->name('administrator-users-team-leader-inspectors');
            Route::get('/inspectors', AdministratorInspectors::class)->name('administrator-users-inspectors');
            Route::get('/group-inspectors', AdministratorInspectorGroups::class)->name('administrator-users-group-inspectors');
            Route::get('/work-roles', AdministratorWorkRoles::class)->name('administrator-users-work-roles');
            
        });
        Route::get('/certifications', AdministratorCertification::class)->name('administrator-certifications');
        Route::get('/certifications/generate/{id}', AdministratorCertificationGenerate::class)->name('administrator-generate-certifications');
        Route::get('/violations', AdministratorViolations::class)->name('administrator-violations');
        Route::get('/barangay-locations', AdministratorBarangayLocations::class)->name('administrator-barangay-locations');
        Route::get('/activity-logs', AdministratorActivityLogs::class)->name('administrator-activity-logs');
        Route::get('/profile', AdministratorProfile::class)->name('administrator-profile');
    });
});