<?php

use App\Livewire\Periods\Crud;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

use App\Livewire\Campuses\Index as CampusesIndex;
use App\Livewire\Careers\Index as CareersIndex;
use App\Livewire\Semesters\Index as SemestersIndex;
use App\Livewire\Admin\CreateAdmin as CreateAdmin;
//use App\Livewire\Admin\WelcomeSection as WelcomeSection;
use App\Livewire\Dashboard\Index as DashboardIndex;
//use App\Livewire\Dashboard\Period\PeriodDetail;
//use App\Http\Controllers\WelcomeController;
use App\Livewire\Dashboard\Period\PeriodStudents;
use App\Livewire\Dashboard\Period\PeriodDocuments;
use App\Livewire\Dashboard\Period\PeriodRevision;


//use App\Livewire\Students\Profile as StudentsProfile;
use App\Livewire\Students\Profile\Index as StudentsProfile;
use App\Livewire\StudentDocuments\Crud as StudentDocumentsCrud;

//Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::group(['middleware' => ['role:admin']], function () { 

        Route::get('dashboard', DashboardIndex::class)->name('dashboard');

        //Route::get('campuses', CampusesCrud::class)->name('campuses.index');
        Route::get('campuses', CampusesIndex::class)->name('campuses.index');
        //Route::get('careers', CareersCrud::class)->name('careers.index');    
        Route::get('careers', CareersIndex::class)->name('careers.index');
        //Route::get('semesters', SemestersCrud::class)->name('semesters.index');
        Route::get('semesters', SemestersIndex::class)->name('semesters.index');
        Route::get('admin/create-admin', CreateAdmin::class)->name('admin.create-admin');
        //Route::get('admin/welcome-section', WelcomeSection::class)->name('admin.welcome-section');
        //Route::get('periods/{id}', PeriodDetail::class)->name('periods.detail');

        Route::get('periods/{id}', fn($id) => redirect()->route('periods.students', $id))
            ->name('periods.detail');

        Route::get('periods/{id}/students', PeriodStudents::class)->name('periods.students');
        Route::get('periods/{id}/documents',  PeriodDocuments::class)->name('periods.documents');
        Route::get('periods/{id}/revision',    PeriodRevision::class)->name('periods.revision');
    });

    // 🔹 SECCIÓN ESTUDIANTES (accesible para cualquier usuario autenticado)
    Route::middleware(['auth'])->group(function () { 
        Route::get('students/profile', StudentsProfile::class)->name('students.profile'); 
        Route::get('students/documents', StudentDocumentsCrud::class)->name('student-documents.index');   
    });


 
});

require __DIR__.'/auth.php';
