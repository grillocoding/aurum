<?php
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DasSimulatorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NameSuggestionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RevenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
// Rotas de convidado (não autenticado)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/faturamento', [RevenueController::class, 'index'])->name('revenues.index');
    Route::post('/faturamento', [RevenueController::class, 'store'])->name('revenues.store');
    Route::delete('/faturamento/{revenue}', [RevenueController::class, 'destroy'])->name('revenues.destroy');

    Route::get('/despesas', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/despesas', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/despesas/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    Route::get('/relatorios', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/relatorios/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');

    Route::get('/simulador-das', [DasSimulatorController::class, 'index'])->name('das.index');

    Route::get('/sugestao-nomes', [NameSuggestionController::class, 'index'])->name('names.index');

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});
