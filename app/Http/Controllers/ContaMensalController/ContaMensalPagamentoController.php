<?php

namespace App\Http\Controllers\ContaMensalController;

use App\Http\Controllers\Controller;
use App\Models\ContaMensal;
use App\Models\ContaMensalPagamento;
use Illuminate\Http\Request;

class ContaMensalPagamentoController extends Controller
{
    public function index(ContaMensal $contaMensal)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);

        $pagamentos = $contaMensal->pagamentos()
            ->orderByDesc('competencia_ano')
            ->orderByDesc('competencia_mes')
            ->get();

        return response()->json($pagamentos);
    }

    public function store(Request $request, ContaMensal $contaMensal)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'competencia_mes' => 'required|integer|between:1,12',
            'competencia_ano' => 'required|integer|min:2000',
            'valor_pago' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
            'status' => 'sometimes|in:pendente,pago,atrasado',
        ]);

        $pagamento = $contaMensal->pagamentos()->create($dados);

        return response()->json($pagamento, 201);
    }

    public function update(Request $request, ContaMensal $contaMensal, ContaMensalPagamento $pagamento)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);
        abort_if($pagamento->conta_mensal_id !== $contaMensal->id, 404);

        $dados = $request->validate([
            'valor_pago' => 'nullable|numeric|min:0',
            'data_pagamento' => 'nullable|date',
            'status' => 'sometimes|in:pendente,pago,atrasado',
        ]);

        $pagamento->update($dados);

        return response()->json($pagamento);
    }

    public function destroy(ContaMensal $contaMensal, ContaMensalPagamento $pagamento)
    {
        abort_if($contaMensal->usuario_id !== auth()->id(), 403);
        abort_if($pagamento->conta_mensal_id !== $contaMensal->id, 404);

        $pagamento->delete();

        return response()->json(null, 204);
    }
}
