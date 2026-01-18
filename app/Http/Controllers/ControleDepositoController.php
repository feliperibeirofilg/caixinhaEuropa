<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depositos;

class ControleDepositoController extends Controller
{
    
    public function totalDepositos(){

        $depositosModel = new Depositos();
        $listaDeDepositos = $depositosModel->quantidadeDeposito();

        return view('controle_depositos', ['listaDeDepositos' => $listaDeDepositos]);
    }
}
