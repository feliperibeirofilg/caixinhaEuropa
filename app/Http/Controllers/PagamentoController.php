<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagamentoController extends Controller
{
    public function index()
    {
        $pagamentos = Pagamento::with('faturaCartao')
            ->where('usuario_id', auth()->id())
            ->orderByDesc('data_pagamento')
            ->get();

        return response()->json($pagamentos);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'fatura_cartao_id' => [
                'nullable',
                Rule::exists('faturas_cartao', 'id')->where(function ($query) {
                    $query->whereIn('cartao_id', auth()->user()->cartoes()->pluck('id'));
                }),
            ],
            'valor' => 'required|numeric|min:0',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'nullable|in:pix,debito,boleto,dinheiro',
            'observacao' => 'nullable|string',
        ]);

        $dados['usuario_id'] = auth()->id();

        $pagamento = Pagamento::create($dados);

        if ($pagamento->fatura_cartao_id) {
            $pagamento->faturaCartao->update(['status' => 'paga']);
        }

        return response()->json($pagamento, 201);
    }

    public function update(Request $request, Pagamento $pagamento)
    {
        abort_if($pagamento->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'valor' => 'sometimes|required|numeric|min:0',
            'data_pagamento' => 'sometimes|required|date',
            'forma_pagamento' => 'nullable|in:pix,debito,boleto,dinheiro',
            'observacao' => 'nullable|string',
        ]);

        $pagamento->update($dados);

        return response()->json($pagamento);
    }

    public function destroy(Pagamento $pagamento)
    {
        abort_if($pagamento->usuario_id !== auth()->id(), 403);

        $pagamento->delete();

        return response()->json(null, 204);
    }
}
