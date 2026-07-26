<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use Illuminate\Http\Request;
use App\Http\Requests\CartaoRequest;

class CartaoController extends Controller
{
    public function index()
    {
        $cartoes = Cartao::where('usuario_id', auth()->id())->orderBy('nome')->get();

        return response()->json($cartoes);
    }

    public function store(CartaoRequest $request)
    {
        $dados = $request->validate();

        $dados['usuario_id'] = auth()->id();

        $cartao = Cartao::create($dados);

        return response()->json($cartao, 201);
    }

    public function update(Request $request, Cartao $cartao)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:credito,debito',
            'bandeira' => 'nullable|string|max:255',
            'limite' => 'nullable|numeric|min:0',
            'dia_fechamento' => 'nullable|integer|between:1,31',
            'dia_vencimento' => 'nullable|integer|between:1,31',
            'ativo' => 'sometimes|boolean',
        ]);

        $cartao->update($dados);

        return response()->json($cartao);
    }

    public function destroy(Cartao $cartao)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);

        $cartao->delete();

        return response()->json(null, 204);
    }
}
