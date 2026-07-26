<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixinha;
use App\Models\Depositos;
use App\Models\Usuario;

class CaixinhaController extends Controller
{

public function index(){
    if(auth()->user()->caixinha_id){
        return redirect()->route('inicio');
    }
    return view('metas.index');
}

public function escolhaCaixinha(Request $request){
    $usuario = auth()->user();
    if($usuario->caixinha_id){
        return redirect()->route('inicio')->with('info', 'Você já escolheu uma caixinha.');
    }
    $valorEscolhido = $request->input('valor');

    $caixinha = Caixinha::where('meta_valor', $valorEscolhido)->first();

    if(!$caixinha){
        return redirect()->back()->with('error', 'Caixinha não encontrada.');
    }

    $this->atribuirCaixinha($usuario, $caixinha);

    return redirect()->route('inicio')->with('success', 'Caixinha escolhida com sucesso!');

}

public function formPersonalizada(){
    if(auth()->user()->caixinha_id){
        return redirect()->route('inicio');
    }
    return view('metas.personalizada');
}

public function criarPersonalizada(Request $request){
    $usuario = auth()->user();
    if($usuario->caixinha_id){
        return redirect()->route('inicio')->with('info', 'Você já escolheu uma caixinha.');
    }

    $dados = $request->validate([
        'nome' => 'required|string|max:255',
        'meta_valor' => 'required|numeric|min:0.01',
        'distribuicao' => 'required|array|min:1',
        'distribuicao.*.valor' => 'required|numeric|min:0.01',
        'distribuicao.*.quantidade' => 'required|integer|min:1',
    ]);

    $somaDistribuicao = 0;
    $configuracao = [];

    foreach ($dados['distribuicao'] as $item) {
        $chave = (string) $item['valor'];
        $configuracao[$chave] = ($configuracao[$chave] ?? 0) + (int) $item['quantidade'];
        $somaDistribuicao += $item['valor'] * $item['quantidade'];
    }

    if (abs($somaDistribuicao - $dados['meta_valor']) > 0.01) {
        return back()->withInput()->with('error', 'A soma dos depósitos informados (R$ ' . number_format($somaDistribuicao, 2, ',', '.') . ') não bate com o valor da meta (R$ ' . number_format($dados['meta_valor'], 2, ',', '.') . ').');
    }

    $caixinha = Caixinha::create([
        'nome' => $dados['nome'],
        'meta_valor' => $dados['meta_valor'],
        'quantidade' => json_encode($configuracao),
    ]);

    $this->atribuirCaixinha($usuario, $caixinha);

    return redirect()->route('inicio')->with('success', 'Meta criada e escolhida com sucesso!');
}

private function atribuirCaixinha(Usuario $usuario, Caixinha $caixinha): void
{
    $usuario->caixinha_id = $caixinha->id;
    $usuario->save();

    $configuracao = json_decode($caixinha->quantidade, true);

    if ($configuracao) {
        foreach ($configuracao as $valor => $quantidade) {
            for ($i = 0; $i < $quantidade; $i++) {
                Depositos::create([
                    'usuario_id' => $usuario->id,
                    'valor' => $valor,
                    'pago' => false,
                ]);
            }
        }
    }
}
}
