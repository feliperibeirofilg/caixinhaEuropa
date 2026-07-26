<?php

namespace App\Http\Controllers\CartaoController;

use App\Http\Controllers\Controller;
use App\Models\Cartao;
use App\Models\FaturaCartao;
use Illuminate\Http\Request;

class FaturaCartaoController extends Controller
{
    public function index(Cartao $cartao)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);

        $faturas = $cartao->faturas()
            ->orderByDesc('ano_referencia')
            ->orderByDesc('mes_referencia')
            ->get();

        return response()->json($faturas);
    }

    public function store(Request $request, Cartao $cartao)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'mes_referencia' => 'required|integer|between:1,12',
            'ano_referencia' => 'required|integer|min:2000',
            'valor_total' => 'sometimes|numeric|min:0',
            'data_fechamento' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
            'status' => 'sometimes|in:aberta,fechada,paga',
        ]);

        $fatura = $cartao->faturas()->create($dados);

        return response()->json($fatura, 201);
    }

    public function update(Request $request, Cartao $cartao, FaturaCartao $fatura)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);
        abort_if($fatura->cartao_id !== $cartao->id, 404);

        $dados = $request->validate([
            'valor_total' => 'sometimes|numeric|min:0',
            'data_fechamento' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
            'status' => 'sometimes|in:aberta,fechada,paga',
        ]);

        $fatura->update($dados);

        return response()->json($fatura);
    }

    public function destroy(Cartao $cartao, FaturaCartao $fatura)
    {
        abort_if($cartao->usuario_id !== auth()->id(), 403);
        abort_if($fatura->cartao_id !== $cartao->id, 404);

        $fatura->delete();

        return response()->json(null, 204);
    }
}
