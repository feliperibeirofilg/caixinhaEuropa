<?php

namespace App\Http\Controllers\TransferenciaController;

use App\Http\Requests\TransferenciaRequest;
use App\Http\Controllers\Controller;
use App\Models\Transferencia;
use Illuminate\Http\Request;


class TransferenciaController extends Controller
{
    public function index()
    {
        $transferencias = Transferencia::where('usuario_id', auth()->id())
            ->orderByDesc('data')
            ->get();

        return response()->json($transferencias);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'caixinha_id' => 'nullable|exists:caixinhas,id',
            'descricao' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0',
            'data' => 'required|date',
        ]);

        $dados['usuario_id'] = auth()->id();

        $transferencia = Transferencia::create($dados);

        return response()->json($transferencia, 201);
    }

    public function update(Request $request, Transferencia $transferencia)
    {
        abort_if($transferencia->usuario_id !== auth()->id(), 403);

        $dados = $request->validate([
            'caixinha_id' => 'nullable|exists:caixinhas,id',
            'descricao' => 'nullable|string|max:255',
            'valor' => 'sometimes|required|numeric|min:0',
            'data' => 'sometimes|required|date',
        ]);

        $transferencia->update($dados);

        return response()->json($transferencia);
    }

    public function destroy(Transferencia $transferencia)
    {
        abort_if($transferencia->usuario_id !== auth()->id(), 403);

        $transferencia->delete();

        return response()->json(null, 204);
    }
}
