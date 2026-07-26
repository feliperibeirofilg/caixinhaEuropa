<?php

//Rotas
use Illuminate\Support\Facades\Route;
//Controllers
use App\Http\Controllers\UsuarioController\UsuarioController;
use App\Http\Controllers\DepositoController\ControleDepositoController;
use App\Http\Controllers\CaixinhaController;
use App\Http\Controllers\CategoriaController\CategoriaController;
use App\Http\Controllers\CartaoController\CartaoController;
use App\Http\Controllers\FinancaController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ContaMensalController\ContaMensalController;
use App\Http\Controllers\ContaMensalController\ContaMensalPagamentoController;
use App\Http\Controllers\TransferenciaController\TransferenciaController;
use App\Http\Controllers\CartaoController\FaturaCartaoController;
use App\Http\Controllers\PagamentoController\PagamentoController;
//Middleware
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
//Rota inicial do sistema (ações rápidas: registrar gasto/salário)
Route::get('/inicio', [InicioController::class, 'index'])->name('inicio');

//Rota para o usuario escolher o valor do deposito
Route::get('/metas', [CaixinhaController::class, 'index'])->name('caixinha.escolha.form');
Route::get('/processar-escolha', [CaixinhaController::class, 'escolhaCaixinha'])->name('caixinha.escolha');
Route::get('/metas/personalizada', [CaixinhaController::class, 'formPersonalizada'])->name('caixinha.personalizada.form');
Route::post('/metas/personalizada', [CaixinhaController::class, 'criarPersonalizada'])->name('caixinha.personalizada.criar');
Route::get('dashboard', [ControleDepositoController::class, 'totalDepositos'])
->name('dashboard');
// Rotas para os depositos
Route::post('/depositos/pagar/{valor}', [ControleDepositoController::class, 'pagarPorValor'])->name('depositos.pagar');

//Rota para excluir deposito
Route::delete('/depositos/excluir/{valor}', [ControleDepositoController::class, 'excluirPorValor'])->name('depositos.excluir');

// Paginas do painel financeiro (telas HTML que consomem as rotas JSON abaixo)
Route::prefix('painel')->name('painel.')->group(function () {
    Route::get('cartoes', [PainelController::class, 'cartoes'])->name('cartoes');
    Route::get('financas', [PainelController::class, 'financas'])->name('financas');
    Route::get('categorias', [PainelController::class, 'categorias'])->name('categorias');
    Route::get('contas-mensais', [PainelController::class, 'contasMensais'])->name('contas-mensais');
    Route::get('transferencias', [PainelController::class, 'transferencias'])->name('transferencias');
});

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


