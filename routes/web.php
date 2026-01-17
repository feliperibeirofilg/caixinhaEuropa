<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('usuario/criar-usuario', [UsuarioController::class, 'formCriarUsuario'])->name('formulario');
Route::post('usuario/criar-usuario', [UsuarioController::class, 'criarUsuario'])->name('criarUsuario');


Route::get('usuario/login', [UsuarioController::class, 'login'])->name('login');
Route::post('usuario/login', [UsuarioController::class, 'autenticar'])->name('autenticar');

// Dashboard - área protegida por Middleware de autenticação
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Rota para deslogar o usuario
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


