<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ControleDepositoController;
use App\Http\Controllers\CaixinhaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\FinancaController;
use App\Http\Controllers\ContaMensalController;
use App\Http\Controllers\ContaMensalPagamentoController;
use App\Http\Controllers\TransferenciaController;
use App\Http\Controllers\FaturaCartaoController;
use App\Http\Controllers\PagamentoController;
use Illuminate○\Http\middleware\Auth;

Route::get('/', function () {
    return view('usuario.login');
});

Route::get('usuario/criar-usuario', [UsuarioController::class, 'formCriarUsuario'])->name('formulario');
Route::post('usuario/criar-usuario', [UsuarioController::class, 'criarUsuario'])->name('criarUsuario');

Route::get('usuario/verificar-email', [UsuarioController::class, 'formVerificarEmail'])->name('usuario.verificar-email');
Route::post('usuario/verificar-email', [UsuarioController::class, 'verificarEmail'])->name('usuario.verificar-email.confirmar');


Route::get('usuario/login', [UsuarioController::class, 'login'])->name('login');
Route::post('usuario/login', [UsuarioController::class, 'autenticar'])->name('autenticar');

Route::middleware(['auth'])->group(function(){
//Rota para o usuario escolher o valor do deposito
Route::get('/', [CaixinhaController::class, 'index'])->name('caixinha.escolha.form');
Route::get('/processar-escolha', [CaixinhaController::class, 'escolhaCaixinha'])->name('caixinha.escolha');
Route::get('dashboard', [ControleDepositoController::class, 'totalDepositos'])
->name('dashboard');
// Rotas para os depositos
Route::post('/depositos/pagar/{valor}', [ControleDepositoController::class, 'pagarPorValor'])->name('depositos.pagar');

//Rota para excluir deposito
Route::delete('/depositos/excluir/{valor}', [ControleDepositoController::class, 'excluirPorValor'])->name('depositos.excluir');

// Rotas do sistema financeiro
Route::apiResource('categorias', CategoriaController::class)->except(['show']);
Route::apiResource('cartoes', CartaoController::class)
    ->parameters(['cartoes' => 'cartao'])
    ->except(['show']);
Route::apiResource('financas', FinancaController::class)->except(['show']);
Route::apiResource('contas-mensais', ContaMensalController::class)
    ->parameters(['contas-mensais' => 'contaMensal'])
    ->except(['show']);
Route::apiResource('contas-mensais.pagamentos', ContaMensalPagamentoController::class)
    ->parameters(['contas-mensais' => 'contaMensal', 'pagamentos' => 'pagamento'])
    ->except(['show']);
Route::apiResource('transferencias', TransferenciaController::class)->except(['show']);
Route::apiResource('cartoes.faturas', FaturaCartaoController::class)
    ->parameters(['cartoes' => 'cartao', 'faturas' => 'fatura'])
    ->except(['show']);
Route::apiResource('pagamentos', PagamentoController::class)->except(['show']);

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


