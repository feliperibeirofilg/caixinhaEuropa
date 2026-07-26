<?php

namespace App\Http\Controllers\PagamentoController;

use App\Http\Controllers\Controller;
use App\Http\Requests\PagamentoRequest;
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

    public function store(PagamentoRequest $request)
    {
        $dados = $request->validated();

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
