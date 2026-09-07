<?php

use App\Http\Controllers\ProtectedFileController;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

use App\Livewire\Campuses\Index as CampusesIndex;
use App\Livewire\Careers\Index as CareersIndex;
use App\Livewire\Semesters\Index as SemestersIndex;
use App\Livewire\Admin\CreateAdmin as CreateAdmin;
use App\Livewire\Admin\Trash as Trash;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Livewire\Home\Index as HomeIndex;
use App\Livewire\Dashboard\Period\PeriodStudents;
use App\Livewire\Dashboard\Period\PeriodDocuments;
use App\Livewire\Dashboard\Period\PeriodRevision;

use App\Livewire\Students\Documents\Index as StudentDocumentsIndex;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('student-documents.index');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('secure-file', [ProtectedFileController::class, 'show'])->name('files.show');

    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');

    Route::group(['middleware' => ['role:admin']], function () { 

        Route::get('dashboard', HomeIndex::class)->name('dashboard');
        Route::get('periods', DashboardIndex::class)->name('periods');

        Route::get('campuses', CampusesIndex::class)->name('campuses.index');
        Route::get('careers', CareersIndex::class)->name('careers.index');
        Route::get('semesters', SemestersIndex::class)->name('semesters.index');
        Route::get('admin/create-admin', CreateAdmin::class)->name('admin.create-admin');
        Route::get('admin/trash', Trash::class)->name('admin.trash');

        Route::get('periods/{id}', fn($id) => redirect()->route('periods.students', $id))
            ->name('periods.detail');

        Route::get('periods/{id}/students', PeriodStudents::class)->name('periods.students');
        Route::get('periods/{id}/documents',  PeriodDocuments::class)->name('periods.documents');
        Route::get('periods/{id}/revision',    PeriodRevision::class)->name('periods.revision');
    });

    // 🔹 SECCIÓN ESTUDIANTES
    Route::get('students/documents', StudentDocumentsIndex::class)->name('student-documents.index');
});

require __DIR__.'/auth.php';
