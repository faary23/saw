<?php

use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NIlaiAsliController;
use App\Http\Controllers\PemberkasanAdminController;
use App\Http\Controllers\PemberkasanPenilaiController;
use App\Http\Controllers\PemberkasanUserController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganSawController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SubCriteriaController;
use App\Http\Controllers\UserAlternatif;
use App\Http\Controllers\UserRangking;
use App\Http\Controllers\PenilaiController;
use App\Http\Controllers\JurusanController;
use Illuminate\Support\Facades\Route;

// Halaman utama dan hasil bisa diakses publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hasil', [HomeController::class, 'index'])->name('hasil.index'); // **Pindah keluar auth supaya bisa diakses tanpa login**

// Rute login dan register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang membutuhkan autentikasi
Route::middleware('auth')->group(function () {
    Route::resource('criteria', CriteriaController::class);
    Route::resource('sub_criterias', SubCriteriaController::class);
    Route::resource('alternatives', AlternativeController::class);

    // Ranking
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
    Route::post('/ranking', [RankingController::class, 'rank'])->name('ranking.rank');
    Route::post('/ranking/set-accepted-count', [RankingController::class, 'setAcceptedCount'])->name('ranking.setAcceptedCount');
    Route::get('/ranking/export-pdf/awal', [RankingController::class, 'exportPDFawal'])->name('ranking.exportPDFawal');
    Route::get('/ranking/export-pdf/akhir', [RankingController::class, 'exportPDFakhir'])->name('ranking.exportPDFakhir');

    Route::get('/perhitungansaw', [PerhitunganSawController::class, 'index'])->name('perhitungansaw.index');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pemberkasan
    Route::get('/pemberkasan', [PemberkasanUserController::class, 'index'])->name('pemberkasanuser.index');
    Route::get('/pemberkasan/edit', [PemberkasanUserController::class, 'edit'])->name('pemberkasanuser.edit');
    Route::put('/pemberkasan/update', [PemberkasanUserController::class, 'update'])->name('pemberkasanuser.update');
    Route::get('/pemberkasanadmin', [PemberkasanAdminController::class, 'index'])->name('pemberkasanadmin.index');
    Route::get('/pemberkasanpenilai', [PemberkasanPenilaiController::class, 'index'])->name('pemberkasanpenilai.index');
    Route::get('/download/{filename}', [PemberkasanUserController::class, 'download'])->name('download.file');

    // Password
    Route::post('/ubah-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change-password');

    // Penilaian resource
    Route::resource('penilaian', PenilaianController::class)->parameters([
        'penilaian' => 'alternative'
    ]);
  
 	Route::resource('penilai', PenilaiController::class);
    Route::resource('jurusan', JurusanController::class);
});
