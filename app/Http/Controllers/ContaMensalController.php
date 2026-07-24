<?php

namespace App\Http\Controllers;

use App\Models\ContaMensal;
use Illuminate\Http\Request;

class ContaMensalController extends Controller
{
    public function index()
    {
        $contas = ContaMensal::with('categoria')
            ->where('usuario_id', auth()->id())
            ->orderBy('dia_vencimento')
            ->get();

        return response()->json($contas);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'nome' => 'required|string|max:255',
            'valor_estimado' => 'nullable|numeric|min:0',
            'dia_vencimento' => 'required|integer|between:1,31',
            'tipo' => 'sometimes|in:fixa,variavel',
            'ativa' => 'sometimes|boolean',
            'observacao' => 'nullable|string',
        ]);

        $dados['usuario_id'] = auth()->id();

        $conta = ContaMensal::create($dados);

        return response()->json($conta, 201);
    }

    public function update(Request $request, ContaMensal $contaMensal)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'nome' => 'sometimes|required|string|max:255',
            'valor_estimado' => 'nullable|numeric|min:0',
            'dia_vencimento' => 'sometimes|required|integer|between:1,31',
            'tipo' => 'sometimes|in:fixa,variavel',
            'ativa' => 'sometimes|boolean',
            'observacao' => 'nullable|string',
        ]);

        $contaMensal->update($dados);

        return response()->json($contaMensal);
    }

    public function destroy(ContaMensal $contaMensal)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);

        $contaMensal->delete();

        return response()->json(null, 204);
    }
}
