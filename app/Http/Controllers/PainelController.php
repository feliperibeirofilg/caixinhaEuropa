<?php

namespace App\Http\Controllers;

class PainelController extends Controller
{
    public function cartoes()
    {
        return view('cartoes.index');
    }

    public function financas()
    {
        return view('financas.index');
    }

    public function categorias()
    {
        return view('categorias.index');
    }

    public function contasMensais()
    {
        return view('contas-mensais.index');
    }

    public function transferencias()
    {
        return view('transferencias.index');
    }
}
