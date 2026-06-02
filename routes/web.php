<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\CriarHistoriaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HistoriaController as AdminHistoriaController;
use App\Http\Controllers\Admin\AlunoController as AdminAlunoController;

// Site - Jua Literária
Route::get('/', [SiteController::class, 'welcome'])->name('site.welcome');
Route::get('/entrar', [SiteController::class, 'entrar'])->name('site.entrar');
Route::post('/entrar', [SiteController::class, 'login'])->name('site.login');
Route::post('/sair', [SiteController::class, 'sair'])->name('site.sair');

// Biblioteca (requer login do aluno)
Route::middleware(['check.aluno'])->group(function () {
    Route::get('/biblioteca', [SiteController::class, 'biblioteca'])->name('site.biblioteca');

    // Criação de histórias
    Route::get('/criar/iniciar', [CriarHistoriaController::class, 'iniciar'])->name('site.criar.iniciar');
    Route::get('/criar/etapa/{etapa}', [CriarHistoriaController::class, 'etapa'])->name('site.criar.etapa');
    Route::post('/criar/etapa/{etapa}/salvar', [CriarHistoriaController::class, 'salvarEtapa'])->name('site.criar.salvar-etapa');
    Route::get('/criar/revisar', [CriarHistoriaController::class, 'revisar'])->name('site.criar.revisar');
    Route::post('/criar/gerar', [CriarHistoriaController::class, 'gerar'])->name('site.criar.gerar');
    Route::post('/criar/regenerar/{slug}', [CriarHistoriaController::class, 'regenerar'])->name('site.criar.regenerar');
});

// Resultado público
Route::get('/hq/{slug}', [CriarHistoriaController::class, 'resultado'])->name('site.criar.resultado');
Route::get('/hq/{slug}/imprimir', [CriarHistoriaController::class, 'imprimir'])->name('site.criar.imprimir');

// Admin - Mediador
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/historias', [AdminHistoriaController::class, 'index'])->name('historias.index');
    Route::get('/historias/{id}', [AdminHistoriaController::class, 'show'])->name('historias.show');
    Route::get('/historias/{id}/pdf', [AdminHistoriaController::class, 'downloadPdf'])->name('historias.download-pdf');
    Route::delete('/historias/{id}', [AdminHistoriaController::class, 'destroy'])->name('historias.destroy');
    Route::get('/alunos', [AdminAlunoController::class, 'index'])->name('alunos.index');
    Route::get('/alunos/{id}', [AdminAlunoController::class, 'show'])->name('alunos.show');
});

// Dashboard redirect
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('home');
