<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ControleDepositoController;

Route::get('/', function () {
    return view('usuario.login');
});

Route::get('usuario/criar-usuario', [UsuarioController::class, 'formCriarUsuario'])->name('formulario');
Route::post('usuario/criar-usuario', [UsuarioController::class, 'criarUsuario'])->name('criarUsuario');


Route::get('usuario/login', [UsuarioController::class, 'login'])->name('login');
Route::post('usuario/login', [UsuarioController::class, 'autenticar'])->name('autenticar');

Route::get('dashboard', [ControleDepositoController::class, 'totalDepositos'])
->middleware('auth')
->name('dashboard');
// Rotas para os depositos
Route::post('/depositos/pagar/{valor}', [ControleDepositoController::class, 'pagarPorValor'])->name('depositos.pagar')
->middleware('auth');
//Rota para excluir deposito
Route::delete('/depositos/excluir/{valor}', [ControleDepositoController::class, 'excluirPorValor'])->name('depositos.excluir')
->middleware('auth');

// Rota para deslogar o usuario
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


//Conexão com o bot do telegram
// Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'handle']);


