<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixinha;
use App\Models\Depositos;

class CaixinhaController extends Controller
{
    
public function index(){
    return view('index');
}

public function escolhaCaixinha(Request $request){
    $usuario = auth()->user();
    if($usuario->caixinha_id){
        return redirect()->route('dashboard')->with('info', 'Você já escolheu uma caixinha.');
    }
    $valorEscolhido = $request->input('valor');

    $caixinha = Caixinha::where('meta_valor', $valorEscolhido)->first();
    
    if(!$caixinha){
        return redirect()->back()->with('error', 'Caixinha não encontrada.');
    }
    
    $usuario->caixinha_id = $caixinha->id;
    
    $usuario->save();

        $configuracao = json_decode($caixinha->quantidade, true);

    if($configuracao) {
        foreach($configuracao as $valor => $quantidade){
            for($i = 0; $i < $quantidade; $i++){
                Depositos::create([
                    'usuario_id'=> $usuario->id,
                    'valor' => $valor,
                    'pago' => false,
                ]);
            }
        }
    }

    return redirect()->route('dashboard')->with('success', 'Caixinha escolhida com sucesso!');

}
}
