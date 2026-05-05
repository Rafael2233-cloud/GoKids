<?php

use App\Http\Controllers\ChildController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\AdminProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

// User routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/artikel/{article}', [DashboardController::class, 'showArticle'])->name('artikel.show');

    Route::get('/input', [ChildController::class, 'index'])->name('input');
    Route::post('/input/store', [ChildController::class, 'store'])->name('child.store');
    Route::get('/input/{child}', [ChildController::class, 'show'])->name('child.show');
    Route::put('/input/{child}', [ChildController::class, 'update'])->name('child.update');
    Route::delete('/input/{child}', [ChildController::class, 'destroy'])->name('child.destroy');
    
    Route::post('/input/{child}/growth', [ChildController::class, 'storeGrowth'])->name('child.growth.store');
    Route::put('/input/{child}/growth/{growth}', [ChildController::class, 'updateGrowth'])->name('child.growth.update');
    Route::delete('/input/{child}/growth/{growth}', [ChildController::class, 'destroyGrowth'])->name('child.growth.destroy');
    
    Route::post('/children/{child}/vaccinations', [ChildController::class, 'storeVaccination'])->name('vaccinations.store');
    Route::put('/children/{child}/vaccinations/{vaccination}/status', [ChildController::class, 'updateVaccinationStatus'])->name('vaccinations.status');

    Route::get('/profil', [UserProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [UserProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/password', [UserProfileController::class, 'updatePassword'])->name('profil.password');
    
    // Milestone routes
    Route::post('/children/{child}/milestones', [ChildController::class, 'storeMilestone'])->name('milestones.store');
    Route::put('/milestones/{milestone}', [ChildController::class, 'updateMilestone'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [ChildController::class, 'destroyMilestone'])->name('milestones.destroy');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/artikel', [ArticleController::class, 'index'])->name('admin.artikel.index');
    Route::get('/artikel/create', [ArticleController::class, 'create'])->name('admin.artikel.create');
    Route::post('/artikel', [ArticleController::class, 'store'])->name('admin.artikel.store');
    Route::get('/artikel/{artikel}/edit', [ArticleController::class, 'edit'])->name('admin.artikel.edit');
    Route::put('/artikel/{artikel}', [ArticleController::class, 'update'])->name('admin.artikel.update');
    Route::delete('/artikel/{artikel}', [ArticleController::class, 'destroy'])->name('admin.artikel.destroy');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');
    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.pdf');
    Route::get('/profil', [AdminProfileController::class, 'index'])->name('admin.profil');
    Route::post('/profil/update', [AdminProfileController::class, 'update'])->name('admin.profil.update');
    Route::post('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('admin.profil.password');
});

require __DIR__ . '/auth.php';
