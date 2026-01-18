<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depositos;

class ControleDepositoController extends Controller
{
    
    public function totalDepositos(){

        $depositosModel = new Depositos();
        $listaUnificada = $depositosModel->depositosFeitos();
        
        $valorDepositado = Depositos::where('pago', 1)->sum('valor');

        return view('dashboard', 
        ['listaDeDepositos' => $listaUnificada
    
        ,'valorDepositado' => $valorDepositado
        ]);
    }

    public function pagarPorValor($valor){

        $deposito = Depositos::where('valor', $valor)->where('pago', 0)->first();
        $Faltam = Depositos::where('pago', 0)->sum('valor');
        

        if($deposito){
            $deposito->update(['pago' => 1]);
            return back()->with('success', 'Depósito de R$' . $valor . ' pago com sucesso! Faltam R$' . ($Faltam - $valor) . ' para completar a meta.');
        }
        return back()->with('error', 'Depósito de R$' . $valor . ' não encontrado.');
    }
}
