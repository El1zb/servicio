<?php

use App\Livewire\Periods\Crud;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

use App\Livewire\Periods\Crud as PeriodsCrud;
use App\Livewire\Campuses\Crud as CampusesCrud;
use App\Livewire\Careers\Crud as CareersCrud;
use App\Livewire\Semesters\Crud as SemestersCrud;
use App\Livewire\Students\Profile as StudentsProfile;
use App\Livewire\Files\Crud as FilesCrud;
use App\Livewire\StudentDocuments\Crud as StudentDocumentsCrud;
use App\Livewire\Students\Index as StudentsIndex;
use App\Livewire\Students\Details as StudentsDetails;
use App\Livewire\Admin\StudentsApproval as StudentsApproval;
use App\Livewire\Admin\CreateAdmin as CreateAdmin;

use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Dashboard\PeriodDetail;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::group(['middleware' => ['role:admin']], function () { 

        Route::get('dashboard', DashboardIndex::class)->name('dashboard');

        Route::get('periods', PeriodsCrud::class)->name('periods.index');
        Route::get('campuses', CampusesCrud::class)->name('campuses.index');
        Route::get('careers', CareersCrud::class)->name('careers.index');    
        Route::get('semesters', SemestersCrud::class)->name('semesters.index');
        Route::get('files', FilesCrud::class)->name('files.index');
        Route::get('students/index', StudentsIndex::class)->name('students.index');         
        Route::get('students/details/{studentId}', StudentsDetails::class)->name('students.details');         
        Route::get('students/approval', StudentsApproval::class)->name('admin.students-approval'); 
        Route::get('admin/create-admin', CreateAdmin::class)->name('admin.create-admin');

        Route::get('periods/{id}', PeriodDetail::class)->name('periods.detail');
    });

    // 🔹 SECCIÓN ESTUDIANTES (accesible para cualquier usuario autenticado)
    Route::middleware(['auth'])->group(function () { 
        Route::get('students/profile', StudentsProfile::class)->name('students.profile'); 
        Route::get('students/documents', StudentDocumentsCrud::class)->name('student-documents.index');   
    });


 
});

require __DIR__.'/auth.php';
