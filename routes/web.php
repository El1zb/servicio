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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::group(['middleware' => ['role:admin']], function () { 
        Route::get('periods', PeriodsCrud::class)->name('periods.index');
        Route::get('campuses', CampusesCrud::class)->name('campuses.index');
        Route::get('careers', CareersCrud::class)->name('careers.index');    
        Route::get('semesters', SemestersCrud::class)->name('semesters.index');
    });

    
    Route::get('students/profile', StudentsProfile::class)->name('students.profile');
});

require __DIR__.'/auth.php';
