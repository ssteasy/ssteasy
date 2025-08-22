<?php

use Illuminate\Support\Facades\Route;
use Spatie\WebTinker\Http\Controllers\WebTinkerController;
use App\Http\Controllers\UserResumeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GestionCambioPdfController;

Route::get('export/plan/{plan}.pdf', [ExportController::class,'exportPlanPdf'])
    ->name('export.plan.pdf')
    ->middleware(['auth']);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])
->prefix('admin')
->group(function () {
    Route::get('users/{user}/resume.pdf', [UserResumeController::class, 'download'])
        ->name('users.resume.pdf');
});




// routes/web.php
Route::post('/admin/sgsst-files/{file}/sign', function (\App\Models\SgsstFile $file) {
    $file->signatories()
        ->where('user_id', auth()->id())
        ->whereNull('signed_at')
        ->update(['signed_at' => now()]);

    return back()->with(
        'filament-success',
        'Documento firmado correctamente.'
    );
})->name('sgsst-files.sign')->middleware(['auth', 'verified']);




Route::get('/gestion-cambio/{cambio}/pdf', [GestionCambioPdfController::class, 'download'])
     ->middleware(['auth'])
     ->name('gestion-cambio.pdf');