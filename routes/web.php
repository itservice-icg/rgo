<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    ProfileController,
    MailSettingController,
};

use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ManufactureRegisController;
use App\Http\Controllers\ChemicalRegistrationController;
use App\Http\Controllers\RenewRegisController;
use App\Http\Controllers\ChemicalImportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductionRegistrationImportController;
use App\Http\Controllers\ProductionRegistrationController;

Route::get('/', function () {
    return redirect('/admin/login');
});

require __DIR__ . '/auth.php';

// ✅ ครอบทุก route ที่ต้องการให้ล็อกอินก่อนเข้า
Route::middleware(['auth'])->group(function () {

    Route::namespace('App\Http\Controllers\Admin')->name('admin.')->prefix('admin')->group(function () {
        Route::resource('roles', 'RoleController');
        Route::resource('permissions', 'PermissionController');
        Route::resource('users', 'UserController');
        Route::resource('posts', 'PostController');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile-update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/mail', [MailSettingController::class, 'index'])->name('mail.index');
        Route::put('/mail-update/{mailsetting}', [MailSettingController::class, 'update'])->name('mail.update');
        Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::get('/import', [ChemicalImportController::class, 'index'])->name('import.index');
    Route::get('/import/create', [ChemicalImportController::class, 'create'])->name('import.create');
    Route::post('/import/store', [ChemicalImportController::class, 'store'])->name('import.store');
    Route::get('/import/{import}/edit', [ChemicalImportController::class, 'edit'])->name('import.edit');
    Route::get('/import/{import}/additional-document', [ChemicalImportController::class, 'additionalDocument'])->name('import.additional-document');
    Route::get('/import/{import}/files/{file}', [ChemicalImportController::class, 'file'])->name('import.file');
    Route::delete('/import/{import}/files/{file}', [ChemicalImportController::class, 'destroyFile'])->name('import.file.destroy');
    Route::put('/import/{import}', [ChemicalImportController::class, 'update'])->name('import.update');
    Route::delete('/import/{import}', [ChemicalImportController::class, 'destroy'])->name('import.destroy');
    Route::get('/import/{import}', [ChemicalImportController::class, 'show'])->name('import.show');

    Route::get('/new/product', [ChemicalRegistrationController::class, 'index'])->name('newregis.index');
    Route::get('/new/product/show/{registrationNumber}', [ChemicalRegistrationController::class, 'show'])->name('newregis.show');
    Route::get('/new/product/create', [ChemicalRegistrationController::class, 'create'])->name('newregis.create');
    Route::post('/new/product/store', [ChemicalRegistrationController::class, 'store'])->name('newregis.store');
    Route::get('/new/product/edit/{registrationNumber}', [ChemicalRegistrationController::class, 'edit'])->name('newregis.edit');
    Route::put('/new/product/update/{registrationNumber}', [ChemicalRegistrationController::class, 'update'])->name('newregis.update');
    Route::delete('/newregis/{id}', [ChemicalRegistrationController::class, 'destroy'])->name('newregis.destroy');
    Route::put('/newregis/{drug}/update-subprogress', [ChemicalRegistrationController::class, 'updateSubProgress'])->name('newregis.update-subprogress');
    Route::get('/new/productall', [ChemicalRegistrationController::class, 'indexAll'])->name('newregis.productall');
    Route::get('/new/productall/{newregi}/edit', [ChemicalRegistrationController::class, 'editAll'])->name('newregis.editall');
    Route::put('/new/productall/{newregi}', [ChemicalRegistrationController::class, 'updateAll'])->name('newregis.updateall');
    Route::get('/new/productall/{newregi}/show', [ChemicalRegistrationController::class, 'showAll'])->name('newregis.showall');

    Route::get('/create/product', [ProductionRegistrationController::class, 'index'])->name('createproduct.index');
    Route::get('/insert/product', [ProductionRegistrationController::class, 'create'])->name('createproduct.create');
    Route::post('/store/product', [ProductionRegistrationController::class, 'store'])->name('createproduct.store');
    Route::get('/edit/product/{productionRegistration}', [ProductionRegistrationController::class, 'edit'])->name('createproduct.edit');
    Route::get('/product/{productionRegistration}/additional-document', [ProductionRegistrationController::class, 'additionalDocument'])->name('createproduct.additional-document');
    Route::get('/product/{productionRegistration}/files/{file}', [ProductionRegistrationController::class, 'file'])->name('createproduct.file');
    Route::delete('/product/{productionRegistration}/files/{file}', [ProductionRegistrationController::class, 'destroyFile'])->name('createproduct.file.destroy');
    // Route::put('/import/{import}', [ProductionRegistrationController::class, 'update'])->name('createproduct.update');
    Route::put('/import2/{import}', [ProductionRegistrationController::class, 'update'])->name('createproduct.update');
    Route::get('/show/product/{productionRegistration}', [ProductionRegistrationController::class, 'show'])->name('createproduct.show');
    Route::delete('/createproducts/{id}', [ProductionRegistrationController::class, 'destroy'])->name('createproduct.destroy');

    Route::get('/renew/product', [RenewRegisController::class, 'index'])->name('renewregis.index');
    Route::get('/manufactture/product', [ManufactureRegisController::class, 'index'])->name('manufactureregis.index');

    Route::resource('company', CompanyController::class);

    // import วัตถุดิบ
    Route::get('/chemical-imports/import', [ChemicalImportController::class, 'showImportForm'])->name('chemical_imports.import.form');
    Route::post('/chemical-imports/import', [ChemicalImportController::class, 'import'])->name('chemical_imports.import');

    // import ผลิต
    Route::get('/test-import', [ProductionRegistrationImportController::class, 'showForm'])->name('index.import_data');;
    Route::post('/import/production-registration', [ProductionRegistrationImportController::class, 'import'])->name('import.production-registration');
});
