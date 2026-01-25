<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaixinhaController extends Controller
{
    
public function index(){
    return redirect()->route('caixinha.escolha.form');
}

public function escolhaCaixinha(Request $request){
    
    $request->validate([
        'caixinha_id' => 'required|exists:caixinhas,id',
    ]);

    validated('caixinha_id');
    $usuario = $request->user();
    $usuario->caixinha_id = $request->caixinha_id;
    $usuario->save();
    return redirect()->route('index');

}
}
