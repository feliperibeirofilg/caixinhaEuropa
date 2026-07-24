<?php

namespace App\Http\Controllers;

use App\Models\Financa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FinancaController extends Controller
{
    public function index(Request $request)
    {
        $financas = Financa::with(['categoria', 'cartao'])
            ->where('usuario_id', auth()->id())
            ->when($request->query('mes') && $request->query('ano'), function ($query) use ($request) {
                $query->whereMonth('data_compra', $request->query('mes'))
                    ->whereYear('data_compra', $request->query('ano'));
            })
            ->when($request->query('categoria_id'), function ($query, $categoriaId) {
                $query->where('categoria_id', $categoriaId);
            })
            ->when($request->query('cartao_id'), function ($query, $cartaoId) {
                $query->where('cartao_id', $cartaoId);
            })
            ->orderByDesc('data_compra')
            ->get();

        return response()->json($financas);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'cartao_id' => [
                'nullable',
                Rule::exists('cartoes', 'id')->where('usuario_id', auth()->id()),
            ],
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|in:receita,despesa',
            'forma_pagamento' => 'required|in:debito,credito,pix,dinheiro,boleto,transferencia',
            'data_compra' => 'required|date',
            'parcelas' => 'sometimes|integer|min:1',
            'parcela_atual' => 'sometimes|integer|min:1',
            'recorrente' => 'sometimes|boolean',
            'status' => 'sometimes|in:pendente,pago',
            'observacao' => 'nullable|string',
        ]);

        $dados['usuario_id'] = auth()->id();

        $financa = Financa::create($dados);

        return response()->json($financa, 201);
    }

    public function update(Request $request, Financa $financa)
    {
        abort_if($financa->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'cartao_id' => [
                'nullable',
                Rule::exists('cartoes', 'id')->where('usuario_id', auth()->id()),
            ],
            'descricao' => 'sometimes|required|string|max:255',
            'valor' => 'sometimes|required|numeric|min:0',
            'tipo' => 'sometimes|required|in:receita,despesa',
            'forma_pagamento' => 'sometimes|required|in:debito,credito,pix,dinheiro,boleto,transferencia',
            'data_compra' => 'sometimes|required|date',
            'parcelas' => 'sometimes|integer|min:1',
            'parcela_atual' => 'sometimes|integer|min:1',
            'recorrente' => 'sometimes|boolean',
            'status' => 'sometimes|in:pendente,pago',
            'observacao' => 'nullable|string',
        ]);

        $financa->update($dados);

        return response()->json($financa);
    }

    public function destroy(Financa $financa)
    {
        abort_if($financa->usuario_id !== auth()->id(), 403);

        $financa->delete();

        return response()->json(null, 204);
    }
}
