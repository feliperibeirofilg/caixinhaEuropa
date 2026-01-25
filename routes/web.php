<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ControleDepositoController;
use App\Http\Controllers\CaixinhaController;
use Illuminate○\Http\middleware\Auth;

Route::get('/', function () {
    return view('usuario.login');
});

Route::get('usuario/criar-usuario', [UsuarioController::class, 'formCriarUsuario'])->name('formulario');
Route::post('usuario/criar-usuario', [UsuarioController::class, 'criarUsuario'])->name('criarUsuario');


Route::get('usuario/login', [UsuarioController::class, 'login'])->name('login');
Route::post('usuario/login', [UsuarioController::class, 'autenticar'])->name('autenticar');

Route::middleware(['auth'])->group(function(){
//Rota para o usuario escolher o valor do deposito
Route::get('index', [CaixinhaController::class, 'index'])->name('caixinha.escolha.form');
Route::post('index', [CaixinhaController::class, 'escolhaCaixinha'])->name('caixinha.escolha');
Route::get('dashboard', [ControleDepositoController::class, 'totalDepositos'])
->name('dashboard');
// Rotas para os depositos
Route::post('/depositos/pagar/{valor}', [ControleDepositoController::class, 'pagarPorValor'])->name('depositos.pagar');

//Rota para excluir deposito
Route::delete('/depositos/excluir/{valor}', [ControleDepositoController::class, 'excluirPorValor'])->name('depositos.excluir');


});



// Rota para deslogar o usuario
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


//Conexão com o bot do telegram
// Route::post('/telegram/webhook', [App\Http\Controllers\TelegramController::class, 'handle']);


