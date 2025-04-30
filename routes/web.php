<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NIlaiAsliController;
use App\Http\Controllers\PemberkasanAdminController;
use App\Http\Controllers\PemberkasanUserController;
use App\Http\Controllers\PerhitunganSawController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SubCriteriaController;
use App\Http\Controllers\UserAlternatif;
use App\Http\Controllers\UserRangking;
use Illuminate\Support\Facades\Route;

// Redirect ke halaman login jika mengakses root '/'
// Route::get('/', function () {
//     return redirect()->route('login');
// });
Route::get('/', [HomeController::class, 'index'])->name('home');
// Rute untuk login dan logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Menampilkan form login
Route::post('/login', [AuthController::class, 'login']); // Memproses login
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Memproses logout

// Rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Rute dengan resource untuk Criteria
    Route::resource('criteria', CriteriaController::class);

    // Rute dengan resource untuk SubCriteria
    Route::resource('sub_criterias', SubCriteriaController::class);

    // Rute dengan resource untuk Alternatives
    Route::resource('alternatives', AlternativeController::class);

    // Rute untuk ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index'); // Menampilkan halaman ranking
    Route::post('/ranking', [RankingController::class, 'rank'])->name('ranking.rank'); // Memproses perhitungan ranking  
    // Route::post('/ranking/tolak/{id}', [RankingController::class, 'addKeterangan'])->name('ranking.tolak');
    // Route::post('/ranking/{alternativeId}/add-diterima', [RankingController::class, 'addDiterima'])->name('ranking.addDiterima');
    // Route::get('/ranking/keputusan', [RankingController::class, 'keputusan'])->name('ranking.keputusan');
    Route::post('/ranking/set-accepted-count', [RankingController::class, 'setAcceptedCount'])->name('ranking.setAcceptedCount');

    Route::get('/perhitungansaw', [PerhitunganSawController::class, 'index'])->name('perhitungansaw.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pemberkasan', [PemberkasanUserController::class, 'index'])->name('pemberkasanuser.index');
    Route::get('/pemberkasan/edit', [PemberkasanUserController::class, 'edit'])->name('pemberkasanuser.edit');
    Route::put('/pemberkasan/update', [PemberkasanUserController::class, 'update'])->name('pemberkasanuser.update');
    Route::get('/pemberkasanadmin', [PemberkasanAdminController::class, 'index'])->name('pemberkasanadmin.index');
    Route::get('/download/{filename}',[PemberkasanUserController::class,'download'])->name('download.file');

    Route::get('/ranking/export-pdf/awal', [RankingController::class, 'exportPDFawal'])->name('ranking.exportPDFawal');
    Route::get('/ranking/export-pdf/akhir', [RankingController::class, 'exportPDFakhir'])->name('ranking.exportPDFakhir');
});
